<?php

/*
 * @author : ZAHIR
 */

class Sectors_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC : list All Sectors
     */

    function listAllSectors() {

        $this->db->where('status', 1);
//        $this->db->limit(3, 307);        
        $this->db->select('*');
        $query = $this->db->get('sectors');

        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }
    /*
     * @author : ZAHIR
     * DESC : list All Live crawled Allowed Sectors
     */

    function listAllLiveSectors() {

        $this->db->where('status', 1);
        $this->db->where('live_fetch', 1);
        $this->db->select('*');
        $query = $this->db->get('sectors');

        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: insert sectors whole api data in json
     */
    function insertEachSectorsWholeApiDataInLog( $sectors_data, $sector_id, $index_name ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        $data["sectors_id"] = $sector_id;
        $data["index_name"] = $index_name;
        $data["data"] = json_encode($sectors_data);
        
        $this->db->insert('sectors_data_log', $data);
        return $insert_id = $this->db->insert_id();
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: insert sectors whole api data in json
     */
    function insertSectorsData( $sectors_data_arr ){
        
        $sectors_data_arr["created_at"] = date("Y-m-d H:i:s");
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $this->db->insert('sectors_data', $sectors_data_arr);
        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
			
			echo 'Error ' . $errorz['message'] . '<br/>';
			$insert_id = false;
		}
		
		$this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: insert Live sectors data
     */
    function insertLiveSectorsData( $sectors_data_arr ){
        
        $db_debug = $this->db->db_debug; //save setting
        
        $this->db->db_debug = FALSE;
        
        $sectors_data_arr["created_at"] = date("Y-m-d H:i:s");
        
        $this->db->insert('sectors_data_live', $sectors_data_arr);
        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            $insert_id = false;
            
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
        
    }
    
    /*
     * Check if todays data already present
     */
    function checkTodaysDataAlreadyInserted(){
        
        $this->db->where('status', 1);
        $this->db->like('stock_date_time', date('Y-m-d'), 'after'); 
        
        $num_rows = $this->db->count_all_results('sectors_data');
        
        $this->db->select('*');
        $query = $this->db->get('sectors');
        
        

        if ($num_rows > 0 ) {
            
            
            echo ' data is already present in database <br/>';
            
            exit();
            
        }else{
            
            echo ' data is not present in database  <br/>';
            
        }
        
    }
    
    /*
     * @authors: ZAHIR
     * DESC: Sectors Info By Id And Date
     */
    
    public function sectorsInfoByIdAndDate( $sector_id, $date, $date_to, $live=false, $loop_count=0 ){
        
        $this->db->where('status', 1);
        $this->db->where('sectors_id', $sector_id);
                
//        $this->db->where('stock_date_time like "' . $date  . '%"');
//        $this->db->where('stock_date_time >= "' . $date  . ' 00:00:01"');
        $this->db->where('stock_date >= ', $date );
        
        if( $date_to ){
            
            $this->db->where('stock_date <= ', $date_to);
        }
        
        if( empty($live)){
            
            $query = $this->db->get('sectors_data'); 
        }else{
            
            $query = $this->db->get('sectors_data_live'); 
        } 
        
        if( count($query->result()) > 0 ){
            
            $data = $query->result();
            
//            echo '<pre>'; print_r($data); exit;

            return $query->result();
            
        }else{
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($date)));
            
            $data = $this->sectorsInfoByIdAndDate( $sector_id, $date, $date_to, $live, $loop_count );
            
//            echo count($data);
            
            if (is_array($data) && count($data) > 0) {
                
                return $data;
            }
//            echo '<pre>'; print_r($data);
        }
    }
    
    /*
     * Fetch All sector log data
     */
    function listAllSectorLog(){
        
        $this->db->where('status', 1);       
        $this->db->select('*');
        $query = $this->db->get('sectors_data_log');

        if (count($query->result()) > 0) {

//            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }
}
