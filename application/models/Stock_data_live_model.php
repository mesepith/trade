<?php

/*
 * @author : ZAHIR
 */

class Stock_data_live_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC :get stock detail
     */
    
    function getStockDetailByCompanyIdAndSymbol( $company_id, $company_symbol, $date, $loop_count=0 ){
//        echo $date = date('Y-m-d', strtotime('-2 day', strtotime($date)));
//        echo '<br/>';
        
//        echo 'date: ' . $date;
        
//        echo '<br/>';
        
        $this->db->where('status', 1);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        
        
        $this->db->where('stock_date = ', $date );
        
        $this->db->select('company_name, open_price, stock_date, stock_time, last_price, price_change, price_change_in_p, vwap, total_traded_volume, delivery_quantity, delivery_to_traded_quantity, total_buy_quantity, total_sell_quantity, total_traded_value ');
        $this->db->order_by('stock_time', 'asc');
        $query = $this->db->get('stock_data_live');        
        
//        echo $this->db->last_query();
//        exit;
        if( count($query->result()) > 0 ){
            
            $data = $query->result();
            
//            echo '<pre>'; print_r($data); exit;

            return $query->result();
            
        }else{
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->getStockDetailByCompanyIdAndSymbol( $company_id, $company_symbol, $date, $loop_count );
            
//            echo count($data);
            
            if( count($data) >  0 ){
                
                return $data;
            }
//            echo '<pre>'; print_r($data);
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check if stock_data_live table has todays any stock
    */
    
    function checkStocksLiveTodayDataInserted(){
        
        $this->db->where('status', 1);
        $this->db->where('stock_date', date('Y-m-d'));
        $this->db->order_by('id desc');
        $this->db->limit(1);
        $this->db->select('count(*) AS ispresent');
        $query = $this->db->get('stock_data_live');
        
        if( $query->result()[0]->ispresent > 0 ){
            
            return 'present';
            
        }else{
            
            return 'absent';
            
        }
        
    }
    
}
