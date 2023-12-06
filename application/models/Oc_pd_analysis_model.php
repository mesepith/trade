<?php

/*
 * @author : ZAHIR
 * DESC: Option chain , premium decay analysis data
 */

class Oc_pd_analysis_model extends CI_Model {
    
    /*
     * @author: ZAHIR
     * DESC: Last inserted company list
     */
    
    function lastInsertedCompanyList(){
          
        $this->db->where('status', 1); 
        $this->db->where('underlying_date_end', date('Y-m-d')); 
//        $this->db->where('underlying_date', '2019-11-28'); 
        $this->db->order_by('id desc'); 
        $this->db->limit(1);
        $this->db->select('company_id');
        $query = $this->db->get('oc_pd_input');
        
        if (count($query->result()) > 0 && !empty( $query->result()[0]->company_id ) && $query->result()[0]->company_id > 0) {
        
            return $query->result()[0]->company_id;
            
        }else{
            
            return false;
        }
        
        return $query->result();
    }
    
     /*
     * @author: ZAHIR
     * DESC: non inserted company list
     */
    
    function oCPDNonInserteDCompanyList( $last_calculated_company ){
          
        $this->db->where('status', 1); 
        if( $last_calculated_company ){
            
            $this->db->where('company_id >', $last_calculated_company);
            
        }
        $this->db->select('*');
//        $this->db->limit('1'); #delete it after test
        $query = $this->db->get('put_call_companies');
        
        return $query->result();
    }    
    
    /*
     * @author: ZAHIR
     * DESC: Get underlying date and underlying price 
     */
    
