<?php

/*
 * @author : ZAHIR
 */

class ShareHolding_model extends CI_Model {
    
    /*
     * Share distribution Data insert
     */
    
    function insertShareDistribution($share_distribution_arr){
        
//        $old_sd_participant = $this->checkShareDistributionExists($share_distribution_arr);                        
        
//        if( empty($old_sd_participant) ){ /* if no data return then insert entry */
            
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $this->db->insert('share_distribution', $share_distribution_arr);
        
        $insert_id = $this->db->insert_id();
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            $insert_id = false;
            
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;

//            return $insert_id = $this->db->insert_id();
            
//        }else{ 
            
//            return 'exists';
            
//        }
        
        
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check Share distribution Data exists
     */
    
    function checkShareDistributionExists( $share_distribution_arr ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $share_distribution_arr['company_id']); 
        $this->db->where('company_symbol', $share_distribution_arr['company_symbol']); 
        $this->db->where('market_date', $share_distribution_arr['market_date']); 
        $this->db->where('record_id', $share_distribution_arr['record_id']); 
        $this->db->select('id');
        $query = $this->db->get('share_distribution');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return false;
        }
        
    }   
    
    /*
     * Share Holding Data insert
     */
    
    function insertShareHolding($share_insert_arr){
        
//        $old_hd_participant = $this->checkShareHoldingExists($share_insert_arr);                        
        
//        if( empty($old_hd_participant) ){ /* if no data return then insert entry */
            
        $this->db->insert('share_holding', $share_insert_arr);

//            return $insert_id = $this->db->insert_id();
            
//        }else{ 
//            
//            return 'exists';
//            
//        }
        
        
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check Share Holding Data exists
     */
    
    function checkShareHoldingExists( $share_insert_arr ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $share_insert_arr['company_id']); 
        $this->db->where('company_symbol', $share_insert_arr['company_symbol']); 
        $this->db->where('market_date', $share_insert_arr['market_date']); 
        $this->db->where('record_id', $share_insert_arr['record_id']); 
        
        $this->db->where('shares_type', $share_insert_arr['shares_type']); 
        $this->db->where('category', $share_insert_arr['category']); 
        $this->db->where('shareholder_category', $share_insert_arr['shareholder_category']); 
        
        $this->db->select('id');
        
        $query = $this->db->get('share_holding');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return false;
        }
        
    }
    
    
    /*
     * Share Declaration Data insert
     */
    
    function insertDeclaration($declaration_arr){
        
//        $old_dec_participant = $this->checkDeclarationExists($declaration_arr);                        
        
//        if( empty($old_dec_participant) ){ /* if no data return then insert entry */
            
        $this->db->insert('share_declaration', $declaration_arr);

//            return $insert_id = $this->db->insert_id();
            
//        }else{ 
//            
//            return 'exists';
//            
//        }
        
    } 
        /*
     * @author: ZAHIR
     * DESC: Check Declaration Data exists
     */
    
    function checkDeclarationExists( $declaration_arr ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $declaration_arr['company_id']); 
        $this->db->where('company_symbol', $declaration_arr['company_symbol']); 
        $this->db->where('market_date', $declaration_arr['market_date']); 
        $this->db->where('record_id', $declaration_arr['record_id']); 
        
        $this->db->where('question', $declaration_arr['question']); 
        
        $this->db->select('id');
        
        $query = $this->db->get('share_declaration');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return false;
        }
        
    }
    /*
     * Details of Unclaimed shares Data insert
     */
    
    function insertUnclaimedShares($unclaimed_shares_arr){
        
//        $old_dec_participant = $this->checkUnclaimedSharesExists($unclaimed_shares_arr);                        
//        
//        if( empty($old_dec_participant) ){ /* if no data return then insert entry */
            
        $this->db->insert('share_unclaimed', $unclaimed_shares_arr);

//            return $insert_id = $this->db->insert_id();
//            
//        }else{ 
//            
//            return 'exists';
//            
//        }
        
    } 
        /*
     * @author: ZAHIR
     * DESC: Check Unclaimed shares Data exists
     */
    
    function checkUnclaimedSharesExists( $unclaimed_shares_arr ){
        
        $this->db->where('status', 1); 
        $this->db->where('company_id', $unclaimed_shares_arr['company_id']); 
        $this->db->where('company_symbol', $unclaimed_shares_arr['company_symbol']); 
        $this->db->where('market_date', $unclaimed_shares_arr['market_date']); 
        $this->db->where('record_id', $unclaimed_shares_arr['record_id']); 
        $this->db->where('shares_type', $unclaimed_shares_arr['shares_type']); 
        
        $this->db->select('id');
        
        $query = $this->db->get('share_unclaimed');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return false;
        }
        
    }
    
    /*
     * Details of Concert Share holder  Data insert
     */
    
    function insertConcertShare($concert_shareholder_arr){
        
//        $old_cs_participant = $this->checkConcertShareExists($concert_shareholder_arr);                        
        
//        if( empty($old_cs_participant) ){ /* if no data return then insert entry */
            
            $this->db->insert('share_consert', $concert_shareholder_arr);

            return $insert_id = $this->db->insert_id();
            
//        }else{ 
            
//            return 'exists';
            
//        }
        
    } 
    /*
     * Details of Concert Share holder  Data insert
     */
    
    function insertBeneficialOwner($beneficial_owners_arr){
            
        $this->db->insert('share_beneficial', $beneficial_owners_arr);

        return $insert_id = $this->db->insert_id();
        
    }
    
    /*
     * 
     */
    function insertInsiderTrading( $insider_trading ){
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        $this->db->insert('share_insider_trading', $insider_trading);

        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            echo 'Duplicate ' ;  
            echo '<pre>'; print_r($insider_trading);
            
            $insert_id = false;
        }
        
        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
        
    }
    
    /*
     * insert pledge share data
     */
    function insertPledgeData( $pledge_data ){
        
        $old_pledge_data = $this->checkPledgeDataExists($pledge_data);                        
        
        if( empty($old_pledge_data) ){ /* if no data return then insert entry */
            
            $pledge_data["created_at"] = date("Y-m-d H:i:s");
            
            $this->db->insert('share_pledged', $pledge_data);

            return $insert_id = $this->db->insert_id();
            
        }else{ 
//          
//            $this->db->where('status', 1);
//        
//            foreach( $pledge_data AS $pledge_data_key=> $pledge_data_val){
//
//                $this->db->where($pledge_data_key, $pledge_data_val);
//            }
//        
//            $this->db->update('share_pledged', array('latest' => 0, 'updated_at'=> date("Y-m-d H:i:s") ));
            
            return 'exists';
//            
        }
    }
    
    function checkPledgeDataExists( $pledge_data ){
        
        $this->db->where('status', 1);
        
        foreach( $pledge_data AS $pledge_data_key=> $pledge_data_val){
            
            $this->db->where($pledge_data_key, $pledge_data_val);
        }
        
        $this->db->select('id');
        
        $query = $this->db->get('share_pledged');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return false;
        }
        
        
    }
    
    function insertSastBuySale( $sast_data ){
        
        $sast_data["created_at"] = date("Y-m-d H:i:s");
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
            
        $this->db->insert('share_sast_buy_sale', $sast_data);

        $insert_id = $this->db->insert_id();
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {
            
            $insert_id = false;
            
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
    }

    /**
     * List share ditribution records that needs to be crawl by all_data_fetched = 0
     */
    function listPendingFetchedShareDistribution($company_id, $company_symbol){

        $this->db->where('status', 1); 
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        $this->db->where('all_data_fetched', 0); 
        // $this->db->order_by('id'); 
        
        $query = $this->db->get('share_distribution');
        
        if (count($query->result()) > 0 ) {
        
            $data = $query->result();

            return $data;
        
        }else{
            
            return false;
        }
    }

    /**
     * Delete Old Fetching status to avoid duplicacy
     */

    function deleteOldFetching( $company_id, $company_symbol, $ndsId, $share_distribution_id ){

        $tables = array('share_holding', 'share_declaration', 'share_unclaimed', 'share_consert', 'share_beneficial');

        foreach ($tables as $table) {
            $this->db->set('status', 0);
            $this->db->where('share_distribution_id', $share_distribution_id);
            $this->db->where('company_id', $company_id);
            $this->db->where('company_symbol', $company_symbol);
            $this->db->where('record_id', $ndsId);
            $this->db->update($table);
        }

    }

    /**
     * On succesfull Crawl, update Share Distribution Fetch Status 
     */
    function updateShareDistributionFetchStatus($company_id, $company_symbol, $ndsId, $share_distribution_id){

        $this->db->where('id', $share_distribution_id);
        $this->db->where('company_id', $company_id);
        $this->db->where('company_symbol', $company_symbol);
        $this->db->where('record_id', $ndsId);
        $this->db->update('share_distribution', array('all_data_fetched' => 1,'updated_at'=> date("Y-m-d H:i:s") ));
    }
       
}
