<?php

/*
 * @author : ZAHIR
 */

class Future_model extends CI_Model {

     /*
     * @author: ZAHIR
     * DESC: get company ids which are not crawled
     */
    
    function futureCompanyList( $last_calculated_company ){
          
        $this->db->where('status', 1); 
        
        if( $last_calculated_company ){
            
            $this->db->where('company_id >', $last_calculated_company);
            
        }
        $this->db->select('*');

        $query = $this->db->get('future_companies');
        
        return $query->result();
    }
    
    /*
     * @author : ZAHIR
     * DESC :insert Future data log
     */
    
    function insertFutureDataLog( $data_log_arr ){
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
//        $data_log_arr["created_at"] = date("Y-m-d H:i:s");
        
        $data_log_arr["created_at"] = date("Y-m-d H:i:s");
        
        $this->db->insert('future_log', $data_log_arr);
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Future_model';

            $error_db_data['model_methode_name'] = 'insertFutureDataLog';

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
    
    function fetchUnprocessedData( $future_log_id, $market_running ){
        
           
        $this->db->where('status', 1);    
        $this->db->where('data_processed', 0); 
        
        if( $market_running ){
            
            $this->db->where('id', $future_log_id); 
            
            $this->db->select('*');
            $query = $this->db->get('future_live_log');
        
        }else{
            
            $this->db->where('id > ', $future_log_id); 
            $this->db->limit(100);
            $this->db->select('*');
            $query = $this->db->get('future_log');
        }
        
        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    } 
    
    /*
     * Insert Future Data
     */
    function insertFutureData( $future_data_arr ){
        
        $db_debug = $this->db->db_debug; //save setting
        
        $this->db->db_debug = FALSE;
        
        $this->db->insert('future', $future_data_arr);
        
        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            echo '<pre>';
            print_r($errorz); 
            
            if( $errorz['code'] === 1062 ){ /* If Duplicate then make status inactive */
                
                $this->db->where('id', $future_data_arr['future_log_id']);
                $this->db->where('company_id', $future_data_arr['company_id']);
                
                if($future_data_arr['market_running']){
                
                    $this->db->update('future_live_log', array('status' => 2,'updated_at'=> date("Y-m-d H:i:s") ) );

                }else{

                    $this->db->update('future_log', array('status' => 2,'updated_at'=> date("Y-m-d H:i:s") ) );
                }
            }
            
            $insert_id = false;
            
        }else{
            
            $this->db->where('id', $future_data_arr['future_log_id']);
            $this->db->where('company_id', $future_data_arr['company_id']);
            
            if($future_data_arr['market_running']){
                
                $this->db->update('future_live_log', array('data_processed' => 1,'updated_at'=> date("Y-m-d H:i:s") ) );

            }else{

                $this->db->update('future_log', array('data_processed' => 1,'updated_at'=> date("Y-m-d H:i:s") ) );
            }
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
    }
    
     /*
     * @author: ZAHIR
     * DESC: Set status = 2 for inactive companies in future_companies table
     */
    
    function makeFutureCompanyInactive( $company_id, $company_symbol ){
        
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->update('future_companies', array('status' => 2,'updated_at'=> date("Y-m-d H:i:s") ));
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get Latest Underlying Date
     */
    
    function getLatestFrUnderlyingDateofAll($live=false ){
          
        $this->db->where('status', 1); 
        $this->db->order_by('underlying_date desc'); 
        $this->db->limit(1);
        $this->db->select('underlying_date');
        
        if($live){
            
//            $query = $this->db->get('put_call_expiry_live');
            $this->db->where('market_running', 1);
            
        }else{
//            $query = $this->db->get('put_call_expiry');
            $this->db->where('market_running', 0);
        }
        
        $query = $this->db->get('future');
        
        if (count($query->result()) > 0) {
        
            return $query->result()[0];
            
        }else{
            
            return false;
        }
    }
    /*
     * @author: ZAHIR
     * DESC: Get Latest Underlying Date
     */
    
    function getLatestFrUnderlyingDate( $company_id, $company_symbol, $live=false ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->order_by('underlying_date desc'); 
        $this->db->limit(1);
        $this->db->select('underlying_date');
        
        if($live){
            
//            $query = $this->db->get('put_call_expiry_live');
            $this->db->where('market_running', 1);
            
        }else{
//            $query = $this->db->get('put_call_expiry');
            $this->db->where('market_running', 0);
        }
        
        $query = $this->db->get('future');
        
        if (count($query->result()) > 0) {
        
            return $query->result()[0];
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get Current Expiry Date By Underlying Date for all company
     */
    
    function getFrCurrentExpiryDateByUnderlyingDateofAll(  $underlying_date ){
          
        $this->db->where('status', 1); 
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->order_by('expiry_date asc'); 
        $this->db->group_by('expiry_date'); 
//        $this->db->limit(1);
        $this->db->select('expiry_date');
        
        $query = $this->db->get('future');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get Current Expiry Date By Underlying Date
     */
    
    function getFrCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $underlying_date, $live=false ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->order_by('expiry_date asc'); 
//        $this->db->limit(1);
        $this->db->select('expiry_date');
        
        if($live){
            
            $this->db->group_by('expiry_date'); 
//            $query = $this->db->get('put_call_expiry_live');
            $this->db->where('market_running', 1);
        }else{
            $this->db->where('market_running', 0);
            
        }
        
        $query = $this->db->get('future');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get future Data of stock by comapny id and symbol
     */
    
    function getFrDataOfStock( $company_id, $company_symbol, $underlying_date, $searching_underlying_date_to=false, $expiry_date, $live=false, $underlying_time=false, $get_all_expiry_date=false ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        if( !empty($searching_underlying_date_to)){
            
            $this->db->where('underlying_date >=', $underlying_date); 
            $this->db->where('underlying_date <=', $searching_underlying_date_to); 
            
        }else{
            
            $this->db->where('underlying_date', $underlying_date); 
            
        }
        
        if( $get_all_expiry_date !== 'yes' ){
         
            $this->db->where('expiry_date', $expiry_date); 
            
        }
        $this->db->select('*');
        $this->db->order_by('id');
        
        if($live){
            
            $this->db->where('underlying_time', $underlying_time);
            $this->db->where('market_running', 1);
//            $query = $this->db->get('put_call_live');
            
        }else{
            
            $this->db->where('market_running', 0);
        
//            $query = $this->db->get('put_call');
        
        }
        
        $query = $this->db->get('future');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    /*
     * @author: ZAHIR
     * DESC: Get future Data of All stock
     */
    
    function getFrDataOfAllStock( $underlying_date, $expiry_date, $turnover_sortby, $volume_sortby, $oi_sortby, $change_oi_sortby, $change_oi_p_sortby, $daily_volatility_sortby ){
          
        $this->db->where('status', 1); 
        
        $this->db->where('underlying_date', $underlying_date); 
        
        $this->db->where('expiry_date', $expiry_date); 
        
        $this->db->select('*');
        
        if( !empty($turnover_sortby)){
            
            if( $turnover_sortby === 'high' ){
                
                $this->db->order_by('total_turnover', 'desc');
                
            }else if( $turnover_sortby === 'low' ){
                
                $this->db->order_by('total_turnover');
            }
            
        }else if( !empty($volume_sortby)){
            
            if( $volume_sortby === 'high' ){
                
                $this->db->order_by('no_of_contracts_traded', 'desc');
                
            }else if( $volume_sortby === 'low' ){
                
                $this->db->order_by('no_of_contracts_traded');
            }
            
        }else if( !empty($oi_sortby)){
            
            if( $oi_sortby === 'high' ){
                
                $this->db->order_by('oi', 'desc');
                
            }else if( $oi_sortby === 'low' ){
                
                $this->db->order_by('oi');
            }
            
        }else if( !empty($change_oi_sortby)){
            
            if( $change_oi_sortby === 'high' ){
                
                $this->db->order_by('change_in_oi', 'desc');
                
            }else if( $change_oi_sortby === 'low' ){
                
                $this->db->order_by('change_in_oi');
            }
            
        }else if( !empty($change_oi_p_sortby)){
            
            if( $change_oi_p_sortby === 'high' ){
                
                $this->db->order_by('p_change_in_oi', 'desc');
                
            }else if( $change_oi_p_sortby === 'low' ){
                
                $this->db->order_by('p_change_in_oi');
            }
                        
        }else if( !empty($daily_volatility_sortby)){
            
            if( $daily_volatility_sortby === 'high' ){
                
                $this->db->order_by('daily_volatility', 'desc');
                
            }else if( $daily_volatility_sortby === 'low' ){
                
                $this->db->order_by('daily_volatility');
            }
            
        }else{
            
            $this->db->order_by('company_symbol');
        }
          
        $query = $this->db->get('future');
        
//        return $this->db->last_query();
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }

    /*
     * Insert Future Roll over Data
     */
    function insertRolloverData( $roll_over_final_arr ){
        
        $db_debug = $this->db->db_debug; //save setting
        
        $this->db->db_debug = FALSE;
        
        $this->db->insert('future_rollover', $roll_over_final_arr);
        
        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            echo '<pre>';
            print_r($errorz); 
            
            $insert_id = false;
            
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get Future rollover data of all stock
     */
    
    function getFrRolloverDataOfAllStock( $underlying_date, $rollover_sortby, $rollcost_sortby ){
        
        $this->db->where('status', 1); 
        
        $this->db->where('underlying_date', $underlying_date); 
        
        $this->db->select('*');
        
        if( !empty($rollover_sortby)){
            
            if( $rollover_sortby === 'high' ){
                
                $this->db->order_by('rollover_percentage', 'desc');
                
            }else if( $rollover_sortby === 'low' ){
                
                $this->db->order_by('rollover_percentage');
            }
            
        }else if( !empty($rollcost_sortby)){
            
            if( $rollcost_sortby === 'high' ){
                
                $this->db->order_by('roll_cost', 'desc');
                
            }else if( $rollcost_sortby === 'low' ){
                
                $this->db->order_by('roll_cost');
            }
                
        }else{ 
        
            $this->db->order_by('company_symbol');
        
        }
        
        $query = $this->db->get('future_rollover');
        
//        return $this->db->last_query();
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    function getFrRolloverofSingleStock( $company_id, $company_symbol, $underlying_date, $searching_underlying_date_to=false, $loop_count=0 ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        if( !empty($searching_underlying_date_to)){
            
            $this->db->where('underlying_date >=', $underlying_date); 
            $this->db->where('underlying_date <=', $searching_underlying_date_to); 
            
        }else{
            
            $this->db->where('underlying_date', $underlying_date); 
            
        }
        
        $this->db->select('*');
        
        $query = $this->db->get('future_rollover');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $underlying_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($underlying_date)));
            
            $data = $this->getFrRolloverofSingleStock( $company_id, $company_symbol, $underlying_date, $searching_underlying_date_to, $loop_count );
            
//            echo '<pre>'; print_r($data);
            
//            echo count($data);
            
            if( count($data) >  0 ){
                
                return $data;
            }
        }
    }
}
