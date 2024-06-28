<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Python_Controller.php");
include_once (dirname(__FILE__) . "/Nse_Contr.php");
include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class Fetch_Controller extends MX_Controller {

    public function extractSectorsData() {
        
        $this->load->model('Sectors_model');
        
        $Nse_Contr = new Nse_Contr();
        
        /*
         * Check data is already in database start
         */
        
        $this->Sectors_model->checkTodaysDataAlreadyInserted();
        
        /*
         * Check data is already in database end
         */
        
        $sectors_list = $this->Sectors_model->listAllSectors();
        
        foreach($sectors_list AS $sectors_list_value){
            
            $sectors_data = $Nse_Contr->curlNseOld( $sectors_list_value->url, $sectors_list_value->referer);
            
//            echo '<pre>'; print_r($data); exit;
            
            /*
             * Check sectors data date start
             */
            
            echo 'index_name : ' . $sectors_list_value->index_name . ' <br/>';
            
//            echo '<pre>';
//            print_r($sectors_data);
            $api_date_time = strtotime($sectors_data['time']);
            $api_date = date('Y-m-d', $api_date_time);             
            $api_date_timestanmp = strtotime($api_date);
            
            $today_date_timestanmp = strtotime(date('Y-m-d'));
            
            if( $api_date_timestanmp != $today_date_timestanmp ){
                
                echo 'api data of nse is not updated <br/>';
                exit();
                
            }else{
                echo 'api data of nse is updated  <br/>';
            }
            
            echo '<br/><br/> ############ <br/><br/>';
            /*
             * Check sectors data date end
             */
            
            $sectors_data_log_id = $this->Sectors_model->insertEachSectorsWholeApiDataInLog($sectors_data, $sectors_list_value->id, $sectors_list_value->index_name);
            
            $market_running = 0;
            
            $this->sectorDataBuild( $sectors_list_value, $sectors_data_log_id, $sectors_data, $market_running );
            
            
        }
    }
    
    function extractSectorsLiveData(){
        
        $Nse_Contr = new Nse_Contr();                
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
        if( $check_open_and_closing_price === 'no' ){ return; }/* Check if market is open, if returns no then exit */
        
        $this->load->model('Sectors_model');
        
        $sectors_list = $this->Sectors_model->listAllLiveSectors();
        
        $sectors_data_log_id = 0;
        
        $market_running = 1;
        
        if( empty($sectors_list)){ return; }
        
        foreach($sectors_list AS $sectors_list_value){
            
            $sectors_data = $Nse_Contr->curlNseOld( $sectors_list_value->url, $sectors_list_value->referer);
            
            $this->sectorDataBuild( $sectors_list_value, $sectors_data_log_id, $sectors_data, $market_running );
            
        }
    }
    
    function sectorDataBuild( $sectors_list_value, $sectors_data_log_id, $sectors_data, $market_running ){
        
        $sectors_data_arr = array();
                
        $sectors_data_arr['sectors_id'] = $sectors_list_value->id;
        $sectors_data_arr['index_name'] = $sectors_list_value->index_name;
        $sectors_data_arr['sectors_data_log_id'] = $sectors_data_log_id;

        $sectors_data_arr['declines'] = $sectors_data['declines'];
        $sectors_data_arr['advances'] = $sectors_data['advances'];
        $sectors_data_arr['unchanged'] = $sectors_data['unchanged'];

        $sectors_data_arr['trade_volume_sum'] = str_replace(",","",$sectors_data['trdVolumesum']);

        $sectors_data_arr['open_price'] = str_replace(",","",$sectors_data['latestData'][0]['open']);
        $sectors_data_arr['high_price'] = str_replace(",","",$sectors_data['latestData'][0]['high']);
        $sectors_data_arr['low_price'] = str_replace(",","",$sectors_data['latestData'][0]['low']);

        $sectors_data_arr['ltp'] = str_replace(",","",$sectors_data['latestData'][0]['ltp']);

        $sectors_data_arr['change'] = $sectors_data['latestData'][0]['ch'];
        $sectors_data_arr['change_in_percent'] = $sectors_data['latestData'][0]['per'];
        $sectors_data_arr['year_change_in_percent'] = $sectors_data['latestData'][0]['yCls'];
        $sectors_data_arr['month_change_in_percent'] = $sectors_data['latestData'][0]['mCls'];

        $sectors_data_arr['year_high_price'] = str_replace(",","",$sectors_data['latestData'][0]['yHigh']);
        $sectors_data_arr['year_low_price'] = str_replace(",","",$sectors_data['latestData'][0]['yLow']);

        $old_date_timestamp = strtotime($sectors_data['time']);
        $new_date = date('Y-m-d H:i:s', $old_date_timestamp);   
        $sectors_data_arr['stock_date_time'] = $new_date;
        $sectors_data_arr['stock_date'] = date('Y-m-d', $old_date_timestamp);  
        $sectors_data_arr['stock_time'] = date('H:i:s', $old_date_timestamp);

        $sectors_data_arr['trade_value_sum'] = str_replace(",","",$sectors_data['trdValueSum']);
        
        if( $market_running ===  0 ){
            
            $this->Sectors_model->insertSectorsData( $sectors_data_arr );
            
        }else{
            
            $this->Sectors_model->insertLiveSectorsData( $sectors_data_arr );
        }
        
        

        echo '<pre>';
        print_r($sectors_data_arr);
        
    }

    /**
     * Extract indices list availbale on nse
     */

     function extractNseIndicesName2023(){

        $Python_contr = new Python_Controller();
        $Nse_Contr = new Nse_Contr();
        $Python_contr->executeCookieScript();

        $this->load->model('Sectors_model');

        $url = 'https://www.nseindia.com/api/allIndices';
        
        $referer = 'https://www.nseindia.com/market-data/live-market-indices';

        $all_indices  = $Nse_Contr->curlNse($url, $referer);

        $index_arr = array();

        if(!empty($all_indices['data'])){

            foreach($all_indices['data'] AS $each_indices){
                
                $index_arr['name'] = $each_indices['index'];
                $index_arr['index_name'] = $each_indices['indexSymbol'];

                $this->Sectors_model->insertSectorsIndicesName( $index_arr );
            }
             
        }
     }
    
    /**
     * Extract Sector Indices
     */
    function extractSectorIndices2023(){

        $Python_contr = new Python_Controller();
        $Nse_Contr = new Nse_Contr();
        $Python_contr->executeCookieScript();

        $this->load->model('Sectors_model');
        $sectors_list = $this->Sectors_model->listAllSectors();

        foreach( $sectors_list AS $each_sector ){

            $index_name = urlencode($each_sector->index_name);

            // if($each_sector->index_name !='INDIA VIX'){

            //     continue;
            // }

            // echo '$index_name : ' . $index_name . '<br/><br/>';

            $url = 'https://www.nseindia.com/api/equity-stockIndices?index=' . $index_name;
            $referer = 'https://www.nseindia.com/market-data/live-equity-market?symbol='. $index_name;

            $each_indices_result  = $Nse_Contr->curlNse($url, $referer);

            // echo '<pre>'; print_r($each_indices_result); exit;

            if( !empty($each_indices_result) && !empty($each_indices_result['data']) && !empty($each_indices_result['data'][0]) ){

                $sectors_data_arr = array();

                $sectors_data_arr['sectors_id'] = $each_sector->id;
                $sectors_data_arr['index_name'] = $each_sector->index_name;

                $sectors_data_arr['declines'] = $each_indices_result['advance']['declines'];
                $sectors_data_arr['advances'] = $each_indices_result['advance']['advances'];
                $sectors_data_arr['unchanged'] = $each_indices_result['advance']['unchanged'];

                $sectors_data_arr['trade_volume_sum'] = $each_indices_result['data'][0]['totalTradedVolume'];
                $sectors_data_arr['trade_value_sum'] = $each_indices_result['data'][0]['totalTradedValue']; //New Column

                $sectors_data_arr['open_price'] = $each_indices_result['data'][0]['open'];
                $sectors_data_arr['high_price'] = $each_indices_result['data'][0]['dayHigh'];
                $sectors_data_arr['low_price'] = $each_indices_result['data'][0]['dayLow'];

                $sectors_data_arr['ltp'] = $each_indices_result['data'][0]['lastPrice'];

                $sectors_data_arr['change'] = $each_indices_result['data'][0]['change'];
                $sectors_data_arr['change_in_percent'] = $each_indices_result['data'][0]['pChange'];
                $sectors_data_arr['year_change_in_percent'] = $each_indices_result['data'][0]['perChange365d'];
                $sectors_data_arr['month_change_in_percent'] = $each_indices_result['data'][0]['perChange30d'];

                $sectors_data_arr['year_high_price'] = $each_indices_result['data'][0]['yearHigh'];
                $sectors_data_arr['year_low_price'] = $each_indices_result['data'][0]['yearLow'];

                $stock_timestamp = strtotime($each_indices_result['timestamp']);  
                $sectors_data_arr['stock_date_time'] = date('Y-m-d H:i:s', $stock_timestamp);   
                $sectors_data_arr['stock_date'] = date('Y-m-d', $stock_timestamp);  
                $sectors_data_arr['stock_time'] = date('H:i:s', $stock_timestamp);

                $this->Sectors_model->insertSectorsData( $sectors_data_arr );

                echo '<pre>'; print_r($sectors_data_arr); 

            }
            
            // exit;
        }
    }
    
    function extractSectorIndices2023Dup(){

        $Python_contr = new Python_Controller();
        $Nse_Contr = new Nse_Contr();
        $Python_contr->executeCookieScript();

        $index_name = urlencode('NIFTY 50');
        $url = 'https://www.nseindia.com/api/equity-stockIndices?index=' . $index_name;
        // $url = 'https://www.nseindia.com/api/allIndices';
        
        $referer = 'https://www.nseindia.com/market-data/live-equity-market?symbol='. $index_name;
        // $referer = 'https://www.nseindia.com/market-data/live-market-indices';
        
        $all_indices  = $Nse_Contr->curlNse($url, $referer);

        echo '<pre>'; print_r($all_indices); exit;

        $sectors_data_arr = array();
        if(!empty($all_indices['data'])){

            foreach($all_indices['data'] AS $each_indices){

                echo '<pre>'; print_r($each_indices); 

                $sectors_data_arr['index_name'] = $each_indices['index'];

                $sectors_data_arr['ltp'] = $each_indices['last'];

                $sectors_data_arr['change'] = $each_indices['variation'];
                $sectors_data_arr['change_in_percent'] = $each_indices['percentChange'];
                $sectors_data_arr['year_change_in_percent'] = $each_indices['perChange365d'];
                $sectors_data_arr['month_change_in_percent'] = $each_indices['perChange30d'];

                //$sectors_data_arr['trade_volume_sum'] = str_replace(",","",$sectors_data['trdVolumesum']);

                $sectors_data_arr['declines'] = $each_indices['declines'];
                $sectors_data_arr['advances'] = $each_indices['advances'];
                $sectors_data_arr['unchanged'] = $each_indices['unchanged'];
            }
        }
    }

    /*
     * OBSOLETE
     * OLD NSE Website
     * Fetch 52 week high low data
     */
    
    public function extractYearHighData( $high_or_low ) {
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Year_high_low_model');
        
        $is_inserted = $this->Year_high_low_model->checkTodaysDataAlreadyInserted($high_or_low);
        
        if( $is_inserted === 'inserted' ){ #Since data is already inserted, so exit it
            
            echo 'data is already inserted';
            exit;
        }
        
        $client = new GuzzleHttp\Client();
        
        if( $high_or_low == 'high' ){
        
            $url = 'https://www1.nseindia.com/products/dynaContent/equities/equities/json/online52NewHigh.json';
        
        }else if( $high_or_low == 'low' ){
            
            $url = 'https://www1.nseindia.com/products/dynaContent/equities/equities/json/online52NewLow.json';
            
        } 
        
        $year_high_low_data = json_decode(($client->request('GET', $url, ['timeout' => 30, 'connect_timeout' => 30, 
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.76 Safari/537.36',
                    'Upgrade-Insecure-Request'     => '1',
                    'DNT'     => '1',
                    'Accept'     => 'application/json',
                    'Accept-Language'     => 'en-US,en;q=0.5',
                    'Accept-Encoding'     => 'gzip, deflate'
                ]
            ])->getBody()->getContents()), true);
        
        $api_date_time = strtotime($year_high_low_data['time']);
        $api_date = date('Y-m-d', $api_date_time);             
        $api_date_timestanmp = strtotime($api_date);

        $today_date_timestanmp = strtotime(date('Y-m-d'));

        if( $api_date_timestanmp != $today_date_timestanmp ){

            echo 'api data of nse is not updated <br/>';
            exit();

        }else{
            echo $high_or_low . ' api data of nse is updated  <br/>';
        }
        
        $year_high_low_log_id = $this->Year_high_low_model->insertYearHighLowApiDataInLog($year_high_low_data, $high_or_low, $api_date);
        
        $this->load->model('Companies_model');
        $this->load->model('Put_call_model');
        
        $year_high_low_arr = array();
        
        foreach($year_high_low_data['data'] AS $year_high_low_data_value){
            
            if( $high_or_low == 'high' ){
            
                $year_high_low_arr['year_high_low_log_id'] = $year_high_low_log_id;
                $year_high_low_arr['company_symbol'] = trim($year_high_low_data_value['symbol']);
                $year_high_low_arr['company_id'] = $this->Companies_model->getCompanyIdBySymbol(trim($year_high_low_data_value['symbol']));
                
                $year_high_low_arr['pc_exists'] = $Send_Api_Contr->checkCompanyExistInPCByIdAndSymbol($year_high_low_arr['company_id'], $year_high_low_arr['company_symbol']);
                
                $year_high_low_arr['new_high'] = trim(str_replace(",","",$year_high_low_data_value['value']));
                
                $year_high_low_arr['year_high'] = trim(str_replace(",","",$year_high_low_data_value['year']));
                $year_high_low_arr['ltp'] = trim(str_replace(",","",$year_high_low_data_value['ltp']));
                $year_high_low_arr['prev_high'] = trim(str_replace(",","",$year_high_low_data_value['value_old']));

                $prev_high_date_timestamp = strtotime(trim($year_high_low_data_value['dt']));
                $prev_high_date = date('Y-m-d', $prev_high_date_timestamp);
                $year_high_low_arr['prev_high_date'] = $prev_high_date;

                $year_high_low_arr['prev_close'] = trim(str_replace(",","",$year_high_low_data_value['prev']));
                $year_high_low_arr['change'] = trim(str_replace(",","",$year_high_low_data_value['change']));
                $year_high_low_arr['pChange'] = trim($year_high_low_data_value['pChange']);
                $year_high_low_arr['market_date'] = $api_date;
                $year_high_low_arr["created_at"] = date("Y-m-d H:i:s");


                $this->Year_high_low_model->insertYearHighApiData($year_high_low_arr );

            }else if($high_or_low =='low'){
                
                $year_high_low_arr['year_high_low_log_id'] = $year_high_low_log_id;
                $year_high_low_arr['company_symbol'] = trim($year_high_low_data_value['symbol']);  
                $year_high_low_arr['company_id'] = $this->Companies_model->getCompanyIdBySymbol(trim($year_high_low_data_value['symbol']));
                
                $year_high_low_arr['pc_exists'] = $Send_Api_Contr->checkCompanyExistInPCByIdAndSymbol($year_high_low_arr['company_id'], $year_high_low_arr['company_symbol']);
                
                $year_high_low_arr['new_low'] = trim(str_replace(",","",$year_high_low_data_value['value']));
                
                $year_high_low_arr['year_low'] = trim(str_replace(",","",$year_high_low_data_value['year']));
                $year_high_low_arr['ltp'] = trim(str_replace(",","",$year_high_low_data_value['ltp']));
                $year_high_low_arr['prev_low'] = trim(str_replace(",","",$year_high_low_data_value['value_old']));
                
                $prev_high_date_timestamp = strtotime(trim($year_high_low_data_value['dt']));
                $prev_high_date = date('Y-m-d', $prev_high_date_timestamp);
                $year_high_low_arr['prev_low_date'] = $prev_high_date;
                
                $year_high_low_arr['prev_close'] = trim(str_replace(",","",$year_high_low_data_value['prev']));
                $year_high_low_arr['change'] = trim(str_replace(",","",$year_high_low_data_value['change']));
                $year_high_low_arr['pChange'] = trim($year_high_low_data_value['pChange']);
                $year_high_low_arr['market_date'] = $api_date;
                $year_high_low_arr["created_at"] = date("Y-m-d H:i:s");
                
                $this->Year_high_low_model->insertYearLowApiData($year_high_low_arr );
            }
        }
        
    }
    
    /*
     * @author : ZAHIR
     * Fetch 52 week high low data
     */
    function extractYearHighLow($high_or_low){
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Year_high_low_model');
        
        $is_inserted = $this->Year_high_low_model->checkTodaysDataAlreadyInserted($high_or_low);
        
        if( $is_inserted === 'inserted' ){ #Since data is already inserted, so exit it
            
            echo 'data is already inserted';
            exit;
        }
        
        $Python_contr = new Python_Controller();
        $Nse_Contr = new Nse_Contr();   
        
        $Python_contr->executeCookieScript();
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
        if( $check_open_and_closing_price === 'no' ){ echo 'Market is closed today - ' . date('d-M-Y'); return; } /* Check if market is open, if returns no then exit */
        
        if( ( $check_open_and_closing_price > 0 ) ||  ( date('H')>= (HOUR_FOR_FINAL_DATA+1) ) ){
            
        }else{
            
            echo 'Market is not updated yet';
            
            return;
        }
        
        if( $high_or_low == 'high' ){
        
            $url = 'https://www.nseindia.com/api/live-analysis-52Week?index=high';
            
            $referer = 'https://www.nseindia.com/market-data/new-52-week-high-low-equity-market';
        
        }else if( $high_or_low == 'low' ){
            
            $url = 'https://www.nseindia.com/api/live-analysis-52Week?index=low';
            
            $referer = 'https://www.nseindia.com/market-data/new-52-week-high-low-equity-market';
            
        } 
                
        $year_high_low_data  = $Nse_Contr->curlNse($url, $referer); 
        
        if( !empty($year_high_low_data['dataLtpGreater20']) && !empty($year_high_low_data['dataLtpLess20']) ){
            
            $year_high_low_data['data'] = (array_merge($year_high_low_data['dataLtpGreater20'],$year_high_low_data['dataLtpLess20']));
            
            unset($year_high_low_data['dataLtpGreater20']);
            unset($year_high_low_data['dataLtpLess20']);
            
        }else if( !empty($year_high_low_data['dataLtpGreater20']) ){
            
            $year_high_low_data['data'] = $year_high_low_data['dataLtpGreater20'];
            
            unset($year_high_low_data['dataLtpGreater20']);
            
        }else if( !empty($year_high_low_data['dataLtpGreater20']) ){
            
            $year_high_low_data['data'] = $year_high_low_data['dataLtpLess20'];
            
            unset($year_high_low_data['dataLtpLess20']);
            
        }else{
            
            return;
        }
            
        echo '<pre>'; print_r($year_high_low_data);
        
        $api_date_time = strtotime($year_high_low_data['timestamp']);
        $api_date = date('Y-m-d', $api_date_time);             
        $api_date_timestanmp = strtotime($api_date);

        $today_date_timestanmp = strtotime(date('Y-m-d'));
        
        if( $api_date_timestanmp != $today_date_timestanmp ){

            echo 'api data of nse is not updated <br/>';
            return;

        }else{
            echo $high_or_low . ' api data of nse is updated  <br/>';
        }
        
        $year_high_low_log_id = $this->Year_high_low_model->insertYearHighLowApiDataInLog($year_high_low_data, $high_or_low, $api_date);
        
        $this->load->model('Companies_model');
        
        $year_high_low_arr = array();
        
        foreach($year_high_low_data['data'] AS $year_high_low_data_value){
            
            if( $high_or_low == 'high' ){
            
                $year_high_low_arr['year_high_low_log_id'] = $year_high_low_log_id;
                $year_high_low_arr['company_symbol'] = trim($year_high_low_data_value['symbol']);
                $year_high_low_arr['company_id'] = $this->Companies_model->getCompanyIdBySymbol(trim($year_high_low_data_value['symbol']));
                
                $year_high_low_arr['pc_exists'] = $Send_Api_Contr->checkCompanyExistInPCByIdAndSymbol($year_high_low_arr['company_id'], $year_high_low_arr['company_symbol']);
                
                $year_high_low_arr['new_high'] = trim(str_replace(",","",$year_high_low_data_value['new52WHL']));
                
                $year_high_low_arr['ltp'] = trim(str_replace(",","",$year_high_low_data_value['ltp']));
                $year_high_low_arr['prev_high'] = trim(str_replace(",","",$year_high_low_data_value['prev52WHL']));
                
                $prev_high_date_timestamp = strtotime(trim($year_high_low_data_value['prevHLDate']));
                $prev_high_date = date('Y-m-d', $prev_high_date_timestamp);
                $year_high_low_arr['prev_high_date'] = $prev_high_date;
                
                $year_high_low_arr['prev_close'] = trim(str_replace(",","",$year_high_low_data_value['prevClose']));
                $year_high_low_arr['change'] = trim(str_replace(",","",$year_high_low_data_value['change']));
                $year_high_low_arr['pChange'] = trim($year_high_low_data_value['pChange']);
                $year_high_low_arr['market_date'] = $api_date;
                $year_high_low_arr["created_at"] = date("Y-m-d H:i:s");
                
                $this->Year_high_low_model->insertYearHighApiData($year_high_low_arr );
                
            }else if($high_or_low =='low'){
                
                $year_high_low_arr['year_high_low_log_id'] = $year_high_low_log_id;
                $year_high_low_arr['company_symbol'] = trim($year_high_low_data_value['symbol']);  
                $year_high_low_arr['company_id'] = $this->Companies_model->getCompanyIdBySymbol(trim($year_high_low_data_value['symbol']));
                
                $year_high_low_arr['pc_exists'] = $Send_Api_Contr->checkCompanyExistInPCByIdAndSymbol($year_high_low_arr['company_id'], $year_high_low_arr['company_symbol']);
                
                $year_high_low_arr['new_low'] = trim(str_replace(",","",$year_high_low_data_value['new52WHL']));
                
                $year_high_low_arr['ltp'] = trim(str_replace(",","",$year_high_low_data_value['ltp']));
                $year_high_low_arr['prev_low'] = trim(str_replace(",","",$year_high_low_data_value['prev52WHL']));
                
                $prev_high_date_timestamp = strtotime(trim($year_high_low_data_value['prevHLDate']));
                $prev_high_date = date('Y-m-d', $prev_high_date_timestamp);
                $year_high_low_arr['prev_low_date'] = $prev_high_date;
                
                $year_high_low_arr['prev_close'] = trim(str_replace(",","",$year_high_low_data_value['prevClose']));
                $year_high_low_arr['change'] = trim(str_replace(",","",$year_high_low_data_value['change']));
                $year_high_low_arr['pChange'] = trim($year_high_low_data_value['pChange']);
                $year_high_low_arr['market_date'] = $api_date;
                $year_high_low_arr["created_at"] = date("Y-m-d H:i:s");
                
                $this->Year_high_low_model->insertYearLowApiData($year_high_low_arr );
                
            }
        }
        
    }
    
    /*
     * Read lot monthly size of derivative 
     */
    
    function readLotSizeMonthly($lot_model_loaded=0){
        
        $System_Notification_contr = new System_Notification_Controller();
        $Send_Api_Contr = new Send_Api_Contr();
        
        try{
            
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );

            $data = file_get_contents("https://www1.nseindia.com/content/fo/fo_mktlots.csv", false, $context);

            if($data === FALSE) {

                echo 'data not found'; $System_Notification_contr->failReadLotSize('option'); return;
            }

            $rows = explode("\n", $data);
            
            foreach ($rows as $row_key=>$lot_csv_row_value) {

                $lot_csv_arr = str_getcsv($lot_csv_row_value); 
                
                if( empty($lot_csv_arr[1])){ continue; }
                
                if( trim($lot_csv_arr[1]) === 'SYMBOL' ){
                    
                    $month_year_arr = array();
                    
                    for( $i=2; $i<count($lot_csv_arr); $i++ ){
                        
                        $month_year = trim($lot_csv_arr[$i]);
                        
                        $month_year_expld = explode('-', $month_year);
                        
                        $month = $month_year_expld[0];
                        
                        $month_year_arr[$i]['month'] = $month_year_expld[0];
                        
                        $month_year_arr[$i]['year'] = $month_year_expld[1];
                    }
                    
                    echo '<pre>'; print_r($month_year_arr);
                    
                }else{
                    
                    $index_or_company_symbol = trim($lot_csv_arr[1]);

                    $company_id = $Send_Api_Contr->getCompanyIdAndIndexIdBySymbol($index_or_company_symbol);
                    
                    if( empty($company_id) ){ continue; }
                    
                    for( $i=2; $i<count($lot_csv_arr); $i++ ){
                        
                        if(empty(trim($lot_csv_arr[$i]))){ continue; }
                        
                        $lot_arr = array();
                        
                        $lot_arr['company_id'] = $company_id;
                        $lot_arr['company_symbol'] = $index_or_company_symbol;
                        $lot_arr['size'] = trim($lot_csv_arr[$i]);
                        $lot_arr['month'] = date('m', strtotime($month_year_arr[$i]['month'] ));
                        $lot_arr['year'] = $month_year_arr[$i]['year'];
                        
                        $lot_arr["created_at"] = date("Y-m-d H:i:s");
                        
                        echo '<pre>'; print_r($lot_arr);
                        
                        $insert_lot_size = $Send_Api_Contr->inserMonthlytLotSize($lot_arr, $lot_model_loaded);
                        
                    }
                }
                
            }
            
        }catch (Exception $e) {
            
            echo 'errz ' ;  
            echo '<pre>'; print_r($e);
            
            $System_Notification_contr->failReadLotSize('option');
        }
    }
    
    /*
     * Read Lot size of option from csv
     */
    
    function readLotSizeOfOC($lot_model_loaded=0){
        
        $System_Notification_contr = new System_Notification_Controller();
        $Send_Api_Contr = new Send_Api_Contr();
        
        try{
        
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );

            $data = file_get_contents("https://www1.nseindia.com/content/fo/fo_mktlots.csv", false, $context);

            if($data === FALSE) {

                echo 'data not found'; $System_Notification_contr->failReadLotSize('option'); return;
            }

            $rows = explode("\n", $data);

            foreach ($rows as $row_key=>$lot_csv_row_value) {

                $lot_csv_arr = str_getcsv($lot_csv_row_value);                        

                if( empty($lot_csv_arr[1]) || trim($lot_csv_arr[1]) === 'SYMBOL' || trim($lot_csv_arr[1]) === 'SYMBOL' ){

                    continue;
                }



                $index_or_company_symbol = trim($lot_csv_arr[1]);

                $company_id = $Send_Api_Contr->getCompanyIdAndIndexIdBySymbol($index_or_company_symbol);

                if( empty($company_id) ){ continue; }

                $lot_value = 0;

                for($i=2; $i<count($lot_csv_arr); $i++){

                    if(empty(trim($lot_csv_arr[$i]))){ continue; }

                    $lot_value = trim($lot_csv_arr[$i]);

                    if( $i>2 && trim($lot_csv_arr[$i]) !== trim($lot_csv_arr[$i-1])){

                        $System_Notification_contr->lotSizevalueNotSame('option', $index_or_company_symbol);
                    }

                }

                if(empty($lot_value)){ continue; }

                $lot_arr = array();

                $lot_arr['company_id'] = $company_id;
                $lot_arr['company_symbol'] = $index_or_company_symbol;
                $lot_arr['derivative_type'] = 'oc';
                $lot_arr['size'] = $lot_value;
                $lot_arr["created_at"] = date("Y-m-d H:i:s");

                $insert_lot_size = $Send_Api_Contr->insertLotSize($lot_arr, $lot_model_loaded);

            }
            
        }catch (Exception $e) {
            
            echo 'errz ' ;  
            echo '<pre>'; print_r($e);
            
            $System_Notification_contr->failReadLotSize('option');
        }
        
    }
    
    /*
    * @Author: Zahir
     * Read daily volatility and annual volatility
     */
    
    function readDailyAnnualyVolatility(){
        
        $System_Notification_contr = new System_Notification_Controller();
        $Send_Api_Contr = new Send_Api_Contr();
        
        try{
            
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );
            $url = "https://www.nseindia.com/archives/nsccl/volt/CMVOLT_".date('dmY').".CSV";   
            echo $url . "<br/>";         
            
            $data = file_get_contents($url, false, $context);

            echo '<pre>'; print_r($data); exit;
            
            if($data === FALSE) {

                echo 'data not found'; $System_Notification_contr->failReadDailyAnnualyVolatility($url); return;
            }
            
            $rows = explode("\n", $data);                        
            
            foreach ($rows as $row_key=>$each_row_value) {

                $each_row_arr = str_getcsv($each_row_value); 
                
                if( empty($each_row_arr[0]) || empty($each_row_arr[1]) || empty($each_row_arr[6]) || empty($each_row_arr[7]) ){ continue; }
                
                $date_raw_csv = $each_row_arr[0];
                
                $date_raw_csv_timestamp = strtotime(trim($date_raw_csv));
                $date_csv = date('Y-m-d', $date_raw_csv_timestamp);
               
                $index_or_company_symbol = trim($each_row_arr[1]);

                $company_id = $Send_Api_Contr->getCompanyIdAndIndexIdBySymbol($index_or_company_symbol);

                if( empty($company_id) ){ continue; }
                
                echo $date_csv . '<br/>';
                
                echo '<pre>'; print_r($each_row_arr);
                
                $volatility_arr = array();

                $volatility_arr['company_id'] = $company_id;
                $volatility_arr['company_symbol'] = $index_or_company_symbol;
                $volatility_arr['daily_volatility'] = trim($each_row_arr[6]);
                $volatility_arr['daily_volatility_p'] = trim($each_row_arr[6]) * 100;
                $volatility_arr['annual_volatility'] = trim($each_row_arr[7]);
                $volatility_arr['annual_volatility_p'] = trim($each_row_arr[7]) * 100;
                
                $volatility_arr['derivative'] = $Send_Api_Contr->checkCompanyExistInPCByIdAndSymbol($company_id, $index_or_company_symbol);
                
                $volatility_arr["market_date"] = $date_csv;
                $volatility_arr["created_at"] = date("Y-m-d H:i:s");
                
                echo '<pre>'; print_r($volatility_arr);
                
                $this->load->model('Volatility_model');
                
                $this->Volatility_model->insertDailyAnnualyVolatility($volatility_arr);
                
            }
            
        } catch (Exception $e) {
            
            echo 'errz ' ;  
            echo '<pre>'; print_r($e);
            
            echo 'data not found'; $System_Notification_contr->failReadDailyAnnualyVolatility($url);
        }
        
        
    }
    
    function readParticipantOiForAllDate(){
        
        // Start date
	$date = '2019-01-01';
	// End date
	$end_date = '2020-03-13';

	while (strtotime($date) <= strtotime($end_date)) {
                echo "$date\n <br/>";
                
                $market_date = date("dmY", strtotime($date) );                                
                
                echo "$market_date\n <br/>";
                
                $this->readParticipantOi($date);
                
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
    }
    
    /*
     * Read Participant wise Open Interest (no. of contracts) in Equity Derivatives 
     */
    
    function readParticipantOi( $input_market_date=false ){
        
        
        $System_Notification_contr = new System_Notification_Controller();              
        
        if( !empty ($input_market_date) ){
            
            $input_market_date = $input_market_date;
            
        }else{
            
            $input_market_date = date ("Y-m-d");
        }
        
        $url = "https://www1.nseindia.com/content/nsccl/fao_participant_oi_" . date("dmY", strtotime($input_market_date)) . ".csv";
//        $url = "https://www1.nseindia.com/content/nsccl/fao_participant_oi_16032020.csv";
        
        try{
        
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );
            
            $data = file_get_contents($url, false, $context);            
            
            if($data === FALSE) {

                echo 'data not found'; $System_Notification_contr->failReadParticipantOi($url, $input_market_date); return;
            }
            
            
            echo 'rows ' ;  
            echo '<pre>'; print_r($data);
            
            $rows = explode("\n", $data);
            
            echo 'rows ' ;  
            echo '<pre>'; print_r($rows);
            
            foreach ($rows as $row_key=>$each_row_value) {

                $each_row_arr = str_getcsv($each_row_value); 
                
                echo '$row_key : ' . $row_key . ' <br/>' ;  
                echo '<pre>'; print_r($each_row_arr);
                
                if( $row_key === 0 || $row_key === 1 || $row_key === 7 ){ continue; }
                
                $participant_oi_arr = array();
                
                $participant_oi_arr['market_date'] = $input_market_date;
                
                $participant_oi_arr['client_type'] = $each_row_arr[0];
                $participant_oi_arr['future_index_long'] = $each_row_arr[1];
                $participant_oi_arr['future_index_short'] = $each_row_arr[2];
                $participant_oi_arr['future_stock_long'] = $each_row_arr[3];
                $participant_oi_arr['future_stock_short'] = $each_row_arr[4];
                $participant_oi_arr['option_index_call_long'] = $each_row_arr[5];
                $participant_oi_arr['option_index_put_long'] = $each_row_arr[6];
                $participant_oi_arr['option_index_call_short'] = $each_row_arr[7];
                $participant_oi_arr['option_index_put_short'] = $each_row_arr[8];
                $participant_oi_arr['option_stock_call_long'] = $each_row_arr[9];
                $participant_oi_arr['option_stock_put_long'] = $each_row_arr[10];
                $participant_oi_arr['option_stock_call_short'] = $each_row_arr[11];
                $participant_oi_arr['option_stock_put_short'] = $each_row_arr[12];
                $participant_oi_arr['total_long_contracts'] = $each_row_arr[13];
                $participant_oi_arr['total_short_contracts'] = $each_row_arr[14];
                
                $participant_oi_arr["created_at"] = date("Y-m-d H:i:s");
                
                echo '<pre>'; print_r($participant_oi_arr);
                
                $this->load->model('ParticipantOi_model');
                
                $this->ParticipantOi_model->insertParticipantOi($participant_oi_arr);
                
            }
            
        } catch (Exception $e) {
            
            echo 'errz ' ;  
            echo '<pre>'; print_r($e);
            
            echo 'data not found Exception'; $System_Notification_contr->failReadParticipantOi($url, $input_market_date);
        }
    }
    
    /*
     * Participant wise Trading Volume (no. of contracts) in Equity Derivatives
     */
    
    function readParticipantVolume( $input_market_date=false ){
        
        
        $System_Notification_contr = new System_Notification_Controller();              
        
        if( !empty ($input_market_date) ){
            
            $input_market_date = $input_market_date;
            
        }else{
            
            $input_market_date = date ("Y-m-d");
        }
        
        $url = "https://www1.nseindia.com/content/nsccl/fao_participant_vol_" . date("dmY", strtotime($input_market_date)) . ".csv";
//        $url = "https://www1.nseindia.com/content/nsccl/fao_participant_vol_15032020.csv";
        
        try{
        
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );
            
            $data = file_get_contents($url, false, $context);            
            
            if($data === FALSE) {

                echo 'data not found'; $System_Notification_contr->failReadParticipantVolume($url, $input_market_date); return;
            }
            
            
            echo 'rows ' ;  
            echo '<pre>'; print_r($data);
            
            $rows = explode("\n", $data);
            
            echo 'rows ' ;  
            echo '<pre>'; print_r($rows);
            
            foreach ($rows as $row_key=>$each_row_value) {

                $each_row_arr = str_getcsv($each_row_value); 
                
                echo '$row_key : ' . $row_key . ' <br/>' ;  
                echo '<pre>'; print_r($each_row_arr);
                
                if( $row_key === 0 || $row_key === 1 || $row_key === 7 ){ continue; }
                
                $participant_vol_arr = array();
                
                $participant_vol_arr['market_date'] = $input_market_date;
                
                $participant_vol_arr['client_type'] = $each_row_arr[0];
                $participant_vol_arr['future_index_long'] = $each_row_arr[1];
                $participant_vol_arr['future_index_short'] = $each_row_arr[2];
                $participant_vol_arr['future_stock_long'] = $each_row_arr[3];
                $participant_vol_arr['future_stock_short'] = $each_row_arr[4];
                $participant_vol_arr['option_index_call_long'] = $each_row_arr[5];
                $participant_vol_arr['option_index_put_long'] = $each_row_arr[6];
                $participant_vol_arr['option_index_call_short'] = $each_row_arr[7];
                $participant_vol_arr['option_index_put_short'] = $each_row_arr[8];
                $participant_vol_arr['option_stock_call_long'] = $each_row_arr[9];
                $participant_vol_arr['option_stock_put_long'] = $each_row_arr[10];
                $participant_vol_arr['option_stock_call_short'] = $each_row_arr[11];
                $participant_vol_arr['option_stock_put_short'] = $each_row_arr[12];
                $participant_vol_arr['total_long_contracts'] = $each_row_arr[13];
                $participant_vol_arr['total_short_contracts'] = $each_row_arr[14];
                
                $participant_vol_arr["created_at"] = date("Y-m-d H:i:s");
                
                echo '<pre>'; print_r($participant_vol_arr);
                
                $this->load->model('ParticipantVolume_model');
                
                $this->ParticipantVolume_model->insertParticipantVolume($participant_vol_arr);
                
            }
            
        } catch (Exception $e) {
            
            echo 'errz ' ;  
            echo '<pre>'; print_r($e);
            
            echo 'data not found Exception'; $System_Notification_contr->failReadParticipantVolume($url, $input_market_date);
        }
    }
    
    /*
     * Read Nifty top 10 stocks
     */
    function readNiftyTopWeightageStock( $input_market_date=false ){
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $System_Notification_contr = new System_Notification_Controller();  
        
        if( !empty ($input_market_date) ){
            
            $input_market_date = $input_market_date;
            
        }else{
            
            $input_market_date = date ("Y-m-d");
        }
        
//        echo $input_market_date; exit;
        
//        echo date("dmy", strtotime($input_market_date)); exit;
        
//        $url = "https://www1.nseindia.com/content/nsccl/fao_participant_vol_" . date("dmY", strtotime($input_market_date)) . ".csv";
        $url = "https://www1.nseindia.com/content/indices/top10nifty50_".date("dmy", strtotime($input_market_date)).".csv";                
        
        $context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
        );

        $data = file_get_contents($url, false, $context);            

        if($data === FALSE) {

            echo 'data not found'; $System_Notification_contr->failReadNiftyTopWeightageStock($url, $input_market_date); return;
        }

