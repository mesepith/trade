<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

include_once (dirname(__FILE__) . "/Put_call_oi_change_contr.php");

class Stock_Analysis extends MX_Controller {
    
    /*
     * @author: ZAHIR
     * DESC: stock bearish or bullish determined by impleid volatility of option chain
     */
    
    public function stockBearishOrBullishByIVOfOC(  ) {
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Analysis_task_model');
        $check_task_done = $this->Analysis_task_model->checkAnalysisDone('oc_iv_analysis');
        
        if($check_task_done=='done'){
            echo 'Todays task is done';
            exit;
        }
        $Put_call_oi_change_contr = new Put_call_oi_change_contr();
        
        $check_todays_put_call_data_inserted = $Put_call_oi_change_contr->checkTodayPutCallDataInserted();
        
        if($check_todays_put_call_data_inserted =='no'){ exit;}
        
        $this->load->model('Oc_iv_analysis_model');
        
        $last_inserted_company = $this->Oc_iv_analysis_model->lastInsertedCompanyList( );
        
//        echo '<pre>';
//        print_r($last_inserted_company);
//        
//        exit;
        
//        $company_list = $this->Oc_iv_analysis_model->oCIVNonInserteDCompanyList( $last_inserted_company );
        $company_list = $Send_Api_Contr->oCPDNonInserteDCompanyList( $last_inserted_company );
        
        if(empty($company_list)){
            
            
            $this->Analysis_task_model->insertOcIvAnalysisDone( );
            echo '<br/>';
            echo 'All companies analysis is done';
            echo '<br/>';
            
            exit;
        }
        
//        echo '<pre>'; print_r($company_list);  
        
        
        foreach ($company_list AS $company_list_value) {
            
            $company_symbol = $company_list_value->company_symbol;
            $company_id = $company_list_value->company_id;
            
            echo '$company_symbol : ' . $company_symbol;
            
            $underlying_date_obj = $this->Put_call_model->getLatestUnderlyingDate($company_id, $company_symbol);
            
            if( empty($underlying_date_obj) ){ continue; }
                        
            echo '<pre>'; print_r($underlying_date_obj);
            
            if( empty($underlying_date_obj->underlying_date) ){ continue; }
            
            $underlying_date = $underlying_date_obj->underlying_date;
            
            if( $underlying_date != date('Y-m-d') ){ 
//            if ($underlying_date != '2019-11-28') {

                echo '<br/>';
                echo "No underlying_date on " . date('Y-m-d') . " for " . $company_symbol;
                echo '<br/>';
                echo '<br/>';

                continue;
            }
            
            $expiry_dates = $this->Put_call_model->getCurrentExpiryDateByUnderlyingDate($company_id, $company_symbol, $underlying_date);

            echo '<pre>';
            print_r($expiry_dates);
            
            if (empty($expiry_dates) && count($expiry_dates) <= 0) {

                echo '<br/>';
                echo "No expiry_dates on " . date('Y-m-d') . " for " . $company_symbol . " for underlying_date " . $underlying_date;
                echo '<br/>';

                continue;
            }
            
            foreach ($expiry_dates AS $expiry_dates_value) {
                
                if (empty($expiry_dates_value->expiry_date)) {

                    echo '<br/>';
                    echo "array value not found for expiry_dates array";
                    echo '<br/>';
                    continue;
                }
                
                //$trading_days = $this->getNumberOfTradingDaysAgainstExpiryDate( $expiry_dates_value->expiry_date );
                $this->load->helper('function_helper');
                $trading_days = diffOfTwoDates( $underlying_date, $expiry_dates_value->expiry_date );
                
                
                if( $trading_days < 6 || $trading_days > 20 ){
                    
                    echo '<br/>';
                    echo ' No of trading days ('.$trading_days.') is less than 6 or greater than 20, so ignore this . Expiry date : ' . $expiry_dates_value->expiry_date;
                    echo '<br/>';
                    echo '<br/>';
                    continue;
                }
                
                echo '<br/>';
                echo '***************************************************';
                
                echo '<br/>';
                echo '$trading_days : ' . $trading_days;
                echo '<br/>';
                
                $underlying_date = date('Y-m-d');
//                $underlying_date = '2019-11-28';
                $underlying_price = $this->Put_call_model->getUnderlyingPrice( $expiry_dates_value->expiry_date, $underlying_date, $company_id, $company_symbol );
                if(empty($underlying_price)){
                   
                    continue;
                }
                echo '$underlying_price : ' . $underlying_price;
                echo '<br/>';
                
                $this->oCIVAnalysisCalculate( $company_id, $company_symbol, $underlying_date, $underlying_price, $expiry_dates_value->expiry_date, $trading_days );
                
                
            }
            
            echo '<br/>';
            echo '<br/>';
            echo '################################################';
            echo '<br/>';
            echo '<br/>';
            
//            exit;
        }
        
        
    }
    
    
    function oCIVAnalysisCalculate( $company_id, $company_symbol, $underlying_date, $underlying_price, $expiry_date, $trading_days, $market_running=false, $underlying_time=false, $script_start_time=false ){                
        
        $near_at_the_money_data = $this->Put_call_model->getFirstTableRowWithBiggerStrikePriceThanUnderlyingPrice($underlying_price, $expiry_date, $underlying_date, $company_id, $company_symbol, $market_running, $underlying_time);
        
        if(empty($near_at_the_money_data)){ return; }
        
        echo '<pre>';
        print_r($near_at_the_money_data);

        $secondMinStrikePrice = $this->Put_call_model->getSecondStrikePrice($underlying_price, $expiry_date, $underlying_date, $company_id, $company_symbol, $near_at_the_money_data[0]->strike_price, $market_running, $underlying_time);
        
        if(empty($secondMinStrikePrice)){ return; }
        
        echo '$secondMinStrikePrice : ' . $secondMinStrikePrice;
        echo '<br/>';

        $strike_price_with_highest_oi_in_call = $this->Put_call_model->getStrikePriceWithHighestOiInCall($expiry_date, $underlying_date, $company_id, $company_symbol, $near_at_the_money_data[0]->strike_price, $market_running, $underlying_time);
        if(empty($strike_price_with_highest_oi_in_call)){ return; }
        
        $strike_price_with_highest_oi_in_put = $this->Put_call_model->getStrikePriceWithHighestOiInPut($expiry_date, $underlying_date, $company_id, $company_symbol, $near_at_the_money_data[0]->strike_price, $secondMinStrikePrice, $market_running, $underlying_time);
        if(empty($strike_price_with_highest_oi_in_put)){ return; }
        
        echo '<br/>';
        echo '$strike_price_with_highest_oi_in_call : ' . $strike_price_with_highest_oi_in_call;

        echo '<br/>';
        echo '$strike_price_with_highest_oi_in_put : ' . $strike_price_with_highest_oi_in_put;
        echo '<br/>';

        if ($near_at_the_money_data[0]->calls_iv == 0 || $near_at_the_money_data[0]->puts_iv == 0 || empty($strike_price_with_highest_oi_in_call) || empty($strike_price_with_highest_oi_in_put)) {
            echo '<br/>';
            echo 'ignore due to empty values with company_symbol : ' . $company_symbol . ' and Expiry date : ' . $expiry_date;
            echo '<br/>';
            
            return;
        }

        $data = array();
        $data['company_id'] = $company_id;
        $data['company_symbol'] = $company_symbol;
        $data['trading_days'] = $trading_days;
        $data['underlying_date'] = $underlying_date;
        $data['expiry_date'] = $expiry_date;
        $data['underlying_price'] = $underlying_price;
        $data['strike_price'] = $near_at_the_money_data[0]->strike_price;
        $data['calls_iv'] = $near_at_the_money_data[0]->calls_iv;
        $data['puts_iv'] = $near_at_the_money_data[0]->puts_iv;
        $data['strike_price_with_highest_oi_in_call'] = $strike_price_with_highest_oi_in_call;
        $data['strike_price_with_highest_oi_in_put'] = $strike_price_with_highest_oi_in_put;

//                $this->getBearishOrBullishSentimentByIv( $trading_days, $near_at_the_money_data[0]->strike_price, $near_at_the_money_data[0]->calls_iv , $near_at_the_money_data[0]->puts_iv, $strike_price_with_highest_oi_in_call, $strike_price_with_highest_oi_in_put );

        try {

            $this->getBearishOrBullishSentimentByIv($data, $market_running, $underlying_time, $script_start_time);
        } catch (Exception $e) {

            echo '<br/>';
            echo 'Error Message :::::::::::::::::: : ' . $e->getMessage();
            echo '<br/>';
        }

        echo '<br/>';
        echo '<br/>';
        echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1';
        echo '<br/>';
        echo '<br/>';
    }
    
