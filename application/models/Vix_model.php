<?php

/*
 * @author : ZAHIR
 */

class Vix_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC : insert india vix
     */
    
    function insertIndiaVix( $data ){
        
        $last_vix_arr = $this->getLastVix();
        
        $last_vix = $last_vix_arr->last_price;  
        
        if( ( number_format($last_vix) != number_format($data['last_price']) )  || ( $data['market_date'] != $last_vix_arr->market_date ) ){
        
            $data["created_at"] = date("Y-m-d H:i:s");

            $this->db->insert('vix', $data);
            return $insert_id = $this->db->insert_id();
        
        }else{
            
            return 'No new VIX , Last VIX Present in Db is ' . $last_vix;
        }
        
    }

    /*
     * Get Last Vix
     */
    function getLastVix( $market_date=false  ){
        
        $this->db->where('status', 1);  
        
        if( !empty($market_date) ){
            
            $this->db->where('market_date', $market_date );  
        }
        
        $this->db->order_by('id', 'desc'); 
        $this->db->limit(1); 
        $this->db->select('last_price, market_date'); 
        
        $query = $this->db->get('vix');
        
        if (count($query->result()) > 0 ) {
            
//            return $query->result()[0]->last_price;
            return $query->result()[0];
        }else{
            
            return false;
        }
    }
   
}
    
   
