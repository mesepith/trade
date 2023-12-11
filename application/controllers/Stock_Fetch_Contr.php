<?php

include_once (dirname(__FILE__) . "/Python_Controller.php");
include_once (dirname(__FILE__) . "/Send_Api_Contr.php");
include_once (dirname(__FILE__) . "/Nse_Contr.php");
include_once (dirname(__FILE__) . "/System_Notification_Controller.php");

class Stock_Fetch_Contr extends MX_Controller {
    
    public function fetchCompaniesStkData(){
        
        $Python_contr = new Python_Controller();
        $Nse_Contr = new Nse_Contr();

        $Python_contr->executeCookieScript();
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
    //    echo '<pre>';
    //    print_r($check_open_and_closing_price); exit;
        // echo 'check_open_and_closing_price :: ' . $check_open_and_closing_price; exit;
        if( $check_open_and_closing_price === 'no' ){ echo 'here'; exit; return; }/* Check if market is open, if returns no then exit */
        // echo 'here'; exit;
//        echo '$is_open : ' . $is_open;
        
        $Send_Api_Contr = new Send_Api_Contr(); 
        
        $last_inserted_company_id = 0;
        
        $market_running = 1;
        
        /* 
         * Insert todays final data :
         * Here this ($check_open_and_closing_price) means closing price
         * Logic: We check time is greater than equal to 6pm and closing price > 0 , that means markets final price availabe OR
         * if time is greater than equal to 7pm , we will consider that final price of market is available
         */
        
        if( ( date('H')>= HOUR_FOR_FINAL_DATA && $check_open_and_closing_price > 0 ) || ( date('H')>= (HOUR_FOR_FINAL_DATA+1) ) ){
            
            $check_task_done = $Send_Api_Contr->chkTodayFinalTaskIsDone();
            
            if( $check_task_done === 'done'){ echo 'Today final stk data is inserted'; return; }
            
            $last_inserted_detail = $Send_Api_Contr->lastInsertedStkCrawledCompany();
            
            if( !empty($last_inserted_detail)){
            
                $last_inserted_company_id = $last_inserted_detail->company_id;            
                $last_calculated_time = $last_inserted_detail->updated_at;

                /*
                 * Difference between current time and last script execution time start
                 */
                echo '$current_date_time ' . $current_date_time = date('Y-m-d H:i:s');
                echo '<br/>';

                $to_time = strtotime($current_date_time);
                $from_time = strtotime($last_calculated_time);
                $last_execution_ago = round(abs($to_time - $from_time) / 60,2); #calculated in minutes

                echo 'difference between current time and last inserted company time of company : ' . $last_execution_ago. " minute";
                echo '<br/>';

                if( $last_execution_ago < 5 ){ #if last script execution time is less than 5 minutes then we consider that previous script is already running, so exit current script

                    echo '<br/>';
                    echo '<b> Last script execution time is less than 5 minutes then we consider that previous script is already running, so exit current script </b>';

                    return;
                }

                /*
                 * Difference between current time and last script execution time end
                 */
            
            }
            
            $market_running = 0;
            
        }

        /** If market is running then we need to find last crawled company_id */
        if( $check_open_and_closing_price == 0 ){ exit; } //for now we are not storing live data }
               
        $System_Notification_contr = new System_Notification_Controller();
        
        $company_list = $Send_Api_Contr->listAllCompanies( $last_inserted_company_id );
        
//        echo '<pre>';
//        print_r($company_list); exit;
        
        if(empty($company_list)){            

            $Send_Api_Contr->todayFinalStkDataInserted();
            echo '<br/>';
            echo 'All companies is inserted';
            echo '<br/>';
            
            return;
        }
        
        foreach ($company_list AS $company_list_value) {
            
            $company_symbol = $company_list_value['symbol'];
            
            $company_id = $company_list_value['id'];
            
            echo '<br/><br/>$company_id ' . $company_id;
            echo '<br/><br/>$company_symbol ' . $company_symbol . '<br/> <br/>';
            
            $this->crawlStockData($company_id, $company_symbol, $Python_contr, $System_Notification_contr, $Send_Api_Contr, $market_running, $Nse_Contr);
                        
//            if( FINAL_DATA_SERVER === 'yes' ){ sleep(1); }
            
            echo '<br/><br/> ********************************************** <br/><br/>';
//            exit();
            
        }
        
    }
    
