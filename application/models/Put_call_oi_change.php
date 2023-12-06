<?php

/*
 * @author : ZAHIR
 */

class Put_call_oi_change extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC :insert oi change in pecent
     */
    
    function insertOiChngPrcntLog( $data ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        
        $this->db->insert('put_call_oi_change', $data);
        
        $insert_id = $this->db->insert_id();
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Put_call_oi_change';

            $error_db_data['model_methode_name'] = 'insertOiChngPrcntLog';

            $error_db_data['data'] = json_encode($data);

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
    
    /*
     * @author: ZAHIR
     * DESC: check Todays Oi Change Data Present
     */
    function checkTodaysOiChangeDataPresent(){
        
        $this->db->where('status', 1);    
        $this->db->where('underlying_date', date('Y-m-d')); 
        $this->db->limit(1); 
        $this->db->select('count(*) AS ispresent');
        $query = $this->db->get('put_call_oi_change');
        
        if( $query->result()[0]->ispresent > 0 ){
            
            return 'present';
            
        }else{
            
            return 'absent';
            
        }
        
    }
    
    
}
    
   