    function getLatestUnderlyingDateNPrice( $company_id, $company_symbol ){
          
        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->order_by('underlying_date desc'); 
        $this->db->limit(1);
        $this->db->select('underlying_date, underlying_price');
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {
        
            return $query->result()[0];
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get underlying date and underlying price of end of day data
     */
    
    function getLatestUnderlyingDateNPriceEOD( $company_id, $company_symbol ){
          
        $this->db->where('status', 1); 
        
        $this->db->where('market_running', 0);
        
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->order_by('underlying_date desc'); 
        $this->db->limit(1);
        $this->db->select('underlying_date, underlying_price');
        $query = $this->db->get('put_call_expiry');
        
        if (count($query->result()) > 0) {
        
            return $query->result()[0];
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: get lowest underlying price of a stock in oc in a date range for an expiry date
     */
    
    function getLowestUP($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date){
        
        $this->db->where('status', 1);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('underlying_date >=', $underlying_date_start);
        $this->db->where('underlying_date <=', $underlying_date_end);
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->order_by('underlying_price');
        $this->db->limit(1);
        $this->db->select('underlying_price');
        $query = $this->db->get('put_call_expiry');
        
        if ( count( $query->result() ) > 0  && ( !empty($query->result()[0]->underlying_price) ) ) {
        
            return $query->result()[0]->underlying_price;
            
        }else{
            
            return false;
        }
    }
    /*
     * @author: ZAHIR
     * DESC: get highest underlying price of a stock in oc in a date range for an expiry date
     */
    
    function getHighestUP($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date){
        
        $this->db->where('status', 1);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('underlying_date >=', $underlying_date_start);
        $this->db->where('underlying_date <=', $underlying_date_end);
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->order_by('underlying_price desc');
        $this->db->limit(1);
        $this->db->select('underlying_price');
        $query = $this->db->get('put_call_expiry');
        
        if ( count( $query->result() ) > 0  && ( !empty($query->result()[0]->underlying_price) ) ) {
        
            return $query->result()[0]->underlying_price;
            
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: fetch strike price that has highest oi in out of the money of call side
     */
    function strikePriceWithHighestOiInCall($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $current_price){
        
        $this->db->where('status', 1);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('underlying_date >=', $underlying_date_start);
        $this->db->where('underlying_date <=', $underlying_date_end);
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->where('strike_price >', $current_price); #strike price needs to be greater than underlying price(current price) to make it out of the money in put
        $this->db->where('strike_price !=', '(NULL)');
        $this->db->where('strike_price >', 0);
        $this->db->order_by('calls_oi desc');
        $this->db->limit(1);
        $this->db->select('strike_price ');
        $query = $this->db->get('put_call');
        
        if ( count( $query->result() ) > 0  && ( !empty($query->result()[0]->strike_price) ) ) {
        
            return $query->result()[0]->strike_price;
            
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: fetch strike price that has second highest oi in out of the money of call side
     */
    function strikePriceWithSecondHighestOiInCall($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $current_price, $strike_price_with_highest_oi_in_call_otm){
        
        $this->db->where('status', 1);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('underlying_date >=', $underlying_date_start);
        $this->db->where('underlying_date <=', $underlying_date_end);
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->where('strike_price >', $current_price); #strike price needs to be greater than underlying price(current_price) to get data from out of the money in call
        $this->db->where('strike_price !=', $strike_price_with_highest_oi_in_call_otm);
        $this->db->where('strike_price !=', '(NULL)');
        $this->db->where('strike_price >', 0);
        $this->db->order_by('calls_oi desc');
        $this->db->limit(1);
        $this->db->select('strike_price ');
        $query = $this->db->get('put_call');
        
        if ( count( $query->result() ) > 0  && ( !empty($query->result()[0]->strike_price) ) ) {
        
            return $query->result()[0]->strike_price;
            
        }else{
            
            return false;
        }
        
    }
    /*
     * @author: ZAHIR
     * DESC: fetch strike price that has highest oi in out of the money of put side
     */
    function strikePriceWithHighestOiInPut($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $current_price){
        
        $this->db->where('status', 1);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('underlying_date >=', $underlying_date_start);
        $this->db->where('underlying_date <=', $underlying_date_end);
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->where('strike_price <', $current_price); #strike price needs to be less than underlying price(current_price) to make it out of the money in put
        $this->db->where('strike_price >', 0); 
        $this->db->where('strike_price !=', '(NULL)');
        $this->db->order_by('puts_oi desc');
        $this->db->limit(1);
        $this->db->select('strike_price ');
        $query = $this->db->get('put_call');
        if ( count( $query->result() ) > 0  && ( !empty($query->result()[0]->strike_price) ) ) {
        
            return $query->result()[0]->strike_price;
            
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: fetch strike price that has second highest oi in out of the money of put side
     */
    function strikePriceWithSecondHighestOiInPut($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $current_price, $strike_price_with_highest_oi_in_put_otm){
        
        $this->db->where('status', 1);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('underlying_date >=', $underlying_date_start);
        $this->db->where('underlying_date <=', $underlying_date_end);
        $this->db->where('expiry_date', $expiry_date); 
        $this->db->where('strike_price <', $current_price); #strike price needs to be less than underlying price(current_price) to make it out of the money in put
        $this->db->where('strike_price >', 0);
        $this->db->where('strike_price !=', $strike_price_with_highest_oi_in_put_otm);
        $this->db->where('strike_price !=', '(NULL)');
        $this->db->order_by('puts_oi desc');
        $this->db->limit(1);
        $this->db->select('strike_price ');
        $query = $this->db->get('put_call');
        if ( count( $query->result() ) > 0  && ( !empty($query->result()[0]->strike_price) ) ) {
        
            return $query->result()[0]->strike_price;
            
        }else{
            
            return false;
        }
        
    }
        /*
         *@author: ZAHIR
         * DESC: Get market range of stock by strike price
         */
        
        function getMarketRangeBySP($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $lowest_up, $highest_up){
            
            $this->db->where('status', 1);
            $this->db->where('company_id', $company_id);
            $this->db->where('company_symbol', $company_symbol);
            $this->db->where('underlying_date >=', $underlying_date_start);
            $this->db->where('underlying_date <=', $underlying_date_end);
            $this->db->where('expiry_date', $expiry_date); 
            $this->db->where('strike_price >=', $lowest_up);
            $this->db->where('strike_price <=', $highest_up);
            $this->db->where('strike_price !=', '(NULL)');
            $this->db->where('strike_price >', 0);
            $this->db->select('strike_price ');
            $this->db->group_by('strike_price ');
            $query = $this->db->get('put_call');
            
//            echo $this->db->last_query();
            
            if ( count( $query->result() ) > 0  ) {
        
                return $query->result();

            }else{

                return false;
            }
            
        }
        
        /*
         * @author: ZAHIR
         * DESC: Get lowest strike price
         */
        function getLowestStrikePrice($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $second_lowest_strike_price){
         
            $this->db->where('status', 1);
            $this->db->where('company_id', $company_id);
            $this->db->where('company_symbol', $company_symbol);
            $this->db->where('underlying_date >=', $underlying_date_start);
            $this->db->where('underlying_date <=', $underlying_date_end);
            $this->db->where('expiry_date', $expiry_date); 
            $this->db->where('strike_price <', $second_lowest_strike_price);
            $this->db->where('strike_price >', 0);
            $this->db->where('strike_price !=', '(NULL)');
            $this->db->select('strike_price');
            $this->db->order_by('strike_price desc');
            $this->db->limit('1 ');
            $query = $this->db->get('put_call');
            
            if ( count( $query->result() ) > 0  && ( !empty($query->result()[0]->strike_price) ) ) {
        
                return $query->result()[0]->strike_price;

            }else{

                return false;
            }
            
        }
        /*
         * @author: ZAHIR
         * DESC: Get lowest strike price
         */
        function geHighestStrikePrice($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $second_highest_strike_price){
         
            $this->db->where('status', 1);
            $this->db->where('company_id', $company_id);
            $this->db->where('company_symbol', $company_symbol);
            $this->db->where('underlying_date >=', $underlying_date_start);
            $this->db->where('underlying_date <=', $underlying_date_end);
            $this->db->where('expiry_date', $expiry_date); 
            $this->db->where('strike_price >', $second_highest_strike_price);
            $this->db->where('strike_price >', 0);
            $this->db->where('strike_price !=', '(NULL)');
            $this->db->select('strike_price');
            $this->db->order_by('strike_price');
            $this->db->limit('1 ');
            $query = $this->db->get('put_call');
            
            if ( count( $query->result() ) > 0  && ( !empty($query->result()[0]->strike_price) ) ) {
        
                return $query->result()[0]->strike_price;

            }else{

                return false;
            }
            
        }
        
    /*
     * @author: ZAHIR
     * DESC: get Premium Of Put in out of the money
     */
        
    function getPremiumOfPut($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $strike_price_with_highest_oi_in_put_otm, $current_price, $market_range_arr){
        
//        SELECT underlying_price, puts_ltp, underlying_date FROM put_call 
//WHERE company_symbol='TCS' AND expiry_date='2019-12-26'  AND underlying_date >='2019-11-08' AND underlying_date <='2019-12-06' 
//AND underlying_price >2010 AND underlying_price <=2030 
//AND strike_price=1980;
        
        $premium_arr = array();
        
        $current_price_condition_found = 'no';
        $put_otm_range_end = 'no';
        
        for( $i=0; $i< (count($market_range_arr)-1); $i++ ){
            
            if( $market_range_arr[$i+1] <= $strike_price_with_highest_oi_in_put_otm  ){ #if market price less than strike price then it will be in the money, so ignore it
                
                echo '<br/>';
                echo '$strike_price_with_highest_oi_in_put_otm : '. $strike_price_with_highest_oi_in_put_otm;               
                echo '<br/>';
                echo 'Market price is less then strike price so it will be in the money(INM), so ignore it %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%';
                echo '<br/>';
                echo '<br/>';
                continue;
                
            }
            
            if( $put_otm_range_end ==='yes' ){ #'Put out of the money range found ::::::::::::::: :::::::::::::::::::::::::: ';

                return $premium_arr;
                
            }
            
            $this->db->where('status', 1);
            $this->db->where('company_id', $company_id);
            $this->db->where('company_symbol', $company_symbol);
            
            if( $current_price_condition_found==='no' ){
            
                $this->db->where('underlying_date >=', $underlying_date_start);
                $this->db->where('underlying_date <', $underlying_date_end);
                
                
                if($strike_price_with_highest_oi_in_put_otm > $market_range_arr[$i] && $strike_price_with_highest_oi_in_put_otm< $market_range_arr[$i+1]){
                    
                    echo '<br/>';
                    echo 'make underlying price greater than strike price to get the data of out of the money(otm), bcz: in put SP must be smaller than market price for OTM ::::::::::::::::::::::::';
                    echo '<br/>';
                    
                    $this->db->where('underlying_price >', $strike_price_with_highest_oi_in_put_otm ); #make underlying price greater than strike price to get the data of out of the money(otm), bcz: in put SP must be smaller than market price for OTM
                    
                }else{
                    
                    $this->db->where('underlying_price >', $market_range_arr[$i]);
                    
                }
                
                $premium_arr[$i]['min_condition'] = $market_range_arr[$i];
                
                if( $market_range_arr[$i+1] >= $current_price ){
            
                    $this->db->where('underlying_price <=', ( $current_price - 0.1 )  );
                    $premium_arr[$i]['max_condition'] = $current_price - 0.1;
                    
                    $current_price_condition_found = 'yes';

                }else{

                    $this->db->where('underlying_price <=', $market_range_arr[$i+1] );
                    
                    
                    $premium_arr[$i]['max_condition'] = $market_range_arr[$i+1];
                }
            
            }else if( $current_price_condition_found ==='yes'){
                
                $premium_arr[$i]['current_market_price'] = $current_price;
                $this->db->where('underlying_date =', $underlying_date_end);
                
                $put_otm_range_end = 'yes';
                
            }
            
            $this->db->where('expiry_date', $expiry_date); 
                        
            $this->db->where('strike_price =', $strike_price_with_highest_oi_in_put_otm);
            $this->db->order_by('underlying_date desc');
            $this->db->limit(1);
            
            $this->db->select('underlying_price, puts_ltp, underlying_date');
            $query = $this->db->get('put_call');
            
//            echo '<br/>';
//            echo '<br/>';
//            echo $this->db->last_query();
//            echo '<br/>';
//            echo '<br/>';
            
            if ( count( $query->result() ) > 0 && ( !empty($query->result()[0]->puts_ltp) ) ) {
                
                $premium_arr[$i]['puts_ltp'] = $query->result()[0]->puts_ltp;

            }else{
                echo '<br/>';
                echo '<br/>';
                echo 'Puts: Query in else which is in false condition i.e no data found ***********************************************';
                echo '<br/>';
                echo '<br/>';
                
//                return false;
            }
            
            
        }
        
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get Premium Of Call
     * 
     * SELECT underlying_price, puts_ltp, underlying_date FROM put_call 
WHERE company_symbol='TCS' AND expiry_date='2019-12-26' AND underlying_date ='2019-12-06'  
AND strike_price=2200; #current market
     * 
     * SELECT underlying_price, calls_ltp, underlying_date FROM put_call 
WHERE company_symbol='TCS' AND expiry_date='2019-12-26'  AND underlying_date >='2019-11-08' AND underlying_date <'2019-12-06' 
AND underlying_price >=(2123.65+0.1) AND underlying_price <2130 AND strike_price=2200;
     * 
     * SELECT underlying_price, calls_ltp, underlying_date FROM put_call 
WHERE company_symbol='TCS' AND expiry_date='2019-12-26'  AND underlying_date >='2019-11-08' AND underlying_date <'2019-12-06'  
AND underlying_price >=2130 AND underlying_price <2150 AND strike_price=2200;
     */
    
    function getPremiumOfCall($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $strike_price_with_highest_oi_in_call_otm, $current_price, $market_range_arr){
        
        $premium_arr = array();
        
        array_push($market_range_arr, $current_price);
        
        $market_range_arr = array_unique($market_range_arr);        
        
        sort($market_range_arr);
        
        $current_price_condition_found = 'no';
        $call_otm_range_start_second = 'no';
        
        for( $i=0; $i< count($market_range_arr); $i++ ){
            
            if( $strike_price_with_highest_oi_in_call_otm <= $market_range_arr[$i] ){#if strike_price_with_highest_oi_in_call_otm is smaller then market price then it is in in the money, so ignore it 
                
                continue;
            }
            
            if($market_range_arr[$i] < $current_price){ #since market price is less than current price then ignore it since it will fall under in the money(INM)
                
                continue;
            }
            
            $this->db->where('status', 1);
            $this->db->where('company_id', $company_id);
            $this->db->where('company_symbol', $company_symbol);
            
            if( $market_range_arr[$i] === $current_price ){ #inside current price
                
                $current_price_condition_found = 'yes';
                $this->db->where('underlying_date =', $underlying_date_end);
                
                $premium_arr[$i]['current_market_price'] = $current_price;
                
                
            }else{
            
                $this->db->where('underlying_date >=', $underlying_date_start);
                $this->db->where('underlying_date <', $underlying_date_end);
                
                if($call_otm_range_start_second==='yes'){
                    
                    $this->db->where('underlying_price >=', $market_range_arr[$i-1]);
                    $premium_arr[$i]['min_condition'] = $market_range_arr[$i-1];
                    
                }else{
                    
                    $this->db->where('underlying_price >=', ($market_range_arr[$i-1]+0.1) );
                    $call_otm_range_start_second='yes';
                    $premium_arr[$i]['min_condition'] = $market_range_arr[$i-1]+0.1;
                }
                
                
                $this->db->where('underlying_price <', $market_range_arr[$i] );
                $premium_arr[$i]['max_condition'] = $market_range_arr[$i];
            
            }
            
            $this->db->where('expiry_date', $expiry_date); 
                        
            $this->db->where('strike_price =', $strike_price_with_highest_oi_in_call_otm);
            
            $this->db->order_by('underlying_date desc');
            $this->db->limit(1);
            
            $this->db->select('underlying_price, calls_ltp, underlying_date');
            $query = $this->db->get('put_call');
            
            echo '<br/>';
            echo '$strike_price_with_highest_oi_in_call_otm : '. $strike_price_with_highest_oi_in_call_otm;                 
            echo '<br/>';
            echo '$market_range_arr[$i-1] : '. $market_range_arr[$i-1];
            echo '<br/>';
            echo '$market_range_arr[$i] : '. $market_range_arr[$i];
            echo '<br/>';
            echo '<br/>';
            echo $this->db->last_query();
            echo '<br/>';
            echo '<br/>';
            
            if ( count( $query->result() ) > 0 && ( !empty($query->result()[0]->calls_ltp) ) ) {
                
//                echo '<pre>'; print_r($query->result());
                $premium_arr[$i]['calls_ltp'] = $query->result()[0]->calls_ltp;

            }else{
                echo '<br/>';
                echo '<br/>';
                echo 'Calls: Query in else which is in false condition i.e no data found ***********************************************';
                echo '<br/>';
                echo '<br/>';
                
//                return false;
            }
        
        }
        
//        echo '<pre>'; print_r($premium_arr);
        
        return $premium_arr;
            
    }
    
    
}
    
   
