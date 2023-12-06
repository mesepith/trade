<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/OC_Analysis.php");

include_once (dirname(__FILE__) . "/OC_PD_Analysis.php");

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class OC_Comn_Contr extends MX_Controller {
    
    /*
     * Iv Pd common data find
     */
    function IvPdCommonAnalysisTimeWise($bull_or_bear){                
        
        $iv_analysis_controller = new OC_Analysis();                
        
        $pd_analysis_controller = new OC_PD_Analysis();
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        /* Get data Start */
        
        $date = empty($this->input->get('sud')) ? date('Y-m-d') : $this->input->get('sud');
        $search_script_start_time = empty($this->input->get('sst')) ? '' : $this->input->get('sst');
        
        /* Get data End */
        
        /* Iv Raw data Start */
        
        $iv_filter['script_start_time']= '';
        
        $iv_script_time_arr = $iv_analysis_controller->getScriptStartTime( $date, $iv_filter, $Send_Api_Contr);
        
        $data['nestedData']['script_start_time_arr'] = empty($iv_script_time_arr['script_start_time_result_arr']) ? '' : $iv_script_time_arr['script_start_time_result_arr'];
        
//        echo '<pre>'; print_r($iv_script_time_arr); exit;
        
//        $iv_script_start_time = $iv_script_time_arr['script_start_time'];        
        $iv_script_start_time = empty($search_script_start_time) ? $iv_script_time_arr['script_start_time'] : $search_script_start_time;        
                
        /* Iv Raw data End */
        
        /* PD Raw data Start */
        
        $pd_filter['script_start_time']= '';
        
//        $pd_script_time_arr = $pd_analysis_controller->getScriptStartTime( $date, $pd_filter);
        
//        $pd_script_start_time = $pd_script_time_arr['script_start_time'];
        $pd_script_start_time = $iv_script_start_time;  
        
        /* PD Raw data End */
        
        $live = 'live';
        
        if( $bull_or_bear == 'bull' ){
         
            $oc_iv_data = $this->ocIvDataAnalysisBull( $date, $live, $iv_script_start_time, $iv_analysis_controller, $Send_Api_Contr );    
            
            $oc_pd_data = $this->ocPdAnalysisBull( $date, $live, $pd_script_start_time, $pd_analysis_controller, $Send_Api_Contr );
            
            
        }else if( $bull_or_bear == 'bear' ){
            
            $oc_iv_data = $this->ocIvDataAnalysisBear( $date, $live, $iv_script_start_time, $iv_analysis_controller, $Send_Api_Contr );                        
            
            $oc_pd_data = $this->ocPdAnalysisBear( $date, $live, $pd_script_start_time, $pd_analysis_controller, $Send_Api_Contr );
                        
        }   
        
        if( empty($oc_iv_data) && empty($oc_pd_data) ){
            
            $common_company = '';
            
        }else{
        
            $common_company = $this->getIVPDCommonCompany( $oc_iv_data, $oc_pd_data );
        
        }
        
        $data['nestedData']['bull_or_bear'] = $bull_or_bear;
        $data['nestedData']['date'] = $date;
        $data['nestedData']['time'] = $pd_script_start_time;
        $data['nestedData']['oc_pd_iv_data'] = $common_company;
        
        $data['content'] = "option-chain/common/pd-iv-common";
        $this->load->view('index', $data);
        
        
    }
    
    function ocIvDataAnalysisBull( $date, $live, $iv_script_start_time, $iv_analysis_controller, $Send_Api_Contr ){
        
        $filter['date'] = $date;
        
        $filter['bullish_probability'] = 'high';
        $filter['bearish_probability'] = '';
        $filter['bullish_probability_min'] = 0;
        $filter['bullish_probability_max'] = 100;
        $filter['bearish_probability_min'] = 0;
        $filter['bearish_probability_max'] = 100;
        $filter['custom_condition'] = 'bullgtbear';
        
        $oc_iv_bull_data = $iv_analysis_controller->dayWiseIvAnalysisProcess( $filter, $live, $iv_script_start_time, $Send_Api_Contr );
        
        return $oc_iv_bull_data; 
    }
    
    function ocPdAnalysisBull( $date, $live, $pd_script_start_time, $pd_analysis_controller, $Send_Api_Contr ){
        
        $filter['date'] = $date;
        $filter['put_avg_decay'] = '';
        $filter['call_avg_decay'] = 'high';
        $filter['custom_condition'] = 'callgtput';
        
        $oc_pd_bull_data = $pd_analysis_controller->dayWisePdAnalysisProcess( $filter, $live, $pd_script_start_time, $Send_Api_Contr);
        
        return $oc_pd_bull_data; 
        
    }        
    
    function ocIvDataAnalysisBear( $date, $live, $iv_script_start_time, $iv_analysis_controller, $Send_Api_Contr ){
        
        $filter['date'] = $date;
        
        $filter['bullish_probability'] = '';
        $filter['bearish_probability'] = 'high';
        $filter['bullish_probability_min'] = 0;
        $filter['bullish_probability_max'] = 100;
        $filter['bearish_probability_min'] = 0;
        $filter['bearish_probability_max'] = 100;
        $filter['custom_condition'] = 'beargtbull';
        
        $oc_iv_bull_data = $iv_analysis_controller->dayWiseIvAnalysisProcess( $filter, $live, $iv_script_start_time, $Send_Api_Contr );
        
        return $oc_iv_bull_data; 
    }
    
    function ocPdAnalysisBear( $date, $live, $pd_script_start_time, $pd_analysis_controller, $Send_Api_Contr ){
        
        $filter['date'] = $date;
        $filter['put_avg_decay'] = 'high';
        $filter['call_avg_decay'] = '';
        $filter['custom_condition'] = 'putgtcall';
        
        $oc_pd_bull_data = $pd_analysis_controller->dayWisePdAnalysisProcess( $filter, $live, $pd_script_start_time, $Send_Api_Contr);
        
        return $oc_pd_bull_data; 
    }
    
    function getIVPDCommonCompany( $oc_iv_data, $oc_pd_data ){
        
        $oc_iv_company_symbol_arr = array();
        
        foreach( $oc_iv_data AS $oc_iv_data_val ){
            
            $oc_iv_company_symbol_arr[] = $oc_iv_data_val->company_symbol;
        }
        
        $common_company = array();
        
        $count = 0;
        
        foreach( $oc_pd_data AS $oc_pd_data_val ){
            
            if (in_array($oc_pd_data_val->company_symbol, $oc_iv_company_symbol_arr)) {
                
                $count++;
                
                $common_company[$count]['company_id'] = $oc_pd_data_val->company_id;
                $common_company[$count]['company_symbol'] = $oc_pd_data_val->company_symbol;
                
            }
        }
        array_multisort( array_column( $common_company, 'company_symbol' ), SORT_ASC, SORT_NUMERIC, $common_company );
//        echo '<pre>'; print_r($common_company); exit;
        return $common_company;
    }
}
