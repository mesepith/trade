<?php

/*
 * @author : ZAHIR
 */

class Delete_duplicate_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC : list All duplicate data of stock data live table
     */

    function duplicateValuesOfStockDataLive( $start, $limit ) {
        
        ini_set('max_execution_time', 0); 
        
        $query = $this->db->query('SELECT id, company_name, open_price, stock_date, created_at, last_price, total_traded_volume, delivery_quantity, delivery_to_traded_quantity, total_buy_quantity, total_sell_quantity, total_traded_value , COUNT(*) AS NumDuplicates 
FROM stock_data_live
GROUP BY company_name, open_price, stock_date, created_at, last_price, total_traded_volume, delivery_quantity, delivery_to_traded_quantity, total_buy_quantity, total_sell_quantity, total_traded_value HAVING NumDuplicates > 1 
limit '.$start.' , ' . $limit);
        
        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }
    
    function eachDuplicateOfStockDataLive( $data, $id ){
        
        $query = 'SELECT id, company_name, open_price, stock_date, created_at, last_price, total_traded_volume, delivery_quantity, delivery_to_traded_quantity, total_buy_quantity, total_sell_quantity, total_traded_value 
FROM stock_data_live where ';
        
        
        $total_column = count((array)$data);
        
        $count = 0;
        
        foreach($data AS $data_key=>$data_value){
            
            $count++;
            
            /*
             * if last column from for loop is empty then we replcae 'AND' with ''
             */
            if( $count== $total_column && empty($data_value) ){
                
                $query = $this->str_lreplace('AND', '', $query);
                continue;
            }
            
            if(empty($data_value)){
                
                continue;
            }
            
            if( $data_key=='delivery_to_traded_quantity' || $data_key=='total_traded_value' || $data_key=='open_price' || $data_key=='last_price' ){
                
                $query.= 'CAST('.$data_key.' AS DECIMAL)  = CAST('.$data_value.' AS DECIMAL)';
                
            }else{
                
                $query.= $data_key .'="'. $data_value.'"';
                
            }
            
            if( $count!= $total_column){
                
                $query.=' AND ';
            }
            
            
        }
 
        $queryz = $this->db->query($query);
        
        echo $this->db->last_query();
        echo '<br/>';
        
        /*
         * If we found more then 1, then delete dulicates 
         */
        
        if (count($queryz->result()) > 1) {

            echo 'This has duplicate data ' . $id;
            
            $delete_query="DELETE FROM stock_data_live WHERE id != ".$id." AND ";
            
            $count = 0;
        
            foreach($data AS $data_key=>$data_value){

                $count++;
                
                /*
                * if last column from for loop is empty then we replcae 'AND' with ''
                */
                if( $count== $total_column && empty($data_value) ){

                    $delete_query = $this->str_lreplace('AND', '', $query);
                    continue;
                }
                
                if(empty($data_value)){

                    continue;
                }
                
                if( $data_key=='delivery_to_traded_quantity' || $data_key=='total_traded_value' || $data_key=='open_price' || $data_key=='last_price'){

                    $delete_query.= 'CAST('.$data_key.' AS DECIMAL)  = CAST('.$data_value.' AS DECIMAL)';

                }else{

                    $delete_query.= $data_key .'="'. $data_value.'"';

                }

                if( $count!= $total_column){

                    $delete_query.=' AND ';
                }                
            }
            
            $this->db->query($delete_query);
            
            echo '<br/>';
            echo '<br/>';
            echo $this->db->last_query();
            echo '<br/>';
            
        } else {

            echo 'This does not have duplicate data ' . $id;
            
        }
        
        echo '<br/>';
//        exit;
        
    }
    /*
     * @author: ZAHIR
     * DESC: Replace last character of a string with other
     */
    function str_lreplace($search, $replace, $subject) {
        $pos = strrpos($subject, $search);

        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

}
