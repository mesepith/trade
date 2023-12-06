<?php

/*
 * @author : ZAHIR
 */

class ParticipantOi_model extends CI_Model {
    
    /*
     * Insert Participant wise Open Interest (no. of contracts) in Equity Derivatives 
     */
    
    function insertParticipantOi($participant_oi_arr){
        
        $old_oi_participant = $this->checkParticipantOiExists($participant_oi_arr);                        
        
        if( empty($old_oi_participant) ){ /* if no data return then insert entry */
            
            $this->db->insert('oi_participant', $participant_oi_arr);

            return $insert_id = $this->db->insert_id();
            
        }else{ 
            
            return 'exists';
            
        }
        
        
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check Participant wise Open Interest exists
     */
    
    function checkParticipantOiExists( $participant_oi_arr ){
        
        $this->db->where('status', 1); 
        $this->db->where('client_type', $participant_oi_arr['client_type']); 
        $this->db->where('market_date', $participant_oi_arr['market_date']); 
        $this->db->select('id');
        $query = $this->db->get('oi_participant');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return false;
        }
        
    }   
    
    /*
     * Fetch OI Participant data
     */
    function fetchOiParticipant( $market_date, $market_date_to, $client_type_chkbox, $loop_count=0 ){
        
        $this->db->where('status', 1); 
        
        if( !empty($market_date_to)){
            
            $this->db->where('market_date >= ', $market_date );
            $this->db->where('market_date <= "' . $market_date_to . '"');
        }else{
            
            $this->db->where('market_date', $market_date);
        }
        
        if( !empty($client_type_chkbox)){
            
            $this->db->where('client_type' , $client_type_chkbox );
        }
        
        $query = $this->db->get('oi_participant');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
//            if( !empty($market_date_to) ){ return false;}
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 5){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $market_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($market_date)));
            
            $data = $this->fetchOiParticipant( $market_date, $market_date_to, $client_type_chkbox, $loop_count );
            
//            echo '<pre>'; print_r($data);
            
//            echo count($data);
            
            if( count($data) >  0 ){
                
                return $data;
            }
        }
    }
    
    
}
