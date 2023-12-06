<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client_Activity_Disp extends MX_Controller {

    public function displayOiParticipant(  ) {
        
        $this->load->model('ParticipantOi_model');
        
        $market_date = $this->input->get('market_date');
        $market_date_to = $this->input->get('market_date_to');
        
        $client_type_chkbox = $this->input->get('client_type_chkbox');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }                
        
        $oi_participant_data = $this->ParticipantOi_model->fetchOiParticipant($market_date, $market_date_to, $client_type_chkbox);
        
//        echo '<pre>'; print_r($oi_participant_data);  exit;      
                        
        $data['nestedData']['market_date'] = empty($oi_participant_data[0]->market_date) ? date('Y-m-d') : $oi_participant_data[0]->market_date;               
        $data['nestedData']['market_date_to'] = empty($market_date_to) ? date('Y-m-d') : $market_date_to;               
        
        $data['nestedData']['client_type_chkbox'] = $client_type_chkbox;
        
        $data['nestedData']['oi_participant_data'] = $oi_participant_data;
        
        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;
        
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        $data['nestedData']['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/client-activity/oi-participant.js");
        
        if( !empty($client_type_chkbox) ){
            
            $data['nestedScript']['js'][] = "assets/plugin/charts/g-chart/loader.js";
            $data['nestedScript']['js'][] = "assets/js/pages/client-activity//partwise-chart.js";
        }
        
        $data['content'] = "client-activity/oi-participant";
        $this->load->view('index', $data);
        
    }
    
    function oiParticipantClusterReturn( $client_type ){
         
        $this->load->model('ParticipantOi_model');
        
        $date = date('Y-m-d', strtotime("-12 month"));
        
        $oi_participant_data = $this->ParticipantOi_model->fetchOiParticipant($date, date('Y-m-d'), $client_type);
                
        $this->load->helper('function_helper');
        
        $avg_data[$client_type . '_future_index_long'] = avgReturnCalc( $oi_participant_data, 'future_index_long', 'market_date' );
        $avg_data[$client_type . '_future_index_short'] = avgReturnCalc( $oi_participant_data, 'future_index_short', 'market_date' );
        $avg_data[$client_type . '_future_stock_long'] = avgReturnCalc( $oi_participant_data, 'future_index_short', 'market_date' );
        $avg_data[$client_type . '_future_stock_short'] = avgReturnCalc( $oi_participant_data, 'future_index_short', 'market_date' );
        
        $avg_data[$client_type . '_option_index_call_long'] = avgReturnCalc( $oi_participant_data, 'option_index_call_long', 'market_date' );
        $avg_data[$client_type . '_option_index_put_long'] = avgReturnCalc( $oi_participant_data, 'option_index_put_long', 'market_date' );
        $avg_data[$client_type . '_option_index_call_short'] = avgReturnCalc( $oi_participant_data, 'option_index_call_short', 'market_date' );
        $avg_data[$client_type . '_option_index_put_short'] = avgReturnCalc( $oi_participant_data, 'option_index_put_short', 'market_date' );
        
        $avg_data[$client_type . '_option_stock_call_long'] = avgReturnCalc( $oi_participant_data, 'option_index_put_short', 'market_date' );
        $avg_data[$client_type . '_option_stock_put_long'] = avgReturnCalc( $oi_participant_data, 'option_stock_put_long', 'market_date' );
        $avg_data[$client_type . '_option_stock_call_short'] = avgReturnCalc( $oi_participant_data, 'option_stock_call_short', 'market_date' );
        $avg_data[$client_type . '_option_stock_put_short'] = avgReturnCalc( $oi_participant_data, 'option_stock_put_short', 'market_date' );
        
        $data['nestedData']['avg_data'] = $avg_data;
        $data['nestedData']['report_name'] = 'OI Participant';
        
        $data['nestedScript']['js'] = array("assets/plugin/charts/g-chart/loader.js", "assets/js/pages/average-chart.js");
        
        $data['content'] = "average/average-return";
        
        $this->load->view('index', $data);
    }

    public function displayVolumeParticipant(  ) {
        
        $this->load->model('ParticipantVolume_model');
        
        $market_date = $this->input->get('market_date');
        $market_date_to = $this->input->get('market_date_to');
        
        $client_type_chkbox = $this->input->get('client_type_chkbox');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }                
        
        $oi_participant_data = $this->ParticipantVolume_model->fetchVolumeParticipant($market_date, $market_date_to, $client_type_chkbox);
        
//        echo '<pre>'; print_r($oi_participant_data);  exit;      
                        
        $data['nestedData']['market_date'] = empty($oi_participant_data[0]->market_date) ? date('Y-m-d') : $oi_participant_data[0]->market_date;               
        $data['nestedData']['market_date_to'] = empty($market_date_to) ? date('Y-m-d') : $market_date_to;               
        
        $data['nestedData']['client_type_chkbox'] = $client_type_chkbox;
        
        $data['nestedData']['oi_participant_data'] = $oi_participant_data;
        
        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;
        
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        $data['nestedData']['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/client-activity/volume-participant.js");
        
        if( !empty($client_type_chkbox) ){
            
            $data['nestedScript']['js'][] = "assets/plugin/charts/g-chart/loader.js";
            $data['nestedScript']['js'][] = "assets/js/pages/client-activity//partwise-chart.js";
        }
        
        $data['content'] = "client-activity/volume-participant";
        $this->load->view('index', $data);
        
    }
        
    function volumeParticipantClusterReturn( $client_type ){
         
        $this->load->model('ParticipantVolume_model');
        
        $date = date('Y-m-d', strtotime("-12 month"));
        
        $oi_participant_data = $this->ParticipantVolume_model->fetchVolumeParticipant($date, date('Y-m-d'), $client_type);
                
        $this->load->helper('function_helper');
        
        $avg_data[$client_type . '_future_index_long'] = avgReturnCalc( $oi_participant_data, 'future_index_long', 'market_date' );
        $avg_data[$client_type . '_future_index_short'] = avgReturnCalc( $oi_participant_data, 'future_index_short', 'market_date' );
        $avg_data[$client_type . '_future_stock_long'] = avgReturnCalc( $oi_participant_data, 'future_index_short', 'market_date' );
        $avg_data[$client_type . '_future_stock_short'] = avgReturnCalc( $oi_participant_data, 'future_index_short', 'market_date' );
        
        $avg_data[$client_type . '_option_index_call_long'] = avgReturnCalc( $oi_participant_data, 'option_index_call_long', 'market_date' );
        $avg_data[$client_type . '_option_index_put_long'] = avgReturnCalc( $oi_participant_data, 'option_index_put_long', 'market_date' );
        $avg_data[$client_type . '_option_index_call_short'] = avgReturnCalc( $oi_participant_data, 'option_index_call_short', 'market_date' );
        $avg_data[$client_type . '_option_index_put_short'] = avgReturnCalc( $oi_participant_data, 'option_index_put_short', 'market_date' );
        
        $avg_data[$client_type . '_option_stock_call_long'] = avgReturnCalc( $oi_participant_data, 'option_index_put_short', 'market_date' );
        $avg_data[$client_type . '_option_stock_put_long'] = avgReturnCalc( $oi_participant_data, 'option_stock_put_long', 'market_date' );
        $avg_data[$client_type . '_option_stock_call_short'] = avgReturnCalc( $oi_participant_data, 'option_stock_call_short', 'market_date' );
        $avg_data[$client_type . '_option_stock_put_short'] = avgReturnCalc( $oi_participant_data, 'option_stock_put_short', 'market_date' );
        
        $data['nestedData']['avg_data'] = $avg_data;
        $data['nestedData']['report_name'] = 'Volume Participant';
        
        $data['nestedScript']['js'] = array("assets/plugin/charts/g-chart/loader.js", "assets/js/pages/average-chart.js");
        
        $data['content'] = "average/average-return";
        
        $this->load->view('index', $data);
    }
    
    
    /*
     * Display Category wise turnover
     */
    
    function dispCatWiseTrnvr( $trading_type ){
        
        $this->load->model('Fii_dii_model');
        
        $market_date = $this->input->get('market_date');
        $market_date_to = $this->input->get('market_date_to');
        
        if(empty($market_date)){
            
            $market_date = date('Y-m-d');
            
        }   
        
        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;
        
        $category_chkbox = $this->input->get('category_chkbox');
        $data['nestedData']['category_chkbox'] = $category_chkbox;
        
        $cat_wise_trnvr = $this->Fii_dii_model->fetchCatWiseTrnvr($market_date, $market_date_to, $category_chkbox, $trading_type);
        
//        echo '<pre>'; print_r($cat_wisw_trnvr);  exit;
        
        $data['nestedData']['cat_wise_trnvr'] = $cat_wise_trnvr;
        $data['nestedData']['trading_type'] = $trading_type;
        
        $show_avg_total_data = $this->input->get('show_avg_total_data');
        $data['nestedData']['show_avg_total_data'] = (empty($show_avg_total_data)) ? 'no' : $show_avg_total_data;
        
        $data['nestedData']['market_date'] = empty($cat_wise_trnvr[0]->market_date) ? ( empty($market_date) ? date('Y-m-d') : $market_date ) : $cat_wise_trnvr[0]->market_date;               
        $data['nestedData']['market_date_to'] = empty($market_date_to) ? date('Y-m-d') : $market_date_to; 
        
        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/client-activity/cat-wise-trnvr.js");
        
        if( !empty($category_chkbox) ){
            
            $data['nestedScript']['js'][] = "assets/plugin/charts/g-chart/loader.js";
            $data['nestedScript']['js'][] = "assets/js/pages/client-activity/cat-wise-chart.js";
        }
        
        $data['content'] = "client-activity/cat-wise-trnvr";
        $this->load->view('index', $data);
    }
    
}
