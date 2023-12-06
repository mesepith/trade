<?php

/*
 * @author : ZAHIR
 */

class Nifty_model extends CI_Model {
    
    /*
     * Insert Nifty top 10 stock
     */
    
    function insertNiftyTopWeightageStock($nifty_top_arr){
        
        $old_nifty_top = $this->checkNiftyTopWeightageStockExists($nifty_top_arr);                        
        
        if( empty($old_nifty_top) ){ /* if no data return then insert entry */
            
            $this->db->insert('nifty_top', $nifty_top_arr);

            return $insert_id = $this->db->insert_id();
            
        }else{ 
            
            return 'exists';
            
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check nifty top 10 stock exists
     */
    
    function checkNiftyTopWeightageStockExists( $nifty_top_arr ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $nifty_top_arr['company_id']); 
        $this->db->where('weightage', $nifty_top_arr['weightage']); 
        $this->db->where('market_date', $nifty_top_arr['market_date']); 
        $this->db->select('id');
        $query = $this->db->get('nifty_top');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return false;
        }
        
    }
    
    /*
     * Fetch Nifty Heavy Stocks
     */
    function fetchNiftyHeavyStock( $market_date, $weightage_sortby, $date_interval=1 ){
        
        $this->db->where('status', 1); 
        
        $this->db->where('market_date >= DATE_SUB("'.$market_date.'", INTERVAL '.$date_interval.' DAY)');
                 
        if( !empty($weightage_sortby)){
            
            if( $weightage_sortby === 'high' ){
                
                $this->db->order_by('weightage', 'desc');
                
            }else if( $weightage_sortby === 'low' ){
                
                $this->db->order_by('weightage', 'asc');
            }
        }
//        $this->db->limit(2);
        $query = $this->db->get('nifty_top');
//        echo $this->db->last_query() . '<br/>';
        if (count($query->result()) > 10 ) {
        
            return $query->result();
            
        }else{
            
            if($date_interval > 30){ return false; }
            
            $date_interval++;
            
            $data = $this->fetchNiftyHeavyStock( $market_date, $weightage_sortby, $date_interval );
            
            if( count($data) >  0 ){
                
                return $data;
            }
        }
    }
    
}
