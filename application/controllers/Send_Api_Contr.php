<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Send_Api_Contr extends MX_Controller {
    
    function listAllCompanies( $last_inserted_company_id ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Companies_model');
            
            if( $last_inserted_company_id > 0  ){
                
                $company_list = $this->Companies_model->listFinalNonInsertedCompanies($last_inserted_company_id);
                
            }else{
                
                $company_list = $this->Companies_model->listAllCompaniesforCrawl();
                
            }
            
            return json_decode(json_encode($company_list), true);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/listAllCompanies/'. $last_inserted_company_id;
            //echo $url;
            return $company_list = json_decode(($client->request('GET', $url)->getBody()->getContents()), true);
            
        }
        
    }
    
    function makeCompanyInactive( $company_id, $company_symbol ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Companies_model');
            
            $this->Companies_model->makeCompanyInactive($company_id, $company_symbol);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/makeCompanyInactive';
            
            $response = $client->request('POST', $url, [
                    'form_params' => [
                        'company_id' => $company_id,
                        'company_symbol' => $company_symbol
                    ]
                ]);   
        }
        
        include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
        $System_Notification_contr = new System_Notification_Controller();
        
        $System_Notification_contr->makeStkInactive($company_symbol);
        
    }
    
    function lastInsertedStkCompany(){
        
        if( FINAL_DATA_SERVER === 'yes' ){
        
            $this->load->model('Companies_model');

            return $lastInsertedCompany = $this->Companies_model->lastInsertedCompany();
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/lastInsertedStkCompany';
            
            return $lastInsertedCompany = $client->request('GET', $url)->getBody()->getContents();
            
        }
        
    }
    
    function lastInsertedStkCrawledCompany(){
        
        if( FINAL_DATA_SERVER === 'yes' ){
        
            $this->load->model('Companies_model');

            return $lastInsertedCompany = $this->Companies_model->lastInsertedStkCrawledCompany();
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/lastInsertedStkCrawledCompany';
            
            $lastInsertedCompany = json_decode($client->request('GET', $url)->getBody()->getContents());
            
            return $lastInsertedCompany;
            
        }
        
    }
    
    
    /*
     * Check today final is inserted
     */
    function chkTodayFinalTaskIsDone(){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Analysis_task_model');
        
            return $check_task_done = $this->Analysis_task_model->checkAnalysisDone('stk_final_day_data');
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/chkTodayFinalTaskIsDone';
            
            return $check_task_done = $client->request('GET', $url)->getBody()->getContents();
            
        }
        
    }
    
    /*
     * Update if today final stock data is inserted
     */
    
    function todayFinalStkDataInserted(){
        
        if( FINAL_DATA_SERVER === 'yes' ){
        
            $this->load->model('Analysis_task_model');

            $this->Analysis_task_model->insertOcAnalysisDone('stk_final_day_data');
        
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/todayFinalStkDataInserted';
            
            return $check_task_done = $client->request('GET', $url)->getBody()->getContents();
            
        }
        
    }
    
    
    /*
     * Check today final PC Data is crawled
     */
    function chkTodayFinalPCDataIsCrawled(){
        
        if( FINAL_OC_SERVER === 'yes' ){
            
            $this->load->model('Analysis_task_model');
        
            return $check_task_done = $this->Analysis_task_model->checkAnalysisDone('pc_final_day_data_crawled');
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/chkTodayFinalPCDataIsCrawled';
            
            return $check_task_done = $client->request('GET', $url)->getBody()->getContents();
            
        }
        
    }
    
    /*
     * Get company id of last crawled put call companies
     */
    function lastCrawledPCCompany(){
        
        if( FINAL_OC_SERVER === 'yes' ){

            return $lastCrawledCompany = $this->Put_call_log_model->lastCrawledPCCompany();
            
        }else{
            
            $client = new GuzzleHttp\Client();
//            
            $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/lastCrawledPCCompany';
//            
            return $lastCrawledCompany = $client->request('GET', $url)->getBody()->getContents();
            
        }
        
    }
    
    /*
     * Update if today final put call data is inserted
     */
    
    function todayFinalPCDataCrawled(){
        
        if( FINAL_OC_SERVER === 'yes' ){
            
            $this->load->model('Analysis_task_model');

            $this->Analysis_task_model->insertOcAnalysisDone('pc_final_day_data_crawled');
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/todayFinalPCDataCrawled';
            
            return $check_task_done = $client->request('GET', $url)->getBody()->getContents();
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: From last crawled company id extract next to be crawled company id
     */
    
    function oCPDNonInserteDCompanyList( $last_crawled_company_id=0, $oc_pd_analysis_model=false ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            if( empty($oc_pd_analysis_model) ){
                
                $this->load->model('Oc_pd_analysis_model');
            }            
            
            $company_list = $this->Oc_pd_analysis_model->oCPDNonInserteDCompanyList( $last_crawled_company_id );
            
//            return json_decode(json_encode($company_list), true);
            return json_decode(json_encode($company_list));
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/oCPDNonInserteDCompanyList/' . $last_crawled_company_id;
            
//            return $company_list = json_decode(($client->request('GET', $url)->getBody()->getContents()), true);
            return $company_list = json_decode(($client->request('GET', $url)->getBody()->getContents()));
            
        }
    }
    
    /*
     * @author: ZAHIR
     * insert put call log data crawled from nse
     */
    function insertPutCallDataLog2( $data_log_arr, $market_running ){
        
        if( FINAL_OC_SERVER === 'yes' ){
            
            return $this->Put_call_log_model->insertPutCallDataLog2($data_log_arr, $market_running);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/insertPutCallDataLog2';
            
            return $response = ($client->request('POST', $url, [
                    'form_params' => [
                        'data_log_arr' => $data_log_arr,
                        'market_running' => $market_running
                    ]
                ])->getBody()->getContents());
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: get company ids which are not crawled
     */
    function futureNonCrawledCompanyList($last_crawled_company_id){
        
        if( FINAL_FUTURE_DATA_SERVER === 'yes' ){
            
            $this->load->model('Future_model');
            
            $company_list = $this->Future_model->futureCompanyList( $last_crawled_company_id );
            
            return json_decode(json_encode($company_list), true);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/futureNonCrawledCompanyList/' . $last_crawled_company_id;
            
            return $company_list = json_decode(($client->request('GET', $url)->getBody()->getContents()), true);
            
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Insert future crawled data
     */
    
    function insertFutureDataLog( $future_arr ){
        
        if( FINAL_FUTURE_DATA_SERVER === 'yes' ){
            
            $this->load->model('Future_model');

            $this->Future_model->insertFutureDataLog($future_arr);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/insertFutureDataLog';
            
            $response = $client->request('POST', $url, [
                    'form_params' => [
                        'future_arr' => $future_arr
                    ]
                ]);  
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: get Company Id By Symbol
     */
    
    function getCompanyIdBySymbol( $index_or_company_symbol ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Companies_model');

            return $this->Companies_model->getCompanyIdBySymbol($index_or_company_symbol);
            
        }else{
            
            $this->load->helper('function_helper');
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/getCompanyIdBySymbol/' . base64_url_encode($index_or_company_symbol);
            
            return ($client->request('GET', $url)->getBody()->getContents());
             
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: get Company Id and index id By Symbol
     */
    
    function getCompanyIdAndIndexIdBySymbol( $index_or_company_symbol, $company_model_loaded=false ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            if(empty($company_model_loaded)){ /* If model is already loaded then dont load*/
                
                $this->load->model('Companies_model');
            }
            
            return $this->Companies_model->getCompanyIdAndIndexIdBySymbol($index_or_company_symbol);
            
        }else{
            
            $this->load->helper('function_helper');
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/getCompanyIdAndIndexIdBySymbol/' . base64_url_encode($index_or_company_symbol);
            
            return ($client->request('GET', $url)->getBody()->getContents());
             
        }
        
    }
    /*
     * @author: ZAHIR
     * DESC: get Company Id and Symbol By name
     */
    
    function getCompanyIdAndSymbolByName( $company_name ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Companies_model');

            return $this->Companies_model->getCompanyIdAndSymbolByName($company_name);
            
        }else{
            
            $this->load->helper('function_helper');
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/getCompanyIdAndSymbolByName/' . base64_url_encode($company_name);
            
            return json_decode(($client->request('GET', $url)->getBody()->getContents()));
             
        }
        
    }
    
    /*
     * Insert lot size of Option Chain
     */
    function inserMonthlytLotSize( $lot_arr, $lot_model_loaded ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            if(empty($lot_model_loaded)){ /* If model is already loaded then dont load*/
                
                $this->load->model('Lot_Size_model');
            }
                        

            $this->Lot_Size_model->inserMonthlytLotSize($lot_arr);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/inserMonthlytLotSize';
            
            $response = $client->request('POST', $url, [
                    'form_params' => [
                        'lot_arr' => $lot_arr
                    ]
                ]); 
            
        }
    }
    
    /*
     * Insert lot size of Option Chain
     */
    function insertLotSize( $lot_arr, $lot_model_loaded ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            if(empty($lot_model_loaded)){ /* If model is already loaded then dont load*/
                
                $this->load->model('Lot_Size_model');
            }
                        

            $this->Lot_Size_model->insertLotSize($lot_arr);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/insertLotSize';
            
            $response = $client->request('POST', $url, [
                    'form_params' => [
                        'lot_arr' => $lot_arr
                    ]
                ]); 
            
        }
    }
    
    function checkCompanyExistInPCByIdAndSymbol($company_id, $company_symbol ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Put_call_model');
        
            $return  = $this->Put_call_model->checkCompanyExistInPCByIdAndSymbol($company_id, $company_symbol);
            
            return $return;
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/checkCompanyExistInPCByIdAndSymbol';
            
            $response = $client->request('POST', $url, [
                    'form_params' => [
                        'company_id' => $company_id,
                        'company_symbol' => $company_symbol
                    ]
                ]); 
            
            return $response;
        }
    }
    
    function checkLotExistsByExpiryDate( $lot_arr, $expiry_date_arr ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Lot_Size_model');
        
            return $lot_size = $this->Lot_Size_model->checkLotExistsByExpiryDate( $lot_arr, $expiry_date_arr );
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/checkLotExistsByExpiryDate';            
             
             $response = ($client->request('POST', $url, [
                    'form_params' => [
                        'lot_arr' => $lot_arr,
                        'expiry_date_arr' => $expiry_date_arr,
                    ]
                ])->getBody()->getContents());
             
            return $response;
        }
    }
    
    function checkLotExists( $lot_arr ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Lot_Size_model');
        
            return $lot_size = $this->Lot_Size_model->checkLotExists($lot_arr);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/checkLotExists';            
             
             $response = ($client->request('POST', $url, [
                    'form_params' => [
                        'lot_arr' => $lot_arr
                    ]
                ])->getBody()->getContents());
             
            return $response;
        }
    }
    
    function getLatestUnderlyingDate( $company_id, $company_symbol, $live ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getLatestUnderlyingDate';            

        $response = json_decode(($client->request('POST', $url, [
                'form_params' => [
                    'company_id' => $company_id,
                    'company_symbol' => $company_symbol,
                    'live' => $live
                ]
            ])->getBody()->getContents()));
         
        return $response;
        
    }
    
    function getCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $searching_underlying_date, $live ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getCurrentExpiryDateByUnderlyingDate';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'searching_underlying_date' => $searching_underlying_date,
                   'live' => $live                    
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
    
    function getAllUnderlyingTime( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getAllUnderlyingTime';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'searching_underlying_date' => $searching_underlying_date,
                   'searching_expiry_date' => $searching_expiry_date                    
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
    
    function getOCDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date, $live, $searching_underlying_time ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getOCDataOfStock';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'searching_underlying_date' => $searching_underlying_date,
                   'searching_expiry_date' => $searching_expiry_date,                    
                   'live' => $live,                    
                   'searching_underlying_time' => $searching_underlying_time                    
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
    
    function getOCIVData( $company_id, $company_symbol, $date, $manual_date_to, $live, $searching_expiry_date ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getOCIVData';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'date' => $date,
                   'manual_date_to' => $manual_date_to,
                   'live' => $live,                  
                   'searching_expiry_date' => $searching_expiry_date, 
               ]
           ])->getBody()->getContents()));
         
        return $response;
        
    }
    
    function getOcIVScriptStartTime( $date ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getOcIVScriptStartTime';  
        
        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'date' => $date                
               ]
           ])->getBody()->getContents()) );
        
