<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class OC_Analysis extends MX_Controller {

    public function ivAnalysisCompanyList(  ) {
        
        $this->load->model('Put_call_model');
        
        $company_list = $this->Put_call_model->displayOptionChainCompanyList( );
        
//        echo '<pre>'; print_r($company_list); 
        
        $data['nestedData']['company_list'] = $company_list;
         
        $data['content'] = "option-chain/iv-analysis/oc-iv-company-list";
        $this->load->view('index', $data);
        
        
    }
    
    function getOCIVData($company_id, $company_symbol_encode, $manual_date=false, $manual_date_to=false, $live=false, $searching_expiry_date=false){
        
        $this->load->helper('function_helper');
        
        $company_symbol = base64_url_decode($company_symbol_encode);
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $this->load->model('Put_call_model');
        
//        $other_info = array();
        if(empty($manual_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $manual_date;
        }
        
        $data['nestedData']['company_name'] = $this->Put_call_model->getCompanyNameByIdAndSymbol( $company_id, $company_symbol );
        
        
        
//        $this->load->model('Oc_iv_analysis_model');        
//        $oc_iv_data= $this->Oc_iv_analysis_model->getOCIVData($company_id, $company_symbol, $date, $manual_date_to);
        $oc_iv_data= $Send_Api_Contr->getOCIVData( $company_id, $company_symbol, $date, $manual_date_to, $live, $searching_expiry_date );
        
        $data['nestedData']['oc_iv_data'] = $oc_iv_data;
        
        /* Extract Unique Expiry Dates Start */
        $expiry_date_arr = array_unique(array_map(function ($i) { return $i->expiry_date; }, $oc_iv_data)); /* Get unique expiry date */        
        usort($expiry_date_arr, "date_sort"); /* Sort by date */
        /* Extract Unique Expiry Dates End */
        
        $underlying_date = '';
        if(!empty($oc_iv_data[0]->underlying_date)){
            
            $underlying_date = $oc_iv_data[0]->underlying_date;
        }
        
        $data['nestedData']['expiry_date_arr'] = $expiry_date_arr;
        $data['nestedData']['searching_expiry_date'] = $searching_expiry_date;
        $data['nestedData']['live'] = $live;
        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;
        $data['nestedData']['underlying_date'] = $underlying_date;
        $data['nestedData']['underlying_date_to'] = empty($manual_date_to) ? date('Y-m-d') :$manual_date_to;
        
//        echo count($data['oc_iv_data']);
        
//        echo '<pre>'; print_r($oc_iv_data); exit;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css", "assets/css/pages/oc-iv-company-result.css");
        $data['nestedScript']['js'] = array("assets/plugin/charts/g-chart/loader.js", "assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/oc-iv-analysis.js");
        
        $data['content'] = "option-chain/iv-analysis/oc-iv-company-result";
        $this->load->view('index', $data);
        
//        echo 'za'; exit;
    }
    
    function getOCIVDataFilter(){
        
        $company_id = $this->input->get('company_id');
        $company_symbol = $this->input->get('company_symbol');
        $underlying_date = $this->input->get('underlying_date');
        $underlying_date_to = $this->input->get('underlying_date_to');
        $live = $this->input->get('live');
        $expiry = $this->input->get('expiry');
        
        $this->getOCIVData($company_id, $company_symbol, $underlying_date, $underlying_date_to, $live, $expiry);
        
    }
    
    
    public function dayWiseIvAnalysis($live=false) {
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $underlying_date = $this->input->get('date');
        $bullish_probability = $this->input->get('bullish_probability');
        $bearish_probability = $this->input->get('bearish_probability');
        
        $bullish_probability_min = $this->input->get('bullish_probability_min');
        $bullish_probability_max = $this->input->get('bullish_probability_max');
        
        $bearish_probability_min = $this->input->get('bearish_probability_min');
        $bearish_probability_max = $this->input->get('bearish_probability_max'); 
        
        $custom_condition = $this->input->get('custom_condition'); 
        
        $script_start_time = $this->input->get('script_start_time'); 
        
        $filter = array();        
        
        $filter['bullish_probability'] = $bullish_probability;
        $filter['bearish_probability'] = $bearish_probability;
        $filter['bullish_probability_min'] = $bullish_probability_min;
        $filter['bullish_probability_max'] = $bullish_probability_max;
        $filter['bearish_probability_min'] = $bearish_probability_min;
        $filter['bearish_probability_max'] = $bearish_probability_max;
        $filter['custom_condition'] = $custom_condition;
        
        $filter['script_start_time'] = $script_start_time;
        
        if(empty($underlying_date)){
            
            $date = date('Y-m-d');
            
        }else{
            
            $date = $underlying_date;
        }                                
        
        $action_url = 'option-chain/iv-analysis/day-wise';
        
        if( $live ){    
            
            $script_time_arr = $this->getScriptStartTime( $date, $filter, $Send_Api_Contr );
            $date = $script_time_arr['date'];
            $script_start_time = $script_time_arr['script_start_time'];
            $script_start_time_result_arr = $script_time_arr['script_start_time_result_arr'];            
            
            $action_url = 'option-chain/iv-analysis/day-wise-live';
        }else{
            
            $script_start_time_result_arr = array();
        }        
        
        $filter['date'] = $date;
        
        $oc_iv_data = $this->dayWiseIvAnalysisProcess( $filter, $live, $script_start_time, $Send_Api_Contr );
        
        
        if(!empty($oc_iv_data) && $oc_iv_data > 0 ){
            
            $data['nestedData']['oc_iv_data'] = $oc_iv_data;
            
            $date = $oc_iv_data[0]->underlying_date;
            
        }
                
        
        $data['nestedData']['action_url'] = $action_url;
        
        $data['nestedData']['date'] = $date;
        
        $data['nestedData']['live'] = $live;
        $data['nestedData']['script_start_time'] = $script_start_time;
        $data['nestedData']['script_start_time_result_arr'] = $script_start_time_result_arr;
        
        $data['nestedData']['bullish_probability'] = $bullish_probability;
        $data['nestedData']['bearish_probability'] = $bearish_probability;
        
        $data['nestedData']['bullish_probability_min'] = $bullish_probability_min;
        $data['nestedData']['bullish_probability_max'] = $bullish_probability_max;
        
        $data['nestedData']['bearish_probability_min'] = $bearish_probability_min;
        $data['nestedData']['bearish_probability_max'] = $bearish_probability_max;
        
        $data['nestedData']['custom_condition'] = $custom_condition;
//        echo '<pre>'; print_r($day_wise_data);
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/css/pages/iv-analysis-day-wise.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/iv-analysis-day-wise.js");
        
        $data['content'] = "option-chain/iv-analysis/oc-iv-day-wise-analysis";
        $this->load->view('index', $data);
    }
    
    function getScriptStartTime( $date, $filter, $Send_Api_Contr=false ){                
        
//        $this->load->model('Oc_iv_analysis_model');
//        $script_start_time_arr = $this->Oc_iv_analysis_model->getScriptStartTime( $date ); echo '<pre>'; print_r($script_start_time_arr); exit;
        $script_start_time_arr = $Send_Api_Contr->getOcIVScriptStartTime( $date );

//        $script_start_time_result_arr = empty($script_start_time_arr['result']) ? '' : $script_start_time_arr['result'];            
        $script_start_time_result_arr = empty($script_start_time_arr->result) ? '' : $script_start_time_arr->result;            

        if( empty($script_start_time_arr)){ return; }

        $date = empty($script_start_time_arr->date) ? $date : $script_start_time_arr->date;

        if( empty($filter['script_start_time']) ){

            $total_script_start_time = count($script_start_time_arr->result);

//            $script_start_time = empty($script_start_time_arr['result'][0]->script_start_time) ? '' : $script_start_time_arr['result'][$total_script_start_time-1]->script_start_time;
            
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
    
    function dayWiseIvAnalysisProcess( $filter, $live=false, $script_start_time=false, $Send_Api_Contr=false ){
        
        $date = $filter['date'];
        $bullish_probability = $filter['bullish_probability'];
        $bearish_probability = $filter['bearish_probability'];
        $bullish_probability_min = $filter['bullish_probability_min'];
        $bullish_probability_max = $filter['bullish_probability_max'];
        $bearish_probability_min = $filter['bearish_probability_min'];
        $bearish_probability_max = $filter['bearish_probability_max'];
        $custom_condition = $filter['custom_condition'];
        
//        $this->load->model('Oc_iv_analysis_model');                       
//        $oc_iv_data = $this->Oc_iv_analysis_model->displayOCIVDayWiseData( $date, $bullish_probability, $bearish_probability, $bullish_probability_min, $bullish_probability_max, $bearish_probability_min, $bearish_probability_max, $custom_condition, $live, $script_start_time );        
        $oc_iv_data = $Send_Api_Contr->displayOCIVDayWiseData( $date, $bullish_probability, $bearish_probability, $bullish_probability_min, $bullish_probability_max, $bearish_probability_min, $bearish_probability_max, $custom_condition, $live, $script_start_time );        
        
        return $oc_iv_data;
    }
    
    function getLiveOCIVDataOfStock( $company_id, $company_symbol, $live ){
     
        $this->getOCIVData( $company_id, $company_symbol, false, false, $live );
    }
    
}
