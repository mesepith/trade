<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rbi_Fetch extends MX_Controller {

    function marketTrend(  ) {
        
        $this->load->model('Rbi_model');
        
        include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
        
        $System_Notification_contr = new System_Notification_Controller();
        
        $post_data = $this->input->post();
        
        $json_data = json_decode($post_data['json_data'], true);
        
        foreach( $json_data['data'] AS $arr_val ){
            
//            echo '<pre>'; print_r($arr_val);
            
            if( trim($arr_val[0]) == '91 day T-bills' ){
                
                $data = array();
                
//                echo $arr_val[0] . ' : ' . trim($arr_val[1]);
                
                $data['key'] = trim($arr_val[0]);
                $val = trim(str_replace(':  ', '', $arr_val[1]));
                $data['value'] = trim(str_replace('%*', '', $val) );
                
                $return =  $this->Rbi_model->insertRbiTBill($data);
                
                echo $return;
            }
                
        }
        
        if( empty($data) || empty($data['key']) || empty($data['value']) ){
            
            $System_Notification_contr->failRead('https://www.rbi.org.in/', date ("Y-m-d"), 'RBI - Fail Read 91 Day T Bill');
            
        }

    }
    
}
