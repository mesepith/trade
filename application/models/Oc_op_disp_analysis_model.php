<?php

/*
 * @author : ZAHIR
 * DESC: Option chain ,premium decay data
 */

class Oc_op_disp_analysis_model extends CI_Model {
    
    function displayOCOPDayWiseData( $date, $custom_condition, $loop_count=0){
        
        $this->db->where('status', 1); 
        
        $this->db->where('underlying_date = "' . $date . '"');        
        
        if (!empty($custom_condition)) {
            
            if ($custom_condition === "current_exp_bull") {
                
//                $this->db->where('strike_price_3_current_exp > strike_price_2_current_exp');
//                $this->db->where('strike_price_2_current_exp > strike_price_1_current_exp');
                
                $this->db->where(' strike_price_1_current_exp > underlying_price');
                
            }else if ($custom_condition === "current_exp_bear") {
                
//                $this->db->where('strike_price_3_current_exp < strike_price_2_current_exp');
//                $this->db->where('strike_price_2_current_exp < strike_price_1_current_exp');
                
                $this->db->where('strike_price_1_current_exp < underlying_price');
                            
            }else if ($custom_condition === "next_exp_bull") {
                
//               $this->db->where('strike_price_3_next_exp > strike_price_2_next_exp');
//               $this->db->where('strike_price_2_next_exp > strike_price_1_next_exp');
               
               $this->db->where('strike_price_1_next_exp > underlying_price');
                
            }else if ($custom_condition === "next_exp_bear") {
                
//               $this->db->where('strike_price_3_next_exp < strike_price_2_next_exp');
//               $this->db->where('strike_price_2_next_exp < strike_price_1_next_exp');
                
                 $this->db->where('strike_price_1_next_exp < underlying_price');
                            
            }else if ($custom_condition === "all_exp_bull") {
                
//               $this->db->where('strike_price_3_current_exp > strike_price_2_current_exp');
//               $this->db->where('strike_price_2_current_exp > strike_price_1_current_exp');
               
//               $this->db->where('strike_price_1_next_exp > strike_price_3_current_exp');
               
//               $this->db->where('strike_price_3_next_exp > strike_price_2_next_exp');
//               $this->db->where('strike_price_2_next_exp > strike_price_1_next_exp');
               
               
               $this->db->where(' strike_price_1_current_exp > underlying_price');
               $this->db->where(' strike_price_1_next_exp > underlying_price');
               $this->db->where(' strike_price_1_next_exp > strike_price_1_current_exp');
                            
            }else if ($custom_condition === "all_exp_bear") {
                
//                $this->db->where('strike_price_3_current_exp < strike_price_2_current_exp');
//                $this->db->where('strike_price_2_current_exp < strike_price_1_current_exp');
                
//                $this->db->where('strike_price_1_next_exp < strike_price_3_current_exp');
                
//                $this->db->where('strike_price_3_next_exp < strike_price_2_next_exp');
//                $this->db->where('strike_price_2_next_exp < strike_price_1_next_exp');
                
                $this->db->where('strike_price_1_current_exp < underlying_price');
                $this->db->where('strike_price_1_next_exp < underlying_price');
                $this->db->where('strike_price_1_next_exp < strike_price_1_current_exp');
                                
            }
        }
        
        $this->db->select('*');
        
        $query = $this->db->get('oc_op_analysis');
//        echo $this->db->last_query();
//        echo '<br>';
        
        if (count($query->result()) > 0) {
        
            return $query->result();
        }else{
            
            $loop_count++;

            if($loop_count > 15){ return false; }
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->displayOCOPDayWiseData( $date, $custom_condition, $loop_count );
//            echo 'data <br/>';
//            echo '<pre>'; print_r($data);
            if( !empty($data) ){
                
                return $data;
            }
        }
    }
    
}
    
   