//        echo 'rows ' ;  
//        echo '<pre>'; print_r($data);

        $rows = explode("\n", $data);

//        echo 'rows ' ;  
//        echo '<pre>'; print_r($rows);
        
        foreach ($rows as $row_key=>$each_row_value) {
            
            $nifty_top_arr = array();
            
            $each_row_arr = str_getcsv($each_row_value); 

//            echo '$row_key : ' . $row_key . ' <br/>' ;  
//            echo '<pre>'; print_r($each_row_arr);
            
            $company_symbol = trim($each_row_arr[0]);

            $company_id = $Send_Api_Contr->getCompanyIdAndIndexIdBySymbol($company_symbol);

            if( empty($company_id) ){ continue; }
            
            $nifty_top_arr['company_id'] = $company_id;
            $nifty_top_arr['company_symbol'] = $company_symbol;
            $nifty_top_arr['weightage'] = trim($each_row_arr[2]);
            $nifty_top_arr['market_date'] = $input_market_date;
            $nifty_top_arr["created_at"] = date("Y-m-d H:i:s");
            
            echo '<pre>'; print_r($nifty_top_arr);
            
            $this->load->model('Nifty_model');
                
            $this->Nifty_model->insertNiftyTopWeightageStock($nifty_top_arr);
            
        }
    }
    
    /*
     * Volume and Turnover data of top 10 Clearing Members (no. of contracts) in Equity Derivatives as on Apr 13, 2020 (Amounts in Rs. Crore)
     */
    function readNseTopClearingMember( $input_market_date=false ){
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $System_Notification_contr = new System_Notification_Controller();  
        
        if( !empty ($input_market_date) ){
            
            $input_market_date = $input_market_date;
            
        }else{
            
            $input_market_date = date ("Y-m-d");
        }
        
//        echo date("dmY", strtotime($input_market_date)); exit;
        
//        echo $input_market_date; exit;
        
//        echo date("dmy", strtotime($input_market_date)); exit;
        
              
//        $url = "https://www1.nseindia.com/content/nsccl/fao_top10cm_to_13042020.csv";                
        $url = "https://www1.nseindia.com/content/nsccl/fao_top10cm_to_".date("dmY", strtotime($input_market_date)).".csv";                
        
        $context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
        );

        $data = file_get_contents($url, false, $context);            

        if($data === FALSE) {

            echo 'data not found'; $System_Notification_contr->failReadNseTopClearingMember($url, $input_market_date); return;
        }

        $rows = explode("\n", $data);

