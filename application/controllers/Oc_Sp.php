<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

/*
 * Option chain data by strike price
 */
class Oc_Sp extends MX_Controller {

    public function getStrikePriceLog( $company_id, $company_symbol_encode, $searching_underlying_date, $searching_expiry_date, $strike_price, $live=0 ) {

        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $oc_data = $Send_Api_Contr->getOcSpData( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date, $strike_price, $live );  
        
//        echo '<pre>'; print_r($oc_data);
        
        $other_info['company_id'] = $company_id;
        $other_info['company_symbol'] = $company_symbol;
        
        $other_info['searching_underlying_date'] = $searching_underlying_date;
        $other_info['searching_expiry_date'] = $searching_expiry_date;
        $other_info['strike_price'] = $strike_price;
        
        $data['nestedData']['oc_data'] = $oc_data;
        $data['nestedData']['other_info'] = $other_info;
        
        $data['nestedData']['live'] = $live;
        
        $data['nestedScript']['js'] = array("assets/plugin/charts/g-chart/loader.js" ,"assets/js/pages/option/sp-chart.js");
        
        $data['content'] = "option-chain/sp/oc-sp-log";
        $this->load->view('index', $data);
        
    }
    
   
}
