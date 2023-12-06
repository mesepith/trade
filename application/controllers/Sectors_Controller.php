<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sectors_Controller extends MX_Controller {

    public function sectorsList(  ) {
        
        $this->load->model('Sectors_model');
        
        $sectors_list = $this->Sectors_model->listAllSectors( );
        
//        echo '<pre>'; print_r($sectors_list); exit;
        
        $data['nestedData']['sectors_list'] = $sectors_list;
         
        $data['content'] = "sectors/sectors-list";
        $this->load->view('index', $data);
        
        
    }

    public function sectorsLog($sector_id, $sector_name, $manual_date=false, $manual_date_to=false) {
        
        $this->load->model('Sectors_model');
        
        $date_period = $this->input->get('date_period');
        
        if(empty($manual_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $manual_date;
        }
        
        $sector_data = $this->Sectors_model->sectorsInfoByIdAndDate( $sector_id, $date, $manual_date_to );
        
        $this->load->helper('function_helper');
        
//        $avg_data['change'] = avgReturnCalc( $sector_data, 'change', 'stock_date' );
        
//        echo '<pre>'; print_r($avg_data); exit;
        
//        echo $sector_data[0]->stock_date_time;
        $stock_date = '';
        if(!empty($sector_data[0]->stock_date_time)){
            
            $stock_date_explode = explode(' ', $sector_data[0]->stock_date_time);
            
            $stock_date = $stock_date_explode[0];
            
//            echo '$stock_date : ' . $stock_date; exit;
        }
        
        $data['nestedData']['sector_data'] = $sector_data;
//        $data['nestedData']['avg_data'] = $avg_data;
        $data['nestedData']['sector_name'] = $sector_name;
        $data['nestedData']['date_period'] = $date_period;
        $data['nestedData']['sector_id'] = $sector_id;
        $data['nestedData']['stock_date'] = $stock_date;
        $data['nestedData']['stock_date_to'] = empty($manual_date_to) ? date('Y-m-d') :$manual_date_to;
        
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        $data['nestedData']['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        
        if(!empty($manual_date) && !empty($sector_data[0]->stock_date_time) && $stock_date != $manual_date){
            
            $data['nestedData']['no_data_for_manual_date_msg'] = 'There is No Data For Date ' . date('d-M-Y', strtotime($manual_date));
        }else{
            
            $data['nestedData']['no_data_for_manual_date_msg'] = false;
        }
        
//        echo '<pre>'; print_r($sector_data); exit;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/sector-info.js", "assets/plugin/charts/g-chart/loader.js", "assets/js/pages/sector-chart.js");
//        $data['nestedScript']['js'] = array("");
        
        $data['content'] = "sectors/sector-info";
        
        $this->load->view('index', $data);                
        
        
    }
    /*
    * Data Calculate Quatarly, Monthly And Weekly wise
    */
    function sectorsClusterReturn( $sector_id, $sector_name ){
        
        $this->load->model('Sectors_model');
        
        $manual_date_to = date('Y-m-d', strtotime("-12 month"));
        
        $sector_data = $this->Sectors_model->sectorsInfoByIdAndDate( $sector_id, $manual_date_to, date('Y-m-d') );
        
        $this->load->helper('function_helper');
        
        $avg_data['ltp'] = avgReturnCalc( $sector_data, 'ltp', 'stock_date' );
        $avg_data['change'] = avgReturnCalc( $sector_data, 'change', 'stock_date' );        
        $avg_data['trade_value_sum'] = avgReturnCalc( $sector_data, 'trade_value_sum', 'stock_date' );
        $avg_data['trade_volume_sum'] = avgReturnCalc( $sector_data, 'trade_volume_sum', 'stock_date' );
        
        
//        echo '<pre>'; print_r($avg_data); exit;
//        $data['nestedData']['sector_data'] = $sector_data;
        $data['nestedData']['avg_data'] = $avg_data;
        $data['nestedData']['report_name'] = $sector_name;
        $data['nestedData']['sector_id'] = $sector_id;
        
        $data['nestedScript']['js'] = array("assets/plugin/charts/g-chart/loader.js", "assets/js/pages/average-chart.js");
        
        $data['content'] = "average/average-return";
        
        $this->load->view('index', $data); 
        
    }
    
    function calcsectorAveragesByDays( $sector_id, $sector_name ){
        
        $this->load->helper('function_helper');
        
        $this->load->model('Sectors_model');
        
        $tb_column = $this->input->get('tb_column');
        $data['nestedData']['tb_column'] = $tb_column;
        
        $date_period = empty($this->input->get('date_period')) ? date('Y-m-d', strtotime("-2 week")) : $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;
        
//        $stock_detail = $this->Stock_data_model->getStockDetailByCompanyIdAndSymbol($company_id, $company_symbol, $date_period, date('Y-m-d'));
        
        $stock_detail = $this->Sectors_model->sectorsInfoByIdAndDate( $sector_id, $date_period, date('Y-m-d') );
        
//        echo '<pre>'; print_r($stock_detail); exit;
        
        $calc_avg_by_arr = array(3 ,5, 8, 14, 20);        
        $tb_column_arr = array('ltp','trade_value_sum', 'trade_volume_sum');
        
        $avg_data = calcAveragesByDays( $stock_detail, $calc_avg_by_arr, $tb_column_arr );
        
        $data['nestedData']['url'] = 'sector/average-by-days/' . $sector_id . '/' .$sector_name ;
        
        $data['nestedData']['avg_data'] = $avg_data;
        $data['nestedData']['report_name'] = $sector_name;
        $data['nestedData']['company_id'] = $sector_id;
        $data['nestedData']['tb_column_arr'] = $tb_column_arr;
        
        $data['nestedScript']['js'] = array("assets/js/pages/average-by-days.js", "assets/plugin/charts/g-chart/loader.js", "assets/js/pages/average-by-days-chart.js");
        
        $data['content'] = "average/average-by-days";
        
        $this->load->view('index', $data); 
    }
    
    function sectorsLogFilter(){
        
        $sector_id = $this->input->get('sector_id');
        $sector_date = $this->input->get('sector_date');
        $sector_date_to = $this->input->get('sector_date_to');
        $sector_name = $this->input->get('sector_name');
        
        $this->sectorsLog( $sector_id, $sector_name, $sector_date, $sector_date_to );
    }
    
    /*
     * Day, week , Month wise Calculate
     */
    function avgReturnCalc( $input_data, $tb_column, $tb_date_column ){ 
        
        
    }
    
    /*
     * Display Nifty Live Data
     */
    function displayNiftyLiveData( $sector_id=13, $sector_name='NIFTY 50', $api=false ){
        
        $manual_date = $this->input->get('sector_date');
        
        $this->load->model('Sectors_model');
        
        if(empty($manual_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $manual_date;
        }
        
//        echo $date; exit;
        
        $sector_data = $this->Sectors_model->sectorsInfoByIdAndDate( $sector_id, $date, $manual_date_to=false, $live='live' );
        
        if($api){ echo json_encode($sector_data); exit; }
        
        $stock_date = (empty($sector_data[0]->stock_date)) ? date('Y-m-d') : $sector_data[0]->stock_date;
        
        $total = count($sector_data);
        
//        $data['nestedData']['nifty_last_price'] = $sector_data[$total-1]->ltp;
        $data['nestedData']['total'] = $total;
        
        $data['nestedData']['sector_data'] = $sector_data;
        $data['nestedData']['sector_name'] = $sector_name;
        $data['nestedData']['sector_id'] = $sector_id;
        $data['nestedData']['stock_date'] = $stock_date;
        
//        echo '<pre>'; print_r($sector_data);
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/sector-info-live.js", "assets/plugin/charts/g-chart/loader.js", "assets/js/pages/sector-live-chart.js");
//        $data['nestedScript']['js'] = array("");
        
        $data['content'] = "sectors/sector-live";
        
        $this->load->view('index', $data); 
    }
    
    function fetchNiftyLiveData(){
        
        $this->displayNiftyLiveData( $sector_id=13, $sector_name='NIFTY 50', 'api' );
    }
    
}
