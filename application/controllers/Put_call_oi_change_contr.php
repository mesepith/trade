<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Put_call_oi_change_contr extends MX_Controller {
    /*
     * @author: ZAHIR
     * DESC: Get Best Stock by home analysis
     */

    function getPutCallOiChange() {

//        include_once (dirname(__FILE__) . "/Option_Chain.php");        
//        $option_chain_controller = new Option_Chain();

        $oi_chng_log_arr = array();

        $this->load->model('Put_call_model');
        $this->load->model('Put_call_oi_change');

        $check_todays_oi_chng_data_present = $this->Put_call_oi_change->checkTodaysOiChangeDataPresent();
        
        if( $check_todays_oi_chng_data_present ==='present'){ exit;}
        
//        echo $check_todays_oi_chng_data_present; exit;
        
        
        $check_todays_put_call_data_inserted = $this->checkTodayPutCallDataInserted();
        
//        echo '$check_todays_put_call_data_inserted : ' . $check_todays_put_call_data_inserted; 
        
        if($check_todays_put_call_data_inserted =='no'){ exit;}
        
        
//        exit;
        
        
        $company_list = $this->Put_call_model->displayOptionChainCompanyList();

//        echo '<pre>'; print_r($company_list); 

        foreach ($company_list AS $company_list_value) {

            $company_symbol = $company_list_value->company_symbol;
            $company_id = $company_list_value->company_id;

            $underlying_date_obj = $this->Put_call_model->getLatestUnderlyingDate($company_id, $company_symbol);

//            echo '<pre>'; print_r($underlying_date);

            $underlying_date = $underlying_date_obj->underlying_date;

//            echo $underlying_date;
            if( $underlying_date != date('Y-m-d') ){ 
//            if ($underlying_date != '2019-08-06') {

                echo '<br/>';
                echo "No underlying_date on " . date('Y-m-d') . " for " . $company_symbol;
                echo '<br/>';

                continue;
            }

            $oi_chng_log_arr['company_id'] = $company_id;
            $oi_chng_log_arr['company_symbol'] = $company_symbol;
            $oi_chng_log_arr['underlying_date'] = $underlying_date;

            $expiry_dates = $this->Put_call_model->getCurrentExpiryDateByUnderlyingDate($company_id, $company_symbol, $underlying_date);

            echo '<pre>';
            print_r($expiry_dates);

            if (empty($expiry_dates) && count($expiry_dates) <= 0) {

                echo '<br/>';
                echo "No expiry_dates on " . date('Y-m-d') . " for " . $company_symbol . " for underlying_date " . $underlying_date;
                echo '<br/>';

                continue;
            }

//            exit;

            foreach ($expiry_dates AS $expiry_dates_value) {

                if (empty($expiry_dates_value->expiry_date)) {

                    echo '<br/>';
                    echo "array value not found for expiry_dates array";
                    echo '<br/>';
                    continue;
                }

                $oi_chng_log_arr['expiry_date'] = $expiry_dates_value->expiry_date;

                $oc_data = $this->Put_call_model->getOi($company_id, $company_symbol, $underlying_date, $expiry_dates_value->expiry_date);

                if (empty($oc_data) && count($oc_data) <= 0) {

                    continue;
                }

                echo '<br/>';
                echo 'oc_data for underlying_date ' . $underlying_date . ' with expiry_dates on ' . $expiry_dates_value->expiry_date . ' for ' . $company_symbol;
                echo '<br/>';

                echo '<pre>';
                print_r($oc_data);

                foreach ($oc_data AS $oc_data_value) {

                    $oi_chng_log_arr['calls_oi'] = !empty($oc_data_value->calls_oi) ? $oc_data_value->calls_oi : 0;
                    $oi_chng_log_arr['calls_chng_in_oi'] = !empty($oc_data_value->calls_chng_in_oi) ? $oc_data_value->calls_chng_in_oi : 0;

                    $oi_chng_log_arr['puts_oi'] = !empty($oc_data_value->puts_oi) ? $oc_data_value->puts_oi : 0;
                    $oi_chng_log_arr['puts_chng_in_oi'] = !empty($oc_data_value->puts_chng_in_oi) ? $oc_data_value->puts_chng_in_oi : 0;

                    $oi_chng_log_arr['call_oi_chng_prcnt'] = $oi_chng_log_arr['puts_oi_chng_prcnt'] = 0;

                    if (!empty($oc_data_value->id) && !empty($oc_data_value->strike_price)) {

                        $oi_chng_log_arr['put_call_id'] = $oc_data_value->id;
                        $oi_chng_log_arr['strike_price'] = $oc_data_value->strike_price;
                    } else {

                        $oi_chng_log_arr['put_call_id'] = 0;
                        $oi_chng_log_arr['strike_price'] = 0;
                    }

                    if (!empty($oc_data_value->strike_price) && !empty($oc_data_value->calls_oi) && ( $oc_data_value->calls_oi != 0 ) && !empty($oc_data_value->calls_chng_in_oi)) {

                        $calls_oi = $oc_data_value->calls_oi;
                        $calls_chng_in_oi = $oc_data_value->calls_chng_in_oi;

                        $call_oi_chng_prcnt = (($calls_chng_in_oi / $calls_oi) * 100);

                        echo '$calls_oi : ' . $calls_oi . ' , $calls_chng_in_oi : ' . $calls_chng_in_oi;
                        echo '<br/>';

                        echo 'strike price ' . $oc_data_value->strike_price . ' , $call_oi_chng_prcnt : ' . $call_oi_chng_prcnt;
                        echo '<br/>';
                        echo '<br/>';

                        $oi_chng_log_arr['call_oi_chng_prcnt'] = $call_oi_chng_prcnt;
                    }
                    if (!empty($oc_data_value->strike_price) && !empty($oc_data_value->puts_oi) && ( $oc_data_value->puts_oi != 0 ) && !empty($oc_data_value->puts_chng_in_oi)) {

                        $puts_oi = $oc_data_value->puts_oi;
                        $puts_chng_in_oi = $oc_data_value->puts_chng_in_oi;

                        $puts_oi_chng_prcnt = (($puts_chng_in_oi / $puts_oi) * 100);

                        echo '$puts_oi : ' . $puts_oi . ' , $puts_chng_in_oi : ' . $puts_chng_in_oi;
                        echo '<br/>';

                        echo 'strike price ' . $oc_data_value->strike_price . ' , $puts_oi_chng_prcnt : ' . $puts_oi_chng_prcnt;
                        echo '<br/>';
                        echo '<br/>';

                        $oi_chng_log_arr['puts_oi_chng_prcnt'] = $puts_oi_chng_prcnt;
                    }

                    echo '<pre>';
                    print_r($oi_chng_log_arr);

                    $this->Put_call_oi_change->insertOiChngPrcntLog($oi_chng_log_arr);
                }

//                exit;
            }
        }
    }
    
    /*
     * @author: ZAHIR
     * check todays put option data inserted by last companies 
     */
    function checkTodayPutCallDataInserted(){
        
        $this->load->model('Put_call_model');
        
        $date = date('Y-m-d');
        $yesterday_date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        
        $previous_companies = $this->Put_call_model->getPreviousDaysBottomCompanyList( $yesterday_date );
        
        $previous_company_symbol_arr = array();
        
        foreach( $previous_companies AS $previous_companies_value ){
            
            $previous_company_symbol_arr[] = $previous_companies_value->company_symbol;
        }
        
        $current_companies = $this->Put_call_model->getTodaysBottomCompanyList( $date );
        
        if( empty($current_companies) ){
            
            echo 'Todays option chain data is not inserted ' . $date;
            exit;
        }
        
        $todays_put_call_data_inserted = 'no';
        
        foreach( $current_companies AS $current_companies_value ){
            
            $current_company_symbol_arr[] = $current_companies_value->company_symbol;
            
            if(in_array($current_companies_value->company_symbol, $previous_company_symbol_arr) ){
                
//                echo $current_companies_value->company_symbol . ' is present on previous day' . '<br/>';
                
                $todays_put_call_data_inserted = 'yes';
                
            }
            
        }
        
        return $todays_put_call_data_inserted;
        
    }

}
