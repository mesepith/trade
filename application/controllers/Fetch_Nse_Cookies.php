<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fetch_Nse_Cookies extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('fetch_nse_cookies_model');
    }

    public function fetch_nse_cookie_urls() {
        
        $url_list = $this->fetch_nse_cookies_model->fetchCookieUrls( );
        
        echo json_encode($url_list); exit;
    }
    
    
    public function cookies_hook() {
        
        $data = $this->input->post("data");
        $is_api = $this->input->post("is_api");
        $url= $this->input->post("url");
        $nse_cookies_url_id= $this->input->post("nse_cookies_url_id");
        
        if(!empty($data)) {
            
            $data = json_decode($data, true);            
            
            $last_entry = $this->fetch_nse_cookies_model->lastUpdatedCookie( $url );
            
            $insert = TRUE;
            
//            if(!empty($last_entry)) {
//                $diff_time = (strtotime(date("Y-m-d H:i:s"))-strtotime($last_entry))/60;
//                
//                if($diff_time > COOKIE_EXPIRY) {
//                    $this->fetch_nse_cookies_model->deleteCookies();
//                    $insert = TRUE;
//                } else {
//                    echo json_encode(array('res' => 'FAIL', 'msg' => 'Cookies Not Expired')); die;
//                }
//            } else {
//                $insert = TRUE;
//            }
            
            if($insert) {
                
                $parent_id = 0;
                
                if($is_api == 1 && !empty($this->input->post("main_url_id") ) ){
                    
                    $main_url_id = $this->input->post("main_url_id");
                    
                    $this->fetch_nse_cookies_model->setCookieWorkingStatus($nse_cookies_url_id, 1);
                    
                }else{
                
                    $main_url_id = 0;
                }
                
                foreach($data as $k => $v) {
                    unset($v['sameSite']);   
                    
                    if( $parent_id !== 0 ){
                        $url = '';
                    }
                    
                    $res[] = $this->fetch_nse_cookies_model->addCookies($v, $is_api, $url, $parent_id, $main_url_id, $nse_cookies_url_id);
                    
                    if( $parent_id === 0 && !empty($res[0])){
                        
                        $parent_id = $res[0];
                        
                        if( $is_api == 0 ){
                           
                            $main_url_id = $parent_id;
                        }
                    }
                    
                }
                echo json_encode(array('res' => 'SUCCESS', 'msg' => $res, 'main_url_id' => $main_url_id)); die;
            }
        }
        echo json_encode(array('res' => 'FAIL', 'msg' => $data));
    }

    /*
    Insert nsit and nseappid cookie
    */
    function insertNsitAndnseappid(){

        $data_enc = $this->input->post();

        $data['name'] = 'nsit';
        $data['value'] = $data_enc['nsit'];
        
        $this->fetch_nse_cookies_model->deleteCookies(); //deactivate previous cookies

        $this->fetch_nse_cookies_model->addCookies($data, false, false, false, false, false);

        $data['name'] = 'nseappid';
        $data['value'] = $data_enc['nseappid'];

        $this->fetch_nse_cookies_model->addCookies($data, false, false, false, false, false);

        echo json_encode($data); exit;
    }

     /*
    Get nsit and nseappid cookie
    */
    function getnsitAndnseappid(){

        $cookie['nseappid'] = $this->fetch_nse_cookies_model->getActiveCookieByType('nseappid');
        $cookie['nsit'] = $this->fetch_nse_cookies_model->getActiveCookieByType('nsit');

        return $cookie;
        // $cookie[]
        // echo '<pre>'; print_r($cookie);
    }
}
