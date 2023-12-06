<?php

/*
 * @author : ZAHIR
 * DESC:  Analysis of option chain by highest oi and addition of highest oi for both call and put
 */

class Oc_h_oi_n_h_addoi_analysis_model extends CI_Model {
    
    /*
     * @author: ZAHIR
     * DESC: get highest oi
     */
    
    function getHighestOi( $company_id, $company_symbol, $underlying_date, $expiry_date, $side, $order_by ){
        /*
         * SELECT company_symbol, underlying_price, calls_oi,  calls_chng_in_oi, strike_price FROM put_call WHERE company_symbol='GAIL' AND underlying_date ='2019-12-18' AND expiry_date='2020-01-30'  AND strike_price != '(NULL)' 
ORDER BY calls_oi DESC LIMIT 1;   
         * 
         * SELECT company_symbol, underlying_price, puts_oi,  puts_chng_in_oi, strike_price FROM put_call WHERE company_symbol='GAIL' AND underlying_date ='2019-12-18' AND expiry_date='2020-01-30'  AND strike_price != '(NULL)' 
ORDER BY puts_oi DESC LIMIT 1;   
        */
        $this->db->where('status', 1); 
        $this->db->where('underlying_date', $underlying_date);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('expiry_date', $expiry_date);
        $this->db->where('strike_price !=', '(Null)');
        $this->db->where('strike_price >', 0);
        
        $this->db->order_by($order_by . ' desc'); 
        $this->db->limit(1);
        
        $this->db->where('market_running', 0);
        
        if($side === 'calls'){
            
            $this->db->where('calls_oi > ', 0);
            
            $this->db->select(' calls_oi,  calls_chng_in_oi, strike_price');
            
        }else if($side === 'puts'){
            
            $this->db->where('puts_oi > ', 0);
            
            $this->db->select(' puts_oi,  puts_chng_in_oi, strike_price');
        } 
        
        $query = $this->db->get('put_call');
        
//        echo $this->db->last_query();
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Insert Highest oi Analysis data
     */
    function insertHighestOiData( $highest_oi_arr ){
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        
        $this->db->insert('oc_high_oi_n_high_add_of_oi', $highest_oi_arr);
        
        $insert_id = $this->db->insert_id();
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Oc_h_oi_n_h_addoi_analysis_model';

            $error_db_data['model_methode_name'] = 'insertHighestOiData';

            $error_db_data['data'] = json_encode($highest_oi_arr);

            $error_db_data['query'] = $this->db->last_query();

            $error_db_data['error_code'] = $errorz['code'];

            $error_db_data['error_message'] = $errorz['message'];

            $error_db_data["created_at"] = date("Y-m-d H:i:s");

            $this->Db_error_log->insertDbErrorLog($error_db_data);
            
            $insert_id = false;
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
    }
        
}
    
   
