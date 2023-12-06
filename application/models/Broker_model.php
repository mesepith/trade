<?php

/*
 * @author : ZAHIR
 */

class Broker_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC : insert client fund data by broker
     */
    
    function insertClientFund( $data ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $this->db->insert('broker_client_fund', $data);
        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            echo '<pre>';
            print_r($errorz);
            
            $insert_id = false;
            
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
        
    }
    
   
}
    
   
