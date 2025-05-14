<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UpStock_Contr extends MX_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('UpStock_model');
    }
    
     public function fivePercentUpOnLastTradeStocks( ) {

         $data['stocks'] = $this->UpStock_model->getStocksUpByPercentToday(5);
        $data['title'] = "Stocks 5% UP on Last Trade";
        echo '<pre>'; print_r($data);
        // $this->load->view('stocks/up_stocks', $data);

    }

    public function tenPercentUpInFiveSessions() {
        $data['stocks'] = $this->UpStock_model->getStocksUpByCumulativePercent(10, 5);
        $data['title'] = "10% UP in Last 5 Trading Sessions";
        $this->load->view('stocks/up_stocks_multi', $data);
    }

    public function fifteenPercentUpInTenSessions() {
        $data['stocks'] = $this->UpStock_model->getStocksUpByCumulativePercent(15, 10);
        $data['title'] = "15% UP in Last 10 Trading Sessions";
        $this->load->view('stocks/up_stocks_multi', $data);
    }

}
