<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class Future extends MX_Controller {

    public function displayFutureCompanyList( $live=false ) {
        
        $this->load->model('Future_model');
        
        $company_list = $this->Future_model->futureCompanyList( 0 );
        
//        echo '<pre>'; print_r($company_list); 
        
        $data['nestedData']['company_list'] = $company_list;
        $data['nestedData']['live'] = $live;
         
        $data['content'] = "future/fr-company-list";
        $this->load->view('index', $data);
        
        
    }
    public function getFrDataOfStockByFilter( $live=false ){
        
//        , $underlying_date=false, $expiry_date=false
//        echo '<pre>'; 
//        print_r($this->input->get());
//        $this->displayAllNseData( $this->input->get() );
        
//        http://localhost/trade/option-chain/stock-info?company_id=20&company_symbol=ACC&sud=2019-07-05&sed=2019-07-25
        
        $company_id = $this->input->get('company_id');
        $company_symbol = $this->input->get('company_symbol');
        $searching_underlying_date = $this->input->get('sud');
        $searching_underlying_date_to = $this->input->get('sud_to');
        
//        echo $searching_underlying_date_to; 
//        echo '<pre>'; 
//        print_r($this->input->get());
//        
//        exit;
        
        $searching_expiry_date = $this->input->get('sed');
        $searching_underlying_time = $this->input->get('sut');
        
        $get_all_expiry_date = $this->input->get('get_all_expiry_date');
        $date_period = $this->input->get('date_period');
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        
        $this->getFrDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_underlying_date_to, $searching_expiry_date, $live, $searching_underlying_time, $get_all_expiry_date, $date_period, $show_avg_total_data);
        
    }
    public function getFrDataOfStock( $company_id, $company_symbol_encode, $searching_underlying_date=false, $searching_underlying_date_to=false, $searching_expiry_date=false, $live=false, $searching_underlying_time=false, $get_all_expiry_date=false, $date_period=false, $show_avg_total_data=false ) {
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $other_info = array();
        
        if(empty($searching_underlying_date)){
        
            $underlying_date = $Send_Api_Contr->getLatestFrUnderlyingDate( $company_id, $company_symbol, $live );
            
            if(empty($underlying_date)){echo 'No Data Available'; exit;}

            $searching_underlying_date = $underlying_date->underlying_date;
        }
        
        $searching_underlying_date_loop = (empty($searching_underlying_date)) ? date ("Y-m-d") : $searching_underlying_date;
        $searching_underlying_date_to_loop = (empty($searching_underlying_date_to)) ? date ("Y-m-d") : $searching_underlying_date_to;
        
        while (strtotime($searching_underlying_date_loop) <= strtotime($searching_underlying_date_to_loop)) {
            
//            echo $searching_underlying_date_loop;
            
            $other_info['expiry_dates'] = $Send_Api_Contr->getFrCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $searching_underlying_date_loop, $live ); 
            
            if(!empty($other_info['expiry_dates'])){ break; }
            
//            echo '<pre>'; 
//            print_r($other_info['expiry_dates']);
            
            $searching_underlying_date_loop = date ("Y-m-d", strtotime("+1 day", strtotime($searching_underlying_date_loop)));
//            echo '<br/>';
        }
        
//        exit;
        
//        $other_info['expiry_dates'] = $Send_Api_Contr->getFrCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $searching_underlying_date, $live );               
        
        if(empty($other_info['expiry_dates'])){echo 'No Data Available ..'; exit;}

        if(empty($searching_expiry_date)){

            $searching_expiry_date = $other_info['expiry_dates'][0]->expiry_date;
        }
        else{ }
        
        if($live){
            
            /*$other_info['underlying_time'] = $Send_Api_Contr->getAllUnderlyingTime( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date );            
            
            if(empty($searching_underlying_time)){
                
                $total_underlying_time = count($other_info['underlying_time']);
                
                $searching_underlying_time = $other_info['underlying_time'][$total_underlying_time-1]->underlying_time;
            }*/
            
        }else{
            
            $searching_underlying_time = false;
        }
        
        $other_info['searching_underlying_time'] = $searching_underlying_time;
      
        $fr_data = $Send_Api_Contr->getFrDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_underlying_date_to, $searching_expiry_date, $live, $searching_underlying_time, $get_all_expiry_date );        
        