    /*
     * @author: ZAHIR
     * DESC: stock bearish or bullish determined by impleid volatility of option chain starting from first row mysql table
     */
    
    function stockBearishOrBullishByIVOfOCCalcForPrev(){
        
        ini_set('max_execution_time', 0); 

        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');
        
        $this->load->model('Oc_iv_analysis_model');
        $this->load->model('Put_call_model');
        
        $company_list = $this->Oc_iv_analysis_model->oCIVNonInserteDCompanyList( $last_inserted_company = 0 );
        
        foreach ($company_list AS $company_list_value) {
            
            $company_symbol = $company_list_value->company_symbol;
            $company_id = $company_list_value->company_id;
            
            $allUnderlyingDate = $this->Put_call_model->geAllUnderlyingDate( $company_id, $company_symbol );
            
            if(empty($allUnderlyingDate)){ continue; }
            
            foreach( $allUnderlyingDate AS $underlying_date_obj){
                
                echo '<pre>'; print_r($underlying_date_obj);
                
                $underlying_date = $underlying_date_obj->underlying_date;
                $current_price = $underlying_date_obj->underlying_price;
                
                $expiry_dates = $this->Put_call_model->getCurrentExpiryDateByUnderlyingDate($company_id, $company_symbol, $underlying_date);

                echo '<pre>';
                print_r($expiry_dates);
                
                if (empty($expiry_dates) && count($expiry_dates) <= 0) { echo 'ignore <br/>'; continue; }
                
                foreach ($expiry_dates AS $expiry_dates_value) {
                
                    if (empty($expiry_dates_value->expiry_date)) {

                        echo '<br/>';
                        echo "array value not found for expiry_dates array";
                        echo '<br/>';
                        continue;
                    }
                    
                    $this->load->helper('function_helper');
                    $trading_days = diffOfTwoDates( $underlying_date, $expiry_dates_value->expiry_date );

                    if( $trading_days < 6 || $trading_days > 20 ){

                        echo '<br/>';
                        echo ' No of trading days ('.$trading_days.') is less than 6 or greater than 20, so ignore this . Expiry date : ' . $expiry_dates_value->expiry_date;
                        echo '<br/>';
                        echo '<br/>';
                        continue;
                    }
                    
                    echo '<br/>';
                    echo '$trading_days : ' . $trading_days;
                    echo '<br/>';
                    
                    $underlying_price = $this->Put_call_model->getUnderlyingPrice( $expiry_dates_value->expiry_date, $underlying_date, $company_id, $company_symbol );
                    
                    if(empty($underlying_price)){

                        continue;
                    }
                    echo '$underlying_price : ' . $underlying_price;
                    echo '<br/>';

                    $this->oCIVAnalysisCalculate( $company_id, $company_symbol, $underlying_date, $underlying_price, $expiry_dates_value->expiry_date, $trading_days );
                    
                }
            }
//           exit; //Remove after test 
        }
        
        flush();
    }
    
//    function getBearishOrBullishSentimentByIv( $days_to_expire, $strike_price_near_current_stock_price, $calls_iv, $puts_iv, $strike_price_with_highest_oi_in_call, $strike_price_with_highest_oi_in_put ){
    function getBearishOrBullishSentimentByIv( $data, $market_running=false, $underlying_time=false, $script_start_time=false ){
        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $days_to_expire = $data['trading_days'];
        $strike_price_near_current_stock_price = $data['strike_price'];
        $calls_iv = $data['calls_iv'];
        $puts_iv = $data['puts_iv'];
        $strike_price_with_highest_oi_in_call = $data['strike_price_with_highest_oi_in_call'];
        $strike_price_with_highest_oi_in_put = $data['strike_price_with_highest_oi_in_put'];
        
        echo '<br/>';
        echo 'Probability Finding :::::::::::::::::';;
        echo '<br/>';
        echo '<br/>';
        echo '<br/>';
        
        $this->load->helper('normal_distribution_helper');

        $call_implied_volatility = ($calls_iv / 100); // call implied volatility in selected strike price i.e on $strike_price_near_current_stock_price
        $put_implied_volatility = ($puts_iv / 100); // put implied volatility in selected strike price i.e on $strike_price_near_current_stock_price
        
        /*
        $days_to_expire = 19;
        $strike_price_near_current_stock_price = 130.00; // next strike price after market closing price
        $strike_price_with_highest_oi_in_put = 120; // strike_price < current market price
        $strike_price_with_highest_oi_in_call = 135; // strike_price > current market price         * 
         */



        $bearish_formula = normsdist(( log($strike_price_with_highest_oi_in_put / $strike_price_near_current_stock_price) / log(2.71828) ) / ($call_implied_volatility * SQRT($days_to_expire / 365)));

        echo 'bearish_probability : ' . $bearish_probability = $bearish_formula * 100;
        $data['bearish_probability'] = $bearish_probability;
        echo '<br/>';
        


        /* close above target bearish says probability not to hit $strike_price_with_highest_oi_in_put in percentage */
        $close_above_target_bearish_formula = 1 - normsdist(( log($strike_price_with_highest_oi_in_put / $strike_price_near_current_stock_price) / log(2.71828) ) / ( $call_implied_volatility * SQRT($days_to_expire / 365) ));
        echo '$close_above_target_bearish : ' . $close_above_target_bearish = $close_above_target_bearish_formula * 100;
        $data['close_above_target_bearish'] = $close_above_target_bearish;
        echo '<br/>';

        echo '<br/>';
        echo '----------------------------------------------------------------------------------------------------------';
        echo '<br/>';
        echo '<br/>';

        $bullish_formula = 1 - normsdist((log($strike_price_with_highest_oi_in_call / $strike_price_near_current_stock_price) / log(2.71828)) / ($put_implied_volatility * SQRT($days_to_expire / 365)));
        echo 'bullish_probability : ' . $bullish_probability = $bullish_formula * 100;
        $data['bullish_probability'] = $bullish_probability;
        echo '<br/>';


        /* close above target bullish says probability not to hit $strike_price_with_highest_oi_in_call in percentage */
        $close_above_target_bullish_formula = normsdist(( log($strike_price_with_highest_oi_in_call / $strike_price_near_current_stock_price) / log(2.71828) ) / ( $put_implied_volatility * SQRT($days_to_expire / 365) ));
        echo '$close_above_traget_bullish : ' . $close_above_target_bullish = $close_above_target_bullish_formula * 100;
        $data['close_above_target_bullish'] = $close_above_target_bullish;
        
        
        $data['created_at_date'] = date('Y-m-d');
        $data['created_at'] = date('Y-m-d H:i:s');
        
        /*
         * Since model is already loaded for live market, so we load this model only for EOD(End of day) data
         */
        if(empty($market_running)){
            
            $this->load->model('Oc_iv_analysis_model');
            
        }
        
        $is_insert = $this->Oc_iv_analysis_model->insertOcBearishBullishByIV($data, $market_running, $underlying_time, $script_start_time);
        
        if(!($is_insert)) {
            throw new Exception("Error Occured while doing Match");
        }
        
        return true;
    }
    
    /*
     * @author: ZAHIR
     * Live stock bullish or bearish analysis by IV
     */
    
    function liveStockBearishOrBullishByIVOfOC( $analysis_input_arr, $market_running, $script_start_time ){
        
        
        $company_id = $analysis_input_arr['company_id'];
        $company_symbol = $analysis_input_arr['company_symbol'];
        $underlying_date = $analysis_input_arr['underlying_date'];
        
        $underlying_time = $analysis_input_arr['underlying_time'];
        
        $underlying_price = $analysis_input_arr['underlying_price'];

        $expiry_date = $analysis_input_arr['expiry_date'];
        
        $this->load->helper('function_helper');
        $trading_days = diffOfTwoDates( $underlying_date, $expiry_date );
        
        $this->oCIVAnalysisCalculate( $company_id, $company_symbol, $underlying_date, $underlying_price, $expiry_date, $trading_days, $market_running, $underlying_time, $script_start_time );
    }
}