//        echo 'rows ' ;  
//        echo '<pre>'; print_r($rows);
        
        foreach ($rows as $row_key=>$each_row_value) {
            
            if( $row_key < 2 || $row_key > 12 ){continue;}
            
            $clearing_member_arr = array();
            
            $each_row_arr = str_getcsv($each_row_value); 
            
//            echo $row_key;
//            echo '<pre>'; print_r($each_row_arr);
            
            $clearing_member_arr['serial_no'] = ( trim($each_row_arr[0]) ==='TOTAL' ) ? 11 : trim($each_row_arr[0]);
            
            $clearing_member_arr['index_futures_vol'] = trim($each_row_arr[1]);
            $clearing_member_arr['index_futures_trnvr'] = trim($each_row_arr[2]);
            
            $clearing_member_arr['stock_futures_vol'] = trim($each_row_arr[3]);
            $clearing_member_arr['stock_futures_trnvr'] = trim($each_row_arr[4]);
            
            $clearing_member_arr['index_option_vol'] = trim($each_row_arr[5]);
            $clearing_member_arr['index_option_trnvr'] = trim($each_row_arr[6]);
            $clearing_member_arr['index_option_trnvr_prm'] = trim($each_row_arr[7]);
            
            $clearing_member_arr['stock_option_vol'] = trim($each_row_arr[8]);
            $clearing_member_arr['stock_option_trnvr'] = trim($each_row_arr[9]);
            $clearing_member_arr['stock_option_trnvr_prm'] = trim($each_row_arr[10]);
            
            $clearing_member_arr['exchange'] = 'nse';
            $clearing_member_arr['market_date'] = $input_market_date;
            $clearing_member_arr["created_at"] = date("Y-m-d H:i:s");
            
//            echo '<pre>'; print_r($clearing_member_arr);
            
            $this->load->model('Fii_dii_model');
                
            $this->Fii_dii_model->insertNseTopClearingMember($clearing_member_arr);
            
        }
        
    }
    
    /*
     * Category-Wise Turnover
     */
    function derivativeCategoryWiseTurnover( ){
        
        $this->load->model('Fii_dii_model');
        
        include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
        
        $System_Notification_contr = new System_Notification_Controller();
        
        require PROJECT_DOCUMENT_ROOT . '/application/libraries/simplexls/SimpleXLS.php';
        
        $current_date = date ("Y-m-d");
        
        for( $i=5; $i>=0; $i-- ){
            
            echo $input_market_date = date('Y-m-d', strtotime('-'.$i.' day', strtotime($current_date)));
            echo '<br/>';
        
            // set path to temp directory
            $temp_directory = PROJECT_DOCUMENT_ROOT . '/assets/fii-dii/fii-derivative-nse';

            // set direct url to mp3
    //        $derivative_url = "https://www1.nseindia.com/archives/fo/cat/fo_cat_turnover_130420.xls";
            $derivative_url = "https://www1.nseindia.com/archives/fo/cat/fo_cat_turnover_" . date("dmy", strtotime($input_market_date)) . ".xls";

            // set name of mp3
            $name = 'cat-wise-turnover';                        

            $context = stream_context_create(
                    array(
                        "http" => array(
                            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                        )
                    )
                );

            // download file to temp directory
            $data = file_put_contents($temp_directory.'/'.$name.'.xls',file_get_contents($derivative_url, false, $context));

    //        echo '<pre>';
    //        print_r($data); exit;

            if($data == FALSE) {

                echo 'data not found'; $System_Notification_contr->failRead($derivative_url, $input_market_date, 'Fail Read Category-Wise Turnover of Derivative'); continue;
            }

            if ($xls = SimpleXLS::parse($temp_directory . '/' . $name . '.xls')) {

    //            echo '<pre>';
    //            print_r($xls->rows());

                foreach( $xls->rows() AS $key=>$each_derivative_segment){

                    if( $key !== 1 && $key !== 2 && $key !== 3 ){ continue; }

                    echo '$key :' . $key;

                    $cat_wise_turnover = array();

                    $cat_wise_turnover['category'] = trim($each_derivative_segment[1]);
                    $cat_wise_turnover['buy_value'] = trim($each_derivative_segment[2]);
                    $cat_wise_turnover['sell_value'] = trim($each_derivative_segment[3]);

                    $cat_wise_turnover['exchange'] = 'nse';
                    $cat_wise_turnover['trading_type'] = 'derivative';
                    $cat_wise_turnover['market_date'] = $input_market_date;
                    $cat_wise_turnover["created_at"] = date("Y-m-d H:i:s");

                    echo '<pre>';
                    print_r($cat_wise_turnover);

                    $this->Fii_dii_model->categoryWiseTurnover($cat_wise_turnover);

                }

            } else {
                echo SimpleXLS::parseError();

                echo 'fail';
            }
        
        }
    }
    
    function mostActiveDatainDerivative( ){
        
        $Nse_Contr = new Nse_Contr(); 
        
        $check_open_and_closing_price = $Nse_Contr->checkMarketIsOpenedToday();
        
        if( $check_open_and_closing_price === 'no' ){ return; }/* Check if market is open, if returns no then exit */
        
        $snapshot_arr = array('contracts', 'futures', 'options', 'puts', 'calls', 'oi');
        
        foreach( $snapshot_arr AS $snapshot_of){
            
            if(   ( date('H') < 16 ) && ( ($snapshot_of == 'puts') ||  ($snapshot_of == 'calls')  )  ){
                
                echo '<br/> snapshot_of: '. $snapshot_of . '<br/>'; 
                
            }else if( date('H') >= 16 ){
                
                echo '<br/> <br/> date greater than equal to ' . date('H') . ' , ' .$snapshot_of.  '<br/>';
                
            }else{
                
                echo '<br/> else ' . $snapshot_of . ' !<br/>'; 
                
                continue;
            }
            
            echo ' <br/> #####'; 
            $url = 'https://www.nseindia.com/api/snapshot-derivatives-equity?index=' . $snapshot_of;

            $referer = 'https://www.nseindia.com/market-data/most-active-contracts';

            $api_output = $Nse_Contr->curlNse($url, $referer);
            
//            echo '<pre>';
//            print_r($api_output); 
//            exit;
            
            if( empty($api_output) ){ continue; }

            $active_by_arr = array('volume', 'value');

            $this->load->model('MostActive_model');

            foreach( $active_by_arr AS $active_by ){

                $underlying_date_time = $api_output[$active_by]['timestamp'];

                foreach( $api_output[$active_by]['data'] AS $api_output_val ){

                    $this->load->model('Companies_model');

                    $company_info_arr = $this->Companies_model->getActiveInactiveCompanyBySymbol(trim( $api_output_val['underlying'] ));                

                    if( empty($company_info_arr->id) ){ continue;}

                    $data['snapshot_of'] = $snapshot_of;
                    $data['instrument_type'] = $api_output_val['instrumentType'];
                    $data['instrument'] = $api_output_val['instrument'];
                    $data['company_symbol'] = $api_output_val['underlying'];
                    $data['expiry_date'] = date('Y-m-d', strtotime( $api_output_val['expiryDate']) );
                    $data['option_type'] = $api_output_val['optionType'];
                    $data['strike_price'] = $api_output_val['strikePrice'];
                    $data['last_price'] = $api_output_val['lastPrice'];
                    $data['contracts_traded'] = $api_output_val['numberOfContractsTraded'];
                    $data['total_turnover'] = $api_output_val['totalTurnover'];
                    $data['premium_turnover'] = $api_output_val['premiumTurnover'];
                    $data['oi'] = $api_output_val['openInterest'];
                    $data['underlying_price'] = $api_output_val['underlyingValue'];
                    $data['p_change'] = $api_output_val['pChange'];
                    $data['underlying_date_time'] = date('Y-m-d H:i:s', strtotime($underlying_date_time) );
                    $data['underlying_date'] = date('Y-m-d', strtotime($underlying_date_time) );
                    $data['underlying_time'] = date('H:i:s', strtotime($underlying_date_time) );
                    
                    if( $data['underlying_time'] === '15:30:00' ){
                        
                        $data['market_running'] = 0;
                    }else{
                        
                        $data['market_running'] = 1;
                    }
                    
                    $data["company_id"] = $company_info_arr->id;

                    $data["active_by"] = $active_by;
                    $data["created_at"] = date("Y-m-d H:i:s");

    //                echo '<pre>';
    //                print_r($data);

                    $this->MostActive_model->insertActiveData( $data );
                }
            }
            
        }
        
    }
    
    function msnFundamentalFetch(){
        
        include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
        
        $System_Notification_contr = new System_Notification_Controller();
        
        $first_url = "https://www.msn.com/en-in/money/getfilterresponse?filters=Country%7CIND&ranges=&sortedby=Mc&sortorder=DSC&count=20&offset=0&market=IND&sectype=Stock";                
        
        $first_context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
        );

        $first_data = file_get_contents($first_url, false, $first_context);            
        
        $first_json_data = json_decode($first_data, true);
        
        if( $first_data === FALSE || empty($first_json_data['Count']) ) {

            echo 'data not found'; $System_Notification_contr->failRead($first_url, date ("Y-m-d"), 'MSN - Fail Read Fundamental'); return;
        }
        
        echo ($first_json_data['Count']) . "<br/>";
        
        $total_data = $first_json_data['Count']; 
        
        for( $i=0; $i<=$total_data; $i +=20 ){
            
            if( $i >=60 ){ exit;}
            
            echo 'i : ' . $i . "<br/>";
            
            $url = "https://www.msn.com/en-in/money/getfilterresponse?filters=Country%7CIND&ranges=&sortedby=Mc&sortorder=DSC&count=20&offset=".$i."&market=IND&sectype=Stock";                
            
            echo $url . "<br/>";
            
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );

            $data = file_get_contents($url, false, $context);   
            
