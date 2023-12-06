<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Volatility_Contr extends MX_Controller {
    
    /*
     * View volatility day wise
     */
    public function viewDailyVolatility( ) {
        
        $this->load->model('Volatility_model');
        
        $market_date = $this->input->get('market_date');
        $daily_volatility_p = $this->input->get('daily_volatility_p');
        $only_derivative = $this->input->get('only_derivative');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }                
        
        $volatility_data = $this->Volatility_model->dispDailyVolatility($market_date, $daily_volatility_p ,$only_derivative);
        
//        echo '<pre>'; print_r($volatility_data);   exit;     
                        
        $data['nestedData']['market_date'] = empty($volatility_data[0]->market_date) ? date('Y-m-d') : $volatility_data[0]->market_date;
        
        $data['nestedData']['daily_volatility_p'] = $daily_volatility_p;
        $data['nestedData']['only_derivative'] = $only_derivative;
        $data['nestedData']['volatility_data'] = $volatility_data;
        
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/volatility/daily-volatility.js");
        
        $data['content'] = "volatility/daily-volatility";
        $this->load->view('index', $data);
        
    }
    
    /*
     * View volatility company wise
     */
    function viewDailyVolatilityCompany( $company_id, $company_symbol_encode ){
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $this->load->model('Volatility_model');
        
        $market_date = $this->input->get('market_date');
        
        $market_date_to = $this->input->get('market_date_to');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }  
        
        $volatility_data = $this->Volatility_model->dispVolatilityCompanyWise($company_id, $company_symbol ,$market_date, $market_date_to);
//        echo '<pre>'; print_r($volatility_data);   exit;
                
        $data['nestedData']['market_date'] = $market_date;        
        $data['nestedData']['market_date_to'] = $market_date_to;
        
        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;
        $data['nestedData']['derivative'] = empty($volatility_data[0]->derivative) ? 0 : $volatility_data[0]->derivative;;
        
        $data['nestedData']['volatility_data'] = $volatility_data;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/volatility/volatility-company-wise.js");
        
        $data['content'] = "volatility/company-wise-volatility";
        $this->load->view('index', $data);
        
    }
    
}
