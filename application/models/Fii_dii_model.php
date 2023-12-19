<?php

/*
 * @author : ZAHIR
 */

class Fii_dii_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC : insert total investment by fii and dii
     */
    
    function insertTotalInvestOftradingActivity( $data ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        
        $this->db->insert('fii_dii_activity', $data);
        return $insert_id = $this->db->insert_id();
    }
    
     /*
     * @author: ZAHIR
     * DESC: insert sectorwise investment data of fpi/fpi from NSDL
     */
    
    function insertNsdlSectoreInvestDataofFii( $data ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        
        $this->db->insert('fii_sector_invest', $data);
        return $insert_id = $this->db->insert_id();
        
    }
    
   
    function totalInvestment($invest_date, $invest_date_to, $loop_count=0){
        
        $this->db->where('status', 1); 
        
        if( !empty($invest_date_to)){
            
            $this->db->where('investment_date >= ', $invest_date );
            $this->db->where('investment_date <= "' . $invest_date_to . '"');
        }else{
            
            $this->db->where('investment_date', $invest_date);
        }
        
        $query = $this->db->get('fii_dii_activity');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            if( !empty($invest_date_to) ){ return false;}
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $invest_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($invest_date)));
            
            $data = $this->totalInvestment( $invest_date, $invest_date_to, $loop_count );
            
//            echo '<pre>'; print_r($data);
            
//            echo count($data);
            
            if( count($data) >  0 ){
                
                return $data;
            }
        }
    }
    
    function insertFiiDerivativeData( $data ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        
        $this->db->insert('fii_derivative', $data);
        return $insert_id = $this->db->insert_id();
        
    }
    
    /*
     * Fetch FII Derivative Data
     */
    function fetchFiiDerivative( $market_date, $market_date_to, $source, $product, $loop_count=0 ){
     
        $this->db->where('status', 1); 
        
        if( !empty($market_date_to)){
            
            $this->db->where('reporting_date >= ', $market_date );
            $this->db->where('reporting_date <= "' . $market_date_to . '"');
        }else{
            
            $this->db->where('reporting_date', $market_date);
        }
        
        if( !empty($source)){
            
            $this->db->where('source' , $source );
        }
        
        if( !empty($product)){
            
            $this->db->where('derivative_products' , $product );
        }
        
        $query = $this->db->get('fii_derivative');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
//            if( !empty($market_date_to) ){ return false;}
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $market_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($market_date)));
            
            $data = $this->fetchFiiDerivative( $market_date, $market_date_to, $source, $product, $loop_count );
            
//            echo '<pre>'; print_r($data);
            
//            echo count($data);
            
            if( !empty($data) && count($data) >  0 ){
                
                return $data;
            }
        }
        
    }
    
    /*
     * Fetch Fii Investing Sector List
     */
    function fiiInvestingsectoList(){
        
        $this->db->where('status', 1); 
        $this->db->group_by('sector_name'); 
        $this->db->order_by('sector_name'); 
        
        $query = $this->db->get('fii_sector_invest');
        
        return $query->result();
    }

    /*
     * Fetch Fii Investing Sector List Date wise
     */
    function fiiInvestingsectoListDateWise($market_date, $market_date_to){
        
        $this->db->where('status', 1); 
        $this->db->group_by('sector_name'); 
        $this->db->order_by('sector_name');
        $this->db->where('report_date >= ', $market_date );
        $this->db->where('report_date <= "' . $market_date_to . '"');
        
        $query = $this->db->get('fii_sector_invest');
        
        return $query->result();
    }
    
    /*
     * Display Fii Sector Investment Data
     */
    function fetchFiiSectorData( $market_date, $market_date_to, $sector, $loop_count=0 ){
        
        $this->db->where('status', 1); 
        
        if( !empty($market_date_to)){
            
            $this->db->where('report_date >= ', $market_date );
            $this->db->where('report_date <= "' . $market_date_to . '"');
        }else{
            
            $this->db->where('report_date', $market_date);
        }
        
        if( !empty($sector)){
            
            $this->db->where('sector_name' , $sector );
        }
        
        $this->db->order_by('report_date'); 
        
        $query = $this->db->get('fii_sector_invest');
//        echo $this->db->last_query() . '<br/>';
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
//            if( !empty($market_date_to) ){ return false;}
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 65){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $market_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($market_date)));
            
            $data = $this->fetchFiiSectorData( $market_date, $market_date_to, $sector, $loop_count );
            
//            echo '<pre>'; print_r($data);
            
//            echo count($data);
            
            if( !empty($data) && count($data) >  0 ){
                
                return $data;
            }
        }
    }
    
    /*
     * Volume and Turnover data of top 10 Clearing Members (no. of contracts) in Equity Derivatives as on Apr 13, 2020 (Amounts in Rs. Crore)
     */
    
    function insertNseTopClearingMember( $data ){
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $this->db->insert('exchange_clearing_member', $data);
        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            $insert_id = false;
            
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
        
    }
    
    /*
     * Category-Wise Turnover
     */
    function categoryWiseTurnover( $data ){
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $this->db->insert('category_wise_turnover', $data);
        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            $insert_id = false;
            
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
        
    }
    
    /*
     * Fetch Category wise turnover
     */
    
    function fetchCatWiseTrnvr( $market_date, $market_date_to, $category_chkbox, $trading_type, $loop_count=0 ){
        
        $this->db->where('status', 1); 
        $this->db->where('trading_type', $trading_type); 
        
        if( !empty($market_date_to)){
            
            $this->db->where('market_date >= ', $market_date );
            $this->db->where('market_date <= "' . $market_date_to . '"');
        }else{
            
            $this->db->where('market_date', $market_date);
        }
        
        if( !empty($category_chkbox)){
            
            $this->db->where('category' , $category_chkbox );
        }
        
        $query = $this->db->get('category_wise_turnover');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;

            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $market_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($market_date)));
            
            $data = $this->fetchCatWiseTrnvr( $market_date, $market_date_to, $category_chkbox, $trading_type, $loop_count );
            
            if( count($data) >  0 ){
                
                return $data;
            }
        }
    }
    
    function fetchExchangeClearMembr( $market_date, $market_date_to, $enable_to_date_chkbox, $loop_count=0 ){
        
        $this->db->where('status', 1); 
        
        if( !empty($market_date_to) && $enable_to_date_chkbox==='yes' ){
            
            $this->db->where('market_date >= ', $market_date );
            $this->db->where('market_date <= "' . $market_date_to . '"');
            $this->db->where('serial_no ', 11 );
        }else{
            
            $this->db->where('market_date', $market_date);
        }
        
        $query = $this->db->get('exchange_clearing_member');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;

            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $market_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($market_date)));
            
            $data = $this->fetchExchangeClearMembr( $market_date, $market_date_to, $enable_to_date_chkbox, $loop_count );
            
            if( count($data) >  0 ){
                
                return $data;
            }
        }
        
    }
}
    
   
