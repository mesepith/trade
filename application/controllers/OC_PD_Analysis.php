<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class OC_PD_Analysis extends MX_Controller {

    public function dayWisePdAnalysis( $live=false ) {
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $underlying_date = $this->input->get('date');
        
        $put_avg_decay = $this->input->get('put_avg_decay');
        $call_avg_decay = $this->input->get('call_avg_decay');
        
        $script_start_time = $this->input->get('script_start_time');
        
        if(empty($underlying_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $underlying_date;
        }
        
        $custom_condition = $this->input->get('custom_condition'); 
        
        
        $filter = array();
        
        $filter['date'] = $date;
        $filter['put_avg_decay'] = $put_avg_decay;
        $filter['call_avg_decay'] = $call_avg_decay;
        $filter['custom_condition'] = $custom_condition;
        
        $filter['script_start_time'] = $script_start_time;                
        
        $action_url = 'option-chain/pd-analysis/day-wise';
        
        if( $live ){                                   
            
            $script_time_arr = $this->getScriptStartTime( $date, $filter, $Send_Api_Contr );
            $date = $script_time_arr['date'];
            $script_start_time = $script_time_arr['script_start_time'];
            $script_start_time_result_arr = $script_time_arr['script_start_time_result_arr'];
            
            $action_url = 'option-chain/pd-analysis/day-wise-live';
                        
        }else{
            
            $script_start_time_result_arr =array();
        } 
        
        
        $oc_pd_data = $this->dayWisePdAnalysisProcess( $filter, $live, $script_start_time, $Send_Api_Contr );
        
        if(!empty($oc_pd_data) && $oc_pd_data > 0 ){
            
            $data['nestedData']['oc_pd_data'] = $oc_pd_data;
            
            $date = $oc_pd_data[0]->underlying_date_end;
            
        }
        
//        echo '<pre>'; print_r($oc_pd_data);
        
        $data['nestedData']['action_url'] = $action_url;
        
        $data['nestedData']['date'] = $date;
        
        $data['nestedData']['live'] = $live;
        $data['nestedData']['script_start_time'] = $script_start_time;
        $data['nestedData']['script_start_time_result_arr'] = $script_start_time_result_arr;
        
        $data['nestedData']['put_avg_decay'] = $put_avg_decay;
        $data['nestedData']['call_avg_decay'] = $call_avg_decay;
        
         $data['nestedData']['custom_condition'] = $custom_condition;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/css/pages/pd-analysis-day-wise.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/pd-analysis-day-wise.js");
        
        $data['content'] = "option-chain/pd-analysis/oc-pd-day-wise-analysis";
        $this->load->view('index', $data);
        
    }
    
    function getScriptStartTime( $date, $filter, $Send_Api_Contr=false ){        
        
//        $this->load->model('Oc_pd_disp_analysis_model');
//        $script_start_time_arr = $this->Oc_pd_disp_analysis_model->getScriptStartTime( $date );
        $script_start_time_arr = $Send_Api_Contr->getOcPDScriptStartTime( $date );
        
//        echo '<pre>'; print_r($script_start_time_arr);

        $script_start_time_result_arr = empty($script_start_time_arr->result) ? '' : $script_start_time_arr->result;            

        if( empty($script_start_time_arr)){ return; }

        $date = empty($script_start_time_arr->date) ? $date : $script_start_time_arr->date;

        if( empty($filter['script_start_time']) ){

            $total_script_start_time = count($script_start_time_arr->result);

            $script_start_time = empty($script_start_time_arr->result[0]->script_start_time) ? '' : $script_start_time_arr->result[$total_script_start_time-1]->script_start_time;

        }else{
            
            $script_start_time = $filter['script_start_time'];
        }
        
        $script_time_arr = array();
        
        $script_time_arr['date'] = $date;
        $script_time_arr['script_start_time'] = $script_start_time;
        $script_time_arr['script_start_time_result_arr'] = $script_start_time_result_arr;

        return $script_time_arr;
    }
    
    function dayWisePdAnalysisProcess( $filter, $live=false, $script_start_time=false, $Send_Api_Contr=false ){
        
        $date = $filter['date'];
        $put_avg_decay = $filter['put_avg_decay'];
        $call_avg_decay = $filter['call_avg_decay'];
        $custom_condition = $filter['custom_condition'];
        
//        $this->load->model('Oc_pd_disp_analysis_model');        
//        $oc_pd_data = $this->Oc_pd_disp_analysis_model->displayOCPDDayWiseData( $date, $put_avg_decay, $call_avg_decay, $custom_condition, $live, $script_start_time);
        $oc_pd_data = $Send_Api_Contr->displayOCPDDayWiseData( $date, $put_avg_decay, $call_avg_decay, $custom_condition, $live, $script_start_time );
        
        return $oc_pd_data;
    }
    
    function pdAnalysisCompanyList(){
        
        $this->load->model('Put_call_model');
        
        $company_list = $this->Put_call_model->displayOptionChainCompanyList( );
        
        $data['nestedData']['company_list'] = $company_list;
         
        $data['content'] = "option-chain/pd-analysis/oc-pd-company-list";
        $this->load->view('index', $data);
    }
    
    function getOCPDData($company_id, $company_symbol_encode, $manual_date=false, $manual_date_to=false, $live=false, $searching_expiry_date=false){
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        if(empty($manual_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $manual_date;
        }
        
//        $this->load->model('Oc_pd_disp_analysis_model');        
//        $oc_pd_data= $this->Oc_pd_disp_analysis_model->getOCPDData($company_id, $company_symbol, $date, $manual_date_to);
        $oc_pd_data= $Send_Api_Contr->getOCPDData( $company_id, $company_symbol, $date, $manual_date_to, $live, $searching_expiry_date );
        
        $data['nestedData']['oc_pd_data'] = $oc_pd_data;
        
//        echo '<pre>'; print_r($oc_pd_data);
        
        /* Extract Unique Expiry Dates Start */
        $expiry_date_arr = array_unique(array_map(function ($i) { return $i->expiry_date; }, $oc_pd_data)); /* Get unique expiry date */        
        usort($expiry_date_arr, "date_sort"); /* Sort by date */
        /* Extract Unique Expiry Dates End */
        
//        echo '<pre>'; print_r($expiry_date_arr);
        
        $underlying_date_end = '';
        if(!empty($oc_pd_data[0]->underlying_date_end)){
            
            $underlying_date_end = $oc_pd_data[0]->underlying_date_end;
        }
        
        $data['nestedData']['expiry_date_arr'] = $expiry_date_arr;
        $data['nestedData']['searching_expiry_date'] = $searching_expiry_date;
        $data['nestedData']['live'] = $live;
        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;
        $data['nestedData']['underlying_date_end'] = $underlying_date_end;
        $data['nestedData']['underlying_date_end_to'] = empty($manual_date_to) ? date('Y-m-d') :$manual_date_to;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css", "assets/css/pages/oc-iv-company-result.css");
        $data['nestedScript']['js'] = array("assets/plugin/charts/g-chart/loader.js", "assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/oc-pd-analysis.js");
        
        $data['content'] = "option-chain/pd-analysis/oc-pd-company-result";
        $this->load->view('index', $data);
        
    }
    
    function getOCPDDataFilter(){
        
        $company_id = $this->input->get('company_id');
        $company_symbol = $this->input->get('company_symbol');
        $underlying_date_end = $this->input->get('underlying_date_end');
        $underlying_date_end_to = $this->input->get('underlying_date_end_to');
        $live = $this->input->get('live');
        $expiry = $this->input->get('expiry');
        
        $this->getOCPDData($company_id, $company_symbol, $underlying_date_end, $underlying_date_end_to, $live, $expiry);
        
    }
    
    function getLiveOCPDDataOfStock( $company_id, $company_symbol, $live ){
     
        $this->getOCPDData( $company_id, $company_symbol, false, false, $live );
    }
    
}
