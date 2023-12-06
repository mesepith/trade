<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recieve_Api_Contr extends MX_Controller {

    function listAllCompanies($last_inserted_company_id=0) {

        $this->load->model('Companies_model');

        if( $last_inserted_company_id > 0  ){
                
            $company_list = $this->Companies_model->listFinalNonInsertedCompanies($last_inserted_company_id);

        }else{

            $company_list = $this->Companies_model->listAllCompaniesforCrawl();

        }

        echo json_encode($company_list); exit;
    }

    function makeCompanyInactive() {

        $this->load->model('Companies_model');
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');
        
        $this->Companies_model->makeCompanyInactive($company_id, $company_symbol);
        
        return;
        
    }
    
    function chkTodayFinalTaskIsDone(){

        $this->load->model('Analysis_task_model');
        
        echo $check_task_done = $this->Analysis_task_model->checkAnalysisDone('stk_final_day_data'); 

        exit;
    }
    
    function lastInsertedStkCompany(){
        
        $this->load->model('Companies_model');

        echo $lastInsertedCompany = $this->Companies_model->lastInsertedCompany();
        
        exit;
        
    }
    
    function lastInsertedStkCrawledCompany(){
        
        $this->load->model('Companies_model');

        $lastInsertedCompany = $this->Companies_model->lastInsertedStkCrawledCompany();
        
        echo json_encode($lastInsertedCompany); exit;
        
        exit;
        
    }
    
    function todayFinalStkDataInserted(){
        
        $this->load->model('Analysis_task_model');

        $this->Analysis_task_model->insertOcAnalysisDone('stk_final_day_data');
    }
    
    function chkTodayFinalPCDataIsCrawled(){

        $this->load->model('Analysis_task_model');
        
        echo $check_task_done = $this->Analysis_task_model->checkAnalysisDone('pc_final_day_data_crawled'); 

        exit;
    }
    
    function todayFinalPCDataCrawled(){
        
        $this->load->model('Analysis_task_model');

        $this->Analysis_task_model->insertOcAnalysisDone('pc_final_day_data_crawled');
    }
    
    function lastCrawledPCCompany(){
        
        $this->load->model('Put_call_log_model');

        echo $lastCrawledCompany = $this->Put_call_log_model->lastCrawledPCCompany();
        
        exit;
    }
    
    function oCPDNonInserteDCompanyList( $last_crawled_company_id=0 ){
        
        $this->load->model('Oc_pd_analysis_model');
            
        $company_list = $this->Oc_pd_analysis_model->oCPDNonInserteDCompanyList( $last_crawled_company_id );
        
        echo json_encode($company_list);
        
        exit;
    }
    
    function insertPutCallDataLog2(){
        
        $this->load->model('Put_call_log_model');
        
        $data_log_arr = $this->input->post('data_log_arr');
        $market_running = $this->input->post('market_running');
        
        echo $this->Put_call_log_model->insertPutCallDataLog2($data_log_arr, $market_running); exit;
    }

    function futureNonCrawledCompanyList( $last_crawled_company_id ){
        
        $this->load->model('Future_model');
            
        $company_list = $this->Future_model->futureCompanyList( $last_crawled_company_id );
        
        echo json_encode($company_list);
        
        exit;
    }
    
    function insertFutureDataLog(){
        
        $this->load->model('Future_model');
        
        $future_arr = $this->input->post('future_arr');
        
        $this->Future_model->insertFutureDataLog($future_arr);
    }
    
    function getCompanyIdBySymbol( $index_or_company_symbol ){
        
        $this->load->helper('function_helper');
        
        $index_or_company_symbol = base64_url_decode($index_or_company_symbol);
        
        $this->load->model('Companies_model');

        echo $this->Companies_model->getCompanyIdBySymbol($index_or_company_symbol);
    }
    
    function getCompanyIdAndIndexIdBySymbol( $index_or_company_symbol ){
        
        $this->load->helper('function_helper');
        
        $index_or_company_symbol = base64_url_decode($index_or_company_symbol);
        
        $this->load->model('Companies_model');

        echo $this->Companies_model->getCompanyIdAndIndexIdBySymbol($index_or_company_symbol);
    }
    
     function getCompanyIdAndSymbolByName( $company_name ){
        
        $this->load->helper('function_helper');
        
        $company_name = base64_url_decode($company_name);
        
        $this->load->model('Companies_model');

        $return = $this->Companies_model->getCompanyIdAndSymbolByName($company_name);
        
        echo json_encode($return); exit;
    }
        
    function inserMonthlytLotSize(){
        
        $this->load->model('Lot_Size_model');
        
        $lot_arr = $this->input->post('lot_arr');
        
        $this->Lot_Size_model->inserMonthlytLotSize($lot_arr);
    }
        
    function insertLotSize(){
        
        $this->load->model('Lot_Size_model');
        
        $lot_arr = $this->input->post('lot_arr');
        
        $this->Lot_Size_model->insertLotSize($lot_arr);
    }
    
    function checkCompanyExistInPCByIdAndSymbol(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');
        
        $this->load->model('Put_call_model');
        
        $return  = $this->Put_call_model->checkCompanyExistInPCByIdAndSymbol($company_id, $company_symbol);
        
        echo $return; exit;
    }
    
    function checkLotExistsByExpiryDate( ){

        $this->load->model('Lot_Size_model');
        
        $lot_arr = $this->input->post('lot_arr');
        $expiry_date_arr = $this->input->post('expiry_date_arr');
        
        echo $lot_size = $this->Lot_Size_model->checkLotExistsByExpiryDate( $lot_arr, $expiry_date_arr ); exit;
    }
    
    function checkLotExists( ){

        $this->load->model('Lot_Size_model');
        
        $lot_arr = $this->input->post('lot_arr');
        
        echo $lot_size = $this->Lot_Size_model->checkLotExists($lot_arr); exit;
    }
    
    function getLatestUnderlyingDate(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');
        $live = $this->input->post('live');
        
        $this->load->model('Put_call_model');
        
        $underlying_date = $this->Put_call_model->getLatestUnderlyingDate( $company_id, $company_symbol, $live );
        
        echo json_encode($underlying_date); exit;
        
    }
    
    function getCurrentExpiryDateByUnderlyingDate(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $live = $this->input->post('live');
        
        $this->load->model('Put_call_model');        
        
        $expiry_dates = $this->Put_call_model->getCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $searching_underlying_date, $live );
        
        echo json_encode($expiry_dates); exit;
        
    }
    
    function getAllUnderlyingTime(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');        
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $searching_expiry_date = $this->input->post('searching_expiry_date');                
        
        $this->load->model('Put_call_model');        
        
        $underlying_times = $this->Put_call_model->getAllUnderlyingTime( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date );
        
        echo json_encode($underlying_times); exit;
        
    }
    
    function getOCDataOfStock(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');        
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $searching_expiry_date = $this->input->post('searching_expiry_date');   
        $live = $this->input->post('live');
        $searching_underlying_time = $this->input->post('searching_underlying_time');
        
        $this->load->model('Put_call_model'); 
        
        $oc_data = $this->Put_call_model->getOCDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date, $live, $searching_underlying_time );        
        
        echo json_encode($oc_data); exit;
        
    }
    
    function getOCIVData(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');        
        $date = $this->input->post('date');
        $manual_date_to = $this->input->post('manual_date_to'); 
        $live = $this->input->post('live');  
        $searching_expiry_date = $this->input->post('searching_expiry_date'); 
        
        $this->load->model('Oc_iv_analysis_model'); 
        
        $oc_iv_data= $this->Oc_iv_analysis_model->getOCIVData($company_id, $company_symbol, $date, $manual_date_to, $live, $searching_expiry_date);
        
        echo json_encode($oc_iv_data); exit;
        
    }
    
    function getOcIVScriptStartTime(){
        
        $date = $this->input->post('date');
        
        $this->load->model('Oc_iv_analysis_model');
        $script_start_time_arr = $this->Oc_iv_analysis_model->getScriptStartTime( $date );
        
        echo json_encode($script_start_time_arr); exit;
    }
    
    function displayOCIVDayWiseData(){
        
        $date = $this->input->post('date');
        $bullish_probability = $this->input->post('bullish_probability');
        $bearish_probability = $this->input->post('bearish_probability');
        $bullish_probability_min = $this->input->post('bullish_probability_min');
        $bullish_probability_max = $this->input->post('bullish_probability_max');
        $bearish_probability_min = $this->input->post('bearish_probability_min');
        $bearish_probability_max = $this->input->post('bearish_probability_max');
        $custom_condition = $this->input->post('custom_condition');
        $live = $this->input->post('live');
        $script_start_time = $this->input->post('script_start_time');
        
        $this->load->model('Oc_iv_analysis_model');
        $oc_iv_data = $this->Oc_iv_analysis_model->displayOCIVDayWiseData( $date, $bullish_probability, $bearish_probability, $bullish_probability_min, $bullish_probability_max, $bearish_probability_min, $bearish_probability_max, $custom_condition, $live, $script_start_time );        
        
        echo json_encode($oc_iv_data); exit;
    }
    
    function getOCPDData(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');        
        $date = $this->input->post('date');
        $manual_date_to = $this->input->post('manual_date_to');  
        $live = $this->input->post('live');  
        $searching_expiry_date = $this->input->post('searching_expiry_date');  
        
        $this->load->model('Oc_pd_disp_analysis_model');        
        $oc_pd_data= $this->Oc_pd_disp_analysis_model->getOCPDData($company_id, $company_symbol, $date, $manual_date_to, $live, $searching_expiry_date);
        
        echo json_encode($oc_pd_data); exit;
        
    }
    
    function getOcPDScriptStartTime(){
        
        $date = $this->input->post('date');
        
        $this->load->model('Oc_pd_disp_analysis_model');
        $script_start_time_arr = $this->Oc_pd_disp_analysis_model->getScriptStartTime( $date );
        
        echo json_encode($script_start_time_arr); exit;
    }
    
    function displayOCPDDayWiseData(){
        
        $date = $this->input->post('date');
        
        $put_avg_decay = $this->input->post('put_avg_decay');
        $call_avg_decay = $this->input->post('call_avg_decay');
        
        $custom_condition = $this->input->post('custom_condition');
        $live = $this->input->post('live');
        $script_start_time = $this->input->post('script_start_time');
        
        $this->load->model('Oc_pd_disp_analysis_model');        
        $oc_pd_data = $this->Oc_pd_disp_analysis_model->displayOCPDDayWiseData( $date, $put_avg_decay, $call_avg_decay, $custom_condition, $live, $script_start_time);
        
        echo json_encode($oc_pd_data); exit;
        
    }
    
    function displayOCOPDayWiseData(){
        
        $date = $this->input->post('date');
        $custom_condition = $this->input->post('custom_condition');
        
        $this->load->model('Oc_op_disp_analysis_model');        
        $oc_op_data = $this->Oc_op_disp_analysis_model->displayOCOPDayWiseData( $date, $custom_condition );
        
        echo json_encode($oc_op_data); exit;
        
    }
    
    function displayHighOiNAddOiDayWiseData(){
        
        $date = $this->input->post('date');
        
        $this->load->model('Oc_h_oi_n_h_addoi_disp_analysis_model');        
        $oc_high_oi_n_add_oi_db_data = $this->Oc_h_oi_n_h_addoi_disp_analysis_model->displayHighOiNAddOiDayWiseData( $date );
        
        echo json_encode($oc_high_oi_n_add_oi_db_data); exit;
        
    }
    
    function getOcSpData(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');        
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $searching_expiry_date = $this->input->post('searching_expiry_date');   
        $strike_price= $this->input->post('strike_price');
        $live = $this->input->post('live');
        
        $this->load->model('Put_call_model'); 
        
        $oc_data = $this->Put_call_model->getOcSpData( $company_id, $company_symbol, $searching_underlying_date, $searching_expiry_date, $strike_price, $live );        
        
        echo json_encode($oc_data); exit;
        
    }
    
        
    function inserBulkBlockDeal(){
        
        $this->load->model('BulkBlock_model');
        
        $bulk_block_arr = $this->input->post('bulk_block_arr');
        
        $this->BulkBlock_model->inserBulkBlockDeal($bulk_block_arr);
    }
        
    function checkTodaysBulkBlockInserted(){
        
        $exchange= $this->input->post('exchange');
        $bulk_or_block = $this->input->post('bulk_or_block');
        
        $this->load->model('BulkBlock_model');

        return $this->BulkBlock_model->checkTodaysBulkBlockInserted($exchange, $bulk_or_block);
    }
    
    
    function getLatestFrUnderlyingDateofAll(){
        
        $this->load->model('Future_model');
        
        $underlying_date = $this->Future_model->getLatestFrUnderlyingDateofAll( );
        
        echo json_encode($underlying_date); exit;
        
    }
    
    function getLatestFrUnderlyingDate(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');
        $live = $this->input->post('live');
        
        $this->load->model('Future_model');
        
        $underlying_date = $this->Future_model->getLatestFrUnderlyingDate( $company_id, $company_symbol, $live );
        
        echo json_encode($underlying_date); exit;
        
    }
    
     
    function getFrCurrentExpiryDateByUnderlyingDate(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $live = $this->input->post('live');
        
        $this->load->model('Future_model');        
        
        $expiry_dates = $this->Future_model->getFrCurrentExpiryDateByUnderlyingDate( $company_id, $company_symbol, $searching_underlying_date, $live );
        
        echo json_encode($expiry_dates); exit;
        
    }
     
    function getFrCurrentExpiryDateByUnderlyingDateofAll(){
        
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        
        $this->load->model('Future_model');        
        
        $expiry_dates = $this->Future_model->getFrCurrentExpiryDateByUnderlyingDateofAll( $searching_underlying_date );
        
        echo json_encode($expiry_dates); exit;
        
    }
    
      
    function getFrDataOfStock(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');        
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $searching_underlying_date_to = $this->input->post('searching_underlying_date_to');
        $searching_expiry_date = $this->input->post('searching_expiry_date');   
        $live = $this->input->post('live');
        $searching_underlying_time = $this->input->post('searching_underlying_time');
        $get_all_expiry_date = $this->input->post('get_all_expiry_date');
        
        $this->load->model('Future_model'); 
        
        $oc_data = $this->Future_model->getFrDataOfStock( $company_id, $company_symbol, $searching_underlying_date, $searching_underlying_date_to, $searching_expiry_date, $live, $searching_underlying_time, $get_all_expiry_date );        
        
        echo json_encode($oc_data); exit;
        
    }
      
    function getFrDataOfAllStock(){
              
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $searching_expiry_date = $this->input->post('searching_expiry_date');  
        $turnover_sortby = $this->input->post('turnover_sortby');  
        $volume_sortby = $this->input->post('volume_sortby');  
        $oi_sortby = $this->input->post('oi_sortby');  
        $change_oi_sortby = $this->input->post('change_oi_sortby');  
        $change_oi_p_sortby = $this->input->post('change_oi_p_sortby');  
        $daily_volatility_sortby = $this->input->post('daily_volatility_sortby');  
        
        $this->load->model('Future_model'); 
        
        $oc_data = $this->Future_model->getFrDataOfAllStock( $searching_underlying_date, $searching_expiry_date, $turnover_sortby, $volume_sortby, $oi_sortby, $change_oi_sortby, $change_oi_p_sortby, $daily_volatility_sortby );        
        
        echo json_encode($oc_data); exit;
        
    }
      
    function getFrRolloverDataOfAllStock(){
              
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $rollover_sortby = $this->input->post('rollover_sortby');
        $rollcost_sortby = $this->input->post('rollcost_sortby');
        
        $this->load->model('Future_model'); 
        
        $fr_rollover_data = $this->Future_model->getFrRolloverDataOfAllStock( $searching_underlying_date, $rollover_sortby, $rollcost_sortby );        
        
        echo json_encode($fr_rollover_data); exit;
        
    }
         
    function getFrRolloverofSingleStock(){
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');        
        $searching_underlying_date = $this->input->post('searching_underlying_date');
        $searching_underlying_date_to = $this->input->post('searching_underlying_date_to');
        
        $this->load->model('Future_model'); 
        
        $oc_data = $this->Future_model->getFrRolloverofSingleStock( $company_id, $company_symbol, $searching_underlying_date, $searching_underlying_date_to);        
        
        echo json_encode($oc_data); exit;
        
    }
    
    function listTodayCMCompanies($last_inserted_company_id=0) {

        $this->load->model('Companies_model');

        $company_list = $this->Companies_model->listTodayCMCompanies($last_inserted_company_id);

        echo json_encode($company_list); exit;
    }
    
    function insertCMVolumeByTrade(){
        
        $this->load->model('Stock_data_model');
        
        $company_id = $this->input->post('company_id');
        $company_symbol = $this->input->post('company_symbol');
        $whole_data = $this->input->post('whole_data');
        
        $this->Stock_data_model->insertCMVolumeByTrade($company_id, $company_symbol, $whole_data);
    }
}
