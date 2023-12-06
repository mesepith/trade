<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Year_High_Low_Contr extends MX_Controller {

    public function viewYearHighLow( $high_or_low ) {
        
        $this->load->model('Year_high_low_model');
        
        $market_date = $this->input->get('market_date');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }                
        
        $year_high_or_low_data = $this->Year_high_low_model->dispYearHighLowData($market_date, $high_or_low);
        
//        echo '<pre>'; print_r($year_high_or_low_data);   exit;     
                        
        $data['nestedData']['market_date'] = empty($year_high_or_low_data[0]->market_date) ? date('Y-m-d') : $year_high_or_low_data[0]->market_date;
        
        $data['nestedData']['year_high_or_low_data'] = $year_high_or_low_data;
        $data['nestedData']['high_or_low'] = $high_or_low;
        
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/year-high-low/high-low-view.js");
        
        $data['content'] = "year-high-low/high-low-view";
        $this->load->view('index', $data);
        
    }
    
    function compareCurrentPriceDayWise(){
        
        include_once (dirname(__FILE__) . "/Send_Api_Contr.php");
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Stock_data_model');
//        $this->load->model('Put_call_model');
        
        $market_date = $this->input->get('market_date');
        
        $close_price_cp_with_low = $this->input->get('close_price_cp_with_low');
        $close_price_cp_with_high = $this->input->get('close_price_cp_with_high');
        
        $year_week_low_date_order = $this->input->get('year_week_low_date_order');
        $year_week_high_date_order = $this->input->get('year_week_high_date_order');
                
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }
        
        $stk_data = $this->Stock_data_model->compareCurrentPriceDayWise($market_date, $year_week_low_date_order, $year_week_high_date_order);
        
//        echo '<pre>'; print_r((array) $stk_data);   exit;  
        
        if(!empty($stk_data) && count($stk_data)> 0 ){
            
            foreach( $stk_data AS $stk_data_key=>$stk_data_val ){
                
                $stk_data_val = (array) $stk_data_val;
                
                $data_arr[$stk_data_key] = $stk_data_val;
                
//                echo '<pre>'; print_r($stk_data_val);   exit;  
                
                $close_price = $stk_data_val['close_price'];
                $year_week_low = $stk_data_val['year_week_low'];
                $year_week_high = $stk_data_val['year_week_high'];
                        
//                $close_price_diff_with_low1 = abs(((($year_week_low - $close_price) / ($year_week_low)) * 100));
                $close_price_diff_with_low2 = abs(((($close_price - $year_week_low) / ($close_price)) * 100));
//                $close_price_diff_with_high1 = abs(((($year_week_high - $close_price) / ($year_week_high)) * 100));
                $close_price_diff_with_high2 = abs(((($close_price - $year_week_high) / ($close_price)) * 100));
                
                $data_arr[$stk_data_key]['close_price_diff_with_low_percent'] = $close_price_diff_with_low2;
                $data_arr[$stk_data_key]['close_price_diff_with_high_percent'] = $close_price_diff_with_high2;
                
//                $data_arr[$stk_data_key]['pc_exists'] = $this->Put_call_model->checkCompanyExistInPCByIdAndSymbol($stk_data_val['company_id'], $stk_data_val['company_symbol']);
                $data_arr[$stk_data_key]['pc_exists'] = $Send_Api_Contr->checkCompanyExistInPCByIdAndSymbol($stk_data_val['company_id'], $stk_data_val['company_symbol']);
                
            }
            
//            echo '<pre>'; print_r($data_arr); exit;
            
            
                
//            echo '<pre>'; print_r($data_arr); exit;
            
            if( !empty($close_price_cp_with_low)){
                
                if($close_price_cp_with_low == 'low'){
                    
                    $data_arr = $this->sort_by_cp_low_percent('close_price_diff_with_low_percent', $data_arr, 'asc');
                  
                }else if($close_price_cp_with_low == 'high'){
                    
                    $data_arr = $this->sort_by_cp_low_percent('close_price_diff_with_low_percent', $data_arr, 'desc');
                }
                
            }
            if( !empty($close_price_cp_with_high)){
                
                if($close_price_cp_with_high == 'low'){
                    
                    $data_arr = $this->sort_by_cp_high_percent('close_price_diff_with_high_percent', $data_arr, 'asc');
                  
                }else if($close_price_cp_with_high == 'high'){
                    
                    $data_arr = $this->sort_by_cp_high_percent('close_price_diff_with_high_percent', $data_arr, 'desc');
                }
                
            }
            
//            echo '<pre>'; print_r($data_arr); exit;
            
//            echo '<br/>';
//            
//            echo '#################### zah';
//            
//            echo '<br/>';
//            
//            echo '<pre>'; print_r($data_arr); 
//            
//            exit;
            
            $data['nestedData']['market_date'] = empty($stk_data[0]->stock_date) ? date('Y-m-d') : $stk_data[0]->stock_date;
            
        }else{
            
            $data['nestedData']['market_date'] = date('Y-m-d');
        }
        
        
        $data['nestedData']['year_high_or_low_data'] = $data_arr;
        $data['nestedData']['close_price_cp_with_low'] = $close_price_cp_with_low;
        $data['nestedData']['close_price_cp_with_high'] = $close_price_cp_with_high;
        $data['nestedData']['year_week_low_date_order'] = $year_week_low_date_order;
        $data['nestedData']['year_week_high_date_order'] = $year_week_high_date_order;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/year-high-low/high-low-compare-cp.js");
        
        $data['content'] = "year-high-low/high-low-compare-cp";
        $this->load->view('index', $data);
        
    }

    function sort_by_cp_low_percent( $sort_by, $data_arr, $order_by ){
        
        if( $order_by==='asc'){
        
            usort($data_arr, function($a, $b) {
            $a = $a['close_price_diff_with_low_percent'];
            $b = $b['close_price_diff_with_low_percent'];
            if ($a == $b) { return 0; }
              return ($a < $b) ? -1 : 1;
            });

            return $data_arr;
        
        }else if( $order_by==='desc'){
        
            usort($data_arr, function($a, $b) {
                if($a['close_price_diff_with_low_percent']==$b['close_price_diff_with_low_percent']) return 0;
                return $a['close_price_diff_with_low_percent'] < $b['close_price_diff_with_low_percent']?1:-1;
            });

            return $data_arr;
        
        }
    }

    function sort_by_cp_high_percent( $sort_by, $data_arr, $order_by ){
        
        if( $order_by=='asc'){
        
            usort($data_arr, function($a, $b) {
            $a = $a['close_price_diff_with_high_percent'];
            $b = $b['close_price_diff_with_high_percent'];
            if ($a == $b) { return 0; }
              return ($a < $b) ? -1 : 1;
            });

            return $data_arr;
        
        }else if( $order_by=='desc'){
        
            usort($data_arr, function($a, $b) {
                if($a['close_price_diff_with_high_percent']==$b['close_price_diff_with_high_percent']) return 0;
                return $a['close_price_diff_with_high_percent'] < $b['close_price_diff_with_high_percent']?1:-1;
            });

            return $data_arr;
        
        }
    }

}
