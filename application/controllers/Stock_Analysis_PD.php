<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Put_call_oi_change_contr.php");

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

/*
 * @author: ZAHIR
 * DECS: stock analysis by premium decay
 */

class Stock_Analysis_PD extends MX_Controller {
    
    /*
     * @author: ZAHIR
     * DESC: stock bearish or bullish determined by premium decay of option chain
     */
    
    public function stockAnlysisByPDOfOC(  ) {
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Analysis_task_model');
        $check_task_done = $this->Analysis_task_model->checkAnalysisDone('oc_pd_analysis');
        
        if($check_task_done=='done'){
            echo 'Todays task is done';
            exit;
        }
        
        $Put_call_oi_change_contr = new Put_call_oi_change_contr();
        
        $check_todays_put_call_data_inserted = $Put_call_oi_change_contr->checkTodayPutCallDataInserted();
        
        if($check_todays_put_call_data_inserted =='no'){ exit;}
        
//        $this->load->model('Put_call_model'); #Delete after test
        $this->load->model('Oc_pd_analysis_model');
        
        $last_calculated_company = $this->Analysis_task_model->lastCalculatedCompany('oc_pd_analysis');
        
        if( !empty($last_calculated_company) ){
                    
        
            echo '<pre>'; print_r($last_calculated_company);

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
//        $company_list = $this->Oc_pd_analysis_model->oCPDNonInserteDCompanyList( $last_calculated_company_id );
        $company_list = $Send_Api_Contr->oCPDNonInserteDCompanyList( $last_calculated_company_id );
        
//        echo '<pre>'; print_r($company_list); exit;
        
        if(empty($company_list)){
            
            
            $this->Analysis_task_model->insertOcPDAnalysisDone( );
            echo '<br/>';
            echo 'All companies analysis is done';
            echo '<br/>';
            
            exit;
        }
        
        foreach ($company_list AS $company_list_value) {
            
           $company_symbol = $company_list_value->company_symbol;
//           echo $company_symbol; exit;
//           $company_symbol = 'TITAN';
//           $company_symbol = 'TCS';
//            echo '<br/>';
            $company_id = $company_list_value->company_id;
//            $company_id = 1478;
//            $company_id = 1443;
            
//            exit;
            
            echo '$company_symbol : ' . $company_symbol;
            echo '<br/>';
            
            
            $underlying_date_obj = $this->Oc_pd_analysis_model->getLatestUnderlyingDateNPrice($company_id, $company_symbol);            
            
            if( empty($underlying_date_obj) ){ continue; }
            
            echo '<pre>'; print_r($underlying_date_obj);
            echo '<br/>';
            
            if( empty($underlying_date_obj->underlying_date) || empty($underlying_date_obj->underlying_price) ){ continue; }
            
            $underlying_date = $underlying_date_obj->underlying_date;
            $current_price = $underlying_date_obj->underlying_price;
            
            if( $underlying_date != date('Y-m-d') ){ 
//            if ($underlying_date != '2019-12-17') {

                echo '<br/>';
                echo "No underlying_date on " . date('Y-m-d') . " for " . $company_symbol;
                echo '<br/>';
                echo '<br/>';

                continue;
            }
            
            $this->Analysis_task_model->ocCalculationDone($company_id, $company_symbol, 'oc_pd_analysis');#this means we have done this companies calulation for today
                        
            
            $expiry_dates = $this->Put_call_model->getCurrentExpiryDateByUnderlyingDate($company_id, $company_symbol, $underlying_date);
            
            echo 'expiry_dates : ';
            
            echo '<pre>';
            print_r($expiry_dates);
            
            
            if (empty($expiry_dates) && count($expiry_dates) <= 0) {

                echo '<br/>';
                echo "No expiry_dates on " . date('Y-m-d') . " for " . $company_symbol . " for underlying_date " . $underlying_date;
                echo '<br/>';

                continue;
            }
            
            
            $this->ocPDCalcByUDateNPrice( $company_id, $company_symbol, $underlying_date, $current_price, $expiry_dates );
            
            echo '<br/>';
            echo "###############";
            echo '<br/>';
            echo '<br/>';
            
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: oc calculation by underlying date and price
     */
    function ocPDCalcByUDateNPrice( $company_id, $company_symbol, $underlying_date, $current_price, $expiry_dates ){
        
        foreach ($expiry_dates AS $expiry_dates_value) {
                
            if (empty($expiry_dates_value->expiry_date)) {

                echo '<br/>';
                echo "array value not found for expiry_dates array";
                echo '<br/>';
                continue;
            }
            
            $this->ocPDCalcByUDateNPriceNExpDate( $company_id, $company_symbol, $underlying_date, $current_price, $expiry_dates_value->expiry_date );

//            $underlying_date = '2019-12-17';
            

            echo '<br/>';
            echo '<br/>';
        }
            
    }
    
    /*
     * @author: ZAHIR
     * Premium Decay Calculation
     */
    function ocPDCalcByUDateNPriceNExpDate( $company_id, $company_symbol, $underlying_date, $current_price, $each_expiry_date, $market_running=false, $underlying_time=false, $script_start_time=false){
        
        $date_diff = $this->diffOfTwoDates($underlying_date, $each_expiry_date);
        echo 'diffOfTwoDates : ' . $date_diff . ' , for expiry : ' . $each_expiry_date;

        if( $date_diff < 2 ||  $date_diff > 20){

            echo '<br/>';
            echo 'Ignore expiray date ' . $each_expiry_date . ' , due to date diff is less than 5 days or more than 20 days between expiry date and underlying_date';
            echo '<br/>';
            echo '<br/>';
            return;

        }

        echo '<br/>';
        echo 'current_price : ' . $current_price;
        echo '<br/>';


        $underlying_date_start = date('Y-m-d', strtotime($underlying_date . ' -20 Weekday'));

        echo '<br/>';
        echo 'underlying_date_start : ' . $underlying_date_start;
        echo '<br/>';

        /*
         * Get difference between underlying date end and first date in which we started to insert data start
         * if we don't have earlier data, then we can't do the calculation, so ignore it
         */ 

        $first_date_of_table = $this->Put_call_model->getFirstDateofPCETable( $company_id, $company_symbol );

        if( $underlying_date_start <= $first_date_of_table  ){

            echo '$underlying_date_start is less than $first_date_of_table <br/>';
            echo '<br/> $first_date_of_table ' . $first_date_of_table . '<br/>';

            return;
        }

        /*
         * Get difference between underlying date end and first date in which we started to insert data end
         */

        $lowest_up = $this->Oc_pd_analysis_model->getLowestUP( $company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date );
        $highest_up = $this->Oc_pd_analysis_model->getHighestUP( $company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date );                

        if (empty($lowest_up) && empty($highest_up) ){

            echo 'underlying price range not found';
            echo '<br/>';
            echo '<br/>';
            return;
        }

        echo 'lowest underlying_price : ' . $lowest_up;
        echo '<br/>';
        echo 'highest underlying_price : ' . $highest_up;
        echo '<br/>';


        $strike_price_with_highest_oi_in_call_otm = $this->Oc_pd_analysis_model->strikePriceWithHighestOiInCall($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $current_price);                               


        if (empty($strike_price_with_highest_oi_in_call_otm) ){

            echo 'no strike_price_with_highest_oi_in_call';
            echo '<br/>';
            echo '<br/>';
            return;
        }

        echo 'strike_price_with_highest_oi_in_call in out of the money : ' . $strike_price_with_highest_oi_in_call_otm;
        echo '<br/>';


        $strike_price_with_second_highest_oi_in_call_otm = $this->Oc_pd_analysis_model->strikePriceWithSecondHighestOiInCall($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $current_price, $strike_price_with_highest_oi_in_call_otm);               

        if ( empty($strike_price_with_second_highest_oi_in_call_otm) ){

            echo 'no strike_price_with_second_highest_oi_in_call';
            echo '<br/>';
            echo '<br/>';
            return;
        }

        echo 'strike_price_with_second_highest_oi_in_call in out of the money : ' . $strike_price_with_second_highest_oi_in_call_otm;
        echo '<br/>';


        $strike_price_with_highest_oi_in_put_otm = $this->Oc_pd_analysis_model->strikePriceWithHighestOiInPut($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $current_price);                               


        if (empty($strike_price_with_highest_oi_in_put_otm) ){

            echo 'no strike_price_with_highest_oi_in_put';
            echo '<br/>';
            echo '<br/>';
            return;
        }

        echo 'strike_price_with_highest_oi_in_put in out of the money : ' . $strike_price_with_highest_oi_in_put_otm;
        echo '<br/>';


        $strike_price_with_second_highest_oi_in_put_otm = $this->Oc_pd_analysis_model->strikePriceWithSecondHighestOiInPut($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $current_price, $strike_price_with_highest_oi_in_put_otm);               

        if (empty($strike_price_with_second_highest_oi_in_put_otm) ){

            echo 'no strike_price_with_second_highest_oi_in_put';
            echo '<br/>';
            echo '<br/>';
            return;
        }

        echo 'strike_price_with_second_highest_oi_in_put in out of the money : ' . $strike_price_with_second_highest_oi_in_put_otm;
        echo '<br/>';

        $market_range_arr = $this->arrangingMarketRangePrice($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $lowest_up, $highest_up);

        if( empty($market_range_arr)){ echo '<br/> No market_range_arr <br/>'; return false; }

        echo '<br/>';
        echo 'market_range_arr';
        echo '<pre>'; print_r($market_range_arr);
        echo '<br/>';

        $put_premium_arr = array();

        $put_premium_arr1 = $this->Oc_pd_analysis_model->getPremiumOfPut($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $strike_price_with_highest_oi_in_put_otm, $current_price, $market_range_arr);

        $put_premium_arr[$strike_price_with_highest_oi_in_put_otm] = $put_premium_arr1;


        $put_premium_arr2 = $this->Oc_pd_analysis_model->getPremiumOfPut($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $strike_price_with_second_highest_oi_in_put_otm, $current_price, $market_range_arr);
        $put_premium_arr[$strike_price_with_second_highest_oi_in_put_otm] = $put_premium_arr2;

        echo '<br/>';
        echo '@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Put Premium Arr @@@@@@@@@@@@@@@@@@@@@@';
        echo '<br/>';
        echo '<br/>';
        echo '<pre>'; print_r($put_premium_arr);
        echo '<br/>';

        $call_premium_arr = array();

        $call_premium_arr1 = $this->Oc_pd_analysis_model->getPremiumOfCall($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $strike_price_with_highest_oi_in_call_otm, $current_price, $market_range_arr);
        $call_premium_arr[$strike_price_with_highest_oi_in_call_otm] = $call_premium_arr1;


        $call_premium_arr2 = $this->Oc_pd_analysis_model->getPremiumOfCall($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $strike_price_with_second_highest_oi_in_call_otm, $current_price, $market_range_arr);
        $call_premium_arr[$strike_price_with_second_highest_oi_in_call_otm] = $call_premium_arr2;

        if( empty($put_premium_arr1) || empty($put_premium_arr2) || empty($call_premium_arr1) ||empty($call_premium_arr2) ){

            echo 'No Premium Data Found';
            return;
        }

        echo '<br/>';
        echo '@@@@@@@@@@@@@@@@@@@@@@@@@@@@ Call Premium Arr @@@@@@@@@@@@@@@@@@@@@@';
        echo '<br/>';
        echo '<br/>';
        echo '<pre>'; print_r($call_premium_arr);
        echo '<br/>';


        $put_premium_decay_arr = $this->premiumDiffCalcInPut( $put_premium_arr );
        $call_premium_decay_arr = $this->premiumDiffCalcInCall( $call_premium_arr );

        echo '<br/>';
        echo '<br/>';
        echo '$put_premium_decay_arr';
        echo '<pre>';                print_r($put_premium_decay_arr);


        echo '$call_premium_decay_arr';
        echo '<pre>';                print_r($call_premium_decay_arr);                

        if( empty($put_premium_decay_arr) || empty($call_premium_decay_arr) ){

            echo 'No Premium Decay Data Found';
            return;
        }   

        if( count($put_premium_decay_arr) < 2 || count($call_premium_decay_arr)< 2 ){

            echo 'Count of put_premium_decay or call_premium_decay is less than 2 of ' . $company_symbol; 
            echo '<br/>';
            echo '<br/>';

            echo 'count of $put_premium_decay_arr '. count($put_premium_decay_arr);
            echo '<br/>';
            echo '<br/>';

            echo 'count of $call_premium_decay_arr '. count($call_premium_decay_arr);

            return; # after test replace it with continue;
        }


        /*
         * Data insert to db table start
         */
        
        /*
         * Since model is already loaded for live market, so we load this model only for EOD(End of day) data
         */
        if(empty($market_running)){
         
            $this->load->model('Oc_pd_input_model');
            
        }        

        $inp_data_arr = array();

        $inp_data_arr['company_id'] = $company_id;
        $inp_data_arr['company_symbol'] = $company_symbol;
        $inp_data_arr['current_price'] = $current_price;
        $inp_data_arr['underlying_date_start'] = $underlying_date_start;
        $inp_data_arr['underlying_date_end'] = $underlying_date;
        $inp_data_arr['expiry_date'] = $each_expiry_date;
        $inp_data_arr['lowest_up'] = $lowest_up;
        $inp_data_arr['highest_up'] = $highest_up;
        $inp_data_arr['market_range'] = json_encode($market_range_arr);
        $inp_data_arr['sp_with_highest_oi_in_call'] = $strike_price_with_highest_oi_in_call_otm;
        $inp_data_arr['sp_with_second_highest_oi_in_call'] = $strike_price_with_second_highest_oi_in_call_otm;
        $inp_data_arr['sp_with_highest_oi_in_put'] = $strike_price_with_highest_oi_in_put_otm;
        $inp_data_arr['sp_with_second_highest_oi_in_put'] = $strike_price_with_second_highest_oi_in_put_otm;
        $inp_data_arr['created_at'] = date("Y-m-d H:i:s");
        
        $oc_pd_input_id = 0;
        
        /*
         * For End of day data insert input data
         */
        if( empty($market_running) ){
            
            $oc_pd_input_id = $this->Oc_pd_input_model->insertOcPdInpData($inp_data_arr);

            if(empty($oc_pd_input_id)){

                return;
            }
           
            $premium_arr = array();

            $premium_arr['put'] = $put_premium_arr;
            $premium_arr['call'] = $call_premium_arr;

            echo '<pre>';
            print_r($premium_arr);
            
            $this->Oc_pd_input_model->premiumInsert($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $oc_pd_input_id, $premium_arr);
        }
        
        /*
         * Data insert to db table end
         */

        try{

            $put_average_pd = $this->getPremiumDecayAvgOfPut($put_premium_decay_arr, $company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $oc_pd_input_id, $market_running, $underlying_time);
        }
        catch(Exception $e) {

            echo '<br/>';
            echo 'Error Message :::::::::::::::::: : ' .$e->getMessage();
            echo '<br/>';
            $put_average_pd=false;
        }

        try{

            $call_average_pd = $this->getPremiumDecayAvgOfCall($call_premium_decay_arr, $company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $oc_pd_input_id, $market_running, $underlying_time);
        }

        catch(Exception $e) {

            echo '<br/>';
            echo 'Error Message :::::::::::::::::: : ' .$e->getMessage();
            echo '<br/>';
            $call_average_pd= false;
        }

        if(!empty($put_average_pd) && !empty($call_average_pd)){

            $this->Oc_pd_input_model->insertAvgDecay($company_id, $company_symbol, $underlying_date_start, $underlying_date , $each_expiry_date, $oc_pd_input_id, $put_average_pd, $call_average_pd, $market_running, $underlying_time, $script_start_time);

        }

    }
    
    /*
     * @author: ZAHIR
     * DESC: Arranging market Range
     */
    function arrangingMarketRangePrice($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $lowest_up, $highest_up){

        $strike_prices_arr = $this->Oc_pd_analysis_model->getMarketRangeBySP($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $lowest_up, $highest_up);

        if( empty($strike_prices_arr) ){
            
            echo '<br/>';
            echo 'No strike price range found';
            echo '<br/>';
            
            return false;
        }
        
        if( count($strike_prices_arr)<1 ){            
            
            echo '<br/>';
            echo 'No strike price range found';
            echo '<br/>';
            return false;
        }
        
        $strike_prices_arr_count = count($strike_prices_arr);
        
        $lowest_strike_price = $this->Oc_pd_analysis_model->getLowestStrikePrice($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $strike_prices_arr[0]->strike_price);
        
        echo '<br/>';
        echo 'lowest_strike_price : ' . $lowest_strike_price;
        echo '<br/>';
        
        
        $strike_price_range = array();
        
        if(!empty($lowest_strike_price) && ( $strike_prices_arr[0]->strike_price != $lowest_strike_price ) ){
           
            $strike_price_range[] = $lowest_strike_price;
        
        }        
        
        foreach( $strike_prices_arr AS $strike_prices  ){
            
            $strike_price_range[] = $strike_prices->strike_price;
        }
        
        $highest_strike_price = $this->Oc_pd_analysis_model->geHighestStrikePrice($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $strike_prices_arr[$strike_prices_arr_count-1]->strike_price );
        
        echo '<br/>';
        echo 'highest_strike_price : ' . $highest_strike_price;
        echo '<br/>';
        
        if(!empty($highest_strike_price) && ( $strike_prices_arr[$strike_prices_arr_count-1]->strike_price ) ){
           
            $strike_price_range[] = $highest_strike_price;
        
        } 
        
        $diff_in_sp = $this->getDiffBetweenTwoSP( $strike_price_range);
        
        $half_of_diff_in_sp = ($diff_in_sp/2);
        
        $market_range_arr = array();
//        $market_range_arr[]
        foreach($strike_price_range AS $strike_price_value){
            
            $market_range_arr[] = $strike_price_value-$half_of_diff_in_sp;
        }
        
        $market_range_arr[] = $strike_price_value+10;
        
        return $market_range_arr;
    }
    
    
    /*
     * @author: ZAHIR
     * DESC: Get diiferance between two strike price
     */
    
    function getDiffBetweenTwoSP( $strike_price_range ){
        
        $this->load->helper('function_helper');
        
        $diff_arr = array();
        
        for( $i=0; $i<(count($strike_price_range)-1); $i++ ){
            
            $diff_arr[] = $strike_price_range[$i+1] - $strike_price_range[$i];

            
        } 
        
        $frequent_diff_no = mostFrequent($diff_arr, count($diff_arr));
        
//        echo '$frequent_diff_no : ' . $frequent_diff_no; exit;
        
        return $frequent_diff_no;
        
        /*
        
        $values = array_count_values($diff_arr);
        arsort($values);
        $most_diff = array_slice(array_keys($values), 0, 5, true);
        
        echo '<br/>';
        echo '$most_diff : '. $most_diff[0];
        echo '<br/>';
        
        return $most_diff[0];
         * 
         */
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Difference in premium calculation in Call
     */
    
    function premiumDiffCalcInCall( $call_premium_arr ){
        
        foreach( $call_premium_arr AS $strike_price => $premium_with_market_range ){
            
            $call_premium_arr[$strike_price] = array_values($call_premium_arr[$strike_price]);
        }

        foreach( $call_premium_arr AS $strike_price => $premium_with_market_range ){  #unset that does not have call_ltp   
            
            for( $i=0; $i<( count($premium_with_market_range) ); $i++ ){
                
                if( empty($premium_with_market_range[$i]['calls_ltp']) || $premium_with_market_range[$i]['calls_ltp'] <=0 ){              
                    unset($call_premium_arr[$strike_price][$i]);
                }
                
            }
            
            $call_premium_arr[$strike_price] = array_values($call_premium_arr[$strike_price]); #after unset sort it by key            
            
        }
        
//        echo '############ $call_premium_arr after unset and sort ############';
//        echo '<pre>'; print_r($call_premium_arr);
        
        $call_premium_decay = array();
        
        foreach( $call_premium_arr AS $strike_price => $premium_with_market_range ){
//            echo '<br/>';
//            echo '$premium_with_market_range count ' . count($premium_with_market_range);
//            echo '<br/>';
            if( count($premium_with_market_range) > 0 ){
                
                for( $i=0; $i<( count($premium_with_market_range)-1 ); $i++ ){

                    $call_premium_decay[$strike_price][$i] = $premium_with_market_range[$i]['calls_ltp'] - $premium_with_market_range[$i+1]['calls_ltp'];                

                }
            }
        
        }
        
//        echo '####################### premium_decay in call ########################';
//        echo '<pre>'; print_r($call_premium_decay);
        
        return $call_premium_decay;  
        
    }
    
    function premiumDiffCalcInPut( $put_premium_arr ){
        
        foreach( $put_premium_arr AS $strike_price => $premium_with_market_range ){
            
            $put_premium_arr[$strike_price] = array_values($put_premium_arr[$strike_price]);
        }
        
        foreach( $put_premium_arr AS $strike_price => $premium_with_market_range ){  #unset that does not have puts_ltp   
            
            
            for( $i=0; $i<( count($premium_with_market_range) ); $i++ ){
                
                if( empty($premium_with_market_range[$i]['puts_ltp']) || $premium_with_market_range[$i]['puts_ltp'] <=0 ){  
//                    echo '<br/>';
//                    echo 'Empty Put Premium: '; 
//                    echo '<pre>'; print_r($premium_with_market_range[$i]);
//                    echo '<br/>';
                    unset($put_premium_arr[$strike_price][$i]);
                }
                
            }
            
            $put_premium_arr[$strike_price] = array_values($put_premium_arr[$strike_price]); #after unset sort it by key
            
            
        }
        
//        echo '<br/>';
//        echo '$put_premium_arr after unset and sort';
//        echo '<pre>'; print_r($put_premium_arr);
        
        $put_premium_decay = array();
        
        foreach( $put_premium_arr AS $strike_price => $premium_with_market_range ){
            
            if( count($premium_with_market_range) > 0 ){
            
                for( $i=0; $i<( count($premium_with_market_range)-1 ); $i++ ){

                    $put_premium_decay[$strike_price][$i] = $premium_with_market_range[$i+1]['puts_ltp'] - $premium_with_market_range[$i]['puts_ltp'];                

                }
            
            }
        
        }
        
//        echo '####################### premium_decay in put ########################';
//        echo '<pre>'; print_r($put_premium_decay);
        
        return $put_premium_decay;        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get avg of premium dacay put values
     */
    function getPremiumDecayAvgOfPut( $put_premium_decay_arr, $company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $oc_pd_input_id, $market_running=false, $underlying_time=false ){
        
        $premium_decay_no_arr = array();
        
        foreach( $put_premium_decay_arr AS $premium_decay_arr ){
            
            foreach( $premium_decay_arr AS $premium_decay ){
                
                $premium_decay_no_arr[] = $premium_decay;
            }
            
        }
        
        echo '<br/> Put Premium decay nos: <br/>';
        echo '<pre>'; print_r($premium_decay_no_arr);
        
        $put_or_call = 'put';
        
        /*
         * For End of day data(EOD) insert data
         */
        if(empty($market_running)){
        
            $this->Oc_pd_input_model->premiumDecayInsert($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $oc_pd_input_id, $premium_decay_no_arr, $put_or_call);
        
        }
        
        $put_average_pd = array_sum($premium_decay_no_arr)/count($premium_decay_no_arr);
        echo '<br/> put_average_ premium decay: <br/>' . $put_average_pd;
        echo '<br/>';        
        
        return $put_average_pd;
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get avg of premium dacay put values
     */
    function getPremiumDecayAvgOfCall( $call_premium_decay_arr, $company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $oc_pd_input_id, $market_running=false, $underlying_time=false ){
        
        $premium_decay_no_arr = array();
        
        foreach( $call_premium_decay_arr AS $premium_decay_arr ){
            
            foreach( $premium_decay_arr AS $premium_decay ){
                
                $premium_decay_no_arr[] = $premium_decay;
            }
            
        }
        
        echo '<br/> Call Premium decay nos: <br/>';
        echo '<pre>'; print_r($premium_decay_no_arr);
        
        $put_or_call = 'call';
        
        /*
         * For End of day(EOD) data insert data
         */        
        if(empty($market_running)){
            
            $this->Oc_pd_input_model->premiumDecayInsert($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $oc_pd_input_id, $premium_decay_no_arr, $put_or_call);
        
        }
        
        $call_average_pd = array_sum($premium_decay_no_arr)/count($premium_decay_no_arr);
        echo '<br/> call_average_ premium decay: <br/>' . $call_average_pd;
        echo '<br/>';
        
        return $call_average_pd;
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: difference of two dates
     */
    
    function diffOfTwoDates( $underlying_date, $expiry_date ) {

        $start = new DateTime($underlying_date);

    //$end = new DateTime('2012-09-11');
        $end = new DateTime($expiry_date);
    //        print_r($end);
    // otherwise the  end date is excluded (bug?)
        $end->modify('+1 day');
    //        print_r($end);

        $interval = $end->diff($start);

    // total days
        $days = $interval->days;

    // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

    // best stored as array, so you can add more than one
        $holidays = array('2019-12-25');

        foreach ($period as $dt) {
            $curr = $dt->format('D');

            // substract if Saturday or Sunday
            if ($curr == 'Sat' || $curr == 'Sun') {
                $days--;
            }

            // (optional) for the updated question
            elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                $days--;
            }
        }


        return $days;
    }
    
    
    /*
     * @author: ZAHIR
     * DESC: oc premium decay calculation starting from first row mysql table
     */
    function ocPdCalcForPrevPutCall(){
        
        ini_set('max_execution_time', 0); 

        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Put_call_model');
        $this->load->model('Oc_pd_analysis_model');
        
//        $company_list = $this->Oc_pd_analysis_model->oCPDNonInserteDCompanyList( $last_calculated_company_id = 0 );
        $company_list = $Send_Api_Contr->oCPDNonInserteDCompanyList( $last_calculated_company_id = 0 );
        
        foreach ($company_list AS $company_list_value) {
            
            $company_symbol = $company_list_value->company_symbol;
            $company_id = $company_list_value->company_id;
            
//            $company_symbol='TCS';
//            $company_id = 1443;
            
            $allUnderlyingDate = $this->Put_call_model->geAllUnderlyingDate( $company_id, $company_symbol );
            
            if(empty($allUnderlyingDate)){ continue; }
            
//            echo '<pre>'; print_r($allUnderlyingDate);
            
            
            foreach( $allUnderlyingDate AS $underlying_date_obj){
                
                echo '<pre>'; print_r($underlying_date_obj);
                
                $underlying_date = $underlying_date_obj->underlying_date;
                $current_price = $underlying_date_obj->underlying_price;
                
                $expiry_dates = $this->Put_call_model->getCurrentExpiryDateByUnderlyingDate($company_id, $company_symbol, $underlying_date);
            
                echo 'expiry_dates : ';

                echo '<pre>';
                print_r($expiry_dates);
                
                if (empty($expiry_dates) && count($expiry_dates) <= 0) { echo 'ignore <br/>'; continue; }
                
                
                $this->ocPDCalcByUDateNPrice( $company_id, $company_symbol, $underlying_date, $current_price, $expiry_dates );
                
//                exit; #remove this line after test
                
            }
            
           
//            exit; #remove this line after test
            
        }
        
        flush();
        
    }
    
    /*
     * Live stock premium decay calculate
     */
    function liveOcPDCalcByUDateNPriceNExpDate( $analysis_input_arr, $market_running, $script_start_time ){
        
        $company_id = $analysis_input_arr['company_id'];
        $company_symbol = $analysis_input_arr['company_symbol'];
        $underlying_date = $analysis_input_arr['underlying_date'];
        
        $underlying_time = $analysis_input_arr['underlying_time'];
        
        $underlying_price = $analysis_input_arr['underlying_price'];

        $each_expiry_date = $analysis_input_arr['expiry_date'];
        
        
        $this->ocPDCalcByUDateNPriceNExpDate( $company_id, $company_symbol, $underlying_date, $underlying_price, $each_expiry_date, $market_running, $underlying_time, $script_start_time);
    }
    
}