    /*
     * Crawl Single company or index
     * https://www.ampstart.co/Stock_Fetch_Contr/singleCrawlinLive/458/GAIL
     */
    function singleCrawlinLive( $company_id = '294', $company_symbol = 'CIPLA' ){
        
        $Nse_Contr = new Nse_Contr();
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
        if( $check_open_and_closing_price === 'no' ){ return; }/* Check if market is open, if returns no then exit */
        
        $Python_contr = new Python_Controller();
               
        $System_Notification_contr = new System_Notification_Controller();
        
        $Send_Api_Contr = new Send_Api_Contr(); 
        
        $market_running=1;
        
        $this->crawlStockData($company_id, $company_symbol, $Python_contr, $System_Notification_contr, $Send_Api_Contr, $market_running, $Nse_Contr);
        
    }
    
    public function crawlStockData($company_id, $company_symbol, $Python_contr, $System_Notification_contr, $Send_Api_Contr, $market_running, $Nse_Contr){
        
//        $company_id = 1443;
//        $company_symbol = 'TCS';
        
        $client = new GuzzleHttp\Client();
        
//        $company_symbol = 'GAIL';
        
        $whole_data = array();
        
        
        $stk_data = $this->curlStkPrice(urlencode($company_symbol), $Nse_Contr);

        if (!empty($stk_data) && is_array($stk_data)) {

        }else{
            echo 'Stock Data is empty';
            echo '<pre>'; print_r($stk_data);
            echo '<br/>';
            return;
        }
        
        
        if( $stk_data['metadata']['lastUpdateTime'] === '-' && $stk_data['metadata']['listingDate'] ==='NA' && empty($stk_data['priceInfo']) ){
            
//            $this->Companies_model->makeCompanyInactive($company_id, $company_symbol);
            $Send_Api_Contr->makeCompanyInactive($company_id, $company_symbol);
            
            return;
        }
        
        if( empty($stk_data) || empty($stk_data['metadata']['lastUpdateTime']) ){ 
        
            $System_Notification_contr->stkFailCrawling($company_id, $company_symbol);
            
            return;
        }        
        
        $whole_data['companyName'] = trim($stk_data['info']['companyName']);
        
        $whole_data['series'] = trim($stk_data['metadata']['series']);
        $whole_data['secDate'] = $stk_data['metadata']['lastUpdateTime'];
        
        /*New*/
        $whole_data['pdSectorPe'] = trim($stk_data['metadata']['pdSectorPe']);
        $whole_data['pdSymbolPe'] = trim($stk_data['metadata']['pdSymbolPe']);
        $whole_data['pdSectorInd'] = trim($stk_data['metadata']['pdSectorInd']);
        
        
        /*New*/
        $whole_data['change'] = trim($stk_data['priceInfo']['change']);
        $whole_data['pChange'] = trim($stk_data['priceInfo']['pChange']);
        $whole_data['vwap'] = trim($stk_data['priceInfo']['vwap']);
        $whole_data['lowerCP'] = trim($stk_data['priceInfo']['lowerCP']);
        $whole_data['upperCP'] = trim($stk_data['priceInfo']['upperCP']);
        $whole_data['pPriceBand'] = trim($stk_data['priceInfo']['pPriceBand']);
        $whole_data['basePrice'] = trim($stk_data['priceInfo']['basePrice']);
        
        $whole_data['lastPrice'] = trim($stk_data['priceInfo']['lastPrice']);
        $whole_data['open'] = trim($stk_data['priceInfo']['open']);
        $whole_data['closePrice'] = trim($stk_data['priceInfo']['close']);
        
        $whole_data['dayHigh'] = trim($stk_data['priceInfo']['intraDayHighLow']['max']);
        $whole_data['dayLow'] = trim($stk_data['priceInfo']['intraDayHighLow']['min']);
        
        /*New*/
        $whole_data['yearWeekLow'] = trim($stk_data['priceInfo']['weekHighLow']['min']);
        $whole_data['yearWeekLowDate'] = date('Y-m-d', strtotime(trim($stk_data['priceInfo']['weekHighLow']['minDate']) ));
        $whole_data['yearWeekHigh'] = trim($stk_data['priceInfo']['weekHighLow']['max']);
        $whole_data['yearWeekHighDate'] = date('Y-m-d', strtotime(trim($stk_data['priceInfo']['weekHighLow']['maxDate']) ));
        
//        echo ' <br/> <br/>################################# <br/> <br/>';
        
        $stk_data_other = $this->curlStkDelivery(urlencode($company_symbol), $Nse_Contr);
        
        $raw_data_arr[] = array($stk_data, $stk_data_other);
        
        $raw_data = json_encode($raw_data_arr);
        
//        echo '<pre>';
//        print_r($raw_data_arr); exit;
        
        /*New*/
        if (!empty($stk_data_other) && is_array($stk_data_other)) {

            $whole_data['noBlockDeals'] = trim($stk_data_other['noBlockDeals']);
            
            $whole_data['totalBuyQuantity'] = trim($stk_data_other['marketDeptOrderBook']['totalBuyQuantity']);
            $whole_data['totalSellQuantity'] = trim($stk_data_other['marketDeptOrderBook']['totalSellQuantity']);
            
            $whole_data['totalTradedVolume'] = trim($stk_data_other['marketDeptOrderBook']['tradeInfo']['totalTradedVolume']);
            $whole_data['totalTradedValue'] = trim($stk_data_other['marketDeptOrderBook']['tradeInfo']['totalTradedValue']);
            
            /*New*/
            $whole_data['totalMarketCap'] = trim($stk_data_other['marketDeptOrderBook']['tradeInfo']['totalMarketCap']);
            
            /*New*/
            $whole_data['quantityTraded'] = trim($stk_data_other['securityWiseDP']['quantityTraded']);
            
            
            $whole_data['deliveryQuantity'] = trim($stk_data_other['securityWiseDP']['deliveryQuantity']);
            $whole_data['deliveryToTradedQuantity'] = trim($stk_data_other['securityWiseDP']['deliveryToTradedQuantity']);
        
        }else{
            echo '<br/><br/> $stk_data_other is empty';
        }
        
        /*
         * Find Number of trades Start
         */
        
        /* if(empty($market_running)){
            
            $url_no_of_trade = 'https://www.nseindia.com/api/historical/cm/equity?symbol='.urlencode($company_symbol).'&series=\[%22EQ%22\]&from='.date('d-m-Y').'&to='.date('d-m-Y');
        
            $referer_no_of_trade = 'https://www.nseindia.com/get-quotes/equity?symbol=' . $company_symbol;

            $return_no_of_trade  = $Nse_Contr->curlNse($url_no_of_trade, $referer_no_of_trade);
            
            $whole_data['total_no_of_trades'] = !empty($return_no_of_trade['data'][0]['CH_TOTAL_TRADES']) ? $return_no_of_trade['data'][0]['CH_TOTAL_TRADES'] : 0;
            $whole_data['total_traded_value_eod'] = !empty($return_no_of_trade['data'][0]['CH_TOT_TRADED_VAL']) ? $return_no_of_trade['data'][0]['CH_TOT_TRADED_VAL'] : 0;
            
            
            if( empty($whole_data['totalTradedVolume']) || empty($whole_data['total_no_of_trades']) ){
                    
                $whole_data['volume_by_total_no_of_trade'] = 0;
                
            }else{
            
                try{

                    $whole_data['volume_by_total_no_of_trade'] = $whole_data['totalTradedVolume']/$whole_data['total_no_of_trades'];

                }catch(Exception $e) {

                    $whole_data['volume_by_total_no_of_trade'] = 0;
                }
            
            }
        } */
        
        /*
         * Find Number of trades End
         */
        
        
        $data_log_arr = array();
        $data_log_arr['company_id'] = $company_id;
        $data_log_arr['company_symbol'] = $company_symbol;
        $data_log_arr['exchange_name'] = "nse";
        
        /* Since stocks date is not match with todays days so return */
        if( date('Y-m-d') !== date('Y-m-d', strtotime($stk_data['metadata']['lastUpdateTime'])) ){
            
            return false;
            
        }
        
        $data_log_arr['market_running'] = $market_running;
        
        $data_log_arr['server'] = SERVER_NAME;
        
        $data_log_arr['whole_data'] = json_encode($whole_data);
        
        if( FINAL_DATA_SERVER === 'yes' ){
        
            $api_return = $Python_contr->receivePyStockData($data_log_arr, $raw_data);
            
            if ( filter_var($api_return, FILTER_VALIDATE_INT) === false ) {
                    
                $System_Notification_contr->stkFailInsertApi($company_symbol, $api_return, $raw_data, '');
                    
            }else if($api_return > 0){
            }else{
                $System_Notification_contr->stkFailInsertApi($company_symbol, $api_return, $raw_data, '');
            }
        
        }else{
            
            $url = PARENT_WEB_SERVER . 'Python_Controller/receivePyStockDataViaApi';
            
            try{
                $response = $client->request('POST', $url, [
                    'form_params' => [
                        'post_data' => $data_log_arr,
                        'raw_data' => $raw_data
                    ]
                ]);   

                $api_return = $response->getBody()->getContents();
                
                if ( filter_var($api_return, FILTER_VALIDATE_INT) === false ) {
                    
                    $System_Notification_contr->stkFailInsertApi($company_symbol, $api_return, $raw_data, $url);
                    
                }else if($api_return > 0){
                }else{
                    $System_Notification_contr->stkFailInsertApi($company_symbol, $api_return, $raw_data, $url);
                }
                
            }catch(Exception $e) {
                
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                
                $System_Notification_contr->stkFailInsertApi($company_symbol, $responseBodyAsString, $raw_data, $url);
            }
            
            
        }
        
    }
    
