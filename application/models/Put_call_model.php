<?php

/*
 * @author : ZAHIR
 */

class Put_call_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC :insert put option data log
     */
    
    function insertPutCallData( $data, $market_running=0 ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $data["market_running"] = $market_running;
        
        $this->db->insert('put_call', $data);
        
        $insert_id = $this->db->insert_id();
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Put_call_model';

            $error_db_data['model_methode_name'] = 'insertPutCallData';

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
     * DESC: Insert expiry date with other info
     */
    function insertExpiryWithPrice( $data, $market_running=0 ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;        
        
        $data["market_running"] = $market_running;
        
        $this->db->insert('put_call_expiry', $data);
        
        $insert_id = $this->db->insert_id();
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Put_call_model';

            $error_db_data['model_methode_name'] = 'expiryWithPriceInsert';

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
     * DESC: company list by latest date
     */
    
    function companiesListByLatestDate( $date ){
          
        $this->db->where('status', 1);    
        $this->db->where('underlying_date', $date); 
        $this->db->select('*');
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->companiesListByLatestDate( $date );
            
            if( count($data) >  0 ){

                return $data;
            }
        }
    }
    /*
     * @author: ZAHIR
     * DESC: company list by latest date
     */
    
    function storePutCallCompanyList( $company_list ){
      
        $this->load->model('Db_error_log');
        
        foreach( $company_list AS $company_id=>$company_symbol){
                        
            $is_company_exist = $this->checkCompanyExistInPCByIdAndSymbol( $company_id, $company_symbol );
            
//            $this->db->where('company_id', $company_id);
//            $this->db->where('company_symbol', $company_symbol);
//            $this->db->update('put_call_companies', array('status' => 0));                        
            
            if( empty($is_company_exist) ){
            
                $data["created_at"] = date("Y-m-d H:i:s");
                $data['company_id'] = $company_id;
                $data['company_name'] = $this->getCompanyNameByIdAndSymbol( $company_id, $company_symbol );
                $data['company_symbol'] = $company_symbol;

                $this->db->insert('put_call_companies', $data);
                
                echo $company_symbol . ' is successfully inserted <br/> <br/>';
            
            }else{
                
                echo $company_symbol . ' is already exists <br/>';
            }
        }
        
        
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get Company Name By Id And Symbol
     */
    
    function getCompanyNameByIdAndSymbol( $company_id, $company_symbol ){
        
        $this->db->where('id', $company_id); 
        $this->db->where('symbol', $company_symbol); 
        $this->db->select('name');
        $query = $this->db->get('companies');
        
        if (count($query->result()) > 0) {
        
            $data = $query->result();

            return $data[0]->name;
        
        }else{
            
            return 'Company Name Not Found';
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: check company already exists in put_call_companies table by company id and symbol
     */
    
    function checkCompanyExistInPCByIdAndSymbol( $company_id, $company_symbol ){
        
        $this->db->where('company_id', $company_id);
        $this->db->or_where('company_symbol', $company_symbol);
        $this->db->where('status', 1);
        
        $this->db->select('*');
        $query = $this->db->get('put_call_companies');
        
//        echo $this->db->last_query(); exit;

        if (count($query->result()) > 0) {

            return true;
        } else {

            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: company which have option chain
     */
    
    function displayOptionChainCompanyList(){
          
        $this->db->where('status', 1); 
        $this->db->select('*');
        $this->db->order_by('company_symbol');
        $query = $this->db->get('put_call_companies');
        
        return $query->result();
    }    
    /*
     * @author: ZAHIR
     * DESC: company which have option chain
     */
    
    function listOptionChainCompanyList(){
          
        $this->db->where('status', 1); 
        $this->db->select('company_id as id, company_name as name, company_symbol as symbol, ');
        $this->db->order_by('company_symbol');
        $query = $this->db->get('put_call_companies');
        
        return $query->result();
    }    
    
    /*
     * @author: ZAHIR
     * DESC: Get Expiry Date By Latest Underlying Date
     */
    
    function getLatestUnderlyingDate( $company_id, $company_symbol, $live=false ){
          
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
        
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {
        
            return $query->result()[0];
            
        }else{
            
            return false;
        }
    }    
    /*
     * @author: ZAHIR
     * DESC: Get Current Expiry Date By Underlying Date
     */
    
    function getCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $underlying_date, $live=false ){
          
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
        
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }

/*
     * @author: ZAHIR
     * DESC: Get All underlying time of each stock for live data
     */
    
    function getAllUnderlyingTime( $company_id, $company_symbol, $underlying_date, $expiry_date ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->order_by('expiry_date asc'); 
//        $this->db->limit(1);
        $this->db->select('underlying_time');
        
//        $query = $this->db->get('put_call_expiry_live');
        $this->db->where('market_running', 1);
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get option chain Data of stock
     */
    
    function getOCDataOfStock( $company_id, $company_symbol, $underlying_date, $expiry_date, $live=false, $underlying_time=false ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->where('expiry_date', $expiry_date); 
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
        
        $query = $this->db->get('put_call');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }    
    /*
     * @author: ZAHIR
     * DESC: Get first list of companies
     */
    
    function getFirstListOfCompanies(  ){
          
        $this->db->where('status', 1); 
        $this->db->limit(3); 
        $this->db->select('*');
        
        $query = $this->db->get('put_call_companies');
        
        return $query->result();
    }

    /*
     * @author: ZAHIR
     * DESC: Get option chain Data of stock
     */
    
    function getOi( $company_id, $company_symbol, $underlying_date, $expiry_date ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->select('id, calls_oi, calls_chng_in_oi, strike_price, puts_oi, puts_chng_in_oi');
        
        $query = $this->db->get('put_call');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: fetch previous days last companies of last trading date
     */
    
    function getPreviousDaysBottomCompanyList( $date ){
        
        $this->db->where('status', 1);    
        $this->db->where('underlying_date', $date); 
        $this->db->group_by('company_id'); 
        $this->db->order_by('company_id', 'desc'); 
        $this->db->limit(10); 
        $this->db->select('*');
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->companiesListByLatestDate( $date );
            
            if( count($data) >  0 ){

                return $data;
            }
        }
        
    }
    /*
     * @author: ZAHIR
     * DESC: fetch previous days last companies of todays date
     */
    
    function getTodaysBottomCompanyList( $date ){
        
        $this->db->where('status', 1);    
        $this->db->where('underlying_date', $date); 
        $this->db->group_by('company_id'); 
        $this->db->order_by('company_id', 'desc'); 
        $this->db->limit(10); 
        $this->db->select('*');
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
            
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * get Underlying Price By Expiry Date And Underlying Date
     */
    
    function getUnderlyingPrice( $expiry_date, $underlying_date, $company_id, $company_symbol ){
        
        
        $this->db->where('status', 1);         
        $this->db->where('expiry_date', $expiry_date);
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol);
        
        $this->db->limit(1); 
        $this->db->select('underlying_price');
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0 && !empty( $query->result()[0]->underlying_price ) && $query->result()[0]->underlying_price > 0 ) {

            return $query->result()[0]->underlying_price;
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: 
     */
    function getFirstTableRowWithBiggerStrikePriceThanUnderlyingPrice($underlying_price, $expiry_date, $underlying_date, $company_id, $company_symbol, $market_running=false, $underlying_time=false){
        
        $this->db->where('status', 1);         
        $this->db->where('expiry_date', $expiry_date);
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('strike_price >', $underlying_price);
        $this->db->where('strike_price >', 0);
        
        $this->db->order_by('strike_price', 'asc');
        
        $this->db->limit(1); 
        $this->db->select('calls_iv, strike_price, puts_iv');
        
        if( $market_running ){
            
            $this->db->where('underlying_time', $underlying_time);
            
        }
        
        $this->db->where('market_running', $market_running);
        
        $query = $this->db->get('put_call');
        
        if (count($query->result()) > 0 ) {

            return $query->result();
            
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: get Second Strike Price 
     */
    function getSecondStrikePrice( $underlying_price, $expiry_date, $underlying_date, $company_id, $company_symbol, $strike_price, $market_running=false, $underlying_time=false ){
        
        $this->db->where('status', 1);         
        $this->db->where('expiry_date', $expiry_date);
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('strike_price <', $strike_price);
        $this->db->where('strike_price >', 0);
        
        $this->db->order_by('strike_price', 'desc');
        
        $this->db->limit(1); 
        $this->db->select('strike_price');        
        
        if( $market_running ){
            
            $this->db->where('underlying_time', $underlying_time);

        }
        
        $this->db->where('market_running', $market_running);
        $query = $this->db->get('put_call');
        
        if (count($query->result()) > 0 && !empty( $query->result()[0]->strike_price ) && $query->result()[0]->strike_price > 0 ) {

            return $query->result()[0]->strike_price;
            
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: 
     * DESC: get strike price having highest oi in call
     */
    
    function getStrikePriceWithHighestOiInCall($expiry_date, $underlying_date, $company_id, $company_symbol, $strike_price, $market_running=false, $underlying_time=false){
        
        $this->db->where('status', 1);         
        $this->db->where('expiry_date', $expiry_date);
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol);
        
        $this->db->where('strike_price >', $strike_price);
        $this->db->where('strike_price >', 0);
        
        $this->db->order_by('calls_oi', 'desc');
        
        $this->db->limit(1); 
        
        $this->db->select('strike_price');
        
        if( $market_running ){
            
            $this->db->where('underlying_time', $underlying_time);

        }
        
        $this->db->where('market_running', $market_running);
        $query = $this->db->get('put_call');
        
//        echo $this->db->last_query();
        if (count($query->result()) > 0 && $query->result()[0]->strike_price > 0 ) {

            return $query->result()[0]->strike_price;
            
        }else{
            
            return false;
        }
        
    }
    /*
     * @author: Zahir
     * DESC: get strike price having highest oi in put
     */
    
    function getStrikePriceWithHighestOiInPut($expiry_date, $underlying_date, $company_id, $company_symbol, $strike_price, $secondMinStrikePrice, $market_running=false, $underlying_time=false){
        
        $this->db->where('status', 1);         
        $this->db->where('expiry_date', $expiry_date);
        $this->db->where('underlying_date', $underlying_date); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol);
        
        $this->db->where('strike_price >', 0);
        $this->db->where('strike_price <', $secondMinStrikePrice);
        
        $this->db->order_by('puts_oi', 'desc');
        
        $this->db->limit(1); 
        
        $this->db->select('strike_price');        
        
        if( $market_running ){
            
            $this->db->where('underlying_time', $underlying_time);            

        }   
        
        $this->db->where('market_running', $market_running);
        $query = $this->db->get('put_call');
        
//        echo $this->db->last_query(); 
        if (count($query->result()) > 0 && $query->result()[0]->strike_price > 0 ) {

            return $query->result()[0]->strike_price;
            
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get All Underlying Date
     */
    
    function geAllUnderlyingDate( $company_id, $company_symbol ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->order_by('underlying_date'); 
        $this->db->group_by('underlying_date');
        $this->db->select('underlying_date, underlying_price');
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: get first date of companies , since when we inserted data in table
     * PCE: put_call_expiry table
     */
    function getFirstDateofPCETable( $company_id, $company_symbol ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->order_by('underlying_date'); 
        $this->db->limit(1);
        $this->db->select('underlying_date');
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0 && $query->result()[0]->underlying_date > 0) {
        
            return $query->result()[0]->underlying_date;
            
        }else{
            
            return false;
        }
        
    }
    
    
    /*
     * @author: ZAHIR
     * DESC: Set status = 2 for inactive companies in put_call_companies table
     */
    
    function makeOCCompanyInactive( $company_id, $company_symbol ){
        
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->update('put_call_companies', array('status' => 2,'updated_at'=> date("Y-m-d H:i:s") ));
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get option chain Data by strike Price of stock or index
     */
    
    function getOcSpData( $company_id, $company_symbol, $underlying_date, $expiry_date, $strike_price, $live ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
         
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->where('strike_price', $strike_price); 
        
        if( !empty($live) && $live === 'live' ){
            
            $this->db->where('underlying_date', $underlying_date);
            $this->db->where('market_running', 1);
        }else{
            
             $this->db->where('market_running', 0);
        }
        
        $this->db->select('*');
        $this->db->order_by('id');        
        
        $query = $this->db->get('put_call');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    } 
    
}
    
   
