<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Home.php");

class Analysis_Controller extends MX_Controller {
    
    /*
     * @author: ZAHIR
     * DESC: Get Best Stock by home analysis
     */
    
    function getBestStockByHomeAnalysis(){
        
        $home_controller = new Home();
        
        $data = $home_controller->getNseData(false);
        
        /*Send mail only if today data is available otherwise exit*/
        if( $data['nestedData']['to_date'] !=date('Y-m-d')){
            echo 'No data available on today'; 
            exit;
        }
        
//        echo '<pre>';
//        print_r($data); exit;
        
        $company_arr = $data['nestedData']['company_arr'];
        
        
        
        $all_good_stock_arr = array();
        
        foreach($company_arr AS $company_symbol=>$company_arr_value){
//            echo '<br/>';
//            echo $company_symbol;
//            echo '<pre>';
//            print_r($company_arr_value['are_all_good_stock']);
            
//            echo '<br/>';
            
            if( $company_arr_value['are_all_good_stock'] =='yes'){
                
                $all_good_stock_arr[] = $company_symbol;
            }
            
        }
        
        echo '<pre>';
        print_r($all_good_stock_arr);
        
        $data['all_good_stock_arr'] = $all_good_stock_arr;
        
        $html = $this->load->view('analysis/emails/get_best_stock_by_home', $data, TRUE);
        
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ampstart.co';
//        $mail_data['to']='zahir.alam@ahealz.com';               
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Best Stock By Home Page';                
        $mail_data['message']= $html;
        send_mailz($mail_data);
        
    }
    
    function ocIvDataAnalysis( $filter ){
        
        include_once (dirname(__FILE__) . "/OC_Analysis.php");
        include_once (dirname(__FILE__) . "/Send_Api_Contr.php");
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $iv_analysis_controller = new OC_Analysis();
        
        $oc_iv_data = $iv_analysis_controller->dayWiseIvAnalysisProcess($filter, false, false, $Send_Api_Contr);
        
        if(empty($oc_iv_data)){ return; }
        if( ( $oc_iv_data[0]->underlying_date !== date('Y-m-d') ) ){ return; }
        
        return $oc_iv_data;
        
    }
    
    function ocIvDataAnalysisBull( $pd_iv_combo_analysis=false ){
        
        $filter['date'] = date('Y-m-d');
        
        $filter['bullish_probability'] = 'high';
        $filter['bearish_probability'] = '';
        $filter['bullish_probability_min'] = 0;
        $filter['bullish_probability_max'] = 100;
        $filter['bearish_probability_min'] = 0;
        $filter['bearish_probability_max'] = 100;
        $filter['custom_condition'] = 'bullgtbear';
        
        $oc_iv_bull_data = $this->ocIvDataAnalysis($filter);
        
        if( $pd_iv_combo_analysis === 'yes' ){
            
            return $oc_iv_bull_data;
        }
        
        $data['oc_iv_data'] = $oc_iv_bull_data;
        $data['date'] = date('Y-m-d');
        
        $html = $this->load->view('analysis/emails/oc/oc-iv-stock', $data, TRUE);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ampstart.co';             
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Bull Stock By Option Chain IV Analysis - ' . date('d M Y') ;                
        $mail_data['message']= $html;
        send_mailz($mail_data);
    }
    
    function ocIvDataAnalysisBear( $pd_iv_combo_analysis=false ){        
        
        $filter['date'] = date('Y-m-d');
        
        $filter['bullish_probability'] = '';
        $filter['bearish_probability'] = 'high';
        $filter['bullish_probability_min'] = 0;
        $filter['bullish_probability_max'] = 100;
        $filter['bearish_probability_min'] = 0;
        $filter['bearish_probability_max'] = 100;
        $filter['custom_condition'] = 'beargtbull';                
        
        $oc_iv_bear_data = $this->ocIvDataAnalysis($filter);
        
        if( $pd_iv_combo_analysis === 'yes' ){
            
            return $oc_iv_bear_data;
        }
        
        $data['oc_iv_data'] = $oc_iv_bear_data;
        $data['date'] = date('Y-m-d');
        
        $html = $this->load->view('analysis/emails/oc/oc-iv-stock', $data, TRUE);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ampstart.co';             
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Bear Stock By Option Chain IV Analysis - ' . date('d M Y') ;                
        $mail_data['message']= $html;
        send_mailz($mail_data);
    }
    
