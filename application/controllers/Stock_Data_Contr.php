<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_Data_Contr extends MX_Controller {
    
    /*
     * @author: ZAHIR
     * DESC: Check if stock_data table has todays any stock
     */
    
    public function checkStockTodayDataInserted( ) {
        
        
        $this->load->model('Stock_data_model');   
        
        echo $is_present = $this->Stock_data_model->checkStockTodayDataInserted();
         
        exit;

    }
    /*
     * @author: ZAHIR
     * DESC: Check if famous stock is inserted to stock_data table
     * We have chosen famous stock as TCS as it contains long position of alphabetical order
     */
    
    public function checkStockTodayFamousStockDataInserted( ) {
        
        
        $this->load->model('Stock_data_model');   
        
        echo $is_present = $this->Stock_data_model->checkStockTodayFamousStockDataInserted();
         
        exit;

    }
    /*
     * @author: ZAHIR
     * DESC: Check if stock_data_live table has todays any stock
     */
    
    public function checkStocksLiveTodayDataInserted( ) {
        
        
        $this->load->model('Stock_data_live_model');   
        
        echo $is_present = $this->Stock_data_live_model->checkStocksLiveTodayDataInserted();
         
        exit;

    }

}
