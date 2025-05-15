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
        // Get last N unique trading dates
        $this->db->distinct();
        $this->db->select('stock_date');
        $this->db->order_by('stock_date', 'DESC');
        $this->db->limit($sessions);
        $dates = $this->db->get('stock_data')->result_array();

        if (count($dates) < $sessions) return [];

        $date_values = array_column($dates, 'stock_date');

        // Fix for ONLY_FULL_GROUP_BY mode
        $this->db->select('MAX(company_id) as company_id, 
        company_symbol, MAX(company_name) as company_name, 
        MAX(total_market_cap) as total_market_cap,
        SUM(price_change_in_p) AS total_change');
        $this->db->from('stock_data');
        $this->db->where_in('stock_date', $date_values);
        $this->db->group_by('company_symbol');
        $this->db->having('total_change >=', $percent);
        $this->db->order_by('total_change', 'DESC');

        return $this->db->get()->result_array();
    }




    
}