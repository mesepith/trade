<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MX_Controller {

    public function displayAllNseData( $filter=false ) {

        
        $data = $this->getNseData( $filter );
        
//        echo '<pre>';
//        print_r($data); exit;

        $data['nestedStyle']['css'] = array("assets/css/pages/home.css");
        $data['nestedScript']['js'] = array("assets/js/pages/home.js");
        
        $data['content'] = "home/home-list-companies-stock";
        $this->load->view('index', $data);
//        exit;
    }
    
    function getNseData( $filter ){
        
        $this->load->model('Companies_model');
        $this->load->model('Stock_data_model');

        $company_list = $this->Companies_model->listAllCompanies();
//        echo '<pre>';
//        print_r($companyList);
        $company_arr = array();
        
        $stock_row_count = array(); 
        
        $date_interval_limit = 0;
        
        if(empty($filter)){
            
            $date_interval_limit = $this->Stock_data_model->getIntervalLimitofDatesForNonFilter();
//            echo '$date_interval_limit : ' . $date_interval_limit; 
//            exit;
        }
        
        foreach ($company_list AS $company_list_key => $company_list_value) {
            
            $stock_data = $this->Stock_data_model->stockDataByCompanySymbol($company_list_value->symbol, $filter, $date_interval_limit);
            
            $are_all_good_stock='yes';
            if($stock_data){
                
                $stock_row_count[$company_list_key] = count($stock_data);
            
                $count = count($stock_data);
                foreach ($stock_data AS $stock_data_key => $stock_data_value) {


                    $company_arr[$company_list_value->symbol][$count]['close_price'] = $stock_data_value->close_price;
                    $company_arr[$company_list_value->symbol][$count]['price_change_in_p'] = $stock_data_value->price_change_in_p;
                    $company_arr[$company_list_value->symbol][$count]['stock_date'] = date('d-M-Y', strtotime($stock_data_value->stock_date));
                    $company_arr[$company_list_value->symbol][$count]['total_traded_value'] = $stock_data_value->total_traded_value;
                    $company_arr[$company_list_value->symbol][$count]['total_traded_volume'] = $stock_data_value->total_traded_volume;
                    $company_arr[$company_list_value->symbol][$count]['delivery_quantity'] = $stock_data_value->delivery_quantity;
                    $company_arr[$company_list_value->symbol][$count]['delivery_to_traded_quantity'] = $stock_data_value->delivery_to_traded_quantity;

                    $is_price_increase = 'NA';
                    $is_delivery_to_traded_quantity_increase = 'NA';

                    if(!empty($company_arr[$company_list_value->symbol][$count+1]['close_price'])){

                        $is_price_increase = ( $company_arr[$company_list_value->symbol][$count]['close_price'] > $company_arr[$company_list_value->symbol][$count+1]['close_price']) ? 'yes' : 'no';
                        $is_delivery_to_traded_quantity_increase = ( $company_arr[$company_list_value->symbol][$count]['delivery_to_traded_quantity'] > $company_arr[$company_list_value->symbol][$count+1]['delivery_to_traded_quantity']) ? 'yes' : 'no';

                    }
                    
                    if($is_price_increase==="no" || $is_delivery_to_traded_quantity_increase==="no" || $company_arr[$company_list_value->symbol][$count]['close_price'] == 0 ){
                        
                        $are_all_good_stock = "no";
                        
                    }

                    $company_arr[$company_list_value->symbol][$count]['is_price_increase'] = $is_price_increase;
                    $company_arr[$company_list_value->symbol][$count]['is_delivery_to_traded_quantity_increase'] = $is_delivery_to_traded_quantity_increase;

                    $count--;
//                    break;
                }
                
                $company_arr[$company_list_value->symbol]['are_all_good_stock'] = $are_all_good_stock;
                $company_arr[$company_list_value->symbol]['company_id'] = $company_list_value->id;
            
            }
            
            
        }

        $check_most_occured_no = array_count_values($stock_row_count); 
        $most_occured_no = array_search(max($check_most_occured_no), $check_most_occured_no);
        
//        echo '$most_occured_no : ' . $most_occured_no;
//        echo '<br/>';
//        exit;
        
        /*
         * Unset those stocks which does not have required number of rows
         */
        foreach( $company_arr AS $company_arr_key=> $company_arr_value){
            
//            echo '$company_arr_key ' . $company_arr_key;
//            echo '<br/>';
//            echo 'count($company_arr[$company_arr_key]) ' . count($company_arr[$company_arr_key]);
//            echo '<br/>';
            
            if (count($company_arr[$company_arr_key]) <= ($most_occured_no+1)) {
                unset($company_arr[$company_arr_key]);
            }
        }
        
//        echo '<pre>';
//        print_r($company_arr); exit;
        /*
         * Get first company name to extract from date and to date
         */
        foreach ($company_arr as $company_arr_key => $company_arr_value){
            
            $first_company_symbol = $company_arr_key;
            break;
        }
        
        $stock_date_list_arr = array();
        for($i=1; $i<=$most_occured_no; $i++){
            
            $stock_date_list_arr[$i] = $company_arr[$first_company_symbol][$i]['stock_date'];
            
        }
        
//        echo '<pre>';
//        print_r($stock_date_list_arr); exit;
        
        $from_date = $company_arr[$first_company_symbol][$most_occured_no]['stock_date'];
        $to_date = $company_arr[$first_company_symbol][1]['stock_date'];
        
//        echo '$most_occured_no : ' . $most_occured_no;
//        echo '<pre>';
//        print_r($company_arr); exit;
//        echo '<pre>';
//        print_r($stock_date_list_arr); exit;
//        
//        echo '<pre>';
//        print_r($filter); 
        
        if(!empty($filter['delivery_to_traded_quantity_date']) && !empty($filter['delivery_to_traded_quantity_min']) && !empty($filter['delivery_to_traded_quantity_max']) ){            
            
            $range_key = 'delivery_to_traded_quantity';
            $company_arr = $this->filterByRangeMethod( $company_arr, $filter['delivery_to_traded_quantity_date'], $filter['delivery_to_traded_quantity_min'], $filter['delivery_to_traded_quantity_max'], $stock_date_list_arr, $range_key);
            
        }        
        if(!empty($filter['total_traded_volume_date']) && !empty($filter['total_traded_volume_min']) && !empty($filter['total_traded_volume_max']) ){            
            
            $range_key = 'total_traded_volume';
            $company_arr = $this->filterByRangeMethod( $company_arr, $filter['total_traded_volume_date'], $filter['total_traded_volume_min'], $filter['total_traded_volume_max'], $stock_date_list_arr, $range_key);
            
        }        
        if(!empty($filter['delivery_quantity_date']) && !empty($filter['delivery_quantity_min']) && !empty($filter['delivery_quantity_max']) ){            
            
            $range_key = 'delivery_quantity';
            $company_arr = $this->filterByRangeMethod( $company_arr, $filter['delivery_quantity_date'], $filter['delivery_quantity_min'], $filter['delivery_quantity_max'], $stock_date_list_arr, $range_key);
            
        }        
        
        /*If user is applied sorting by total_traded_value*/
        if(!empty($filter['total_traded_value']) && !empty($filter['sort_date']) ){
            
            $sort_by_key = 'total_traded_value';
            $company_arr = $this->sortByMethod( $company_arr, $sort_by_key, $filter['total_traded_value'], $filter['sort_date'], $stock_date_list_arr );
        }
        
        /*If user is applied sorting by total_traded_volume*/
        if(!empty($filter['total_traded_volume']) && !empty($filter['sort_date']) ){
            
            $sort_by_key = 'total_traded_volume';
            $company_arr = $this->sortByMethod( $company_arr, $sort_by_key, $filter['total_traded_volume'], $filter['sort_date'], $stock_date_list_arr );
        }
        /*If user is applied sorting by delivery_quantity*/
        if(!empty($filter['delivery_quantity']) && !empty($filter['sort_date']) ){
            
            $sort_by_key = 'delivery_quantity';
            $company_arr = $this->sortByMethod( $company_arr, $sort_by_key, $filter['delivery_quantity'], $filter['sort_date'], $stock_date_list_arr );
        }
        /*If user is applied sorting by delivery_to_traded_quantity*/
        if(!empty($filter['delivery_to_traded_quantity']) && !empty($filter['sort_date']) ){
            
            $sort_by_key = 'delivery_to_traded_quantity';
            $company_arr = $this->sortByMethod( $company_arr, $sort_by_key, $filter['delivery_to_traded_quantity'], $filter['sort_date'], $stock_date_list_arr );
        }
        
//        exit;
        $data['nestedData']['from_date'] = date('Y-m-d', strtotime($from_date));
        $data['nestedData']['to_date'] = date('Y-m-d', strtotime($to_date));
        $data['nestedData']['most_occured_no'] = $most_occured_no;
        $data['nestedData']['stock_date_list_arr'] = $stock_date_list_arr;
        $data['nestedData']['filter'] = $filter;
        $data['nestedData']['company_arr'] = $company_arr;
        
        return $data;
    }

    public function stockFilter(){
        
//        echo '<pre>'; print_r($this->input->get()); 
//        exit;
        
        $this->displayAllNseData( $this->input->get() );
    }
    
    function filterByRangeMethod( $company_arr, $delivery_to_traded_quantity_date, $delivery_to_traded_quantity_min, $delivery_to_traded_quantity_max, $stock_date_list_arr, $range_key){
          
//        echo '$delivery_to_traded_quantity_date : ' . $delivery_to_traded_quantity_date . '<br/>';
//        echo '$delivery_to_traded_quantity_min : ' . $delivery_to_traded_quantity_min . '<br/>';
//        echo '$delivery_to_traded_quantity_max : ' . $delivery_to_traded_quantity_max . '<br/>';
//        echo '<pre>';
//        print_r($stock_date_list_arr); 
       
        
        $stock_key_find = array_search ($delivery_to_traded_quantity_date, $stock_date_list_arr);
        
        $stock_key = (!empty($stock_key_find)) ? $stock_key_find : 1;
        
//        echo '$stock_key : ' . $stock_key . '<br/>';
        
        foreach( $company_arr AS $company_arr_key=> $company_arr_value){            
//            echo '$company_arr_key : ' . $company_arr_key . '<br/>';
            if( !empty($company_arr_value[$stock_key][$range_key]) && $company_arr_value[$stock_key][$range_key] >=$delivery_to_traded_quantity_min && $company_arr_value[$stock_key][$range_key] <= $delivery_to_traded_quantity_max ){                
            }else{
                
                unset($company_arr[$company_arr_key]);
                
            }
            
        }
        
//         echo '<pre>';
//        print_r($company_arr); 
        
        return $company_arr;

    }
    
    function sortByMethod( $company_arr, $sort_by_key, $sort_by_order, $sort_date, $stock_date_list_arr ){
        
        $total_traded_volume_arr = array();
        
        $stock_key_find = array_search ($sort_date, $stock_date_list_arr);
        
        $stock_key = (!empty($stock_key_find)) ? $stock_key_find : 1;
        
        foreach( $company_arr AS $company_arr_key=> $company_arr_value){
//            echo 'company_arr_key:: ' . $company_arr_key . ' :: ' . $company_arr_value[$stock_key][$sort_by_key] . '<br/>'; 
            if( !empty($company_arr_value[$stock_key][$sort_by_key]) ){
                
                $total_traded_volume_arr[$company_arr_key] = $company_arr_value[$stock_key][$sort_by_key];
            }
            
        }
        
        if( $sort_by_order == 'high' ){
            
            /* Sort by Sort Array (Descending Order)*/
            arsort($total_traded_volume_arr);
        }else if( $sort_by_order == 'low' ){
            
            asort($total_traded_volume_arr);
        }
        
        
        
//        echo '<pre>';
//        print_r($total_traded_volume_arr);
        
        $company_sort_by_total_traded_volume_arr = array();
        
        foreach($total_traded_volume_arr AS $total_traded_volume_arr_key=>$total_traded_volume_arr_value){
            
            $company_sort_by_total_traded_volume_arr[$total_traded_volume_arr_key] = $company_arr[$total_traded_volume_arr_key];
            
        }
        
        
//        echo '<pre>';
//        print_r($company_sort_by_total_traded_volume_arr);
        
        return $company_sort_by_total_traded_volume_arr;
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: List of Stock companies 
     */
    
    public function companyListForTimeWiseLog(){
        
        $this->load->model('Companies_model');

        $company_list = $this->Companies_model->listAllCompanies();
        
//        echo '<pre>';
//        print_r($company_list);
        
        $data['nestedData']['company_list'] = $company_list;
        
        $data['content'] = "stock-list/stock-list";
        
        $this->load->view('index', $data);
        
    }
    /*
     * @author: ZAHIR
     * DESC: List of Stock companies
     */
    
    public function companyListForDailyLog(){
        
        $this->load->model('Companies_model');

        $company_list = $this->Companies_model->listAllCompanies();
        
//        echo '<pre>';
//        print_r($company_list);
        
        $data['nestedData']['company_list'] = $company_list;
        
        $data['content'] = "stock-list/stock-list-daywise";
        
        $this->load->view('index', $data);
        
    }
    
}
