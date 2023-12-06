<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class Option_Chain extends MX_Controller {

    public function displayOptionChainCompanyList( $live=false ) {
        
        $this->load->model('Put_call_model');
        
        $company_list = $this->Put_call_model->displayOptionChainCompanyList( );
        
//        echo '<pre>'; print_r($company_list); 
        
        $data['nestedData']['company_list'] = $company_list;
        $data['nestedData']['live'] = $live;
         
        $data['content'] = "option-chain/oc-company-list";
        $this->load->view('index', $data);
        
        
    }
    public function getOCDataOfStockByFilter( $live=false ){
        
//        , $underlying_date=false, $expiry_date=false
//        echo '<pre>'; 
//        print_r($this->input->get());
//        $this->displayAllNseData( $this->input->get() );
        
//        http://localhost/trade/option-chain/stock-info?company_id=20&company_symbol=ACC&sud=2019-07-05&sed=2019-07-25
        
        $company_id = $this->input->get('company_id');
        $company_symbol = $this->input->get('company_symbol');
        $searching_underlying_date = $this->input->get('sud');
        $searching_expiry_date = $this->input->get('sed');
        $searching_underlying_time = $this->input->get('sut');
        
        $this->getOCDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date, $live, $searching_underlying_time);
        
    }
    public function getOCDataOfStock( $company_id, $company_symbol_encode, $searching_underlying_date=false, $searching_expiry_date=false, $live=false, $searching_underlying_time=false ) {
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Put_call_model');
        
        $other_info = array();
        
        $other_info['company_name'] = $this->Put_call_model->getCompanyNameByIdAndSymbol( $company_id, $company_symbol );
        
        if(empty($searching_underlying_date)){
        
//            $underlying_date = $this->Put_call_model->getLatestUnderlyingDate( $company_id, $company_symbol, $live );
            $underlying_date = $Send_Api_Contr->getLatestUnderlyingDate( $company_id, $company_symbol, $live );

            if(empty($underlying_date)){echo 'No Data Available'; exit;}

            $searching_underlying_date = $underlying_date->underlying_date;
        }
        

//        $other_info['expiry_dates'] = $Send_Api_Contr->getCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $searching_underlying_date, $live );
                
        $searching_underlying_date_loop = (empty($searching_underlying_date)) ? date ("Y-m-d") : $searching_underlying_date;
//        $searching_underlying_date_to_loop = (empty($searching_underlying_date_to)) ? date ("Y-m-d") : $searching_underlying_date_to;
        
        $searching_underlying_date_to_loop = date ("Y-m-d", strtotime("-10 day", strtotime($searching_underlying_date_loop)));
                
        while (strtotime($searching_underlying_date_loop) >= strtotime($searching_underlying_date_to_loop)) {
            
            $other_info['expiry_dates'] = $Send_Api_Contr->getCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $searching_underlying_date_loop, $live ); 
            
            if(!empty($other_info['expiry_dates'])){ 
                
                $searching_underlying_date = $searching_underlying_date_loop;
                
                break; 
                
            }
            
            $searching_underlying_date_loop = date ("Y-m-d", strtotime("-1 day", strtotime($searching_underlying_date_loop)));
        }
        
        if(empty($other_info['expiry_dates'])){echo 'No Data Available ..'; exit;}

        if(empty($searching_expiry_date)){

            $searching_expiry_date = $other_info['expiry_dates'][0]->expiry_date;
        }
        else{
//            echo 'inside else';
        }
//        exit;
        
        if($live){
            
//            $other_info['underlying_time'] = $this->Put_call_model->getAllUnderlyingTime( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date );            
            $other_info['underlying_time'] = $Send_Api_Contr->getAllUnderlyingTime( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date );            
            
            if(empty($searching_underlying_time)){
                
                $total_underlying_time = count($other_info['underlying_time']);
                
                $searching_underlying_time = $other_info['underlying_time'][$total_underlying_time-1]->underlying_time;
            }
            
        }else{
            
            $searching_underlying_time = false;
        }
        
        $other_info['searching_underlying_time'] = $searching_underlying_time;
        
