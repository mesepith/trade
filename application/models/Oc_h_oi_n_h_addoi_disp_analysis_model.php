<?php

/*
 * @author : ZAHIR
 * DESC: Option chain high oi and addition of new change in oi for both put and call
 */

class Oc_h_oi_n_h_addoi_disp_analysis_model extends CI_Model {
    
    function displayHighOiNAddOiDayWiseData( $date, $loop_count=0){
        
        $this->db->where('status', 1); 
        
        $this->db->where('underlying_date = "' . $date . '"');                
        
        $this->db->select('*');
        
        $query = $this->db->get('oc_high_oi_n_high_add_of_oi');
//        echo $this->db->last_query();
//        echo '<br>';
        
        if (count($query->result()) > 0) {
        
            return $query->result();
        }else{
            
            $loop_count++;

            if($loop_count > 15){ return false; }
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->displayHighOiNAddOiDayWiseData( $date, $loop_count );
//            echo 'data <br/>';
//            echo '<pre>'; print_r($data);
            if( !empty($data) ){
                
                return $data;
            }
        }
    }
    
}
    
   
