<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nifty_Disp_Contr extends MX_Controller {
   
    /*
     * Display Nifty Heavy Weight Stock
     */
    function niftyHeavyStocks(){
        
        $this->load->model('Nifty_model'); 
        
        $market_date = $this->input->get('market_date');
        $weightage_sortby = $this->input->get('weightage_sortby');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }
        
        $nifty_db_return = $this->Nifty_model->fetchNiftyHeavyStock($market_date, $weightage_sortby);
        
        $nifty_heavy_stocks = array();
        
        foreach( $nifty_db_return AS $nifty_db_return_key=>$nifty_db_return_val ){
            
            $nifty_heavy_stocks[$nifty_db_return_val->company_symbol .'#*'. $nifty_db_return_val->company_id][$nifty_db_return_val->market_date] = $nifty_db_return_val->weightage;
//            $nifty_heavy_stocks[$nifty_db_return_val->company_symbol]['market_date'][] = $nifty_db_return_val->market_date;
        }
        
//        echo '<pre>'; print_r($nifty_heavy_stocks);  exit;
        
        $data['nestedData']['market_date'] = empty($nifty_heavy_stocks[0]->market_date) ? ( empty($market_date) ? date('Y-m-d') : $market_date ) : $nifty_heavy_stocks[0]->market_date; 
        
        $data['nestedData']['nifty_heavy_stocks'] = $nifty_heavy_stocks;
        $data['nestedData']['weightage_sortby'] = $weightage_sortby;
        
        $data['nestedData']['url'] = 'nifty-heavy-weight-stocks';
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/nifty/heavy-weight.js"); 
        
        $data['content'] = "nifty/heavy-weight";
        $this->load->view('index', $data);
    }
    
}