    /*
     * Find volume by total no of trade
     */
    function findVolumeByTrade(){
        
        $Python_contr = new Python_Controller();
        $Nse_Contr = new Nse_Contr();

        $Python_contr->executeCookieScript();
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
        if( $check_open_and_closing_price === 'no' ){ return; }/* Check if market is open, if returns no then exit */
        
        $Send_Api_Contr = new Send_Api_Contr(); 
        
        $this->load->model('Analysis_task_model');
        
        $last_calculated_company = $this->Analysis_task_model->lastCalculatedCompany('volume_by_trade');
        
        if( !empty($last_calculated_company) ){
            
            $last_calculated_company_id = $last_calculated_company->company_id;
            $last_calculated_time = $last_calculated_company->updated_at;
            
            /*
             * Difference between current time and last script execution time start
             */
            echo '$current_date_time ' . $current_date_time = date('Y-m-d H:i:s');
            echo '<br/>';

            $to_time = strtotime($current_date_time);
            $from_time = strtotime($last_calculated_time);
            $last_execution_ago = round(abs($to_time - $from_time) / 60,2); #calculated in minutes

            echo 'difference between current time and last calculated time of company : ' . $last_execution_ago. " minute";
            echo '<br/>';

            if( $last_execution_ago < 15 ){ #if last script execution time is less than 15 minutes then we consider that previous script is already running, so exit current script
                
                echo '<br/>';
                echo '<b> Last script execution time is less than 15 minutes then we consider that previous script is already running, so exit current script </b>';

                exit;
            }

            /*
             * Difference between current time and last script execution time end
             */
        
        }else{
            
            $last_calculated_company_id = 0;
        }
        
        $company_list = $Send_Api_Contr->listTodayCMCompanies( $last_calculated_company_id );
        
//        echo '<pre>';
//        print_r($company_list); 
        
        if(empty($company_list)){ echo "<br/> No Company <br/>"; return; }
        
        foreach ($company_list AS $company_list_value) {
            
            $company_symbol = $company_list_value['company_symbol'];
            
            $company_id = $company_list_value['company_id'];
            
            /*
             * Find Number of trades Start
             */

            $url_no_of_trade = 'https://www.nseindia.com/api/historical/cm/equity?symbol='.urlencode($company_symbol).'&series=\[%22EQ%22\]&from='.date('d-m-Y').'&to='.date('d-m-Y');
//            $url_no_of_trade = 'https://www.nseindia.com/api/historical/cm/equity?symbol='.urlencode($company_symbol).'&series=\[%22EQ%22\]&from=26-06-2020&to=26-06-2020';

            $referer_no_of_trade = 'https://www.nseindia.com/get-quotes/equity?symbol=' . $company_symbol;

            $return_no_of_trade  = $Nse_Contr->curlNse($url_no_of_trade, $referer_no_of_trade);

            $whole_data['total_no_of_trades'] = !empty($return_no_of_trade['data'][0]['CH_TOTAL_TRADES']) ? $return_no_of_trade['data'][0]['CH_TOTAL_TRADES'] : 0;
            $whole_data['total_traded_value_eod'] = !empty($return_no_of_trade['data'][0]['CH_TOT_TRADED_VAL']) ? $return_no_of_trade['data'][0]['CH_TOT_TRADED_VAL'] : 0;

            $whole_data['total_traded_volume_eod'] = !empty($return_no_of_trade['data'][0]['CH_TOT_TRADED_QTY']) ? $return_no_of_trade['data'][0]['CH_TOT_TRADED_QTY'] : 0;


            if( empty($whole_data['total_traded_volume_eod']) || empty($whole_data['total_no_of_trades']) ){

                $whole_data['volume_by_total_no_of_trade'] = 0;

            }else{

                try{

                    $whole_data['volume_by_total_no_of_trade'] = $whole_data['total_traded_volume_eod']/$whole_data['total_no_of_trades'];

                }catch(Exception $e) {

                    $whole_data['volume_by_total_no_of_trade'] = 0;
                }

            }
            
            echo $company_symbol;
            echo '<pre>';
            print_r($whole_data);
            
            $Send_Api_Contr->insertCMVolumeByTrade( $company_id, $company_symbol, $whole_data );
            
            $this->Analysis_task_model->ocCalculationDone($company_id, $company_symbol, 'volume_by_trade');#this means we have done this companies calulation for today
            
            /*
             * Find Number of trades End
             */
            
//            if( $company_id > 14 ){
//            
//                exit();
//                
//            }
            
        }
        
        
        exit;
    }
    
    
    function curlStkPrice( $company_symbol, $Nse_Contr ){
        
        $url = 'https://www.nseindia.com/api/quote-equity?symbol=' . $company_symbol;
        
        $referer = 'https://www.nseindia.com/get-quotes/equity?symbol=' . $company_symbol;
        
        $return  = $Nse_Contr->curlNse($url, $referer);
        
        return $return;
        
    }
    
    
    function curlStkDelivery($company_symbol, $Nse_Contr){
        
        $url = 'https://www.nseindia.com/api/quote-equity?symbol='.$company_symbol.'&section=trade_info';
        
        $referer = 'https://www.nseindia.com/get-quotes/equity?symbol=' . $company_symbol;
        
        // echo "<br/> <br/>";
        $return  = $Nse_Contr->curlNse($url, $referer);
        // echo "<br/> <br/>";
        // echo 'returnzz ::: ';
        // echo '<pre>'; print_r($return); exit;
        return $return;
        
    }
}
