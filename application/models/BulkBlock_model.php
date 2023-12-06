<?php

/*
 * @author : ZAHIR
 */

class BulkBlock_model extends CI_Model {
    
    /*
     * Insert bulk block deal
     */
    
    function inserBulkBlockDeal($bulk_block_arr){
          
        $this->db->insert('bulk_block_deal', $bulk_block_arr);

    }
    
    /*
     * check Todays Bulk Block Data Inserted
     */
    function checkTodaysBulkBlockInserted( $exchange, $bulk_or_block ){
        
        $this->db->where('exchange', $exchange);
        $this->db->where('bulk_or_block', $bulk_or_block);
        $this->db->where('market_date', date('Y-m-d'));
       
        $query = $this->db->get('bulk_block_deal');

        if (count($query->result()) > 0) {

            return count($query->result());
            
        } else {

            return false;
        }
    }
    
}
