<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
include_once (dirname(__FILE__) . "/Nse_Contr.php");
include_once (dirname(__FILE__) . "/Send_Api_Contr.php");
include_once (dirname(__FILE__) . "/Fetch_Controller.php");

class Fetch_Put_Call_Contr extends MX_Controller {
    
    /*
     * Crawl option chain data from nse
     */
    
    function fetchPutCallLog(){
        
        $Nse_Contr = new Nse_Contr();                
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
        if( $check_open_and_closing_price === 'no' ){ return; }/* Check if market is open, if returns no then exit */
        
//        echo $check_open_and_closing_price; exit;
        
        $last_crawled_company_id=0;
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Put_call_log_model');
        
        $market_running = 1;
        
        /* 
         * Insert todays final put call crawl log data :
         * Here this ($check_open_and_closing_price) means closing price
         * Logic: We check time is greater than equal to 6pm and closing price > 0 , that means markets final data is updated OR
         * if time is greater than equal to 7pm , we will consider that markets final data is already updated
         */
        
        if( ( $check_open_and_closing_price > 0 ) || ( date('H')>= HOUR_FOR_FINAL_DATA && $check_open_and_closing_price > 0 ) || ( date('H')>= (HOUR_FOR_FINAL_DATA+1) ) ){
            
            $check_task_done = $Send_Api_Contr->chkTodayFinalPCDataIsCrawled();
            
            if( $check_task_done === 'done'){ echo 'Today final option data is crawled'; return; }
//            
            $last_crawled_company_id = $Send_Api_Contr->lastCrawledPCCompany();
//            
            $market_running = 0;
            
        }else if( date('H')>= HOUR_FOR_FINAL_DATA ){
            
            return;
        }
//        $market_running = 1; $last_crawled_company_id=306; #comment after testing
        
        $oc_pd_analysis_model=false;
        
        if( $market_running == 1 ){
            
            $this->load->model('Oc_iv_analysis_model');
            $this->load->model('Oc_pd_analysis_model');
            $this->load->model('Oc_pd_input_model');
            
            $oc_pd_analysis_model='yes';
        
        }
        
        $company_list = $Send_Api_Contr->oCPDNonInserteDCompanyList( $last_crawled_company_id, $oc_pd_analysis_model );
        
//        echo '<pre>'; print_r($company_list); exit;
        
        /*
         * closing price > 0 , that means markets final data is updated
         */
        
        if( empty($company_list) && $check_open_and_closing_price > 0 ){             

            $Send_Api_Contr->todayFinalPCDataCrawled();
            echo '<br/>';
            echo 'All companies PC Data Crawled';
            echo '<br/>';
            
            return;
        }
        
        $System_Notification_contr = new System_Notification_Controller();
        
//        echo '<pre>'; print_r($company_list);
        
        $script_start_time = date('H:i:s');
        
        foreach ($company_list AS $company_list_value) {
            
            $company_symbol = $company_list_value->company_symbol;

            $company_id = $company_list_value->company_id;
            
            if( $market_running == 1 && $company_id == 1739 ){ continue; } /* During Live market do not crawl NIFTY */
            
//            if($company_id==458){ echo 'remove after test'; exit;} #remove after test
            
            $stock_or_index = $company_list_value->stock_or_index;
            
            $this->crawlPCLogData($company_id, $company_symbol, $System_Notification_contr, $Nse_Contr, $Send_Api_Contr, $stock_or_index, $market_running, $script_start_time);
            
//            exit; #comment after testing
            
        }
        
    }
    
    /*
     * Crawl Single company or index
     * https://option.ampstart.co/Fetch_Put_Call_Contr/singleCrawlinLive
     */
    function singleCrawlinLive( $company_id = '1739', $company_symbol = 'NIFTY', $stock_or_index=2 ){
        echo $company_id . '<br/>';
        echo $company_symbol . '<br/>'; 
//        exit;
        $Nse_Contr = new Nse_Contr();
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
        if( $check_open_and_closing_price === 'no' ){ return; }/* Check if market is open, if returns no then exit */
        
        $this->load->model('Put_call_log_model');
        
        $this->load->model('Oc_iv_analysis_model');
        $this->load->model('Oc_pd_analysis_model');
        $this->load->model('Oc_pd_input_model');
        
//        $company_id = '1739';
//        $company_symbol = 'NIFTY';
        
        
        $Send_Api_Contr = new Send_Api_Contr();
        $System_Notification_contr = new System_Notification_Controller();
        
//        $stock_or_index= 2;
        $market_running=1;
        
        $script_start_time = date('H:i:s');
        
        $this->crawlPCLogData($company_id, $company_symbol, $System_Notification_contr, $Nse_Contr, $Send_Api_Contr, $stock_or_index, $market_running, $script_start_time);
    }
    
