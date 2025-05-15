<?php
/*
@author: Zahir Alam
@date: 14-May-2025
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class UpStock_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function getStocksUpByPercentToday($percent) {
        // Get the latest stock_date
        $this->db->select('stock_date');
        $this->db->order_by('stock_date', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('stock_data')->row();
        if (!$query) return [];

        $latest_date = $query->stock_date;

        // Fetch stocks with today's % change >= threshold
        $this->db->where('stock_date', $latest_date);
        $this->db->where('price_change_in_p >=', $percent);
        $this->db->order_by('price_change_in_p', 'DESC');

        return $this->db->get('stock_data')->result_array();
    }

    public function getStocksUpByCumulativePercent($percent = 10, $sessions = 5) {
        // ✅ Step 1: Get the latest $sessions dates for filtering (for cumulative logic)
        $this->db->distinct();
        $this->db->select('stock_date');
        $this->db->order_by('stock_date', 'DESC');
        $this->db->limit($sessions);
        $filter_dates = $this->db->get('stock_data')->result_array();

        if (count($filter_dates) < $sessions) return [];

        $filter_date_values = array_column($filter_dates, 'stock_date');

        // ✅ Step 2: Get latest 5 dates for "Current 5 Days Change" column
        $this->db->distinct();
        $this->db->select('stock_date');
        $this->db->order_by('stock_date', 'DESC');
        $this->db->limit(5);
        $display_dates = $this->db->get('stock_data')->result_array();

        $display_date_values = array_column($display_dates, 'stock_date');

        // ✅ Step 3: Get eligible companies by cumulative change
        $this->db->select('
            MAX(company_id) as company_id,
            company_symbol,
            MAX(company_name) as company_name,
            MAX(total_market_cap) as total_market_cap,
            SUM(price_change_in_p) AS total_change
        ');
        $this->db->from('stock_data');
        $this->db->where_in('stock_date', $filter_date_values);
        $this->db->group_by('company_symbol');
        $this->db->having('total_change >=', $percent);
        $this->db->order_by('total_change', 'DESC');

        $result = $this->db->get()->result_array();

        if (empty($result)) return [];

        // ✅ Step 4: Get 5-day price changes for all selected symbols
        $symbols = array_column($result, 'company_symbol');

        $this->db->select('company_symbol, stock_date, price_change_in_p');
        $this->db->from('stock_data');
        $this->db->where_in('company_symbol', $symbols);
        $this->db->where_in('stock_date', $display_date_values);
        $this->db->order_by('company_symbol, stock_date DESC');
        $priceData = $this->db->get()->result_array();

        // ✅ Step 5: Group the data by symbol and ensure exactly 5 values
        $priceChanges = [];
        foreach ($priceData as $row) {
            $priceChanges[$row['company_symbol']][] = round($row['price_change_in_p'], 2);
        }

        foreach ($result as &$row) {
            $values = isset($priceChanges[$row['company_symbol']]) ? $priceChanges[$row['company_symbol']] : [];
            // Pad missing values with dashes or zeros
            $values = array_pad($values, 5, '-');  // Or 0.00 if preferred
            $row['price_change_5'] = implode('|', $values);
        }

        return $result;
    }





    
}