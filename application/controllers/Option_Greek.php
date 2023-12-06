<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Option_Greek extends MX_Controller {

    public function viewOGCalculator() {
        
        $data['nestedScript']['js'] = array("assets/plugin/gaussian/gaussian.js" ,"assets/js/pages/option/og-calculate.js");
        
        $data['content'] = "option-chain/greek/og-calculator";
        $this->load->view('index', $data);
    }
    
    
}
