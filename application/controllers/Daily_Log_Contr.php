<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_Log_Contr extends MX_Controller {

    public function dailyLog($company_id, $company_symbol_encode, $manual_stock_date = false, $manual_stock_date_to = false) {
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
//        echo '$stock_date : ' . $stock_date;
//        
//        exit;

        $this->load->model('Stock_data_model');

        if (empty($manual_stock_date)) {

            $date = date('Y-m-d');
        } else {

            $date = $manual_stock_date;
        }

        $stock_detail = $this->Stock_data_model->getStockDetailByCompanyIdAndSymbol($company_id, $company_symbol, $date, $manual_stock_date_to);        
        
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        $data['nestedData']['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        
        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;
        
        $data['nestedData']['stock_detail'] = $stock_detail;
        $data['nestedData']['company_name'] = $stock_detail[0]->company_name;
        $data['nestedData']['stock_date'] = $stock_detail[0]->stock_date;
        $data['nestedData']['sector'] = $stock_detail[count($stock_detail)-1]->pd_sector_ind;
        $data['nestedData']['stock_date_to'] = empty($manual_stock_date_to) ? date('Y-m-d') :$manual_stock_date_to;
        
        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;
        
//        echo '<pre>'; print_r($stock_detail); exit;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/daily-stock-data.js", "assets/plugin/charts/g-chart/loader.js", "assets/js/pages/cm-chart.js");
        
        $data['content'] = "daily-log/daily-stock-data";
        $this->load->view('index', $data);
    }
    
    function dailyLogFilter(){
        
        $company_id = $this->input->get('company_id');
        $company_symbol = $this->input->get('company_symbol');
        $stock_date = $this->input->get('stock_date');
        $stock_date_to = $this->input->get('stock_date_to');
        
        $this->dailyLog( $company_id, $company_symbol, $stock_date, $stock_date_to );
    }
    
        /*
    * Data Calculate Quatarly, Monthly And Weekly wise
    */
    function stockClusterReturn( $company_id, $company_symbol_encode ){
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $this->load->model('Stock_data_model');
        
        $date = date('Y-m-d', strtotime("-12 month"));
        
        $stock_detail = $this->Stock_data_model->getStockDetailByCompanyIdAndSymbol($company_id, $company_symbol, $date, date('Y-m-d'));
        
        $avg_data['close_price'] = avgReturnCalc( $stock_detail, 'close_price', 'stock_date' );
        $avg_data['total_traded_value'] = avgReturnCalc( $stock_detail, 'total_traded_value', 'stock_date' );  
        $avg_data['total_traded_volume'] = avgReturnCalc( $stock_detail, 'total_traded_volume', 'stock_date' );  
        $avg_data['delivery_quantity'] = avgReturnCalc( $stock_detail, 'delivery_quantity', 'stock_date' );  
        $avg_data['delivery_to_traded_quantity'] = avgReturnCalc( $stock_detail, 'delivery_to_traded_quantity', 'stock_date' );  
        $avg_data['total_no_of_trades'] = avgReturnCalc( $stock_detail, 'total_no_of_trades', 'stock_date' );
        $avg_data['volume_by_total_no_of_trade'] = avgReturnCalc( $stock_detail, 'volume_by_total_no_of_trade', 'stock_date' );
        
        
//        echo '<pre>'; print_r($avg_data); exit;
//        $data['nestedData']['sector_data'] = $sector_data;
        $data['nestedData']['avg_data'] = $avg_data;
        $data['nestedData']['report_name'] = $company_symbol;
        $data['nestedData']['sector_id'] = $company_id;
        
        $data['nestedScript']['js'] = array("assets/plugin/charts/g-chart/loader.js", "assets/js/pages/average-chart.js");
        
        $data['content'] = "average/average-return";
        
        $this->load->view('index', $data); 
        
    }
    
    /*
     * Calc average of 3, 5, 8, 14, 20 days
     */
    function calcStocksAveragesByDays( $company_id, $company_symbol_encode ){
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $this->load->model('Stock_data_model');
        
        $tb_column = $this->input->get('tb_column');
        $data['nestedData']['tb_column'] = $tb_column;
        
        $date_period = empty($this->input->get('date_period')) ? date('Y-m-d', strtotime("-1 week")) : $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;
        
        $stock_detail = $this->Stock_data_model->getStockDetailByCompanyIdAndSymbol($company_id, $company_symbol, $date_period, date('Y-m-d'));
        
//        echo '<pre>'; print_r($stock_detail); exit;
        
        $calc_avg_by_arr = array(3 ,5, 8, 14, 20);        
        $tb_column_arr = array('close_price','vwap', 'total_traded_volume', 'delivery_quantity', 'total_traded_value', 'total_no_of_trades', 'volume_by_total_no_of_trade');
        
        $avg_data = calcAveragesByDays( $stock_detail, $calc_avg_by_arr, $tb_column_arr );
        
        $data['nestedData']['url'] = 'stock/average-by-days/' . $company_id . '/' .$company_symbol_encode ;
        
        $data['nestedData']['avg_data'] = $avg_data;
        $data['nestedData']['report_name'] = $company_symbol;
        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['tb_column_arr'] = $tb_column_arr;
        
        $data['nestedScript']['js'] = array("assets/js/pages/average-by-days.js", "assets/plugin/charts/g-chart/loader.js", "assets/js/pages/average-by-days-chart.js");
        
        $data['content'] = "average/average-by-days";
        
        $this->load->view('index', $data); 
    }
    
}
