<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class FullDay extends MX_Controller {

    public function wholeDayData( $company_id, $company_symbol_encode, $manual_stock_date=false ) {
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $this->load->model('Stock_data_live_model');
        
        if(empty($manual_stock_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $manual_stock_date;
        }
        
        $stock_detail = $this->Stock_data_live_model->getStockDetailByCompanyIdAndSymbol( $company_id, $company_symbol, $date );        
        
//        $stock_detail = false;
        
        if(!empty($stock_detail)){ 
        
            $data['nestedData']['company_name'] = $stock_detail[0]->company_name;
//            $data['nestedData']['company_symbol'] = $stock_detail[0]->company_symbol;
            
            $data['nestedData']['open_price'] = $stock_detail[0]->open_price;
            $data['nestedData']['stock_date'] = $stock_detail[0]->stock_date;
            $data['nestedData']['stock_detail'] = $stock_detail;
        
        }
        
        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;
        
        if(!empty($manual_stock_date) && !empty($stock_detail[0]->stock_date) && $stock_detail[0]->stock_date != $manual_stock_date){
            
            $data['nestedData']['no_data_for_manual_date_msg'] = 'There is No Data For Date ' . date('d-M-Y', strtotime($manual_stock_date));
        }else{
            
            $data['nestedData']['no_data_for_manual_date_msg'] = false;
        }
        
//        echo '<pre>'; print_r($data); 
//        echo '<pre>'; print_r($stock_detail); 
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/plugin/charts/g-chart/loader.js", "assets/js/pages/cm-chart.js");
        
        $data['content'] = "full-day/full-day-data";
        $this->load->view('index', $data);
        
    }
    
    function wholeDayDataByFilter(){
        
        $company_id = $this->input->get('company_id');
        $company_symbol = $this->input->get('company_symbol');
        $stock_date = $this->input->get('stock_date');
        
        $this->wholeDayData( $company_id, $company_symbol, $stock_date );
        
    }
   
}