//        echo '<pre>'; print_r($response);
         
        return $response;
        
    }
    
    function displayOCIVDayWiseData( $date, $bullish_probability, $bearish_probability, $bullish_probability_min, $bullish_probability_max, $bearish_probability_min, $bearish_probability_max, $custom_condition, $live, $script_start_time ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/displayOCIVDayWiseData';  
        
        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'date' => $date,                 
                   'bullish_probability' => $bullish_probability,                 
                   'bearish_probability' => $bearish_probability,                 
                   'bullish_probability_min' => $bullish_probability_min,                 
                   'bullish_probability_max' => $bullish_probability_max,                 
                   'bearish_probability_min' => $bearish_probability_min,                 
                   'bearish_probability_max' => $bearish_probability_max,                 
                   'custom_condition' => $custom_condition,                 
                   'live' => $live,
                   'script_start_time' => $script_start_time,
               ]
           ])->getBody()->getContents()));
         
        return $response;
        
    }
    
    function getOCPDData( $company_id, $company_symbol, $date, $manual_date_to, $live, $searching_expiry_date ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getOCPDData';  
        
        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'date' => $date,
                   'manual_date_to' => $manual_date_to,                  
                   'live' => $live,                  
                   'searching_expiry_date' => $searching_expiry_date,                  
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
    
    function getOcPDScriptStartTime( $date ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getOcPDScriptStartTime';  
        
        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'date' => $date                
               ]
           ])->getBody()->getContents()) );
         
        return $response;
        
    }
    
    function displayOCPDDayWiseData( $date, $put_avg_decay, $call_avg_decay, $custom_condition, $live, $script_start_time ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/displayOCPDDayWiseData';  
        
        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'date' => $date,                 
                   'put_avg_decay' => $put_avg_decay,                 
                   'call_avg_decay' => $call_avg_decay,                         
                   'custom_condition' => $custom_condition,                 
                   'live' => $live,
                   'script_start_time' => $script_start_time,
               ]
           ])->getBody()->getContents()));
         
        return $response;
        
    }
    
    function displayOCOPDayWiseData( $date, $custom_condition ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/displayOCOPDayWiseData';  
        
        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'date' => $date,                                   
                   'custom_condition' => $custom_condition,   
               ]
           ])->getBody()->getContents()));
         
        return $response;
        
    }
    
    function displayHighOiNAddOiDayWiseData( $date ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/displayHighOiNAddOiDayWiseData';  
        
        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'date' => $date
               ]
           ])->getBody()->getContents()));
         
        return $response;
        
    }
        
    function getOcSpData( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date, $strike_price, $live ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_OC_WEB_SERVER . 'Recieve_Api_Contr/getOcSpData';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'searching_underlying_date' => $searching_underlying_date,
                   'searching_expiry_date' => $searching_expiry_date,                    
                   'strike_price' => $strike_price,                    
                   'live' => $live                  
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
    
     /*
     * check Todays Bulk Block Data Inserted
     */
    function checkTodaysBulkBlockInserted( $exchange, $bulk_or_block ){
        
        if( FINAL_DATA_SERVER === 'yes' ){

            return $this->BulkBlock_model->checkTodaysBulkBlockInserted($exchange, $bulk_or_block);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/checkTodaysBulkBlockInserted';
            
            $response = $client->request('POST', $url, [
                    'form_params' => [
                        'exchange' => $exchange,
                        'bulk_or_block' => $bulk_or_block
                    ]
                ]); 
            
            
            return $response;
            
        }
    }
    
     /*
     * Insert bulk block deal
     */
    function inserBulkBlockDeal( $bulk_block_arr ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->BulkBlock_model->inserBulkBlockDeal($bulk_block_arr);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/inserBulkBlockDeal';
            
            $response = $client->request('POST', $url, [
                    'form_params' => [
                        'bulk_block_arr' => $bulk_block_arr
                    ]
                ]);            
            
        }
    }
    
    /*
     * Get Latest underlying date of future of all company
     */
    
    function getLatestFrUnderlyingDateofAll( ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/getLatestFrUnderlyingDateofAll'; 
        
        return json_decode($client->request('GET', $url)->getBody()->getContents());
        
    }
    
    /*
     * Get Latest underlying date of future, by company id
     */
    
    function getLatestFrUnderlyingDate( $company_id, $company_symbol, $live ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/getLatestFrUnderlyingDate';            

        $response = json_decode(($client->request('POST', $url, [
                'form_params' => [
                    'company_id' => $company_id,
                    'company_symbol' => $company_symbol,
                    'live' => $live
                ]
            ])->getBody()->getContents()));
         
        return $response;
        
    }
    
      
    function getFrCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $searching_underlying_date, $live ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/getFrCurrentExpiryDateByUnderlyingDate';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'searching_underlying_date' => $searching_underlying_date,
                   'live' => $live                    
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
      
    function getFrCurrentExpiryDateByUnderlyingDateofAll( $searching_underlying_date ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/getFrCurrentExpiryDateByUnderlyingDateofAll';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'searching_underlying_date' => $searching_underlying_date                
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
    
    /*
     * Get Future Data of stock by company id
     */
    
    function getFrDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_underlying_date_to, $searching_expiry_date, $live, $searching_underlying_time, $get_all_expiry_date ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/getFrDataOfStock';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'searching_underlying_date' => $searching_underlying_date,
                   'searching_underlying_date_to' => $searching_underlying_date_to,
                   'searching_expiry_date' => $searching_expiry_date,                    
                   'live' => $live,                    
                   'searching_underlying_time' => $searching_underlying_time,                    
                   'get_all_expiry_date' => $get_all_expiry_date,                    
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
    
    /*
     * Get Future Data of All stock
     */
    function getFrDataOfAllStock( $searching_underlying_date, $searching_expiry_date, $turnover_sortby, $volume_sortby, $oi_sortby, $change_oi_sortby, $change_oi_p_sortby, $daily_volatility_sortby ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/getFrDataOfAllStock';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'searching_underlying_date' => $searching_underlying_date,
                   'searching_expiry_date' => $searching_expiry_date,                
                   'turnover_sortby' => $turnover_sortby,                
                   'volume_sortby' => $volume_sortby,                
                   'oi_sortby' => $oi_sortby,                
                   'change_oi_sortby' => $change_oi_sortby,                
                   'change_oi_p_sortby' => $change_oi_p_sortby,                
                   'daily_volatility_sortby' => $daily_volatility_sortby,                
               ]
           ])->getBody()->getContents()));
         
        return $response;
    }
    
    /*
     * Get Future rollover data of all stock
     */
    function getFrRolloverDataOfAllStock( $searching_underlying_date, $rollover_sortby, $rollcost_sortby ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/getFrRolloverDataOfAllStock';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'searching_underlying_date' => $searching_underlying_date,               
                   'rollover_sortby' => $rollover_sortby,               
                   'rollcost_sortby' => $rollcost_sortby,               
               ]
           ])->getBody()->getContents()));
         
        return $response;
        
    }
    
    /*
     * Get Future rollover data of single stock
     */
    function getFrRolloverofSingleStock( $company_id, $company_symbol, $searching_underlying_date, $searching_underlying_date_to ){
        
        $client = new GuzzleHttp\Client();
            
        $url = PARENT_FUTURE_WEB_SERVER . 'Recieve_Api_Contr/getFrRolloverofSingleStock';            

        $response = json_decode(($client->request('POST', $url, [
               'form_params' => [
                   'company_id' => $company_id,
                   'company_symbol' => $company_symbol,
                   'searching_underlying_date' => $searching_underlying_date,
                   'searching_underlying_date_to' => $searching_underlying_date_to                   
               ]
           ])->getBody()->getContents()));
         
        return $response;
        
    }
    
    /*
     * Fetch Today inserted company list
     */
    function listTodayCMCompanies( $last_inserted_company_id ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Companies_model');
            
            $company_list = $this->Companies_model->listTodayCMCompanies($last_inserted_company_id);
            
            return json_decode(json_encode($company_list), true);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/listTodayCMCompanies/'. $last_inserted_company_id;
            //echo $url;
            return $company_list = json_decode(($client->request('GET', $url)->getBody()->getContents()), true);
            
        }
        
    }
    
    /*
     * Insert today's volume by total no of trades data
     */
    function insertCMVolumeByTrade( $company_id, $company_symbol, $whole_data ){
        
        if( FINAL_DATA_SERVER === 'yes' ){
            
            $this->load->model('Stock_data_model');
            
            $this->Stock_data_model->insertCMVolumeByTrade($company_id, $company_symbol, $whole_data);
            
        }else{
            
            $client = new GuzzleHttp\Client();
            
            $url = PARENT_WEB_SERVER . 'Recieve_Api_Contr/insertCMVolumeByTrade';
            
            $response = $client->request('POST', $url, [
                    'form_params' => [
                        'company_id' => $company_id,
                        'company_symbol' => $company_symbol,
                        'whole_data' => $whole_data,
                    ]
                ]);            
            
        }
    }
}
