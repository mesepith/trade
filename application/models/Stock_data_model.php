<?php

/*
 * @author : ZAHIR
 */

class Stock_data_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC :list All stock data
     */
    
    function stockDataByCompanySymbol($companySymbol, $filter, $date_interval_limit){
        
//        WHERE exec_datetime BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW();

        
        $this->db->where('status', 1);
        $this->db->where('company_symbol', $companySymbol);
        
        if(!empty($filter)){
            
            $this->db->where('stock_date >= ', $filter['from-date']);
            $this->db->where('stock_date <= ', $filter['to-date']);            
            
        }else{
            
            $this->db->where('stock_date BETWEEN DATE_SUB(NOW(), INTERVAL '.$date_interval_limit.' DAY) AND NOW()');
            
        }
//        $this->db->limit(4);
        $this->db->select('*');
        $this->db->order_by('created_at', 'asc');
        $query = $this->db->get('stock_data');        
        
//        echo $this->db->last_query();
//        exit;
        if( count($query->result()) > 0 ){
            
            $data = $query->result();

            return $query->result();
            
        }else{
            
            return false;
            
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get date count
     */
    function getIntervalLimitofDatesForNonFilter( $loop_count=0 ){
        
//         echo '$loop_count in fun(): ' . $loop_count . '<br/>';
        
        $this->db->where('status', 1);
        $this->db->where('company_symbol', LAST_SERIAL_FAMOUS_STOCK);
        $this->db->where('stock_date BETWEEN DATE_SUB(NOW(), INTERVAL '. (EACH_STOCK_QUERY_DATE_LIMIT + $loop_count) .' DAY) AND NOW()');        
        $this->db->order_by('created_at', 'asc');
        $this->db->select('id');        
        $query = $this->db->get('stock_data'); 
        
//        echo '<br/>';
//        echo $this->db->last_query();
//        echo '<br/>';
        
//        echo '<br/>';
//        echo '$query->num_rows() : ' . $query->num_rows();
//        echo '<br/>';
        
        
        if( $query->num_rows() >= MIN_STOCK_CHECK_COUNT ){
            
//            echo '<pre>'; print_r($data); exit;

            return EACH_STOCK_QUERY_DATE_LIMIT + $loop_count;
            
        }else{
            
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 7){ return false; }
            
            $date_interval_limit = $this->getIntervalLimitofDatesForNonFilter( $loop_count );
            
            if( !empty($date_interval_limit) ){
                
                return $date_interval_limit;
            }
        }
        
        
    }
    
    function insertStockDataLog( $data_log_arr ){
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $data_log_arr["created_at"] = date("Y-m-d H:i:s");
        
        
        $this->db->insert('stock_data_log', $data_log_arr);
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Stock_data_model';

            $error_db_data['model_methode_name'] = 'insertStockDataLog';

            $error_db_data['data'] = json_encode($data_log_arr);

            $error_db_data['query'] = $this->db->last_query();

            $error_db_data['error_code'] = $errorz['code'];

            $error_db_data['error_message'] = $errorz['message'];

            $error_db_data["created_at"] = date("Y-m-d H:i:s");

            $this->Db_error_log->insertDbErrorLog($error_db_data);
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id = $this->db->insert_id();
        
        
    }
    
    function insertStockData( $stock_data_arr, $stock_data_table ){
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        
        $this->db->insert($stock_data_table, $stock_data_arr);
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Stock_data_model';

            $error_db_data['model_methode_name'] = 'insertStockData';

            $error_db_data['data'] = json_encode($stock_data_arr);

            $error_db_data['query'] = $this->db->last_query();

            $error_db_data['error_code'] = $errorz['code'];

            $error_db_data['error_message'] = $errorz['message'];

            $error_db_data["created_at"] = date("Y-m-d H:i:s");

            $this->Db_error_log->insertDbErrorLog($error_db_data);
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id = $this->db->insert_id();
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check if stock_data table has todays any stock
     */
    
    public function checkStockTodayDataInserted( ) {
        
        $this->db->where('status', 1);
        $this->db->where('stock_date', date('Y-m-d'));
        $this->db->where('stock_time', '00:00:00');
        $this->db->order_by('id desc');
        $this->db->limit(1);
        $this->db->select('count(*) AS ispresent');
        $query = $this->db->get('stock_data');  
//        echo $this->db->last_query();
//        echo '<br/>';
//        print_r($query->result());
        
//        $is_present = $query->result()[0];
        
//        print_r($query->result()[0]->ispresent);
        
//        echo $is_present;
        
        if( $query->result()[0]->ispresent > 0 ){
            
            return 'present';
            
        }else{
            
            return 'absent';
            
        }
    }
    /*
     * DESC: Check if famous stock is inserted to stock_data table
     * We have chosen famous stock as TCS as it contains long position of alphabetical order
     */
    
    public function checkStockTodayFamousStockDataInserted( ) {
        
//        echo 'H: ' . date('H'); exit;
        
         /* If time is greater than 8 pm we will query by alphabetically last stock */
        if(date('H')>20){
            
            $QUERY_BY_STOCK = LAST_SERIAL_FAMOUS_STOCK;
            
        }else{
            
            $QUERY_BY_STOCK = FIRST_SERIAL_FAMOUS_STOCK;
        }
        
        $this->db->where('status', 1);
        $this->db->where('stock_date', date('Y-m-d'));
        $this->db->where('stock_time', '00:00:00');
        $this->db->where('company_symbol', $QUERY_BY_STOCK);
        $this->db->order_by('id desc');
        $this->db->limit(1);
        $this->db->select('count(*) AS total');
        $query = $this->db->get('stock_data');  
//        echo $this->db->last_query(); exit;
//        echo '<br/>';
//        echo '<pre>';
//        print_r($query->result()); exit;
        
//        $is_present = $query->result()[0];
        
//        print_r($query->result()[0]->ispresent);
        
//        echo $is_present;
        
        return $query->result()[0]->total;
        
    }
    
    /*
     * @author : ZAHIR
     * DESC :get stock detail
     */
    function getStockDetailByCompanyIdAndSymbol( $company_id, $company_symbol, $date, $date_to, $loop_count=0 ){
        
        $this->db->where('status', 1);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        
        
        
        
        if( $date_to ){
            
            $this->db->where('stock_date >= ', $date );
            $this->db->where('stock_date <= "' . $date_to . '"');
        }else{
            
            $this->db->where('stock_date = ', $date );
        }
        
        $this->db->select('company_name, open_price, stock_date, last_price, close_price, price_change, price_change_in_p, vwap, day_high_price, day_low_price, total_traded_volume, '
                . 'delivery_quantity, delivery_to_traded_quantity, pd_symbol_pe, pd_sector_pe, pd_sector_ind, total_traded_value, total_no_of_trades, volume_by_total_no_of_trade ');
        $this->db->order_by('created_at', 'asc');
        $query = $this->db->get('stock_data');  
        
        if( count($query->result()) > 0 ){
            
            $data = $query->result();
            
//            echo '<pre>'; print_r($data); exit;

            return $query->result();
            
        }else{
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 9){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->getStockDetailByCompanyIdAndSymbol( $company_id, $company_symbol, $date, $date_to, $loop_count );
            
//            echo count($data);
            
            if( count($data) >  0 ){
                
                return $data;
            }
//            echo '<pre>'; print_r($data);
        }
        
    }
    
    function compareCurrentPriceDayWise($date, $year_week_low_date_order, $year_week_high_date_order, $loop_count=0){
        
        $this->db->where('status', 1); 
        
        $this->db->where('stock_date = "' . $date . '"');  
        
        $this->db->select('company_id, company_symbol, last_price, close_price, year_week_low, year_week_low_date, year_week_high, year_week_high_date, stock_date');
        
        
        if(!empty($year_week_low_date_order)){
            
            if( $year_week_low_date_order ==='desc'){
                
                $this->db->order_by('year_week_low_date desc');
                
            }else{
                
                $this->db->order_by('year_week_low_date');
            }
            
        }else if(!empty($year_week_high_date_order)){
            
            if( $year_week_high_date_order ==='desc'){
                
                $this->db->order_by('year_week_high_date desc');
                
            }else{
                
                $this->db->order_by('year_week_high_date');
            }
            
        }
        
        
        $query = $this->db->get('stock_data');
        
        if (count($query->result()) > 0) {
        
            return $query->result();
        }else{
            
            $loop_count++;

            if($loop_count > 15){ return false; }
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->compareCurrentPriceDayWise( $date, $year_week_low_date_order, $year_week_high_date_order, $loop_count );
//            echo 'data <br/>';
//            echo '<pre>'; print_r($data);
            if( !empty($data) ){
                
                return $data;
            }
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Insert today's volume by total no of trades data
     */
    function insertCMVolumeByTrade($company_id, $company_symbol, $whole_data){
        
        $whole_data['updated_at'] = date("Y-m-d H:i:s");
        
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('stock_date', date('Y-m-d'));
//        $this->db->where('stock_date', '2020-06-15');
        $this->db->update('stock_data', $whole_data );
        
    }

    /*
    * @author: ZAHIR
    * DESC: Check todays stock is inserted or not
    */
    function checkTodaysStockInserted($company_symbol){

        $this->db->where('status', 1);
        $this->db->where('stock_date', date('Y-m-d'));
        $this->db->where('company_symbol', $company_symbol);
        $this->db->order_by('id desc');
        $this->db->limit(1);
        $this->db->select('count(*) AS total');
        $query = $this->db->get('stock_data');  
    //    echo $this->db->last_query(); exit;
    //    echo '<br/>';
//        echo '<pre>';
//        print_r($query->result()); exit;
        
//        $is_present = $query->result()[0];
        
//        print_r($query->result()[0]->ispresent);
        
//        echo $is_present;
        
        return $query->result()[0]->total;

    }

    /*
    @author: Zahir
    Desc: Last 2 weeks stocks volume in a list
    */

    function getStocksTwoWeeksVolume($company_symbol, $table_rows){

        $this->db->where('status', 1);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->order_by('id desc');
        $this->db->limit($table_rows);
        $this->db->select('total_traded_volume, stock_date');
        $query = $this->db->get('stock_data'); 

        // echo '<pre>'; print_r($query->result()); 

        return $query->result();

    }
   
}
