<?php
/*
@author: Zahir Alam
@date: 14-May-2025
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class UpStock_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function listFivePercentUpOnLastTradeStocks( ) {
        
        $query = $this->db->query("SELECT * FROM stocks WHERE last_trade_price > (last_trade_price * 1.05)");
        return $query->result_array();
        
    }
    
}