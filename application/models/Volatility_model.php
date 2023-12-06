<?php

/*
 * @author : ZAHIR
 */

class Volatility_model extends CI_Model {
    
    /*
     * Insert daily volatility and annual volatility
     */
    
    function insertDailyAnnualyVolatility($volatility_arr){
        
        $old_volatility = $this->checkVolatilityExists($volatility_arr);                        
        
        if( empty($old_volatility) ){ /* if no data return then insert entry */
            
            $this->db->insert('volatility', $volatility_arr);

            return $insert_id = $this->db->insert_id();
            
        }else{ 
            
            return 'exists';
            
        }
        
        
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check lot size is already exists
     */
    
    function checkVolatilityExists( $volatility_arr ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $volatility_arr['company_id']); 
        $this->db->where('company_symbol', $volatility_arr['company_symbol']); 
        $this->db->where('market_date', $volatility_arr['market_date']); 
        $this->db->select('id');
        $query = $this->db->get('volatility');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return false;
        }
        
    }    
    
    function dispDailyVolatility( $market_date, $daily_volatility_p, $only_derivative, $loop_count=0 ){
        
        $this->db->where('status', 1); 
         
        $this->db->where('market_date', $market_date);
        
        if( !empty($daily_volatility_p)){
            
            if( $daily_volatility_p === 'high' ){
                
                $this->db->order_by('daily_volatility_p desc');
                
            }else if( $daily_volatility_p === 'low' ){
                
                $this->db->order_by('daily_volatility_p');
            }
            
        }
        
        if( !empty($only_derivative) && $only_derivative=== 'yes'){
            
            $this->db->where('derivative', 1); 
        }
        
        $query = $this->db->get('volatility');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            $loop_count++;

            if($loop_count > 5){ return false; }
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $market_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($market_date)));
            
            $data = $this->dispDailyVolatility( $market_date, $daily_volatility_p, $only_derivative, $loop_count );
            
            if( count($data) >  0 ){
                
                return $data;
            }
            
        }
    }
    
    /*
     * Display volatility company wise
     */
    function dispVolatilityCompanyWise( $company_id, $company_symbol ,$market_date, $market_date_to ){
        
        $this->db->where('status', 1); 
        
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        
        if( $market_date_to ){
            
            $this->db->where('market_date >= ', $market_date );
            $this->db->where('market_date <= "' . $market_date_to . '"');
        }else{
            
            $this->db->where('market_date = ', $market_date );
        }
        
        $query = $this->db->get('volatility');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
}