//            echo $data . '<br/>';
            
            $json_data = json_decode($data, true);            
            
            if( $data === FALSE || empty($json_data['Count']) || empty($json_data['DataList'])) {

                echo 'data not found'; $System_Notification_contr->failRead($first_url, date ("Y-m-d"), 'MSN - Fail Read Fundamental'); continue;
            }
            
            if( $json_data['DataList'] > 0 ){
             
                $this->msnFundamentalDataProcess( $json_data['DataList'] );
                
            }
            
        }        
    }
    
    function msnFundamentalDataProcess( $data_list ){
        
//        $total_data = count($data_list);        
//        echo 'total_data : ' . $total_data . "<br/>";
        
        $msn_data = array();
        
        foreach( $data_list AS $data_list_key=>$data_list_val ){
            
            echo 'data_list_key : ' . $data_list_key . "<br/>";
            
            if( $data_list_val['ExSn'] !='NSE' ){ continue; }
            
            
            $msn_data['predict'] = $data_list_val['Ar'];
            $msn_data['market_cap'] = $data_list_val['Beta'];
            $msn_data['book_value_per_share'] = $data_list_val['Bvps'];
            $msn_data['Ch'] = $data_list_val['Ch'];
            $msn_data['Chp'] = $data_list_val['Chp'];
//            $msn_data['Cmp'] = $data_list_val['Cmp'];
            $msn_data['current_ratio'] = $data_list_val['CrntRt'];           
            $msn_data['debt_to_equity_ratio'] = ($data_list_val['De']/100);
            
            $msn_data['Dh'] = $data_list_val['Dh'];
            $msn_data['DiffYhDh'] = $data_list_val['DiffYhDh'];
            $msn_data['DiffYlDl'] = $data_list_val['DiffYlDl'];            
            $msn_data['Dl'] = $data_list_val['Dl'];
            $msn_data['DlEPS3YrGrth'] = $data_list_val['DlEPS3YrGrth'];
            $msn_data['Dy'] = $data_list_val['Dy']; /* Might be Dividend Rate (Yield) , */
            
            $msn_data['eps'] = $data_list_val['Eps']; 
            $msn_data['eps_esmt'] = $data_list_val['EpsEsmt']; 
            $msn_data['company_smbol'] = $data_list_val['Eqsm']; 
            
            $msn_data['forward_pe'] = $data_list_val['FpEPS']; 
            
            $msn_data['FwdDYld'] = $data_list_val['FwdDYld']; /*Might be Dividend Rate (Yield) percent*/
            
            $msn_data['gross_margin'] = empty($data_list_val['Grm']) ? '' : $data_list_val['Grm']; 
            
            $msn_data['industry'] = $data_list_val['Ind']; 
            $msn_data['market_cap'] = $data_list_val['Mc']; 
            $msn_data['peg'] = $data_list_val['MstarGrRt']; 
            $msn_data['net_profit_margin_p'] = $data_list_val['MstarPrftRt']; /* Net Profit Margin % */
            
            $msn_data['NetIncome5YrAvg'] = $data_list_val['NetIncome5YrAvg']; 
            $msn_data['NiLQr'] = $data_list_val['NiLQr']; 
            $msn_data['NiLYr'] = $data_list_val['NiLYr']; 
            
            $msn_data['net_profit_margin'] = $data_list_val['Nmp']; 
            
            $msn_data['NtIn1YrGr'] = $data_list_val['NtIn1YrGr']; 
            $msn_data['NtIncmGrthRt'] = $data_list_val['NtIncmGrthRt']; 
            $msn_data['Opm'] = $data_list_val['Opm']; 
            $msn_data['Opn'] = $data_list_val['Opn']; 
            
            echo '<pre>';
            print_r($data_list_val);
        }
        
    }
    
    function readIndiaVix(){
        
        $this->load->model('Vix_model');
        
        $System_Notification_contr = new System_Notification_Controller();
        
        $url = 'https://www1.nseindia.com/homepage/Indices1.json';
        $referer = 'https://www1.nseindia.com/index_nse.htm';
        
        
        $Nse_Contr = new Nse_Contr();
        
        $json_data = $Nse_Contr->curlNseOld( $url, $referer);
        
//        echo '<pre>';
//        print_r($json_data); exit;
        
        $status = trim($json_data['status']);
        
        echo $status . '<br/>';
        
        /* Check if market is open or close */
        if (strpos($status, 'Normal Market has Closed') !== false) {
            
            $market_running = 0;
        }else if (strpos($status, 'Normal Market is Open') !== false) {
            
            $market_running = 1;
            
        }else{
            
            echo 'return';
            return;
        }
        
        foreach( $json_data['data'] AS $arr_val ){
            
            if( trim($arr_val['name']) == 'INDIA VIX' ){
                               
                $data['last_price'] = trim($arr_val['lastPrice']);
                $data['change'] = trim($arr_val['change']);
                $data['p_change'] = trim($arr_val['pChange']);
                
                $data['market_running'] = $market_running;
                
                
                $data['market_date'] = date('Y-m-d', strtotime(trim( $json_data['time'] ) ));
                
                $data['created_at_time'] = date("H:i:s");
                
                echo 'VIX from nse ' . (trim($arr_val['lastPrice'])) . '<br/>';
                
//                echo '<pre>';
//                print_r($data); exit;
                
                $return =  $this->Vix_model->insertIndiaVix($data);
                
                echo $return;
            }
        }
    }
    
    function readClientFundByBroker(){
        
        $System_Notification_contr = new System_Notification_Controller(); 
        
        $url = "https://www1.nseindia.com/content/equities/cli_fund.csv";                
        
        $context = stream_context_create(
            array(
                "http" => array(
                    "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                )
            )
        );

        $data = file_get_contents($url, false, $context);            

        if($data === FALSE) {

            echo 'data not found'; $System_Notification_contr->failRead($url, date ("Y-m-d"), 'Client Fund - Fail Read Client Fund By Broker from Nse'); return;
        }

        $rows = explode("\n", $data);
        
        $this->load->model('Broker_model');
        
//        echo '<pre>'; print_r($rows);
        
        foreach ($rows as $row_key=>$each_row_value) {
            
            if( $row_key === 0  ){continue;}
//            
            $client_fund_arr = array();
            
            $each_row_arr = str_getcsv($each_row_value); 
            
            if( empty($each_row_arr[0]) ){continue;}
            
//            echo $row_key;
//            echo '<pre>'; print_r($each_row_arr);
            
            $client_fund_arr['member_code'] = trim($each_row_arr[0]);
            $client_fund_arr['member_name'] = trim($each_row_arr[1]);
            $client_fund_arr['temp_margin_amt_fund'] = trim($each_row_arr[2]);
            $client_fund_arr['inst_cli_amt_fund'] = trim($each_row_arr[3]);
            $client_fund_arr['non_inst_cli_amt_fund'] = trim($each_row_arr[4]);
            $client_fund_arr['under_margin_trad_amt_fund'] = trim($each_row_arr[5]);
            $client_fund_arr['total_amt_fund'] = trim($each_row_arr[6]);
            $client_fund_arr['total_cli_funded'] = trim($each_row_arr[7]);
            $client_fund_arr['submission_date'] = date('Y-m-d', strtotime(trim( $each_row_arr[8]) ) );
            $client_fund_arr['cron_date'] = date('Y-m-d');
            
            echo '<pre>'; print_r($client_fund_arr);
            
            $this->Broker_model->insertClientFund( $client_fund_arr );
            
        }
    }
    
    /*
     * https://www.bseindia.com/markets/equity/EQReports/StockPrcHistori.aspx?flag=1
     */
    
    function cashCategoryWiseTurnoverBse(){
        
        include_once (dirname(__FILE__) . "/Bse_Contr.php");
        $Bse_Contr = new Bse_Contr(); 
        
        $this->load->model('Fii_dii_model');
        
        $cmd_return = $Bse_Contr->curlBseCashCategoryWiseTurnover();
        
        $rows = explode("\n", $cmd_return);
        
//        echo '<pre>';
//        print_r($rows); 
        
        
        foreach ($rows as $row_key=>$each_row_value) {
            
            $each_row_arr = str_getcsv($each_row_value); 
            
            if( $row_key===0 || empty( trim( $each_row_arr[0] )) ){ continue; }
            
            echo $row_key;
            
//            echo '<pre>';
//            print_r($each_row_arr); 
            
            $cat_wise_turnover_common = array();
            
            $cat_wise_turnover_common['exchange'] = 'bse';
            $cat_wise_turnover_common['trading_type'] = 'cash';
            $cat_wise_turnover_common['market_date'] = date('Y-m-d', strtotime(trim( $each_row_arr[0]) ) );
            $cat_wise_turnover_common["created_at"] = date("Y-m-d H:i:s");
            
            $cat_wise_turnover_clients = array();
            $cat_wise_turnover_clients['category'] = 'Clients';
            $cat_wise_turnover_clients['buy_value'] = trim($each_row_arr[1]);
            $cat_wise_turnover_clients['sell_value'] = trim($each_row_arr[2]);
            
            $cat_wise_turnover_clients = array_merge($cat_wise_turnover_common,$cat_wise_turnover_clients);
            $this->Fii_dii_model->categoryWiseTurnover($cat_wise_turnover_clients);
            
            
            $cat_wise_turnover_nri = array();
            $cat_wise_turnover_nri['category'] = 'Nri';
            $cat_wise_turnover_nri['buy_value'] = trim($each_row_arr[4]);
            $cat_wise_turnover_nri['sell_value'] = trim($each_row_arr[5]);
            
            $cat_wise_turnover_nri = array_merge($cat_wise_turnover_common,$cat_wise_turnover_nri);
            $this->Fii_dii_model->categoryWiseTurnover($cat_wise_turnover_nri);
            
            
            $cat_wise_turnover_prop = array();
            $cat_wise_turnover_prop['category'] = 'Proprietory Trades';
            $cat_wise_turnover_prop['buy_value'] = trim($each_row_arr[7]);
            $cat_wise_turnover_prop['sell_value'] = trim($each_row_arr[8]);
            
            $cat_wise_turnover_prop = array_merge($cat_wise_turnover_common,$cat_wise_turnover_prop);
            $this->Fii_dii_model->categoryWiseTurnover($cat_wise_turnover_prop);
            
            
            $cat_wise_turnover_dii = array();
            $cat_wise_turnover_dii['category'] = 'DII';
            $cat_wise_turnover_dii['buy_value'] = trim($each_row_arr[13]);
            $cat_wise_turnover_dii['sell_value'] = trim($each_row_arr[14]);
            
            $cat_wise_turnover_dii = array_merge($cat_wise_turnover_common,$cat_wise_turnover_dii);
            $this->Fii_dii_model->categoryWiseTurnover($cat_wise_turnover_dii);

            

            echo '<pre>';
            print_r($cat_wise_turnover_clients);
            echo '<pre>';
            print_r($cat_wise_turnover_nri);
            echo '<pre>';
            print_r($cat_wise_turnover_prop);
            echo '<pre>';
            print_r($cat_wise_turnover_dii);
            
        }
       
    }
    
    /*
     * Read All Date
     */
    
    function cashCategoryWiseTurnoverNseForAllDate(){
        require PROJECT_DOCUMENT_ROOT . '/application/libraries/simplexls/SimpleXLS.php';
        $this->load->model('Companies_model');
        
        // Start date
	$date = '2019-01-01';
//	$date = '2020-07-08';
	// End date
	$end_date = date ("Y-m-d");

	while (strtotime($date) <= strtotime($end_date)) {
                echo "$date\n <br/>";
                
//                $market_date = date("dmY", strtotime($date) );                                
                
//                echo "$market_date\n <br/>";
                
                $this->cashCategoryWiseTurnoverNse($date);
                
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
    }
     
    /*
     * https://www1.nseindia.com/products/content/all_daily_reports.htm?param=equity
     */
    function cashCategoryWiseTurnoverNse(){
        
        $this->load->model('Fii_dii_model');
        
        require PROJECT_DOCUMENT_ROOT . '/application/libraries/simplexls/SimpleXLS.php';
        
        $current_date = date ("Y-m-d");
        
        for( $i=5; $i>=0; $i-- ){
            
            echo $input_market_date = date('Y-m-d', strtotime('-'.$i.' day', strtotime($current_date)));
            echo '<br/>';
            
            // set path to temp directory
            $temp_directory = PROJECT_DOCUMENT_ROOT . '/assets/fii-dii/fii-derivative-nse';

            // set direct url to mp3
            $derivative_url = "https://www1.nseindia.com/archives/equities/cat/cat_turnover_" . date("dmy", strtotime($input_market_date)) . ".xls";
            
            echo $derivative_url . "<br/>";
            
            // set name of mp3
            $name = 'cat-wise-turnover';                        

            $context = stream_context_create(
                    array(
                        "http" => array(
                            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                        )
                    )
                );

            // download file to temp directory
            $data = file_put_contents($temp_directory.'/'.$name.'.xls',file_get_contents($derivative_url, false, $context));
            
//            echo '<pre>';
//            print_r($data);
            
            if($data == FALSE) {

                echo 'data not found'; continue;
            }

            if ($xls = SimpleXLS::parse($temp_directory . '/' . $name . '.xls')) {

                foreach( $xls->rows() AS $key=>$each_derivative_segment){
                    
//                    echo '<br> key: ' . $key;
//                    echo '<pre>';
//                    print_r($each_derivative_segment);
                    
                    if( $key !== 3 && $key !== 4 && $key !== 5 && $key !== 6 ){ continue; }

                    $cat_wise_turnover = array();
                    
                    if( empty(trim($each_derivative_segment[1])) || ( empty( trim($each_derivative_segment[2])) ) && empty( trim($each_derivative_segment[3]) ) ){
                        
                        continue;
                    }
                    
                    $cat_wise_turnover['category'] = ( trim($each_derivative_segment[1]) === 'PRO-TRADES' ) ? 'Proprietory Trades' : trim($each_derivative_segment[1]);
                    $cat_wise_turnover['buy_value'] = trim($each_derivative_segment[2]);
                    $cat_wise_turnover['sell_value'] = trim($each_derivative_segment[3]);
//
                    $cat_wise_turnover['exchange'] = 'nse';
                    $cat_wise_turnover['trading_type'] = 'cash';
                    $cat_wise_turnover['market_date'] = $input_market_date;
                    $cat_wise_turnover["created_at"] = date("Y-m-d H:i:s");

                    echo '<pre>';
                    print_r($cat_wise_turnover);
//
                    $this->Fii_dii_model->categoryWiseTurnover($cat_wise_turnover);
                    
                }
                
            }
            
        }
    }
    
    /*
     * Fetch FII cash market investment data by downloading xls file from nsdl
     * https://www.fpi.nsdl.co.in/web/Reports/Archive.aspx
     */
    
    function fiiCashNsdl(){
        
        $start_month_year =  date('M-Y', strtotime("Jan-2019" ));
        
//        for( $i=0; $i<19; $i++ ){
//            
//            echo $input_month = date('M-Y', strtotime('+'.$i.' month', strtotime($start_month_year))) . "<br/>";
//            
//        }
//        
//        exit;
        
        $this->load->helper('function_helper');
        $this->load->model('Fii_dii_model');
                
        require PROJECT_DOCUMENT_ROOT . '/application/libraries/simplexls/SimpleXLS.php';
        
        // set path to temp directory
        $temp_directory = PROJECT_DOCUMENT_ROOT . '/assets/fii-dii/fii-cash-nsdl';
        
        for( $i=0; $i<19; $i++ ){
            
            echo $input_month = date('M-Y', strtotime('+'.$i.' month', strtotime($start_month_year)));
            echo "<br/>";
        
            // set name of mp3
            $name = $input_month; 

            echo $temp_directory . '/' . $name . '.xls';

            if ($xls = SimpleXLS::parse($temp_directory . '/' . $name . '.xls')) {

                echo '<br/> inside if'; 

                foreach( $xls->rows() AS $key=>$each_derivative_segment){

                    echo '<br/ > is_valid_date : ' . $is_valid_date = validateDate($each_derivative_segment[0]) . "<br/>"; 

                    if($is_valid_date ==1){                

                        echo '<br> key: ' . $key;

                        $data["market_date"] = date("Y-m-d", strtotime($each_derivative_segment[0])); 

                        $data["category"] = 'FII';
                        $data["buy_value"] = $each_derivative_segment[3];
                        $data["sell_value"] = $each_derivative_segment[4];

                        $data['exchange'] = 'nsdl';
                        $data['trading_type'] = 'cash';
                        $data["created_at"] = date("Y-m-d H:i:s");

                        echo '<pre>';
                        print_r($data);

                        $this->Fii_dii_model->categoryWiseTurnover($data);
                    }

                }
            }else{

                echo '<br/> inside else';
            }
        
        }
        
    }
}
