<?php

/*
 * @author : ZAHIR
 * DESC: Option chain , premium decay analysis data
 */

class Oc_op_analysis_model extends CI_Model {
    
    /*
     * @author: ZAHIR
     * DESC: get highest sum combination of put oi and call oi
     */
    
    function getHighestSumCombinationOfPutCallOi( $company_id, $company_symbol, $underlying_date, $expiry_date ){
        /*
         * SELECT underlying_price, calls_oi,  strike_price, puts_oi, (calls_oi+puts_oi) AS sum_of_call_put 
         * FROM `trade`.`put_call` WHERE `company_symbol` = 'AXISBANK'  AND underlying_date='2019-12-23' 
AND expiry_date="2019-12-26"  AND strike_price !='(Null)'
ORDER BY (calls_oi+puts_oi) DESC LIMIT 2; 
        */
        $this->db->where('status', 1); 
        $this->db->where('underlying_date', $underlying_date);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('expiry_date', $expiry_date);
        $this->db->where('strike_price !=', '(Null)');
        $this->db->where('strike_price >', 0);
        
        $this->db->order_by('(calls_oi+puts_oi) desc'); 
        $this->db->limit(3);
        
        $this->db->where('market_running', 0);
        
        $this->db->select(' strike_price, (calls_oi+puts_oi) AS sum_of_call_put');
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
     * DESC: Insert Option pain Analysis data
     */
    
    function insertOptionPainAnalysisData( $option_pain_arr ){        
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        
        $this->db->insert('oc_op_analysis', $option_pain_arr);
        
        $insert_id = $this->db->insert_id();
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Oc_op_analysis_model';

            $error_db_data['model_methode_name'] = 'insertOptionPainAnalysisData';

            $error_db_data['data'] = json_encode($inp_data_arr);

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
    
   
