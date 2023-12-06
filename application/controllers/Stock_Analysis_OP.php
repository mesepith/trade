<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Put_call_oi_change_contr.php");

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

/*
 * @author: ZAHIR
 * DECS: stock analysis by option pain
 */

class Stock_Analysis_OP extends MX_Controller {
    
    /*
     * @author: ZAHIR
     * DESC: stock bearish or bullish determined by option pain of option chain
     */
    
    public function stockAnlaysisByOPOfOC(){
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Analysis_task_model');
        
        $check_task_done = $this->Analysis_task_model->checkAnalysisDone('oc_op_analysis');
        
        if($check_task_done=='done'){
            echo 'Todays task is done';
            exit;
        }
        
        $Put_call_oi_change_contr = new Put_call_oi_change_contr();
        
        $check_todays_put_call_data_inserted = $Put_call_oi_change_contr->checkTodayPutCallDataInserted();
        
        if($check_todays_put_call_data_inserted =='no'){ exit;}
        
//        $this->load->model('Put_call_model'); #Delete after test
        
        $this->load->model('Oc_pd_analysis_model');
        
        $last_calculated_company = $this->Analysis_task_model->lastCalculatedCompany('oc_op_analysis');
        
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
        
//        echo '<pre>'; print_r($company_list); 
        
        if(empty($company_list)){            
            
            $this->Analysis_task_model->insertOcOPAnalysisDone( );
            echo '<br/>';
            echo 'All companies analysis is done';
            echo '<br/>';
            
            exit;
        }
        
        foreach ($company_list AS $company_list_value) {
            
            $company_symbol = $company_list_value->company_symbol;

            $company_id = $company_list_value->company_id;
            
            echo '$company_symbol : ' . $company_symbol;
            echo '<br/>';
            
            $underlying_date_obj = $this->Oc_pd_analysis_model->getLatestUnderlyingDateNPriceEOD($company_id, $company_symbol);
            
            if( empty($underlying_date_obj) ){ continue; }
            
            echo '<pre>'; print_r($underlying_date_obj);
            echo '<br/>';
            
            if( empty($underlying_date_obj->underlying_date) || empty($underlying_date_obj->underlying_price) ){ continue; }
            
            $underlying_date = $underlying_date_obj->underlying_date;
            $underlying_price = $underlying_date_obj->underlying_price;
            
            if( $underlying_date != date('Y-m-d') ){ 
//            if ($underlying_date != '2019-12-18') {

                echo '<br/>';
                echo "No underlying_date on " . date('Y-m-d') . " for " . $company_symbol;
                echo '<br/>';
                echo '<br/>';

                continue;
            }
            
            $this->Analysis_task_model->ocCalculationDone($company_id, $company_symbol, 'oc_op_analysis');#this means we have done this companies calulation for today
            
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
            
            $this->OcOptionPainCalculate( $company_id, $company_symbol, $underlying_date, $underlying_price, $expiry_dates );
            
//            exit(); # delete it after test
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Option Pain Analysis Calculation
     */
    
    function OcOptionPainCalculate( $company_id, $company_symbol, $underlying_date, $underlying_price, $expiry_dates ){
        
        echo '$underlying_price : ' . $underlying_price;
        echo '<br/>';
        
        $this->load->model('Oc_op_analysis_model');
        
        foreach ($expiry_dates AS $expiry_dates_key=> $expiry_dates_value) {
            
            if($expiry_dates_key >=2 ){ echo 'Consider two expiry dates only <br/><br/>'; break; }
            
            if (empty($expiry_dates_value->expiry_date)) {

                echo '<br/>';
                echo "array value not found for expiry_dates array";
                echo '<br/>';
                continue;
            }
            
            
            $highest_sum_of_put_call_oi[$expiry_dates_key] = $this->Oc_op_analysis_model->getHighestSumCombinationOfPutCallOi( $company_id, $company_symbol, $underlying_date, $expiry_dates_value->expiry_date );                        
            
            echo '<br/>' . count($highest_sum_of_put_call_oi[$expiry_dates_key]) . '<br/>';
            
            /* For first exipry we still needs to option pain and for second exipry we still needs one option pain */
            if( ( $expiry_dates_key == 0 && count($highest_sum_of_put_call_oi[$expiry_dates_key])<2 ) || ( $expiry_dates_key == 1 && count($highest_sum_of_put_call_oi[$expiry_dates_key])<1 ) ){
                
                continue;
            }
            
        }
        
        echo '<pre>';
        print_r($highest_sum_of_put_call_oi);
        
        /* If wet data from current expiry and next expiry then we proceed the data */
        if( count($highest_sum_of_put_call_oi) >= 1){
            
            $option_pain_arr = array();
            
            $option_pain_arr['company_id'] = $company_id;
            $option_pain_arr['company_symbol'] = $company_symbol;
            $option_pain_arr['underlying_date'] = $underlying_date;
            $option_pain_arr['underlying_price'] = $underlying_price;
            
            foreach( $highest_sum_of_put_call_oi AS $highest_sum_of_put_call_oi_key => $highest_sum_of_put_call_oi_arr){
                
                if( $highest_sum_of_put_call_oi_key == 0 ){
                    
                    $option_pain_arr['current_expiry_date'] = $expiry_dates[$highest_sum_of_put_call_oi_key]->expiry_date;
                    
                }else if( $highest_sum_of_put_call_oi_key == 1 ){
                    
                    $option_pain_arr['next_expiry_date'] = $expiry_dates[$highest_sum_of_put_call_oi_key]->expiry_date;
                    
                }
                
                $count = 0;
                
                foreach( $highest_sum_of_put_call_oi_arr AS $highest_sum_of_put_call_oi_arr_val){
                    
                    $count++;
                    
                    if( $highest_sum_of_put_call_oi_key == 0 ){
                        
                        $option_pain_arr['sum_of_call_put_oi_'.$count.'_current_exp'] = $highest_sum_of_put_call_oi_arr_val->sum_of_call_put;
                        $option_pain_arr['strike_price_'.$count.'_current_exp'] = $highest_sum_of_put_call_oi_arr_val->strike_price;
                        
                    }else if( $highest_sum_of_put_call_oi_key == 1 ){
                        
                        $option_pain_arr['sum_of_call_put_oi_'.$count.'_next_exp'] = $highest_sum_of_put_call_oi_arr_val->sum_of_call_put;
                        $option_pain_arr['strike_price_'.$count.'_next_exp'] = $highest_sum_of_put_call_oi_arr_val->strike_price;
                        
                    }
                    
                }
                
                
                
            }
            
            echo '<pre>';
            print_r($option_pain_arr);
            
            $option_pain_arr["created_at"] = date("Y-m-d H:i:s");
            
            $this->Oc_op_analysis_model->insertOptionPainAnalysisData($option_pain_arr);
            
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: option pain calculation starting from first row mysql table
     */
    function ocOPCalcForPrevPutCall(){
        
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
                
                
                $this->OcOptionPainCalculate( $company_id, $company_symbol, $underlying_date, $current_price, $expiry_dates );
                
//                exit; #remove this line after test
                
            }
            
           
//            exit; #remove this line after test
            
        }
        
        flush();
        
    }
}
