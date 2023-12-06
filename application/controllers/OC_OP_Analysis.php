<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class OC_OP_Analysis extends MX_Controller {

    public function dayWiseOpAnalysis(  ) {
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $underlying_date = $this->input->get('date');
                
        if(empty($underlying_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $underlying_date;
        }
        
        $custom_condition = $this->input->get('custom_condition');
        
//        $this->load->model('Oc_op_disp_analysis_model');        
//        $oc_op_data = $this->Oc_op_disp_analysis_model->displayOCOPDayWiseData( $date, $custom_condition );
        $oc_op_data = $Send_Api_Contr->displayOCOPDayWiseData( $date, $custom_condition );
        
//        echo '<pre>'; print_r($oc_op_data); exit;
        
        if(!empty($oc_op_data) && $oc_op_data > 0 ){
            
            $data['nestedData']['oc_op_data'] = $oc_op_data;
            
            $date = $oc_op_data[0]->underlying_date;
            
        }
        
        
        
        $data['nestedData']['date'] = $date;
        $data['nestedData']['custom_condition'] = $custom_condition;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/css/pages/oc-op-day-wise-analysis.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/op-analysis-day-wise.js");
        
        $data['content'] = "option-chain/op-analysis/oc-op-day-wise-analysis";
        $this->load->view('index', $data);
        
    }
    
}
