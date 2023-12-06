<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Delete_Duplicate extends MX_Controller {

    public function delLiveStockDupData( $start=0 ) {
        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        ini_set('max_execution_time', 0); 
        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');
        
        $limit = 15;
        
        $this->load->model('Delete_duplicate_model');
        
        $dup_data_arr = $this->Delete_duplicate_model->duplicateValuesOfStockDataLive( $start, $limit );
        
        echo '<pre>';
        print_r($dup_data_arr);
        
        $dup_data = array();
        
        foreach( $dup_data_arr AS $dup_data_val){
            
            $id = $dup_data_val->id;
            
            unset($dup_data_val->NumDuplicates);
            unset($dup_data_val->id);
            
            print_r($dup_data_val);
            
            $is_duplicate = $this->Delete_duplicate_model->eachDuplicateOfStockDataLive($dup_data_val, $id);
            
        }
        
        $start = $start + $limit;
        
        $this->delLiveStockDupData( $start );
        
//        echo '<pre>';
//        print_r($dup_ids);
        
    }
    
    

}
