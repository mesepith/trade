<?php

/*
 * @author : ZAHIR
 * DESC: Option chain ,premium decay data
 */

class Oc_pd_disp_analysis_model extends CI_Model {
    
    function displayOCPDDayWiseData( $date, $put_avg_decay, $call_avg_decay, $custom_condition, $live=false, $script_start_time=false, $loop_count=0){
        
        $this->db->where('status', 1); 
        
        $this->db->where('underlying_date_end = "' . $date . '"');
        
        if(!empty($put_avg_decay)){
            
            if( $put_avg_decay ==='high'){
                
                $this->db->order_by('put_avg_decay desc');
                
            }else{
                
                $this->db->order_by('put_avg_decay');
            }
            
        }else if(!empty($call_avg_decay)){
            
            if( $call_avg_decay ==='high'){
                
                $this->db->order_by('call_avg_decay desc');
                
            }else{
                
                $this->db->order_by('call_avg_decay');
            }
            
        }
        
        
        if (!empty($custom_condition)) {

            if ($custom_condition === "callgtput") {

                $this->db->where('call_avg_decay > put_avg_decay');
            } else if ($custom_condition === "putgtcall") {

                $this->db->where('put_avg_decay > call_avg_decay');
            }
        }
        
        
        if( $live ){
            
            $this->db->where('market_running', 1);
            
            if( $script_start_time ){
                
                $this->db->where('script_start_time', $script_start_time);
                
            }
            
        }else{
            
            $this->db->where('market_running', 0);
        }
        
        
        $this->db->select('*');
        
        $query = $this->db->get('oc_pd_avg_decay');
//        echo $this->db->last_query();
//        echo '<br>';
        
        if (count($query->result()) > 0) {
        
            return $query->result();
        }else{
            
            $loop_count++;

            if($loop_count > 15){ return false; }
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->displayOCPDDayWiseData( $date, $put_avg_decay, $call_avg_decay, $custom_condition, $live, $script_start_time, $loop_count );
//            echo 'data <br/>';
//            echo '<pre>'; print_r($data);
            if( !empty($data) ){
                
                return $data;
            }
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: get option chain premium decay Data of each company
     */
    function getOCPDData( $company_id, $company_symbol, $date, $date_to, $live, $searching_expiry_date, $loop_count=0 ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        $this->db->where('underlying_date_end >= "' . $date . '"');
        
        if( $date_to ){
            
            $this->db->where('underlying_date_end <= "' . $date_to . '"');
        }
        if( !empty($searching_expiry_date) ){
            
            $this->db->where('expiry_date', $searching_expiry_date); 
        }
        
        if( $live == 'live' ){
            
            $this->db->where('market_running', 1);
        }else{
        
            $this->db->where('market_running', 0);
        }
        
        $this->db->select('*');
        
        $query = $this->db->get('oc_pd_avg_decay');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
            if($loop_count > 11){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->getOCPDData( $company_id, $company_symbol, $date, $date_to, $live, $searching_expiry_date, $loop_count );
            
            if( !empty($data) ){
                
                return $data;
            }
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get script_start_time by date
     */
    function getScriptStartTime( $date, $loop_count=0){
     
        $this->db->where('status', 1); 
        
        $this->db->where('underlying_date_end = "' . $date . '"');
        
        $this->db->where('market_running', 1);
        $this->db->group_by('script_start_time');
        $this->db->select('script_start_time');
        
        $query = $this->db->get('oc_pd_avg_decay');
        
        if (count($query->result()) > 0) {
            
            $return_arr['date']= $date;
            $return_arr['result']= $query->result();
            
            return $return_arr;
            
        }else{
            
            $loop_count++;

            if($loop_count > 15){ return false; }
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->getScriptStartTime( $date, $loop_count );
//            echo 'data <br/>';
//            echo '<pre>'; print_r($data);
            if( !empty($data) ){
                
                return $data;
            }
        }
        
    }
    
}
    
   
