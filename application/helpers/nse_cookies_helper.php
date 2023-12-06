<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function getWorkingApiCookiesRow($url) {

    $CI = &get_instance();

    // You may need to load the model if it hasn't been pre-loaded
    $CI->load->model('NSE_cookie_model');

    $nse_cookies_url_id = $CI->NSE_cookie_model->getWorkingIdByUrl($url);
//    echo $nse_cookies_url_id;
    if (!empty($nse_cookies_url_id)) {

        $api_cookies = $CI->NSE_cookie_model->getCookiesNameofApi($nse_cookies_url_id);                
        
//        echo '<pre>'; print_r($api_cookies); exit;
        
        if (!empty($api_cookies)) {

            $apis_cookies = [];
            foreach ($api_cookies AS $value) {

                $apis_cookies[] = $value['name'];
            }

            $unique_cookie_arr = $CI->NSE_cookie_model->getUniqueCookiesInfo($nse_cookies_url_id, $apis_cookies);
            
//            echo '<pre>'; print_r($unique_cookie_arr); exit;
            
            if (!empty($unique_cookie_arr)) {

                $data = [];
                
                $data['nse_cookies_url_id'] = $nse_cookies_url_id;

                $data['cookies_string'] = '';
                foreach ($unique_cookie_arr as $k => $v) {
                    $data['cookie_array'][$v['name']] = $v['value'];

                    $data['cookies_string'] .= $v['name'] . "=" . $v['value'] . '; ';
                }
                
                return $data;
            }
        }
    }
    
    return NULL;
}