    function crawlPCLogData( $company_id, $company_symbol, $System_Notification_contr, $Nse_Contr, $Send_Api_Contr, $stock_or_index, $market_running, $script_start_time ){
        
        
        $pc_log_data = $this->curlPutCallData( urlencode($company_symbol), $Nse_Contr, $stock_or_index );
        
        if( empty($pc_log_data) ){
            
            $System_Notification_contr->putCallFailCrawl($company_id, $company_symbol);
            
            return;
        }
        
        $data_log_arr = array();
        
        $data_log_arr['company_id'] = $company_id;
        $data_log_arr['company_symbol'] = $company_symbol;
        
        if( empty($pc_log_data['records']) || empty($pc_log_data['records']['data']) || empty($pc_log_data['records']['expiryDates']) || empty($pc_log_data['records']['underlyingValue']) || empty($pc_log_data['records']['timestamp']) ){ 
            
            $System_Notification_contr->putCallNoRecord($company_id, $company_symbol); return; 
            
        } 
            
        $data_log_arr['put_call_data'] = json_encode($pc_log_data['records']['data']);
        
        $data_log_arr['expiry_dates'] = json_encode($pc_log_data['records']['expiryDates']);
        
        $data_log_arr['underlying_price'] = trim($pc_log_data['records']['underlyingValue']);
        
        $data_log_arr['market_date_time'] = date('Y-m-d H:i:s', strtotime(trim($pc_log_data['records']['timestamp']) ) );
        $data_log_arr['market_date'] = date('Y-m-d', strtotime(trim($pc_log_data['records']['timestamp']) ) );
        $data_log_arr['market_time'] = date('H:i:s', strtotime(trim($pc_log_data['records']['timestamp']) ) );         
        
        $data_log_arr['server'] = SERVER_NAME;   
        
        $put_call_log_id = $Send_Api_Contr->insertPutCallDataLog2($data_log_arr, $market_running);
        
        echo 'put_call_log_id ' .$put_call_log_id;
        
        if( $market_running ){
            
            $this->processPutCallLogData2($put_call_log_id, $market_running, $script_start_time, $Send_Api_Contr);
        }
        
    }
    
