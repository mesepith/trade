<?php

/*
 * @author : ZAHIR
 * DESC: Option chain , implied volatility analysis data
 */

class Oc_iv_analysis_model extends CI_Model {
    
     /*
     * @author: ZAHIR
     * DESC: Last inserted company list
     */
    
    function lastInsertedCompanyList(){
          
        $this->db->where('status', 1); 
        $this->db->where('underlying_date', date('Y-m-d')); 
        $this->db->where('market_running', 0);
//        $this->db->where('underlying_date', '2019-11-28'); 
        $this->db->order_by('id desc'); 
        $this->db->limit(1);
        $this->db->select('company_id');
        $query = $this->db->get('oc_iv_analysis');
        
        if (count($query->result()) > 0 && !empty( $query->result()[0]->company_id ) && $query->result()[0]->company_id > 0) {
        
            return $query->result()[0]->company_id;
            
        }else{
            
            return false;
        }
        
        return $query->result();
    } 
     /*
     * @author: ZAHIR
     * DESC: non inserted company list
     */
    
    function oCIVNonInserteDCompanyList( $last_inserted_company ){
          
        $this->db->where('status', 1); 
        if( $last_inserted_company ){
            
            $this->db->where('company_id >', $last_inserted_company);
            
        }
        $this->db->select('*');
        $query = $this->db->get('put_call_companies');
        
        return $query->result();
    } 
    
    
    /*
     * @author : ZAHIR
     * DESC :insert option chain analysis data . Meyhod used impled volatility
     */
    
    function insertOcBearishBullishByIV( $data, $market_running=false, $underlying_time=false, $script_start_time=false ){
//        return false;
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        
        if($market_running){
            
            $data['underlying_time'] = $underlying_time;
            
            $data["script_start_time"] = empty($script_start_time) ? '' : $script_start_time;
            
        }        
        
        $data['market_running'] = $market_running;
        
        $this->db->insert('oc_iv_analysis', $data);
        
        $insert_id = $this->db->insert_id();
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Oc_iv_analysis_model';

            $error_db_data['model_methode_name'] = 'insertOcBearishBullishByIV';

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
     * DESC: get option chain impled volatility Data of each company
     */
    function getOCIVData( $company_id, $company_symbol, $date, $date_to, $live, $searching_expiry_date, $loop_count=0 ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        $this->db->where('underlying_date >= "' . $date . '"');
        
        if( $date_to ){
            
            $this->db->where('underlying_date <= "' . $date_to . '"');
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
        
        $query = $this->db->get('oc_iv_analysis');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->getOCIVData( $company_id, $company_symbol, $date, $date_to, $live, $searching_expiry_date, $loop_count );
            
            if( count($data) >  0 ){
                
                return $data;
            }
        }
        
    }
    
    function displayOCIVDayWiseData($date, $bullish_probability, $bearish_probability, $bullish_probability_min, $bullish_probability_max, $bearish_probability_min, $bearish_probability_max, $custom_condition, $live=false, $script_start_time=false, $loop_count=0){
        
        $this->db->where('status', 1); 
        
        $this->db->where('underlying_date = "' . $date . '"');
        
        if(!empty($bullish_probability)){
            
            if( $bullish_probability ==='high'){
                
                $this->db->order_by('bullish_probability desc');
                
            }else{
                
                $this->db->order_by('bullish_probability');
            }
            
        }else if(!empty($bearish_probability)){
            
            if( $bearish_probability ==='high'){
                
                $this->db->order_by('bearish_probability desc');
                
            }else{
                
                $this->db->order_by('bearish_probability');
            }
            
        }
         
        if($bullish_probability_min>=0 && $bullish_probability_max){
         
            $this->db->where('bullish_probability >=' , $bullish_probability_min);
            $this->db->where('bullish_probability <=' , $bullish_probability_max);
            
        }
         
        if($bearish_probability_min>=0 && $bearish_probability_max){
         
            $this->db->where('bearish_probability >=' , $bearish_probability_min);
            $this->db->where('bearish_probability <=' , $bearish_probability_max);
            
        }
        
        if( !empty($custom_condition)){
            
            if($custom_condition==="bullgtbear"){
                
                $this->db->where('bullish_probability > bearish_probability' );
                
            }else if($custom_condition==="beargtbull"){
                
                $this->db->where('bearish_probability > bullish_probability' );
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
        
        $query = $this->db->get('oc_iv_analysis');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
        }else{
            
            $loop_count++;

            if($loop_count > 15){ return false; }
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->displayOCIVDayWiseData( $date, $bullish_probability, $bearish_probability, $bullish_probability_min, $bullish_probability_max, $bearish_probability_min, $bearish_probability_max, $custom_condition, $live, $script_start_time, $loop_count );
//            echo 'data <br/>';
//            echo '<pre>'; print_r($data);
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
        
        $this->db->where('underlying_date = "' . $date . '"');
        
        $this->db->where('market_running', 1);
        $this->db->group_by('script_start_time');
        $this->db->select('script_start_time');
        
        $query = $this->db->get('oc_iv_analysis');
        
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
    
   
