<?php

/*
 * @author : ZAHIR
 */

class Db_error_log extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC : Insert db error log
     */

    function insertDbErrorLog( $error_db_data ) {
        
        $this->db->insert('db_error_log', $error_db_data);
        
    }

}
