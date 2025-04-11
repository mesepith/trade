<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/System_Notification_Controller.php");

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

     /*
     * @author: ZAHIR
     * DESC: Check if todays stock is inserted by checking the famous stocks
     */
    
     public function checkTodaysStockInserted( ) {
        
        
        $this->load->model('Stock_data_model');   
        
        echo $is_a_present = $this->Stock_data_model->checkTodaysStockInserted(FAMOUS_STOCK_A);
        echo $is_b_present = $this->Stock_data_model->checkTodaysStockInserted(FIRST_SERIAL_FAMOUS_STOCK);
        echo $is_c_present = $this->Stock_data_model->checkTodaysStockInserted(LAST_SERIAL_FAMOUS_STOCK);

        if($is_a_present == 0 || $is_b_present == 0  || $is_c_present == 0 ){

            $System_Notification_contr = new System_Notification_Controller();
            echo '<br/> Todays Stock data is not inserted';

            $System_Notification_contr->stockNotInserted();
        }
         
        exit;

    }

}