    function ocPdAnalysis( $filter ){
        
        include_once (dirname(__FILE__) . "/OC_PD_Analysis.php");
        include_once (dirname(__FILE__) . "/Send_Api_Contr.php");
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $pd_analysis_controller = new OC_PD_Analysis();
        
        $oc_pd_data = $pd_analysis_controller->dayWisePdAnalysisProcess( $filter, false, false, $Send_Api_Contr );
        
        if(empty($oc_pd_data)){ return; }
        if( ( $oc_pd_data[0]->underlying_date_end !== date('Y-m-d') ) ){ return; }
        
        return $oc_pd_data;
    }
    
    function ocPdAnalysisBull( $pd_iv_combo_analysis=false ){
        
        $filter['date'] = date('Y-m-d');
        $filter['put_avg_decay'] = '';
        $filter['call_avg_decay'] = 'high';
        $filter['custom_condition'] = 'callgtput';
        
        $oc_pd_bull_data = $this->ocPdAnalysis( $filter );
        
        if( $pd_iv_combo_analysis === 'yes' ){
            
            return $oc_pd_bull_data;
        }
        
        $data['oc_pd_data'] = $oc_pd_bull_data;
        $data['date'] = date('Y-m-d');
        
        $html = $this->load->view('analysis/emails/oc/oc-pd-stock', $data, TRUE);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ampstart.co';             
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Bull Stock By Option Chain PD Analysis - ' . date('d M Y') ;                
        $mail_data['message']= $html;
        send_mailz($mail_data);
    }
    
    function ocPdAnalysisBear($pd_iv_combo_analysis=false){
        
        $filter['date'] = date('Y-m-d');
        $filter['put_avg_decay'] = 'high';
        $filter['call_avg_decay'] = '';
        $filter['custom_condition'] = 'putgtcall';
        
        $oc_pd_bear_data = $this->ocPdAnalysis( $filter );
        
        if( $pd_iv_combo_analysis === 'yes' ){
            
            return $oc_pd_bear_data;
        }
        
        $data['oc_pd_data'] = $oc_pd_bear_data;
        $data['date'] = date('Y-m-d');
        
        $html = $this->load->view('analysis/emails/oc/oc-pd-stock', $data, TRUE);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ampstart.co';             
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Bear Stock By Option Chain PD Analysis - ' . date('d M Y') ;                
        $mail_data['message']= $html;
        send_mailz($mail_data);
        
    }
    
    function oc_PD_IV_ComboAnalysis($bull_or_bear){
        
        $pd_iv_combo_analysis = 'yes';
        
        if( $bull_or_bear === 'bull' ){
        
            $oc_iv_data = $this->ocIvDataAnalysisBull( $pd_iv_combo_analysis );
            $oc_pd_data = $this->ocPdAnalysisBull( $pd_iv_combo_analysis );
        
        }else if( $bull_or_bear === 'bear' ){
            
            $oc_iv_data = $this->ocIvDataAnalysisBear( $pd_iv_combo_analysis );
            $oc_pd_data = $this->ocPdAnalysisBear( $pd_iv_combo_analysis );
            
        }     
        
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
        
        echo '<pre>'; print_r($common_company);
        
        $data['oc_pd_iv_data'] = $common_company;
        $data['date'] = date('Y-m-d');
        
        $html = $this->load->view('analysis/emails/oc/oc-pd-iv-combo-stock', $data, TRUE);
        
        if( $bull_or_bear === 'bull' ){
            
            $subject = 'Bull Stock By Option Chain IV AND PD Analysis - ' . date('d M Y');  
            
        }else if( $bull_or_bear === 'bear' ){
            
            $subject = 'Bear Stock By Option Chain IV AND PD Analysis - ' . date('d M Y');  
        }
        
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ahealz.com';             
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = $subject ;                
        $mail_data['message']= $html;
        send_mailz($mail_data);
    }
}
