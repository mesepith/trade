<?php

/*
 * @author : ZAHIR
 */

class Put_call_log_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC :insert put option data log
     */
    
    function insertPutCallDataLog( $data_log_arr ){
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
//        $data_log_arr["created_at"] = date("Y-m-d H:i:s");
        
        
        $this->db->insert('put_call_log', $data_log_arr);
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Put_call_log_model';

            $error_db_data['model_methode_name'] = 'insertPutCallDataLog';

            $error_db_data['data'] = json_encode($data_log_arr);

            $error_db_data['query'] = $this->db->last_query();

            $error_db_data['error_code'] = $errorz['code'];

            $error_db_data['error_message'] = $errorz['message'];

            $error_db_data["created_at"] = date("Y-m-d H:i:s");

            $this->Db_error_log->insertDbErrorLog($error_db_data);
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id = $this->db->insert_id();
        
        
    }
    /*
     * @author : ZAHIR
     * DESC :insert put option data log
     */
    
    function insertPutCallDataLog2( $data_log_arr, $market_running ){
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
//        $data_log_arr["created_at"] = date("Y-m-d H:i:s");
        
        $data_log_arr["created_at"] = date("Y-m-d H:i:s");
        
        if( $market_running ){
            
            $data_log_arr["market_running"] = $market_running;
            
            $this->db->insert('put_call_live_log', $data_log_arr);
            
        }else{
            
            $this->db->insert('put_call_log2', $data_log_arr);
        }
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Put_call_log_model';

            $error_db_data['model_methode_name'] = 'insertPutCallDataLog2';

            $error_db_data['data'] = json_encode($data_log_arr);

            $error_db_data['query'] = $this->db->last_query();

            $error_db_data['error_code'] = $errorz['code'];

            $error_db_data['error_message'] = $errorz['message'];

            $error_db_data["created_at"] = date("Y-m-d H:i:s");

            $this->Db_error_log->insertDbErrorLog($error_db_data);
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id = $this->db->insert_id();
        
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Fetch unprocessed data
     */
    
    function fetchUnprocessedData( $put_call_log_id ){
        
        $this->db->where('id > ', $put_call_log_id);    
        $this->db->where('status', 1);    
        $this->db->where('data_processed', 0); 
        $this->db->limit(15);
        $this->db->select('*');
        $query = $this->db->get('put_call_log');
//        echo $this->db->last_query();
        
        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }    
    
    /*
     * @author: ZAHIR
     * DESC: Fetch unprocessed data
     */
    
    function fetchUnprocessedData2( $put_call_log_id, $market_running ){
        
           
        $this->db->where('status', 1);    
        $this->db->where('data_processed', 0); 
        
        if( $market_running ){
            
            $this->db->where('id', $put_call_log_id); 
            
            $this->db->select('*');
            $query = $this->db->get('put_call_live_log');
        
        }else{
            
            $this->db->where('id > ', $put_call_log_id); 
//            $this->db->limit(15);
            $this->db->select('*');
            $query = $this->db->get('put_call_log2');
        }
        
        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }    
    
    /*
     * @author: ZAHIR
     * DESC: update Put Call Data Process Status
     */
    
    function updatePutCallDataProcessStatus( $put_call_log_id, $company_id, $data_process_status ){
        
        $this->db->where('id', $put_call_log_id);
        $this->db->where('company_id', $company_id);
        $this->db->update('put_call_log', array('data_processed' => $data_process_status));
        
    }
    /*
     * @author: ZAHIR
     * DESC: update Put Call Data Process Status
     */
    
    function updatePutCallDataProcessStatus2( $put_call_log_id, $company_id, $data_process_status, $market_running ){
        
        $this->db->where('id', $put_call_log_id);
        $this->db->where('company_id', $company_id);
        
        if($market_running){
        
            $this->db->update('put_call_live_log', array('data_processed' => $data_process_status));
        
        }else{
            
            $this->db->update('put_call_log2', array('data_processed' => $data_process_status));
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: check Companies Data Present By Date
     */
    function checkCompaniesDataPresentByDate( $company_id_arr, $date ){
        
        $this->db->where('status', 1); 
        $this->db->where_in('company_id', $company_id_arr); 
        $this->db->where_in('created_at_date', $date); 
        $this->db->select('count(*) AS is_present');
        
        $query = $this->db->get('put_call_log');
        
//        echo $this->db->last_query();
        
        $is_present = $query->result()[0]->is_present;
        
//        echo '<pre>'; print_r($query->result()); 
//        
//        echo $is_present;
//        
//        exit;
        
        if ($is_present > 0) {
        
            return 'present';
            
        }else{
            
            return 'absent';
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Fetch Last Crawled Company id 
     */
    
    function lastCrawledPCCompany(){
        
        $this->db->where('status', 1); 
        $this->db->where('market_date', date('Y-m-d'));
        $this->db->select('company_id');
        $this->db->order_by('id desc');
        $this->db->limit('1');
        $query = $this->db->get('put_call_log2');
//        echo $this->db->last_query();
        
        if (count($query->result()) > 0 && !empty( $query->result()[0]->company_id ) && $query->result()[0]->company_id > 0) {
            
            return $query->result()[0]->company_id;
            
        }else{
            
            return 0;
        }
    }
    
}
    
   
