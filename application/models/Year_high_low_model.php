<?php

/*
 * @author : ZAHIR
 */

class Year_high_low_model extends CI_Model {
    
    /*
     * @author: ZAHIR
     * DESC: insert year high low whole api data in json
     */
    function insertYearHighLowApiDataInLog( $year_high_low_data, $high_or_low, $market_date ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        $data["high_or_low"] = $high_or_low;
        $data["market_date"] = $market_date;
        $data["data"] = json_encode($year_high_low_data);
        
        $this->db->insert('year_high_low_log', $data);
        return $insert_id = $this->db->insert_id();
        
    }
    
    /*
     * Check if todays data already present
     */
    function checkTodaysDataAlreadyInserted($high_or_low){
        
        $this->db->where('status', 1);
        $this->db->where('high_or_low', $high_or_low);
        $this->db->where('market_date', date('Y-m-d')); 
        
        $num_rows = $this->db->count_all_results('year_high_low_log');
        
        
        

        if ($num_rows > 0 ) {
            
            return 'inserted';
            
        }else{
            
            return 'absent';
            
        }
        
    }
    
    function insertYearHighApiData( $year_high_low_arr ){
        
        $this->db->insert('year_high_data', $year_high_low_arr);
    }
    
    function insertYearLowApiData( $year_high_low_arr ){
        
        $this->db->insert('year_low_data', $year_high_low_arr);
    }
    
    function dispYearHighLowData($market_date, $high_or_low, $loop_count=0){
        
        $this->db->where('status', 1); 
         
        $this->db->where('market_date', $market_date);
        
        if( $high_or_low === 'high'){
        
            $query = $this->db->get('year_high_data');
            
        }else{
            
            $query = $this->db->get('year_low_data');
        }
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            $loop_count++;

            if($loop_count > 5){ return false; }
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $market_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($market_date)));
            
            $data = $this->dispYearHighLowData( $market_date, $high_or_low, $loop_count );
            
            if( count($data) >  0 ){
                
                return $data;
            }
            
        }
        
    }
   
}
