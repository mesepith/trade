<?php

/*
 * @author : ZAHIR
 */

class MostActive_model extends CI_Model {
    
    /*
     * Most Active Data Data insert
     */
    
    function insertActiveData($active_data_arr){
            
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $this->db->insert('most_active', $active_data_arr);
        
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