//        $oc_data = $this->Put_call_model->getOCDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date, $live, $searching_underlying_time );        
        $oc_data = $Send_Api_Contr->getOCDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date, $live, $searching_underlying_time );        
        
        if(empty($oc_data)){echo 'No Data Available ...'; exit;}
        
        /* Get LAst T bills start */
        $this->load->model('Rbi_model');        
        $last_t_bill = $this->Rbi_model->getLastTBill('91 day T-bills');
        
        $data['nestedData']['last_t_bill'] = $last_t_bill;
        /* Get LAst T bills end */
        
        /* Get Option selling Tips start */
        $this->load->model('Vix_model');        
//        $vix = $this->Vix_model->getLastVix( $oc_data[0]->underlying_date );
        $last_vix_arr = $this->Vix_model->getLastVix( $oc_data[0]->underlying_date );
        
        $vix = $last_vix_arr->last_price; 
        
        if( !empty($vix)){
            
            $selling_tips = $this->optionSellingSPTips($vix , $oc_data[0]->underlying_price);
        
            $data['nestedData']['selling_tips'] = $selling_tips;
        }
        
        /* Get Option selling Tips end */
        
//        echo '<pre>'; 
//        print_r($oc_data); exit;
        
        $other_info['company_id'] = $company_id;
        $other_info['company_symbol'] = $company_symbol;
        
        $other_info['total_calls_oi'] = $oc_data[count($oc_data)-1]->calls_oi;
        $other_info['total_puts_oi'] = $oc_data[count($oc_data)-1]->puts_oi;
        
        $other_info['searching_underlying_date'] = $searching_underlying_date;
        $other_info['searching_expiry_date'] = $searching_expiry_date;        
        
        $other_info['underlying_price'] = $oc_data[0]->underlying_price;
        
        $other_info['underlying_date_time']  = date('M d, Y h:i A', strtotime($oc_data[0]->underlying_date_time));
        
        $data['nestedData']['oc_data'] = $oc_data;
        $data['nestedData']['other_info'] = $other_info;
        
        $data['nestedData']['live'] = $live;
        
        $data['nestedScript']['js'] = array("assets/plugin/gaussian/gaussian.js", "assets/js/pages/option/oc-of-stock.js");
         
        $data['content'] = "option-chain/oc-of-stock";
        $this->load->view('index', $data);
        
        
    }
    
    function getLiveOCDataOfStock( $company_id, $company_symbol, $live ){
        
        $this->getOCDataOfStock( $company_id, $company_symbol, false, false, $live );
    }
    
    /*
     * Option selling, on which strike Price Tipcs
     */
    function optionSellingSPTips( $vix, $underlying_price ){
        
        $selling_tips = array();
        
        $selling_tips['annual_range_high'] = number_format($underlying_price *(1+$vix/100));
        
        $selling_tips['annual_range_low'] = number_format($underlying_price * (100-$vix)/100);
        
        $month = 12;
        
        $monthly_vix = number_format( ($vix/ sqrt($month)), 2); 
        
        $selling_tips['monthly_range_high'] = number_format( $underlying_price *(100+$monthly_vix)/100 );
        
        $selling_tips['monthly_range_low'] = number_format( $underlying_price * (100-$monthly_vix)/100 );
        
        $weeks = 52;
        
        $weekly_vix = number_format( ($vix/ sqrt($weeks)), 2); 
        
        $selling_tips['weekly_range_high'] = number_format( $underlying_price *(100+$weekly_vix)/100 );
        
        $selling_tips['weekly_range_low'] = number_format( $underlying_price * (100-$weekly_vix)/100 );
        
        $days = 365;
        
        $daily_vix = number_format( ($vix/ sqrt($days)), 2); 
        
        $selling_tips['daily_range_high'] = number_format( $underlying_price *(100+$daily_vix)/100 );
        
        $selling_tips['daily_range_low'] = number_format( $underlying_price * (100-$daily_vix)/100 );
        
        return $selling_tips;
    }
   
}
