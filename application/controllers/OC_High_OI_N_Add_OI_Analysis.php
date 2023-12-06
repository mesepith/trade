<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class OC_High_OI_N_Add_OI_Analysis extends MX_Controller {

    public function dayWiseAnalysis(  ) {
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $underlying_date = $this->input->get('date');
                
        if(empty($underlying_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $underlying_date;
        }
        
        $custom_condition = $this->input->get('custom_condition');
        
//        $this->load->model('Oc_h_oi_n_h_addoi_disp_analysis_model');        
//        $oc_high_oi_n_add_oi_db_data = $this->Oc_h_oi_n_h_addoi_disp_analysis_model->displayHighOiNAddOiDayWiseData( $date );
        
        $oc_high_oi_n_add_oi_db_data = $Send_Api_Contr->displayHighOiNAddOiDayWiseData( $date );
        
//        echo '<pre>'; print_r($oc_high_oi_n_add_oi_db_data); exit;
        
        $oc_high_oi_n_add_oi_data = array();
        
        $total_expiry_count = 0;
        
        foreach( $oc_high_oi_n_add_oi_db_data AS $arrkey => $oc_high_oi_n_add_oi_db_data_val){
            
            if( $custom_condition === 'bull'){
                
                if(!empty($oc_high_oi_n_add_oi_db_data_val->strike_price_in_call) && $oc_high_oi_n_add_oi_db_data_val->strike_price_in_call< $oc_high_oi_n_add_oi_db_data_val->underlying_price ){
                    
                    $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['bull'] = 'no';
                    continue;
                }                                
                
                if(!empty($oc_high_oi_n_add_oi_db_data_val->strike_price_in_put) && $oc_high_oi_n_add_oi_db_data_val->strike_price_in_put< $oc_high_oi_n_add_oi_db_data_val->underlying_price){
                    
                    $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['bull'] = 'no';
                    continue;
                }
                
                
                
            }else if( $custom_condition === 'bear'){
                
                if(!empty($oc_high_oi_n_add_oi_db_data_val->strike_price_in_call) && $oc_high_oi_n_add_oi_db_data_val->strike_price_in_call> $oc_high_oi_n_add_oi_db_data_val->underlying_price){
                    
                    $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['bear'] = 'no';
                    continue;
                }
                if(!empty($oc_high_oi_n_add_oi_db_data_val->strike_price_in_put) && $oc_high_oi_n_add_oi_db_data_val->strike_price_in_put> $oc_high_oi_n_add_oi_db_data_val->underlying_price){
                    
                    $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['bear'] = 'no';
                    continue;
                }
            }
            
            $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['company_id'] = $oc_high_oi_n_add_oi_db_data_val->company_id;
            $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['company_symbol'] = $oc_high_oi_n_add_oi_db_data_val->company_symbol;
            $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['underlying_price'] = $oc_high_oi_n_add_oi_db_data_val->underlying_price;
            
            $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['data'][$arrkey]['expiry_date'] = $oc_high_oi_n_add_oi_db_data_val->expiry_date;
            $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['data'][$arrkey]['strike_price_in_call'] = $oc_high_oi_n_add_oi_db_data_val->strike_price_in_call;
            $oc_high_oi_n_add_oi_data[$oc_high_oi_n_add_oi_db_data_val->company_symbol]['data'][$arrkey]['strike_price_in_put'] = $oc_high_oi_n_add_oi_db_data_val->strike_price_in_put;
            
            
            if( $oc_high_oi_n_add_oi_db_data_val->company_symbol === 'NIFTY' ){
                
                $total_expiry_count++;
            }
                                    
        }
        
        $total_expiry_count = ($total_expiry_count==0) ? 3 : $total_expiry_count;
        
//        echo $total_expiry_count; exit;
//        echo count($oc_high_oi_n_add_oi_data);
        
        
        
        if(!empty($oc_high_oi_n_add_oi_data) && $oc_high_oi_n_add_oi_data > 0 ){
            
            
            if( $custom_condition === 'bull'){
                
                foreach($oc_high_oi_n_add_oi_data AS $symbol=>$oc_high_oi_n_add_oi_data_val){
                    
                    if( !empty($oc_high_oi_n_add_oi_data_val['bull']) && $oc_high_oi_n_add_oi_data_val['bull']==='no'){
                        unset($oc_high_oi_n_add_oi_data[$symbol]);
                    }
                }
                
            }else if( $custom_condition === 'bear'){
                
                foreach($oc_high_oi_n_add_oi_data AS $symbol=>$oc_high_oi_n_add_oi_data_val){
                    
                    if( !empty($oc_high_oi_n_add_oi_data_val['bear']) && $oc_high_oi_n_add_oi_data_val['bear']==='no'){
                        unset($oc_high_oi_n_add_oi_data[$symbol]);
                    }
                }
                
            }
            
            
//            echo '<pre>'; print_r($oc_high_oi_n_add_oi_data); 
//            exit;
            
            $data['nestedData']['oc_high_oi_n_add_oi_data'] = $oc_high_oi_n_add_oi_data;
            
            $date = $oc_high_oi_n_add_oi_db_data[0]->underlying_date;
            
        }
        
        
        
        $data['nestedData']['date'] = $date;
        $data['nestedData']['custom_condition'] = $custom_condition;
        $data['nestedData']['total_expiry_count'] = $total_expiry_count;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/css/pages/oc-highoi-n-add-oi.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/op-analysis-day-wise.js");
        
        $data['content'] = "option-chain/op-high-oi-analysis/oc-highoi-n-add-oi";
        $this->load->view('index', $data);
        
    }
    
}
