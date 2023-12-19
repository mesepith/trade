<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fii_Dii_Disp_Contr extends MX_Controller {

    public function displayTotalInvestment(  ) {
        
        $this->load->model('Fii_dii_model');
        
        $invest_date = $this->input->get('invest_date');
        $invest_date_to = $this->input->get('invest_date_to');
        
        if(empty($invest_date)){
            
            $invest_date = date('Y-m-d');
            
        }                
        
        $total_investment_data = $this->Fii_dii_model->totalInvestment($invest_date, $invest_date_to);
        
//        echo '<pre>'; print_r($total_investment_data);        
                        
        $data['nestedData']['invest_date'] = empty($total_investment_data[0]->investment_date) ? date('Y-m-d') : $total_investment_data[0]->investment_date;
        
        $total_investment_data_arr = array();
        
        if( !empty($total_investment_data) ){
            
            foreach( $total_investment_data AS $total_investment_data_value ){
                
//                $total_investment[$total_investment_data_value->investment_date]['investor_type'] = $total_investment_data_value->investor_type;
                $total_investment_data_arr[$total_investment_data_value->investment_date][$total_investment_data_value->investor_type]['buy_value'] = $total_investment_data_value->buy_value;
                $total_investment_data_arr[$total_investment_data_value->investment_date][$total_investment_data_value->investor_type]['sell_value'] = $total_investment_data_value->sell_value;
                $total_investment_data_arr[$total_investment_data_value->investment_date][$total_investment_data_value->investor_type]['net_value'] = $total_investment_data_value->net_value;
                
            }
        }
        
//        echo '<pre>'; print_r($total_investment_data_arr);        
        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;
        
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        $data['nestedData']['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        
        $data['nestedData']['total_investment_data_arr'] = $total_investment_data_arr;
        
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/fii-dii/total-invest.js", "assets/plugin/charts/g-chart/loader.js", "assets/js/pages/fii-dii/fii-invest-chart.js");
        
        $data['content'] = "fii-dii/total-invest";
        $this->load->view('index', $data);
        
    }
    
    function displayFiiDerivative(){
        
        $this->load->model('Fii_dii_model');
        
        $market_date = $this->input->get('market_date');
        $market_date_to = $this->input->get('market_date_to');
        $source = $this->input->get('source');
        $product = $this->input->get('product');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }
        
        $fii_derivative_data = $this->Fii_dii_model->fetchFiiDerivative($market_date, $market_date_to, $source, $product);
        
//        echo '<pre>'; print_r($fii_derivative_data);  exit; 
        
        $data['nestedData']['market_date'] = empty($fii_derivative_data[0]->reporting_date) ? date('Y-m-d') : $fii_derivative_data[0]->reporting_date;               
        $data['nestedData']['market_date_to'] = empty($market_date_to) ? date('Y-m-d') : $market_date_to;         
        $data['nestedData']['source'] = $source;
        $data['nestedData']['product'] = $product;
        
        $data['nestedData']['fii_derivative_data'] = $fii_derivative_data;
        
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/fii-dii/fii-derivative.js");
        
        $data['content'] = "fii-dii/fii-derivative";
        $this->load->view('index', $data);
        
    }
    
    function displayFiiSectorInvest(){
        
        $this->load->model('Fii_dii_model');
        
        
        
        $market_date = $this->input->get('market_date');
        $market_date_to = $this->input->get('market_date_to');
        $sector = $this->input->get('sector');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            $fii_investing_sectors = $this->Fii_dii_model->fiiInvestingsectoList();
            
        }else{
            $fii_investing_sectors = $this->Fii_dii_model->fiiInvestingsectoListDateWise($market_date, $market_date_to);
        }
        
        $fii_sector_data = $this->Fii_dii_model->fetchFiiSectorData($market_date, $market_date_to, $sector);
                     
        $data['nestedData']['market_date'] = empty($fii_sector_data[0]->report_date) ? ( empty($market_date) ? date('Y-m-d') : $market_date ) : $fii_sector_data[0]->report_date;    
        
        $data['nestedData']['market_date_to'] = empty($market_date_to) ? date('Y-m-d') : $market_date_to;  
        $data['nestedData']['sector'] = empty($sector) ? '' : $sector;  
        
        $data['nestedData']['fii_investing_sectors'] = $fii_investing_sectors;
        $data['nestedData']['fii_sector_data'] = $fii_sector_data;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/fii-dii/fii-sector.js");
        
        $data['content'] = "fii-dii/fii-sector";
        $this->load->view('index', $data);
    }
    
    
    function dispExchangeClearMembr(){        
        
        $this->load->model('Fii_dii_model');
        
        $market_date = $this->input->get('market_date');
        $market_date_to = $this->input->get('market_date_to');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }   
        
        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;
        
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        $data['nestedData']['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        
        $enable_to_date_chkbox = $this->input->get('enable_to_date_chkbox');
        $data['nestedData']['enable_to_date_chkbox'] = (empty($enable_to_date_chkbox)) ? 'no' : $enable_to_date_chkbox;
        
        $exchng_top_clr_membr = $this->Fii_dii_model->fetchExchangeClearMembr($market_date, $market_date_to, $enable_to_date_chkbox);
        
//        echo '<pre>'; print_r($exchng_top_clr_membr);  exit;
        
        $data['nestedData']['exchng_top_clr_membr'] = $exchng_top_clr_membr;
        
        $data['nestedData']['market_date'] = empty($exchng_top_clr_membr[0]->market_date) ? ( empty($market_date) ? date('Y-m-d') : $market_date ) : $exchng_top_clr_membr[0]->market_date;               
        $data['nestedData']['market_date_to'] = empty($market_date_to) ? date('Y-m-d') : $market_date_to; 
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/fii-dii/exchng-clr-membr.js");
        
        if( $enable_to_date_chkbox ==='yes' ){
            
            $data['nestedScript']['js'][] = "assets/plugin/charts/g-chart/loader.js";
            $data['nestedScript']['js'][] = "assets/js/pages/fii-dii/exchng-clr-membr-chart.js";
        }
        
        $data['content'] = "fii-dii/exchng-clr-membr";
        $this->load->view('index', $data);
    }

}
