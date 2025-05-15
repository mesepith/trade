<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UpStock_Contr extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('UpStock_model');
    }
    
     public function fivePercentUpOnLastTradeStocks( ) {

         $data['nestedData']['stocks'] = $this->UpStock_model->getStocksUpByPercentToday(5);
        $data['nestedData']['title'] = "Stocks 5% UP on Last Trade";
        // echo '<pre>'; print_r($data); exit;
        $data['content'] = "up_stocks/latest_up_stock";
        $this->load->view('index', $data);
        // $this->load->view('up_stocks/latest_up_stock', $data);

    }

    public function upByPercent($percent = 10, $sessions = 2) {
        $data['nestedData']['stocks'] = $this->UpStock_model->getStocksUpByCumulativePercent($percent, $sessions);
        $data['nestedData']['title'] = "{$percent}% UP in Last {$sessions} Trading Sessions";
        $data['content'] = "up_stocks/up_stocks_multi";
        $this->load->view('index', $data);
    }


}
