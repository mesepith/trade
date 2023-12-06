<?php

/*
 * @author : ZAHIR
 */

class Rbi_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC : insert rbi 91 day t - bills
     */
    
    function insertRbiTBill( $data ){
        
        $last_t_bill = $this->getLastTBill($data['key']);
        
        if( $last_t_bill != $data['value'] ){
            
            $data["created_at"] = date("Y-m-d H:i:s");
        
            $this->db->insert('extra_info', $data);
            return $insert_id = $this->db->insert_id();
            
        }else{
            
            return 'No new T Bill , Last T Bill Present in Db is ' . $last_t_bill;
        }
        
    }
    
    /*
     * Get Last T Bill
     */
    function getLastTBill( $key ){
        
        $this->db->where('status', 1); 
        $this->db->where('key', $key); 
        $this->db->order_by('id', 'desc'); 
        $this->db->limit(1); 
        $this->db->select('value'); 
        
        $query = $this->db->get('extra_info');
        
        if (count($query->result()) > 0 ) {
            
            return $query->result()[0]->value;
        }else{
            
            return false;
        }
    }
    
   
}
    
   
