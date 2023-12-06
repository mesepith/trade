<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
include_once (dirname(__FILE__) . "/Nse_Contr.php");
include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class Fetch_Future_Contr extends MX_Controller {

    function fetchFutureLog(){
        
        $Nse_Contr = new Nse_Contr();                
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
        if( $check_open_and_closing_price === 'no' ){ return; }/* Check if market is open, if returns no then exit */
        
//        echo $check_open_and_closing_price; exit;
        
        $last_crawled_company_id=0;
        
        $Send_Api_Contr = new Send_Api_Contr();
        
//        $this->load->model('Put_call_log_model');
        
        /* 
         * Insert todays final put call crawl log data :
         * Here this ($check_open_and_closing_price) means closing price
         * Logic: We check time is greater than equal to 6pm and closing price > 0 , that means markets final data is updated OR
         * if time is greater than equal to 7pm , we will consider that markets final data is already updated
         */
        
        if( ( ( $check_open_and_closing_price > 0 ) || date('H')>= HOUR_FOR_FINAL_DATA && $check_open_and_closing_price > 0 ) || ( date('H')>= (HOUR_FOR_FINAL_DATA+1) ) ){
            
//            $check_task_done = $Send_Api_Contr->chkTodayFinalPCDataIsCrawled();
//            
//            if( $check_task_done === 'done'){ echo 'Today final option data is crawled'; return; }
//
//            $last_crawled_company_id = $Send_Api_Contr->lastCrawledPCCompany();
            
        }else{
            return false;
        }
        
        $company_list = $Send_Api_Contr->futureNonCrawledCompanyList( $last_crawled_company_id );
        
//        echo '<pre>'; print_r($company_list); exit;
        
        /*
         * closing price > 0 , that means markets final data is updated
         */
        
        if( empty($company_list) && $check_open_and_closing_price > 0 ){             

//            $Send_Api_Contr->todayFinalPCDataCrawled();
            echo '<br/>';
            echo 'All companies PC Data Crawled';
            echo '<br/>';
            
            return;
        }
        
        $System_Notification_contr = new System_Notification_Controller();
        
//        echo '<pre>'; print_r($company_list);
        
        foreach ($company_list AS $company_list_value) {
            
//            $company_symbol = 'CASTROLIND';
//            $company_id = 262;
            
            $company_symbol = $company_list_value['company_symbol'];
            $company_id = $company_list_value['company_id'];
            
            $this->crawlFutureLogData($company_id, $company_symbol, $System_Notification_contr, $Nse_Contr, $Send_Api_Contr);
            
//            exit; #comment after testing
            
        }
        
        $this->processFutureLogData(0, 0);
        
    }
    
    function crawlFutureLogData( $company_id, $company_symbol, $System_Notification_contr, $Nse_Contr, $Send_Api_Contr ){
        
        $future_log_data = $this->curlFutureData( urlencode($company_symbol), $Nse_Contr );
        
        if( empty($future_log_data) || empty($future_log_data['stocks']) ){
            echo $company_symbol .' is inactive <br/>' ;
            $System_Notification_contr->futureFailCrawl($company_id, $company_symbol);
            
            return;
        }
//         echo '<pre>'; print_r($future_log_data['stocks']); exit;
         
        $future_arr = array();
        
        $future_arr['company_id'] = $company_id;
        $future_arr['company_symbol'] = $company_symbol;

        $future_arr['info'] = json_encode($future_log_data['info']);
        $future_arr['underlying_price'] = $future_log_data['underlyingValue'];
        $future_arr['volume_freeze_quantity'] = $future_log_data['vfq'];

        $future_arr['market_date_time'] = date('Y-m-d H:i:s', strtotime(trim($future_log_data['fut_timestamp']) ) );
        $future_arr['market_date'] = date('Y-m-d', strtotime(trim($future_log_data['fut_timestamp']) ) );
        $future_arr['market_time'] = date('H:i:s', strtotime(trim($future_log_data['fut_timestamp']) ) );  
        
        $future_arr['server'] = SERVER_NAME; 
         
        $data_arr = array();
        
         foreach( $future_log_data['stocks'] AS $future_crawl_val ){
//             echo $future_crawl_val['metadata']['instrumentType'];
//             echo '<br/><br/>';
             if( trim($future_crawl_val['metadata']['instrumentType']) === 'Stock Futures' || trim($future_crawl_val['metadata']['instrumentType']) === 'Index Futures' ){
                 
                 $data_arr[] = $future_crawl_val;
                 
             }
             
         }
         
         $future_arr['future_data'] = json_encode($data_arr);
         
//         echo '<pre>'; print_r($future_arr); 
         
         $Send_Api_Contr->insertFutureDataLog($future_arr);
         
//         exit;
        
    }
    
    
    function curlFutureData( $company_symbol, $Nse_Contr ){

        $url = 'https://www.nseindia.com/api/quote-derivative?symbol=' . $company_symbol;
        
        $referer = 'https://www.nseindia.com/get-quotes/derivatives?symbol='. $company_symbol;
        
        $return  = $Nse_Contr->curlNse($url, $referer);
        
        return $return;
        
    }
    
    /* 
     * @author: ZAHIR
     * Set status = 2 for inactive companies in put_call_companies table
     */
    function makeFutureCompanyInactive( $company_id, $company_symbol ){
        
        $this->load->model('Future_model');
        
        $this->Future_model->makeFutureCompanyInactive($company_id, $company_symbol);
        
        include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
        $System_Notification_contr = new System_Notification_Controller();
        
        $System_Notification_contr->makeFutureCompanyInactive($company_symbol);
    }
    
    /*
     * Process Future data 
     */
    function processFutureLogData( $future_log_id=0, $market_running=0 ){
//        $this->load->model('Future_model');
        ini_set('max_execution_time', 0); 

        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');
        
        $unprocess_log_data = $this->Future_model->fetchUnprocessedData( $future_log_id, $market_running );                
                         
//        echo '<pre>';
//        print_r($unprocess_log_data); 
//        exit;
                
        if ($unprocess_log_data && count($unprocess_log_data)> 0 ) {
            
            $other_data = array();
            
            foreach ($unprocess_log_data AS $unprocess_log_data_key => $unprocess_log_data_value) {
                
                $future_info_data = json_decode($unprocess_log_data_value->info, true);
                
                $other_data['future_log_id'] = $unprocess_log_data_value->id;
                $other_data['company_id'] = $unprocess_log_data_value->company_id;
                $other_data['company_symbol'] = $unprocess_log_data_value->company_symbol;
                
                $other_data['industry'] = empty($future_info_data['industry']) ? '' : $future_info_data['industry'];
                
                $other_data['underlying_price'] = $unprocess_log_data_value->underlying_price;
                $other_data['volume_freeze_quantity'] = $unprocess_log_data_value->volume_freeze_quantity;
                
                $other_data['underlying_date_time'] = $unprocess_log_data_value->market_date_time;
                $other_data['underlying_date'] = $unprocess_log_data_value->market_date;
                $other_data['underlying_time'] = $unprocess_log_data_value->market_time;  
                
                $future_data = json_decode($unprocess_log_data_value->future_data, true);               
                
                $this->extractFutureData( $future_data, $other_data, $market_running );
                
//                $this->processRolloverData( $future_data, $other_data, $market_running );
                
            }
            
            if( empty($market_running) ){
            
                $this->processFutureLogData( $future_log_id );
            
            }
            flush();
            
        }else{
            
            echo 'Future Data Processed';
            exit;
        }
        
    }
    
    /*
     * https://www.motilaloswal.com/share-market-education/blogs.aspx/27/Translation-of-Derivative-Rollovers-in-Stock-Market
     * Rollover Percentage = ((Next Month Open Interest + Far Month Open Interest) / (Near Month Open Interest + Next Month Open Interest + Far Month Open Interest)) 
     * Roll cost = ((Next series price - Current series price) / (Current series price) % 
     */
    
    function extractFutureData( $future_data, $future_data_arr, $market_running ){
        
        /* Data need to calculate Rollover Start */
        
        $roll_over_final_arr = $future_data_arr;
        
        $underlying_date = $future_data_arr['underlying_date'];
        
        $underlying_month = date('m', strtotime($underlying_date) );
        
        $roll_over_inp_arr = array();
        
        /* Data need to calculate Rollover End */
        
        foreach( $future_data AS $future_data_val ){
            
            $future_data_arr['expiry_date'] = date('Y-m-d', strtotime($future_data_val['metadata']['expiryDate']) );
            $future_data_arr['open_price'] = $future_data_val['metadata']['openPrice'];
            $future_data_arr['high_price'] = $future_data_val['metadata']['highPrice'];
            $future_data_arr['low_price'] = $future_data_val['metadata']['lowPrice'];
            
            $future_data_arr['close_price'] = $future_data_val['metadata']['closePrice'];            
            $future_data_arr['prev_price'] = $future_data_val['metadata']['prevClose'];
            $future_data_arr['last_price'] = $future_data_val['metadata']['lastPrice'];
            
            if( $future_data_val['metadata']['closePrice'] == 0 ){
            
                $future_data_arr['close_price'] = $future_data_val['metadata']['lastPrice'];
            }
            
            $future_data_arr['change'] = $future_data_val['metadata']['change'];
            $future_data_arr['p_change'] = $future_data_val['metadata']['pChange'];
            $future_data_arr['no_of_contracts_traded'] = $future_data_val['metadata']['numberOfContractsTraded'];
            $future_data_arr['total_turnover'] = $future_data_val['metadata']['totalTurnover'];
            
            $future_data_arr['total_buy_quantity'] = $future_data_val['marketDeptOrderBook']['totalBuyQuantity'];
            $future_data_arr['total_sell_quantity'] = $future_data_val['marketDeptOrderBook']['totalSellQuantity'];
            
            $future_data_arr['traded_volume'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['tradedVolume'];
            $future_data_arr['total_traded_value'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['value'];
            $future_data_arr['vmap'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['vmap'];
            $future_data_arr['premium_turnover'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['premiumTurnover'];
            $future_data_arr['oi'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['openInterest'];
            $future_data_arr['change_in_oi'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['changeinOpenInterest'];
            $future_data_arr['p_change_in_oi'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['pchangeinOpenInterest'];
            $future_data_arr['market_lot'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['marketLot'];
            
            $future_data_arr['settlement_price'] = $future_data_val['marketDeptOrderBook']['otherInfo']['settlementPrice'];
            $future_data_arr['daily_volatility'] = $future_data_val['marketDeptOrderBook']['otherInfo']['dailyvolatility'];
            $future_data_arr['annual_volatility'] = $future_data_val['marketDeptOrderBook']['otherInfo']['annualisedVolatility'];
            $future_data_arr['iv'] = $future_data_val['marketDeptOrderBook']['otherInfo']['impliedVolatility'];
            $future_data_arr['client_wise_position_limits'] = $future_data_val['marketDeptOrderBook']['otherInfo']['clientWisePositionLimits'];
            $future_data_arr['market_wide_position_limits'] = $future_data_val['marketDeptOrderBook']['otherInfo']['marketWidePositionLimits'];
            
            $future_data_arr["market_running"] = $market_running;
            
            $future_data_arr["created_at"] = date("Y-m-d H:i:s");
                        
//            echo '<pre>';
//            print_r($future_data_arr); 
            
            $this->Future_model->insertFutureData($future_data_arr);
            
            /* Rollover input data Extract Start */
            
            $expiry_month = date('m', strtotime($future_data_val['metadata']['expiryDate']) );
            
            if( $expiry_month === $underlying_month ){
                
                $month = 'current';
                
            }else if( $expiry_month == ( $underlying_month + 1) ){
                
                $month = 'next';
                
            }else if( $expiry_month > $underlying_month ){
                
                $month = 'far';
            }
            
            $roll_over_inp_arr['oi'][][$month . '_month_oi'] = $future_data_val['marketDeptOrderBook']['tradeInfo']['openInterest'];
            
            $roll_over_inp_arr['close_price'][][$month . '_month_close_price'] = $future_data_arr['close_price'];
            
            /* Rollover input data Extract End */
        }
        
        /* Calculate Rollover Start */
        
        $next_n_far_month_oi_sum = 0;
        
        $sum_of_all_oi = 0;
        
        foreach( $roll_over_inp_arr AS $roll_over_inp_key => $roll_over_inp_val ){
            
            if( $roll_over_inp_key === 'oi' ){
            
                foreach( $roll_over_inp_val AS $roll_over_oi_arr){
                    
                    foreach( $roll_over_oi_arr AS $roll_over_oi_month=>$roll_over_oi_val ){
                        
                        $sum_of_all_oi = $sum_of_all_oi + $roll_over_oi_val;
                        
                        if( $roll_over_oi_month != 'current_month_oi' ){
                            
                            $next_n_far_month_oi_sum = $next_n_far_month_oi_sum + $roll_over_oi_val;
                        }
                        
                    }

                }
            
            }
            
        }
        
        $rollover_percentage = ( ($next_n_far_month_oi_sum / $sum_of_all_oi) * 100 );
        
        $roll_over_final_arr['rollover_percentage'] = $rollover_percentage;
        
        echo '<br/> rollover_percentage : ' . $rollover_percentage;
        
        $current_month_close_price = ( empty($roll_over_inp_arr['close_price'][0]['current_month_close_price']) ) ? 0 : $roll_over_inp_arr['close_price'][0]['current_month_close_price'];
        $next_month_close_price = ( empty($roll_over_inp_arr['close_price'][1]['next_month_close_price']) ) ? 0 : $roll_over_inp_arr['close_price'][1]['next_month_close_price'] ;
        
        $roll_cost = ( ( ( $next_month_close_price - $current_month_close_price ) / $current_month_close_price ) * 100 );
        
        $roll_over_final_arr['roll_cost'] = $roll_cost;
        
        echo '<br/> roll_cost : ' . $roll_cost;
        
        $roll_over_final_arr["market_running"] = $market_running;
            
        $roll_over_final_arr["created_at"] = date("Y-m-d H:i:s");
        
        echo '<pre>';
        print_r($roll_over_final_arr);
        
        $this->Future_model->insertRolloverData($roll_over_final_arr);
        
//        exit; #Comment After test
        
        /* Calculate Rollover End */
        
    }
    
}
