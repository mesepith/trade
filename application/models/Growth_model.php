<?php

/*
 * @author : ZAHIR
 */

class Growth_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC :Insert Volume Growth of Weekly (Two weeks differenece in perchantage )
     */

     function insertStocksTwoWeeksVolumeGrowth($data){

        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;

        $errorz = $this->db->error();

        $data["created_at"] = date("Y-m-d H:i:s");

        $this->db->insert('stock_growth', $data);
        echo $this->db->last_query();

        $this->db->db_debug = $db_debug; //restore setting

     }
    
    
   
}