    function curlPutCallData( $company_symbol, $Nse_Contr, $stock_or_index ){
        
        if( $stock_or_index == 1 ){// 1 means stock
        
            $url = 'https://www.nseindia.com/api/option-chain-equities?symbol=' . $company_symbol;

            $referer = 'https://www.nseindia.com/get-quotes/derivatives?symbol='. $company_symbol;                
        
        }else if( $stock_or_index == 2){ // 2 means index
            
            $url = 'https://www.nseindia.com/api/option-chain-indices?symbol=' . $company_symbol;
            
            $referer = 'https://www.nseindia.com/get-quotes/derivatives?symbol='. $company_symbol;
            
        }else{
            
            return false;
        }
        
        $return  = $Nse_Contr->curlNse($url, $referer);
        
        return $return;
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Process put call data fetch from nse and store it in put_call_expiry and put_call table
     */
    
    function processPutCallLogData2( $put_call_log_id=0, $market_running=0, $script_start_time=false, $Send_Api_Contr=false ) {
        
        ini_set('max_execution_time', 0); 

        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');
        
        if(empty($Send_Api_Contr)){ $Send_Api_Contr = new Send_Api_Contr(); }
        
        $System_Notification_contr = new System_Notification_Controller();
        
        $this->load->model('Put_call_log_model');  
        $this->load->model('Put_call_model');  
        
        $unprocess_log_data = $this->Put_call_log_model->fetchUnprocessedData2( $put_call_log_id, $market_running );
        
        if ($unprocess_log_data && count($unprocess_log_data)> 0 ) {
            
            $other_data = array();
            
            foreach ($unprocess_log_data AS $unprocess_log_data_key => $unprocess_log_data_value) {
                
                $put_call_table_data = json_decode($unprocess_log_data_value->put_call_data, true);
                
                $expiry_date_arr = json_decode($unprocess_log_data_value->expiry_dates, true);
                
                $other_data['put_call_log_id'] = $unprocess_log_data_value->id;
                $other_data['company_id'] = $unprocess_log_data_value->company_id;
                $other_data['company_symbol'] = $unprocess_log_data_value->company_symbol;
                $other_data['underlying_price'] = $unprocess_log_data_value->underlying_price;
                $other_data['underlying_date_time'] = $unprocess_log_data_value->market_date_time;
                $other_data['underlying_date'] = $unprocess_log_data_value->market_date;
                $other_data['underlying_time'] = $unprocess_log_data_value->market_time;  
                
                
                /* Get Lot size by Expiry Month : Start */
                
                $lot_arr['company_id'] = $other_data['company_id'];
                $lot_arr['company_symbol'] = $other_data['company_symbol'];
                                 
//                echo '<pre>'; print_r($lot_arr);
                
                $lot_expiry_arr = array();
                
                foreach( $expiry_date_arr AS $expiry_date_value){ 
                    
//                    echo '<br/> expiry_date_value : ' . $expiry_date_value;
                    
                    $lot_size = $Send_Api_Contr->checkLotExistsByExpiryDate($lot_arr, $expiry_date_value);
                    
                    
                    if( empty($lot_size) ){
                
                        $Fetch_Controller = new Fetch_Controller();

                        $lot_model_loaded = 1;

                        $Fetch_Controller->readLotSizeMonthly($lot_model_loaded);

                        $lot_size = $Send_Api_Contr->checkLotExistsByExpiryDate($lot_arr, $expiry_date_value); 

                        if( empty($lot_size) ){

                            $System_Notification_contr->noLotSizeFound($lot_arr);

                            continue;

                        }
                    }
                    
                    
//                    $lot_expiry_arr[$expiry_date_value] = $lot_size;
                    $lot_expiry_arr[date('Y-m-d', strtotime( $expiry_date_value))] = $lot_size;
                    
//                    echo '<br/> lot_size : ' . $lot_size;
                }
                
//                echo '<pre>'; print_r($lot_expiry_arr); 
//                echo '<pre>'; print_r($expiry_date_arr); 
//                exit;
                
                /* Get Lot size by Expiry Month : End */
                
                
                $return = $this->extractPutCallTable2( $put_call_table_data, $other_data, $System_Notification_contr, $market_running, $Send_Api_Contr, $lot_expiry_arr );
                
                if( $return !== "success"){ continue; }
                
                foreach( $expiry_date_arr AS $expiry_date_value){                                      
                    
                    $expiry_data = $other_data;
                    
                    $expiry_data['expiry_date'] = date('Y-m-d', strtotime($expiry_date_value) );
                    $expiry_data['pcl_created_at_date'] = date('Y-m-d', strtotime($unprocess_log_data_value->created_at));
                    $expiry_data['pcl_created_at_time'] = date('H:i:s', strtotime($unprocess_log_data_value->created_at));
                    $expiry_data['pcl_created_at'] = $unprocess_log_data_value->created_at;       
                    $this->Put_call_model->insertExpiryWithPrice( $expiry_data, $market_running );
                    
                    /*
                     * If market is runnning then bull, bear analysis by option chain
                     */
                    if($market_running){
                        
                        $analysis_input_arr = array();
                        
                        $analysis_input_arr['company_id'] = $other_data['company_id'];
                        $analysis_input_arr['company_symbol'] = $other_data['company_symbol'];
                        $analysis_input_arr['underlying_date'] = $other_data['underlying_date'];
                        $analysis_input_arr['underlying_time'] = $other_data['underlying_time'];
                        $analysis_input_arr['underlying_price'] = $other_data['underlying_price'];
                        
                        $analysis_input_arr['expiry_date'] = $expiry_data['expiry_date'];
                        
                        $this->pcAnalysisBegin( $analysis_input_arr, $market_running, $script_start_time );
                    }
                    
                }
                
//                exit; #remove after test
                
                $data_process_status = 1;
                $this->Put_call_log_model->updatePutCallDataProcessStatus2($unprocess_log_data_value->id, $unprocess_log_data_value->company_id, $data_process_status, $market_running);
                
            } 
            
            if( empty($market_running) ){
            
                $this->processPutCallLogData2( $put_call_log_id );
            
            }
            flush();
            
        }else{
            
            echo 'put call data processed';
            exit;
        }
        
        
    }        
    
    function extractPutCallTable2( $put_call_table_data, $put_call_arr, $System_Notification_contr, $market_running, $Send_Api_Contr, $lot_expiry_arr ){
        
        $lot_arr['company_id'] = $put_call_arr['company_id'];
        $lot_arr['company_symbol'] = $put_call_arr['company_symbol']; 
        
        /*       
        $lot_arr['derivative_type'] = 'oc';
        
        $lot_size = $Send_Api_Contr->checkLotExists($lot_arr);
        
        if( empty($lot_size)){
            
            $Fetch_Controller = new Fetch_Controller();
            
            $lot_model_loaded = 1;
            
            $Fetch_Controller->readLotSizeOfOC($lot_model_loaded);
            
            $lot_size = $this->Lot_Size_model->checkLotExists($lot_arr);
            
            if( empty($lot_size)){
                
                $System_Notification_contr->noLotSizeFound($lot_arr);
                
                return;
                
            }
        }
         
         */
        
//        echo '<pre>';
//        print_r($put_call_arr);
        
        $date_arr = array();
        
        $option_type_arr = array('CE'=>'calls','PE'=>'puts');
        
        foreach( $put_call_table_data AS $key=>$put_call_table_value){
            
            $expiry_date = '';
            
            if( empty($put_call_table_value['expiryDate']) ){ return; }
            
            $expiry_date = date('Y-m-d', strtotime($put_call_table_value['expiryDate']));
            
            /* Get Lot size by month start */
          
            echo '<br/ >expiry_datez ' . $expiry_date;
            
            $lot_size = $lot_expiry_arr[$expiry_date];
            
            echo '<br/ >lot_size ' . $lot_size;
            
            if( empty($lot_size)){ $System_Notification_contr->noLotSizeFound($lot_arr); return; }
            
//            exit;
            
            /* Get Lot size by month end */
            
            $date_arr[$expiry_date][$key] = $put_call_arr;
            $date_arr[$expiry_date][$key]['expiry_date'] = $expiry_date;
            
            $date_arr[$expiry_date][$key]['strike_price'] = !empty($put_call_table_value['strikePrice']) ? $put_call_table_value['strikePrice'] : 0;
            
            foreach( $option_type_arr AS $option_type_key=>$option_type){
            
                $date_arr[$expiry_date][$key][$option_type . '_oi_no_lot'] = !empty($put_call_table_value[$option_type_key]['openInterest']) ? $put_call_table_value[$option_type_key]['openInterest'] : 0;
                $date_arr[$expiry_date][$key][$option_type . '_chng_in_oi_no_lot'] = !empty($put_call_table_value[$option_type_key]['changeinOpenInterest']) ? $put_call_table_value[$option_type_key]['changeinOpenInterest'] : 0;
                
                $date_arr[$expiry_date][$key][$option_type . '_oi'] = !empty($put_call_table_value[$option_type_key]['openInterest']) ? ($put_call_table_value[$option_type_key]['openInterest']*$lot_size) : 0;
                $date_arr[$expiry_date][$key][$option_type . '_chng_in_oi'] = !empty($put_call_table_value[$option_type_key]['changeinOpenInterest']) ? ($put_call_table_value[$option_type_key]['changeinOpenInterest']*$lot_size) : 0;

                /* New */
                $date_arr[$expiry_date][$key][$option_type . '_chng_in_oi_p'] = !empty($put_call_table_value[$option_type_key]['pchangeinOpenInterest']) ? $put_call_table_value[$option_type_key]['pchangeinOpenInterest'] : 0;

                $date_arr[$expiry_date][$key][$option_type . '_volume'] = !empty($put_call_table_value[$option_type_key]['totalTradedVolume']) ? $put_call_table_value[$option_type_key]['totalTradedVolume'] : 0;
                $date_arr[$expiry_date][$key][$option_type . '_iv'] = !empty($put_call_table_value[$option_type_key]['impliedVolatility']) ? $put_call_table_value[$option_type_key]['impliedVolatility'] : 0;
                $date_arr[$expiry_date][$key][$option_type . '_ltp'] = !empty($put_call_table_value[$option_type_key]['lastPrice']) ? $put_call_table_value[$option_type_key]['lastPrice'] : 0;
                $date_arr[$expiry_date][$key][$option_type . '_net_chng'] = !empty($put_call_table_value[$option_type_key]['change']) ? $put_call_table_value[$option_type_key]['change'] : 0;

                /* New */
                $date_arr[$expiry_date][$key][$option_type . '_net_chng_p'] = !empty($put_call_table_value[$option_type_key]['pChange']) ? $put_call_table_value[$option_type_key]['pChange'] : 0;

                /* New */
                $date_arr[$expiry_date][$key][$option_type . '_total_buy_quantity'] = !empty($put_call_table_value[$option_type_key]['totalBuyQuantity']) ? $put_call_table_value[$option_type_key]['totalBuyQuantity'] : 0;
                $date_arr[$expiry_date][$key][$option_type . '_total_sell_quantity'] = !empty($put_call_table_value[$option_type_key]['totalSellQuantity']) ? $put_call_table_value[$option_type_key]['totalSellQuantity'] : 0;

                $date_arr[$expiry_date][$key][$option_type . '_bid_qty'] = !empty($put_call_table_value[$option_type_key]['bidQty']) ? $put_call_table_value[$option_type_key]['bidQty'] : 0;
                $date_arr[$expiry_date][$key][$option_type . '_bid_price'] = !empty($put_call_table_value[$option_type_key]['bidprice']) ? $put_call_table_value[$option_type_key]['bidprice'] : 0;
                $date_arr[$expiry_date][$key][$option_type . '_ask_qty'] = !empty($put_call_table_value[$option_type_key]['askQty']) ? $put_call_table_value[$option_type_key]['askQty'] : 0;
                $date_arr[$expiry_date][$key][$option_type . '_ask_price'] = !empty($put_call_table_value[$option_type_key]['askPrice']) ? $put_call_table_value[$option_type_key]['askPrice'] : 0;                                
                
            }
            
        }        
        
        foreach( $date_arr AS $data_key =>$data_val ){
            
            $total_arr[$data_key]['calls_oi'] = 0;
            $total_arr[$data_key]['calls_chng_in_oi'] = 0;
            $total_arr[$data_key]['calls_oi_no_lot'] = 0;
            $total_arr[$data_key]['calls_chng_in_oi_no_lot'] = 0;
            $total_arr[$data_key]['calls_volume'] = 0;
            $total_arr[$data_key]['calls_ltp'] = 0;
            $total_arr[$data_key]['calls_net_chng'] = 0;
            $total_arr[$data_key]['calls_total_buy_quantity'] = 0;
            $total_arr[$data_key]['calls_total_sell_quantity'] = 0;
            
            $total_arr[$data_key]['puts_oi'] = 0;
            $total_arr[$data_key]['puts_chng_in_oi'] = 0;
            $total_arr[$data_key]['puts_oi_no_lot'] = 0;
            $total_arr[$data_key]['puts_chng_in_oi_no_lot'] = 0;
            $total_arr[$data_key]['puts_volume'] = 0;
            $total_arr[$data_key]['puts_ltp'] = 0;
            $total_arr[$data_key]['puts_net_chng'] = 0;
            $total_arr[$data_key]['puts_total_buy_quantity'] = 0;
            $total_arr[$data_key]['puts_total_sell_quantity'] = 0;
            
            foreach( $data_val AS $put_call_dataz){
                
//                echo '<pre>';
//                print_r($put_call_dataz); 
                
                $total_arr[$data_key]['put_call_log_id'] = $put_call_dataz['put_call_log_id'];
                $total_arr[$data_key]['company_id'] = $put_call_dataz['company_id'];
                $total_arr[$data_key]['company_symbol'] = $put_call_dataz['company_symbol'];
                $total_arr[$data_key]['expiry_date'] = $put_call_dataz['expiry_date'];
                $total_arr[$data_key]['underlying_price'] = $put_call_dataz['underlying_price'];
                $total_arr[$data_key]['underlying_date_time'] = $put_call_dataz['underlying_date_time'];
                $total_arr[$data_key]['underlying_date'] = $put_call_dataz['underlying_date'];
                $total_arr[$data_key]['underlying_time'] = $put_call_dataz['underlying_time'];
                
                $total_arr[$data_key]['calls_oi'] = $total_arr[$data_key]['calls_oi'] + $put_call_dataz['calls_oi'];
                $total_arr[$data_key]['calls_chng_in_oi'] = $total_arr[$data_key]['calls_chng_in_oi'] + $put_call_dataz['calls_chng_in_oi'];
                
                $total_arr[$data_key]['calls_oi_no_lot'] = $total_arr[$data_key]['calls_oi_no_lot'] + $put_call_dataz['calls_oi_no_lot'];
                $total_arr[$data_key]['calls_chng_in_oi_no_lot'] = $total_arr[$data_key]['calls_chng_in_oi_no_lot'] + $put_call_dataz['calls_chng_in_oi_no_lot'];
                
                $total_arr[$data_key]['calls_volume'] = $total_arr[$data_key]['calls_volume'] + $put_call_dataz['calls_volume'];
                $total_arr[$data_key]['calls_ltp'] = $total_arr[$data_key]['calls_ltp'] + $put_call_dataz['calls_ltp'];
                $total_arr[$data_key]['calls_net_chng'] = $total_arr[$data_key]['calls_net_chng'] + $put_call_dataz['calls_net_chng'];
                $total_arr[$data_key]['calls_total_buy_quantity'] = $total_arr[$data_key]['calls_total_buy_quantity'] + $put_call_dataz['calls_total_buy_quantity'];
                $total_arr[$data_key]['calls_total_sell_quantity'] = $total_arr[$data_key]['calls_total_sell_quantity'] + $put_call_dataz['calls_total_sell_quantity'];
                
                $total_arr[$data_key]['strike_price'] = 0;
                
                $total_arr[$data_key]['puts_oi'] = $total_arr[$data_key]['puts_oi'] + $put_call_dataz['puts_oi'];
                $total_arr[$data_key]['puts_chng_in_oi'] = $total_arr[$data_key]['puts_chng_in_oi'] + $put_call_dataz['puts_chng_in_oi'];
                
                $total_arr[$data_key]['puts_oi_no_lot'] = $total_arr[$data_key]['puts_oi_no_lot'] + $put_call_dataz['puts_oi_no_lot'];
                $total_arr[$data_key]['puts_chng_in_oi_no_lot'] = $total_arr[$data_key]['puts_chng_in_oi_no_lot'] + $put_call_dataz['puts_chng_in_oi_no_lot'];
                
                $total_arr[$data_key]['puts_volume'] = $total_arr[$data_key]['puts_volume'] + $put_call_dataz['puts_volume'];
                $total_arr[$data_key]['puts_ltp'] = $total_arr[$data_key]['puts_ltp'] + $put_call_dataz['puts_ltp'];
                $total_arr[$data_key]['puts_net_chng'] = $total_arr[$data_key]['puts_net_chng'] + $put_call_dataz['puts_net_chng'];
                $total_arr[$data_key]['puts_total_buy_quantity'] = $total_arr[$data_key]['puts_total_buy_quantity'] + $put_call_dataz['puts_total_buy_quantity'];
                $total_arr[$data_key]['puts_total_sell_quantity'] = $total_arr[$data_key]['puts_total_sell_quantity'] + $put_call_dataz['puts_total_sell_quantity'];
                
                $this->Put_call_model->insertPutCallData($put_call_dataz, $market_running);
            }
            
            $this->Put_call_model->insertPutCallData($total_arr[$data_key], $market_running);
            
        }
        
        return 'success';
        
    }
    
    /*
     * Put call analysis begin
     */
    
    function pcAnalysisBegin( $analysis_input_arr, $market_running, $script_start_time ){
        
        include_once (dirname(__FILE__) . "/Stock_Analysis.php");
        include_once (dirname(__FILE__) . "/Stock_Analysis_PD.php");
        
        $Stock_Analysis_IV_contr = new Stock_Analysis();
        $Stock_Analysis_PD_contr = new Stock_Analysis_PD();
        
        echo '<br/><br/> ----------------------'.$analysis_input_arr['company_symbol'].' -  IV CALCULATION LIVE ------------------------------------- <br/><br/>';
        
        $Stock_Analysis_IV_contr->liveStockBearishOrBullishByIVOfOC( $analysis_input_arr, $market_running, $script_start_time );
        
        echo '<br/><br/> ---------------------- '.$analysis_input_arr['company_symbol'].' - PD CALCULATION LIVE ------------------------------------- <br/><br/>';
        
        $Stock_Analysis_PD_contr->liveOcPDCalcByUDateNPriceNExpDate( $analysis_input_arr, $market_running, $script_start_time );
        
    }
}
