<?php

include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_contr extends MX_Controller {

    public function bestShareByCorporate(  ) {
        
        $best_stocks_by_insider = $this->bestShareByInsiderTrading();
//        echo $best_stocks_by_insider;
//        echo '<pre>'; print_r($best_stocks_by_insider);
//        echo 'zaa';
        $this->bestShareBySast29( $best_stocks_by_insider );
        
    }
    
    public function bestShareByInsiderTrading(){
//        echo 'zaaa';
        $this->load->model('ShareHolding_disp_model');      
        $this->load->model('Companies_model');
        
        $broadcaste_date = date('Y-m-d', strtotime("-3 month"));
        $acq_disp = 'all';
        $security_sortby = 'high';
        $broadcaste_date_to = date('Y-m-d');
        $acq_mode = 'Market Purchase';
        $person_category = array('Promoter Group', 'Promoters');
        $sum_sec_val_by_comp = 'yes'; 
        $sec_val_gt = 10000000; //1 cr
        
        $insider_trading = $this->ShareHolding_disp_model->fetchInsiderTrading($broadcaste_date, $acq_disp, $security_sortby, false, false, $broadcaste_date_to, false, $acq_mode, $person_category, $sum_sec_val_by_comp, $sec_val_gt);
        
        $best_stocks_by_insider = array();
        
        $count = 0 ;
        
        foreach( $insider_trading AS $each_company){
            
            $company_id = $this->Companies_model->getCompanyIdBySymbol( $each_company->company_symbol );
            
            if( empty($company_id)) { continue; }
            
            $acq_mode ='Market Sale';
            
            $each_insider_trading = $this->ShareHolding_disp_model->fetchInsiderTrading($broadcaste_date, $acq_disp, $security_sortby, $company_id, $each_company->company_symbol, $broadcaste_date_to, false, $acq_mode, $person_category, $sum_sec_val_by_comp);
            
            if( empty($each_insider_trading) ){ // No market sale means good stock
                
                $best_stocks_by_insider[$count]['company_id'] = $company_id;
                $best_stocks_by_insider[$count]['company_symbol'] = $each_company->company_symbol;
                $count ++;
            }
            
        }
        
//        echo '<pre>'; print_r($best_stocks_by_insider);
        
        return $best_stocks_by_insider;
    }
    
    public function bestShareBySast29( $best_stocks_by_insider ){
        
//        echo '<pre>'; print_r($best_stocks_by_insider);
        $best_stocks_by_sast = array();
        
        $count = 0 ;
        
        foreach( $best_stocks_by_insider AS $each_company){
                        
            $broadcaste_date = date('Y-m-d', strtotime("-3 month"));
            $acq_or_sale_disp = 'Sale';
            $promoter_type = 'all';
            $total_share_acq_sortby='';
            $total_share_sale_sortby='';
            $broadcaste_date_to = date('Y-m-d');
            $acq_saler_name = '';

            $sast_data = $this->ShareHolding_disp_model->fetchSastRegulation29($broadcaste_date, $acq_or_sale_disp, $promoter_type, $total_share_acq_sortby, $total_share_sale_sortby, $each_company['company_id'], $each_company['company_symbol'], $broadcaste_date_to, $acq_saler_name);
            
            if( empty($sast_data) ){ // No sale means good stock
                
                $best_stocks_by_sast[$count]['company_id'] = $each_company['company_id'];
                $best_stocks_by_sast[$count]['company_symbol'] = $each_company['company_symbol'];
                $count ++;
            }
            
            
        }
        
        echo '<pre>'; print_r($best_stocks_by_sast);
    }

    /**
     * @author : Zahir
     * Desc: Checking growth by volume implement
     */
    public function insertWeeklyVolumeGrowth(){

        $this->load->model('Growth_model');

        $Send_Api_Contr = new Send_Api_Contr(); 

        $last_inserted_company_id = 0;

        $company_list = $Send_Api_Contr->listAllCompanies( $last_inserted_company_id );
        
    //    echo '<pre>';
    //    print_r($company_list); exit;

       if(empty($company_list)){   
            echo 'All companies Growth percentage is inserted';
            echo '<br/>';
            
            return;
        }
    
        foreach ($company_list AS $company_list_value) {
            
            $company_symbol = $company_list_value['symbol'];
            $company_id = $company_list_value['id'];

            $data = $this->weeklyVolumeGrowthCalculation($company_symbol, $company_id);

            echo '<pre>'; print_r($data);

            if($company_id > 0){ exit;}

            if(!empty($data)){ $this->Growth_model->insertStocksTwoWeeksVolumeGrowth($data);}

            
        }


    }

    function weeklyVolumeGrowthCalculation($company_symbol, $company_id){

        $this->load->helper('function_helper');

        $this->load->model('Stock_data_model');

        // $company_symbol = base64_url_decode($company_symbol_encode);

        $table_rows = 10;

        $stock_volume_arr = $this->Stock_data_model->getStocksTwoWeeksVolume($company_symbol, $table_rows);
        $market_date = $stock_volume_arr[0]->stock_date;

        if( count($stock_volume_arr) <$table_rows) {  return false; } //if return false, it means we don't have 10 days(2 trading weeks) data

        echo '<br/>' .$company_symbol. ':  count : ' . count($stock_volume_arr) . '<br/>';

        // echo '<pre>'; print_r($stock_volume_arr);

        $sum_of_current_week = 0;
        for($i=0; $i<($table_rows/2); $i++){

            // echo $stock_volume_arr[$i]->total_traded_volume . '<br/>'; 

            $sum_of_current_week = $sum_of_current_week + $stock_volume_arr[$i]->total_traded_volume;

        }

        echo 'sum_of_current_week : ' .  $sum_of_current_week . '<br/>';

        $sum_of_last_week = 0;
        for($i=5; $i<$table_rows; $i++){

            // echo $stock_volume_arr[$i]->total_traded_volume . '<br/>'; 

            $sum_of_last_week = $sum_of_last_week + $stock_volume_arr[$i]->total_traded_volume;

        }

        echo 'sum_of_last_week : ' .  $sum_of_last_week . '<br/>';

        $volume_growth = computeGrowthPercentage($sum_of_current_week, $sum_of_last_week );

        return array('company_id'=>$company_id, 'company_symbol'=>$company_symbol,'market_date'=>$market_date, 'growth_percent'=>$volume_growth, 'calculation_date'=>date('Y-m-d'), 'name'=>'volume', 'type'=>'weekly', 'logic'=>'difference_of_current_and_previous_week');

    }
}
