<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Put_Call extends MX_Controller {
    
    /*
     * OBSOLETE
     */
    
    public function processPutCallLogData( $put_call_log_id=0 ) {
        ini_set('max_execution_time', 0); 

        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');
        
//        echo '$put_call_log_id :::::::::::::::: ' . $put_call_log_id . '<br/>';
        
        
        $this->load->model('Put_call_log_model');    
        $this->load->model('Put_call_model');
        
//        $limit = 15;
        
        $unprocess_log_data = $this->Put_call_log_model->fetchUnprocessedData( $put_call_log_id );
        
//        echo '$unprocess_log_data : ' . count($unprocess_log_data);
//        echo '<pre>';
//        print_r($unprocess_log_data); 
//        exit;
            
        if ($unprocess_log_data && count($unprocess_log_data)> 0 ) {


            $other_data = array();

            foreach ($unprocess_log_data AS $unprocess_log_data_key => $unprocess_log_data_value) {

                $price_date_time = json_decode($unprocess_log_data_value->price_date_time);
                $put_call_table_data = json_decode($unprocess_log_data_value->put_call_data);



                $put_call_log_id = $unprocess_log_data_value->id;

//                echo 'put_call_log_id : ' . $put_call_log_id . '<br/>';
//                echo 'company_id : ' . $unprocess_log_data_value->company_id . '<br/>';
//                echo 'company_symbol : ' . $unprocess_log_data_value->company_symbol . '<br/>';
//                echo 'expiry_date : ' . $unprocess_log_data_value->expiry_date . '<br/>';

                $other_data['put_call_log_id'] = $unprocess_log_data_value->id;
                $other_data['company_id'] = $unprocess_log_data_value->company_id;
                $other_data['company_symbol'] = $unprocess_log_data_value->company_symbol;
                $other_data['expiry_date'] = $unprocess_log_data_value->expiry_date;


                $other_data['underlying_price'] = $price_date_time->underlying_price;
                $other_data['underlying_date_time'] = str_replace('"', "", $price_date_time->underlying_date_time);
                $other_data['underlying_date'] = str_replace('"', "", $price_date_time->underlying_date);
                $other_data['underlying_time'] = str_replace('"', "", $price_date_time->underlying_time);

                $this->extractPutCallTable($put_call_table_data, $other_data);                                                
                
                /* Fillup put_call_expiry table start */
                
                $expiry_data = $other_data;
                $expiry_data['pcl_created_at_date'] = $unprocess_log_data_value->created_at_date;
                $expiry_data['pcl_created_at_time'] = $unprocess_log_data_value->created_at_time;
                $expiry_data['pcl_created_at'] = $unprocess_log_data_value->created_at;
                
                $this->Put_call_model->insertExpiryWithPrice( $expiry_data );
                
                /* Fillup put_call_expiry table end */
                
                
                $data_process_status = 1;
                $this->Put_call_log_model->updatePutCallDataProcessStatus($put_call_log_id, $unprocess_log_data_value->company_id, $data_process_status);

//                exit;
            }
                        
//            $new_start = ($start+$limit);
//            print('just before redirect');
            
            $this->processPutCallLogData( $put_call_log_id );
            flush();
            
            /* echo $redirect_url = base_url() . 'data-process/put-call-log-data/' . $put_call_log_id;
            
            redirect($redirect_url, 'refresh'); */
            
        } else {
            echo $redirect_url = 'none'; exit;
//            echo '<br/> <h1>No unprocess data is available</h1>';
        }

        exit;

    }
    
    /*
     * OBSOLETE
     */
    
    function extractPutCallTable($put_call_table_data, $put_call_arr) {
        
        $this->load->model('Put_call_model');
        
//        echo '<pre>';
//        print_r($put_call_table_data->data);
        
        foreach($put_call_table_data->data AS $put_call_table_data_key=>$put_call_table_data){
            
            $put_call_arr['calls_oi'] = $put_call_table_data[1];
            $put_call_arr['calls_chng_in_oi'] = $put_call_table_data[2];
            $put_call_arr['calls_volume'] = $put_call_table_data[3];
            $put_call_arr['calls_iv'] = $put_call_table_data[4];
            $put_call_arr['calls_ltp'] = $put_call_table_data[5];
            $put_call_arr['calls_net_chng'] = $put_call_table_data[6];
            $put_call_arr['calls_bid_qty'] = $put_call_table_data[7];
            $put_call_arr['calls_bid_price'] = $put_call_table_data[8];
            $put_call_arr['calls_ask_price'] = $put_call_table_data[9];
            $put_call_arr['calls_ask_qty'] = $put_call_table_data[10];
            $put_call_arr['strike_price'] = empty($put_call_table_data[11]) ? 0 :$put_call_table_data[11];
            $put_call_arr['puts_bid_qty'] = $put_call_table_data[12];
            $put_call_arr['puts_bid_price'] = $put_call_table_data[13];
            $put_call_arr['puts_ask_price'] = $put_call_table_data[14];
            $put_call_arr['puts_ask_qty'] = $put_call_table_data[15];
            $put_call_arr['puts_net_chng'] = $put_call_table_data[16];
            $put_call_arr['puts_ltp'] = $put_call_table_data[17];
            $put_call_arr['puts_iv'] = $put_call_table_data[18];
            $put_call_arr['puts_volume'] = $put_call_table_data[19];
            $put_call_arr['puts_chng_in_oi'] = $put_call_table_data[20];
            $put_call_arr['puts_oi'] = $put_call_table_data[21];
            
            $this->Put_call_model->insertPutCallData($put_call_arr);
            
        }
    }
    
    /* @author: ZAHIR
     * DESC Extract companies which have list on option chain and store in put_call_companies table
     */
    function processCompanyList(){
        
        $this->load->model('Put_call_model');
        
        $date = date('Y-m-d');
        
        $unprocess_company_list = $this->Put_call_model->companiesListByLatestDate( $date );
        
//        echo '<pre>'; print_r($unprocess_company_list);
        
        $process_company_list = array();
        foreach ($unprocess_company_list as $unprocess_company_list_value) {
            $process_company_list[$unprocess_company_list_value->company_id] = $unprocess_company_list_value->company_symbol;
        }
        $company_list = array_unique($process_company_list);
        echo '################';
        echo '<pre>'; print_r($company_list);
        
        $this->Put_call_model->storePutCallCompanyList( $company_list );
        
        exit;
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check if put call data is inserted or not
     */
    function checkDataInserted( $date ){
        
//        $date = date('Y-m-d');
        
//        echo $date;
        
        $this->load->model('Put_call_model');
        $this->load->model('Put_call_log_model');
        
        $first_list_companies = $this->Put_call_model->getFirstListOfCompanies();
        
//        echo '<pre>'; print_r($first_list_companies);
        
        $company_id_arr = array();
        
        foreach($first_list_companies AS $first_list_companies_value){
            
            $company_id_arr[] = $first_list_companies_value->company_id;
            
        }
        
//        echo '<pre>'; print_r($company_id_arr);
        
        $put_call_data = $this->Put_call_log_model->checkCompaniesDataPresentByDate( $company_id_arr, $date );
        
//        echo 'is put_call log inserted data : ';
//        echo '<pre>'; print_r($put_call_data);
        
        echo $put_call_data;
        
        exit;
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: check todays put option data inserted by last companies 
     */
    
    function checkTodayDataInserted(){
        
        $this->load->model('Put_call_model');
        
        $date = date('Y-m-d');
//        $yesterday_date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
//        
//        $previous_companies = $this->Put_call_model->getPreviousDaysBottomCompanyList( $yesterday_date );
//        
//        echo '<pre>'; print_r($previous_companies);
//        
//        $previous_company_symbol_arr = array();
//        
//        foreach( $previous_companies AS $previous_companies_value ){
//            
//            $previous_company_symbol_arr[] = $previous_companies_value->company_symbol;
//        }
//        
//        echo '$previous_company_symbol_arr';
//        echo '<pre>'; print_r($previous_company_symbol_arr);
        
        $current_companies = $this->Put_call_model->getTodaysBottomCompanyList( $date );
        
        if( empty($current_companies) ){
            
            echo 'Todays option chain data is not inserted ' . $date;
            $this->notifyNoOptionChainData( $date );
            
            exit;
        }else{
            
            echo 'Todays option chain data is inserted ' . $date;
        }
        
//        $todays_put_option_data_inserted = 'no';
//        
//        foreach( $current_companies AS $current_companies_value ){
//            
//            $current_company_symbol_arr[] = $current_companies_value->company_symbol;
//            
//            if(in_array($current_companies_value->company_symbol, $previous_company_symbol_arr) ){
//                
//                echo $current_companies_value->company_symbol . ' is present on previous day' . '<br/>';
//                
//                $todays_put_option_data_inserted = 'yes';
//                
//            }
//            
//        }
//        
//        echo '$todays_put_option_data_inserted  ' . $todays_put_option_data_inserted . '<br/>';
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Notify that no option chain data inserted
     */
    function notifyNoOptionChainData( $date ){
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ampstart.co';
        $mail_data['to']='zahir.alam@ahealz.com';               
        $mail_data['subject'] = 'No Option Chain Data inserted - ' . date('M d, Y', strtotime($date));   
        $mail_data['message']= "We haven't find todays option chain data of date " . date('M d, Y', strtotime($date));
        send_mailz($mail_data);
    }
    
    /*
     * @author: ZAHIR
     * DESC: Fetch Companies List to extract put call urls from nse
     * if date is less than equal to 4 then we fetch data from companies table as it contains all companies  name, 
     * else we fetch data from put_call_companies table due to as it has only companies which are present only on option chain
     */
    public function fetchDataToExtractPCUrls(  ) {
        
        if( date("d") <= 4 ){
            
            $this->load->model('Companies_model');

            $company_list = $this->Companies_model->listAllCompanies();
            
        }else{
        
            $this->load->model('Put_call_model');
            
            $company_list = $this->Put_call_model->listOptionChainCompanyList();
        
        }
        
        echo json_encode($company_list); 
        exit;
    }
    
    /* 
     * @author: ZAHIR
     * Set status = 2 for inactive companies in put_call_companies table
     */
    function makeOCCompanyInactive( $company_id, $company_symbol ){
        
        $this->load->model('Put_call_model');
        
        $this->Put_call_model->makeOCCompanyInactive($company_id, $company_symbol);
        
        include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
        $System_Notification_contr = new System_Notification_Controller();
        
        $System_Notification_contr->makeOCCompanyInactive($company_symbol);
    }

}