//        echo '<pre>'; 
//        print_r($fr_data);
//        exit;
        
        if(empty($fr_data)){echo 'No Data Available ...'; exit;}
        
        $total = count($fr_data);
        
        $other_info['company_id'] = $company_id;
        $other_info['company_symbol'] = $company_symbol;
        
        $other_info['searching_underlying_date'] = $searching_underlying_date;
        $other_info['searching_expiry_date'] = $searching_expiry_date;        
        $other_info['searching_underlying_date_to'] = $searching_underlying_date_to;        
        $other_info['get_all_expiry_date'] = (empty($get_all_expiry_date)) ? 'no' : $get_all_expiry_date;        
        $other_info['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        $data['nestedData']['date_period'] = $date_period;
        
        $other_info['underlying_price'] = $fr_data[0]->underlying_price;
        $other_info['industry'] = empty($fr_data[$total-1]->industry) ? "NA" : $fr_data[$total-1]->industry;
        $other_info['volume_freeze_quantity'] = empty($fr_data[0]->volume_freeze_quantity) ? "NA" : $fr_data[0]->volume_freeze_quantity;
        
        $other_info['underlying_date_time']  = date('M d, Y h:i A', strtotime($fr_data[0]->underlying_date_time));
        
        $data['nestedData']['fr_data'] = $fr_data;
        $data['nestedData']['other_info'] = $other_info;
        
        $data['nestedData']['live'] = $live;
        
//        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
//        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/future/fr-daily.js");
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/future/fr-daily.js");
        
        if( $get_all_expiry_date !=='yes' && count($fr_data) > 0 ){
            
            $data['nestedScript']['js'][] = "assets/plugin/charts/g-chart/loader.js";
            $data['nestedScript']['js'][] = "assets/js/pages/future/fr-chart.js";
        }
         
        $data['content'] = "future/fr-of-stock";
        $this->load->view('index', $data);
                
    }
    
    function dayWiseAnalysis(){
        
        $searching_underlying_date = $this->input->get('sud');
        $searching_expiry_date = $this->input->get('sed');
        $turnover_sortby = $this->input->get('turnover_sortby');
        $volume_sortby = $this->input->get('volume_sortby');
        $oi_sortby = $this->input->get('oi_sortby');
        $change_oi_sortby = $this->input->get('change_oi_sortby');
        $change_oi_p_sortby = $this->input->get('change_oi_p_sortby');
        $daily_volatility_sortby = $this->input->get('daily_volatility_sortby');
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $other_info = array();
        
        if(empty($searching_underlying_date)){
        
            $underlying_date = $Send_Api_Contr->getLatestFrUnderlyingDateofAll( );
            
//            echo '<pre>'; 
//            print_r($underlying_date);
            
            if(empty($underlying_date)){echo 'No Data Available'; exit;}

            $searching_underlying_date = $underlying_date->underlying_date;
        }
        
        $other_info['expiry_dates'] = $Send_Api_Contr->getFrCurrentExpiryDateByUnderlyingDateofAll( $searching_underlying_date ); 
        
//        echo '<pre>'; 
//        print_r($other_info['expiry_dates']);
        
        if(empty($other_info['expiry_dates'])){echo 'No Data Available ..'; exit;}

        if(empty($searching_expiry_date)){

            $searching_expiry_date = $other_info['expiry_dates'][0]->expiry_date;
        }
        
        $fr_data = $Send_Api_Contr->getFrDataOfAllStock( $searching_underlying_date, $searching_expiry_date, $turnover_sortby, $volume_sortby, $oi_sortby, $change_oi_sortby, $change_oi_p_sortby, $daily_volatility_sortby ); 
        
//        echo '<pre>'; 
//        print_r($fr_data);
        
        $other_info['searching_underlying_date'] = $searching_underlying_date;
        $other_info['searching_expiry_date'] = $searching_expiry_date;
        
        $data['nestedData']['turnover_sortby'] = $turnover_sortby;
        $data['nestedData']['volume_sortby'] = $volume_sortby;
        $data['nestedData']['oi_sortby'] = $oi_sortby;
        $data['nestedData']['change_oi_sortby'] = $change_oi_sortby;
        $data['nestedData']['change_oi_p_sortby'] = $change_oi_p_sortby;
        $data['nestedData']['daily_volatility_sortby'] = $daily_volatility_sortby;
        
        $data['nestedData']['fr_data'] = $fr_data;
        $data['nestedData']['other_info'] = $other_info;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/future/fr-of-all-stock.js");
        
        $data['content'] = "future/fr-of-all-stock";
        $this->load->view('index', $data);
    }
    
    /*
     * Day wise rollover data of all companies
     */
    
    function dayWiseRolloverAnalysis(){
        
        $searching_underlying_date = $this->input->get('sud');
        $rollover_sortby = $this->input->get('rollover_sortby');
        $rollcost_sortby = $this->input->get('rollcost_sortby');
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        if(empty($searching_underlying_date)){
        
            $underlying_date = $Send_Api_Contr->getLatestFrUnderlyingDateofAll( );            
            
            if(empty($underlying_date)){echo 'No Data Available'; exit;}

            $searching_underlying_date = $underlying_date->underlying_date;
        }
        
        $fr_rollover_data = $Send_Api_Contr->getFrRolloverDataOfAllStock( $searching_underlying_date, $rollover_sortby, $rollcost_sortby );
                
//        echo '<pre>'; 
//        print_r($fr_rollover_data); exit;
        
        $other_info['searching_underlying_date'] = $searching_underlying_date;
        
        $data['nestedData']['rollover_sortby'] = $rollover_sortby;
        $data['nestedData']['rollcost_sortby'] = $rollcost_sortby;
        
        $data['nestedData']['fr_rollover_data'] = $fr_rollover_data;
        $data['nestedData']['other_info'] = $other_info;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/future/fr-rollover-of-all-stock.js");
        
        $data['content'] = "future/fr-rollover-of-all-stock";
        $this->load->view('index', $data);
        
    }
    
    function getFrRolloverofSingleStockByFilter(){
        
        $company_id = $this->input->get('company_id');
        $company_symbol = $this->input->get('company_symbol');
        $searching_underlying_date = $this->input->get('sud');
        $searching_underlying_date_to = $this->input->get('sud_to');
        
        $date_period = $this->input->get('date_period');
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        
        $this->getFrRolloverofSingleStock( $company_id, $company_symbol, $searching_underlying_date, $searching_underlying_date_to, $live=false, $date_period, $show_avg_total_data);
    }
    
    function getFrRolloverofSingleStock( $company_id, $company_symbol_encode, $searching_underlying_date=false, $searching_underlying_date_to=false, $live=false, $date_period=false, $show_avg_total_data=false  ){
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $other_info = array();
        
        if(empty($searching_underlying_date)){
        
            $underlying_date = $Send_Api_Contr->getLatestFrUnderlyingDate( $company_id, $company_symbol, $live );
            
            if(empty($underlying_date)){echo 'No Data Available'; exit;}

            $searching_underlying_date = $underlying_date->underlying_date;
        }
        
        $fr_data = $Send_Api_Contr->getFrRolloverofSingleStock( $company_id, $company_symbol, $searching_underlying_date, $searching_underlying_date_to );    
        
        $total = count($fr_data);
        
        $other_info['company_id'] = $company_id;
        $other_info['company_symbol'] = $company_symbol;
        $other_info['industry'] = empty($fr_data[$total-1]->industry) ? "NA" : $fr_data[$total-1]->industry;
        
        $other_info['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        $data['nestedData']['date_period'] = $date_period;
        
        $other_info['searching_underlying_date'] = $searching_underlying_date;      
        $other_info['searching_underlying_date_to'] = $searching_underlying_date_to;  
        
        $other_info['underlying_date_time']  = date('M d, Y', strtotime($fr_data[0]->underlying_date_time));
        
        $data['nestedData']['live'] = $live;
        
//        echo '<pre>'; 
//        print_r($fr_rollover_data);
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/future/fr-rollover.js", "assets/plugin/charts/g-chart/loader.js", "assets/js/pages/future/fr-rollover-chart.js");
        
        $data['nestedData']['fr_data'] = $fr_data;
        $data['nestedData']['other_info'] = $other_info;
        
        $data['content'] = "future/fr-rollover";
        $this->load->view('index', $data);
    }
   
}
