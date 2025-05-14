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

    public function tenPercentUpInTwoSessions() {
        $data['nestedData']['stocks'] = $this->UpStock_model->getStocksUpByCumulativePercent(10, 2);
        $data['nestedData']['title'] = "10% UP in Last 2 Trading Sessions";
        // echo '<pre>'; print_r($data); exit;
        $data['content'] = "up_stocks/up_stocks_multi";
        $this->load->view('index', $data);
    }

    public function fifteenPercentUpInThreeSessions() {
        $data['nestedData']['stocks'] = $this->UpStock_model->getStocksUpByCumulativePercent(15, 3);
        $data['nestedData']['title'] = "15% UP in Last 3 Trading Sessions";
        $data['content'] = "up_stocks/up_stocks_multi";
        $this->load->view('index', $data);
    }

    public function twentyPercentUpInFourSessions() {
        $data['nestedData']['stocks'] = $this->UpStock_model->getStocksUpByCumulativePercent(20, 4);
        $data['nestedData']['title'] = "20% UP in Last 4 Trading Sessions";
        $data['content'] = "up_stocks/up_stocks_multi";
        $this->load->view('index', $data);
    }

    public function twentyFivePercentUpInFiveSessions() {
        $data['nestedData']['stocks'] = $this->UpStock_model->getStocksUpByCumulativePercent(25, 5);
        $data['nestedData']['title'] = "25% UP in Last 5 Trading Sessions";
        $data['content'] = "up_stocks/up_stocks_multi";
        $this->load->view('index', $data);
    }

}
