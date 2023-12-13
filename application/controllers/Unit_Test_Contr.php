<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_Test_Contr extends MX_Controller {
    
     function __construct() {

        parent::__construct();
        ob_start();
        $this->load->library('unit_test');
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check ahz server is running or not
     */
    
    public function testAhzServerStatus( ) {
        
        $client = new GuzzleHttp\Client();
        
        try {
            
            $test_ret = trim($client->request('GET', AHZ_SERVER . '/server_status.php', ['timeout' => 20, 'connect_timeout' => 20] )->getBody()->getContents());
            
        } catch (Exception $errorz) {
            
            $test_ret = $errorz;
            
        }
        
        $expected_ans = 'ok';

        $test_name = 'Checking AHZ Server Status';

        $verify_res = $this->unit->run($test_ret, $expected_ans, $test_name);
        
        print($verify_res);

        if ($test_ret != 'ok') {
            
            $this->sendTestFailEmail("AHZ Server not working", $verify_res);
        }

    }
    /*
     * @author: ZAHIR
     * DESC: Check ahz server is running or not
     */
    
    public function testTradeSeleniumServerStatus( ) {
        
        $client = new GuzzleHttp\Client();
        
        try {
            
            $test_ret = trim($client->request('GET', TRADE_SELENIUM_SERVER . '/server_status.php', ['timeout' => 20, 'connect_timeout' => 20] )->getBody()->getContents());
            
        } catch (Exception $errorz) {
            
            $test_ret = $errorz;
            
        }
        
        $expected_ans = 'ok';

        $test_name = 'Checking AHZ Server Status';

        $verify_res = $this->unit->run($test_ret, $expected_ans, $test_name);
        
        print($verify_res);

        if ($test_ret != 'ok') {
            
            $this->sendTestFailEmail("AHZ Server not working", $verify_res);
        }

    }

    function emailTest(){

        $this->sendTestFailEmail("Email Subject Test", 'Body of the Email by Zahir');
    }
    
    /*
     * @author: ZAHIR
     * DESC : If Unit test is fail then send mail
     */
    function sendTestFailEmail($subject, $body){
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = $subject;                
        $mail_data['message']= $body;
        echo send_mailz($mail_data);
        
    }

}
