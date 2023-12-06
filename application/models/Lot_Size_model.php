<?php

/*
 * @author : ZAHIR
 */

class Lot_Size_model extends CI_Model {
    
    /*
     * Insert monthly lot size
     */
    
    function inserMonthlytLotSize($lot_arr){
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $this->db->insert('lot_size_monthly', $lot_arr);

        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            $insert_id = false;
            
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
        
    }
    
    /*
     * Insert lot size of Option Chain
     */
    
    function insertLotSize($lot_arr){
        
        $old_lot_size = $this->checkLotExists($lot_arr);                        
        
        if( empty($old_lot_size) ){ /* if no data return then insert entry */
            
            $this->db->insert('lot_size', $lot_arr);

            return $insert_id = $this->db->insert_id();
            
        }else if( $old_lot_size !== $lot_arr['size'] ){ /*If old lot size is not similar with new lot size then inactive previous lot row and insert new lot arr */
            
            $this->db->where('status', 1); 
            $this->db->where('company_id', $lot_arr['company_id']); 
            $this->db->where('company_symbol', $lot_arr['company_symbol']); 
            $this->db->where('derivative_type', $lot_arr['derivative_type']); 
            $this->db->update('lot_size', array('status' => 0,'updated_at'=> date("Y-m-d H:i:s") ));
            
            return $this->db->insert('lot_size', $lot_arr);
            
        }else{
            
            return 1;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check lot size is already exists
     */
    
    function checkLotExists($lot_arr ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $lot_arr['company_id']); 
        $this->db->where('company_symbol', $lot_arr['company_symbol']); 
        $this->db->where('derivative_type', $lot_arr['derivative_type']); 
        $this->db->select('size');
        $query = $this->db->get('lot_size');
        
        if (count($query->result()) > 0 && $query->result()[0]->size > 0 ) {
        
            $data = $query->result();

            return $data[0]->size;
        
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check lot size by expiry monthand year
     */
    
    function checkLotExistsByExpiryDate($lot_arr, $expiry_date ){
        
        $month = date('m', strtotime( $expiry_date ));
        $year = date('y', strtotime( $expiry_date ));
        
//        echo '<br/> month : ' . $month . ' , year : ' . $year; 
        
        $this->db->where('status', 1); 
        $this->db->where('year', $year ); 
        
        $this->db->where('company_id', $lot_arr['company_id']); 
        $this->db->where('company_symbol', $lot_arr['company_symbol']);         
        
        $this->db->select('size');
        $query = $this->db->get('lot_size_monthly');
        
        if (count($query->result()) > 0 && $query->result()[0]->size > 0 ) {
        
            $data = $query->result();

            return $data[0]->size;
        
        }else{
            
            return false;
        }
        
    }
}
