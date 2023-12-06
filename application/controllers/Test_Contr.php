<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test_Contr extends MX_Controller {
    
    public function autoSuggestView(){
        /*
         * https://ampbyexample.com/advanced/autosuggest_form/
         */
//        echo $seo_url; exit;
        $this->load->model('Ytb_model');
        
//        $ytbObjectList = $this->Ytb_model->playVideo($seo_url);
//        
//        
//        $data['nestedHead']['component'] = array("amp-selector" => "https://cdn.ampproject.org/v0/amp-selector-0.1.js",
//            "amp-bind" => "https://cdn.ampproject.org/v0/amp-bind-0.1.js",
//            "amp-list" => "https://cdn.ampproject.org/v0/amp-list-0.1.js",
//            "amp-youtube" => "https://cdn.ampproject.org/v0/amp-youtube-0.1.js"
//            );
//        $data['nestedHead']['custom_template'] = array(
//            "amp-mustache" => "https://cdn.ampproject.org/v0/amp-mustache-0.1.js"
//            );
//        
//        $data['nestedHead']['css'] = array('assets/css/pages/video-play.css');
//        $data['nestedHead']['nestedSeo']['title'] = $ytbObjectList[0]->title;
//        $data['nestedHead']['nestedSeo']['description'] = $ytbObjectList[0]->title;
//        
//        $data['nestedData']['ytbObjectList'] = $ytbObjectList;  
        
        $data['content'] = "test/autoSuggestView";

        $this->load->view('test/autoSuggestView', $data);
//        $this->load->view('index', $data);
        
    }
    public function autoSuggestTitleView(){
        
        $this->load->model('Ytb_model'); 
        
        $data['content'] = "test/autoSuggestView";

        $this->load->view('test/autoSuggestTitleView', $data);
//        $this->load->view('index', $data);
        
    }
    
    /*
     * Process Sector Data 
     */
    
    function processSectorLog(){
        
        $this->load->model('Sectors_model');
        
        $sectors_list_value = $this->Sectors_model->listAllSectorLog();
        
//        echo '<pre>'; print_r($sectors_list_value); exit;
        
        foreach( $sectors_list_value AS $sectors_list_value_val){
            
            $sectors_data = json_decode( $sectors_list_value_val->data, true);
            
            unset($sectors_data['data']);
            
//            echo '<pre>'; print_r($sectors_data); 
            
            
            $sectors_data_arr = array();
            
            $sectors_data_arr['sectors_id'] = $sectors_list_value_val->sectors_id;
            $sectors_data_arr['index_name'] = $sectors_list_value_val->index_name;
            $sectors_data_arr['sectors_data_log_id'] = $sectors_list_value_val->id;
            
            
            $sectors_data_arr['declines'] = str_replace(",","",$sectors_data['declines']);
            $sectors_data_arr['advances'] = str_replace(",","",$sectors_data['advances']);
            $sectors_data_arr['unchanged'] = str_replace(",","",$sectors_data['unchanged']);
            
            $sectors_data_arr['trade_volume_sum'] = str_replace(",","",$sectors_data['trdVolumesum']);
            
            $sectors_data_arr['open_price'] = str_replace(",","",$sectors_data['latestData'][0]['open']);
            $sectors_data_arr['high_price'] = str_replace(",","",$sectors_data['latestData'][0]['high']);
            $sectors_data_arr['low_price'] = str_replace(",","",$sectors_data['latestData'][0]['low']);

            $sectors_data_arr['ltp'] = str_replace(",","",$sectors_data['latestData'][0]['ltp']);
            
            $sectors_data_arr['change'] = str_replace(",","",$sectors_data['latestData'][0]['ch']);
            $sectors_data_arr['change_in_percent'] = str_replace(",","",$sectors_data['latestData'][0]['per']);
            $sectors_data_arr['year_change_in_percent'] = str_replace(",","",$sectors_data['latestData'][0]['yCls']);
            $sectors_data_arr['month_change_in_percent'] = str_replace(",","",$sectors_data['latestData'][0]['mCls']);
            
            $sectors_data_arr['year_high_price'] = str_replace(",","",$sectors_data['latestData'][0]['yHigh']);
            $sectors_data_arr['year_low_price'] = str_replace(",","",$sectors_data['latestData'][0]['yLow']);
            
            $old_date_timestamp = strtotime($sectors_data['time']);
            $new_date = date('Y-m-d H:i:s', $old_date_timestamp);   
            $sectors_data_arr['stock_date_time'] = $new_date;
            $sectors_data_arr['stock_date'] = date('Y-m-d', $old_date_timestamp);  
            $sectors_data_arr['stock_time'] = date('H:i:s', $old_date_timestamp);  
            
            $sectors_data_arr['trade_value_sum'] = str_replace(",","",$sectors_data['trdValueSum']);
            
            echo '<pre>'; print_r($sectors_data_arr);
            
            $sectors_data_log_id = $this->Sectors_model->insertSectorsData( $sectors_data_arr );
            
        }
    }
    
}
