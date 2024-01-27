<?php

/*
 * @author : ZAHIR
 */

class ShareHolding_disp_model extends CI_Model {
    
    /*
     * DisplayShare Distibution Data
     */
    function fetchShareDistrubution( $company_id, $company_symbol, $market_date, $market_date_to, $all_date_chkbox, $loop_count=0 ){
        
        $this->db->where('status', 1); 
        
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        if( $all_date_chkbox !== 'all' ){
            
            if( !empty($market_date_to)){

                $this->db->where('market_date >= ', $market_date );
                $this->db->where('market_date <= "' . $market_date_to . '"');
            }else{

                $this->db->where('market_date', $market_date);
            }
            
        }
        
        $this->db->order_by('market_date', 'desc');
        
        $query = $this->db->get('share_distribution');
//        echo $this->db->last_query() . '<br/>';
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
//            if( !empty($market_date_to) ){ return false;}
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 120){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $market_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($market_date)));
            
            $data = $this->fetchShareDistrubution( $company_id, $company_symbol, $market_date, $market_date_to, $all_date_chkbox, $loop_count );
            
//            echo '<pre>'; print_r($data);
            
//            echo count($data);
            
            if( !empty($data) && count($data) >  0 ){
                
                return $data;
            }
        }
    }
    
    /*
     * List All Share Distribution
     */
    
    function listAllShareDistribution( $company_id, $company_symbol ){
        
        $this->db->where('status', 1); 
        
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol);
        
        $this->db->select('market_date, record_id');
        
        $query = $this->db->get('share_distribution');
//        echo $this->db->last_query() . '<br/>';
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * Fetch Share Summary
     */
    
    function fetchShareSummary( $shares_type, $company_id, $company_symbol, $market_date, $record_id ){
        
        $this->db->where('status', 1); 
        
        $this->db->where('shares_type', $shares_type); 
        
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        $this->db->where('market_date', $market_date);
        $this->db->where('record_id', $record_id);
        
        $query = $this->db->get('share_holding');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * Fetrch significant-beneficial-owners of company shares
     */
    function fetchShareBeneficialOwner( $company_id, $company_symbol, $market_date, $record_id ){
        
         
        $this->db->where('status', 1); 
        
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        $this->db->where('market_date', $market_date);
        $this->db->where('record_id', $record_id);
        
        $query = $this->db->get('share_beneficial');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * Details of the shareholders acting as persons in Concert
     */
    function fetchShareConsert( $company_id, $company_symbol, $market_date, $record_id ){
        
         
        $this->db->where('status', 1); 
        
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        $this->db->where('market_date', $market_date);
        $this->db->where('record_id', $record_id);
        
        $query = $this->db->get('share_consert');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * Declaration: The Listed entity has submitted the following declaration.
     */
    function fetchShareDeclaration( $company_id, $company_symbol, $market_date, $record_id ){        
         
        $this->db->where('status', 1); 
        
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        $this->db->where('market_date', $market_date);
        $this->db->where('record_id', $record_id);
        
        $query = $this->db->get('share_declaration');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * Details of Unclaimed shares
     */
    function fetchShareUnclaimed( $company_id, $company_symbol, $market_date, $record_id ){        
         
        $this->db->where('status', 1); 
        
        $this->db->where('company_id', $company_id); 
        $this->db->where('company_symbol', $company_symbol); 
        
        $this->db->where('market_date', $market_date);
        $this->db->where('record_id', $record_id);
        
        $query = $this->db->get('share_unclaimed');
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    /*
     * Fetch Insider Trading Info
     */
    function fetchInsiderTrading($broadcaste_date, $acq_disp, $security_sortby, $company_id=false, $company_symbol=false, $broadcaste_date_to=false, $acq_disp_name=false, $acq_mode=false, $person_category=false, $sum_sec_val_by_comp=false, $sec_val_gt=false, $loop_count=0){
        
        $this->db->where('status', 1); 
        
        if( !empty($broadcaste_date_to) && $broadcaste_date !== 'all' ){
            
            $this->db->where('broadcaste_date >= ', $broadcaste_date );
            $this->db->where('broadcaste_date <= "' . $broadcaste_date_to . '"');
            
        }else{
            
            if( $broadcaste_date !== 'all' ){
                
                $this->db->where('broadcaste_date', $broadcaste_date);
            }
            
        }
        
        if( !empty($acq_disp) && ($acq_disp !== 'all') ){
            
            $this->db->where('tdp_transaction_type', $acq_disp);
        }
        if( !empty($acq_mode) && ($acq_mode !== 'all') ){
            
            $this->db->where('acq_mode', $acq_mode);
        }
        if( !empty($person_category) && ($person_category !== 'all') ){
            
            $this->db->where_in('person_category', $person_category);
        }
        
        if( !empty($security_sortby)){
            
            if( $security_sortby === 'high' ){
                
                $this->db->order_by('sec_val', 'desc');
                
            }else if( $security_sortby === 'low' ){
                
                $this->db->order_by('sec_val', 'asc');
            }
        }else{
            
            $this->db->order_by('broadcaste_datetime', 'desc');
        }
        
        if( !empty($company_id) && !empty($company_symbol) ){
            
            $this->db->where('company_id', $company_id); 
            $this->db->where('company_symbol', $company_symbol);
        }
        
        if( !empty($acq_disp_name) ){
            
            $this->db->where('acq_name', $acq_disp_name); 
        }
        
        if(!empty($sum_sec_val_by_comp) && $sum_sec_val_by_comp == 'yes'){
            
            $this->db->select('company_symbol, SUM(sec_val) AS sec_val');
            $this->db->group_by('company_symbol');
        }
        
        if( !empty($sec_val_gt) ){
                        
            $this->db->where('sec_val>=', $sec_val_gt);
        }
        
        $query = $this->db->get('share_insider_trading');
//        echo $this->db->last_query() . '<br/>';
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
//            if( !empty($market_date_to) ){ return false;}
            
            /* We are not allowing to looping this function more than 5 times as it will slow server */
            $loop_count++;
//            echo '$loop_count : ' . $loop_count . '<br/>';
            if($loop_count > 100){ return false; }
            
            /*If we dont find data on current data then we search in previous date */
            
            $substrac_day = empty($substrac_day) ? 1 : ($substrac_day+1);
            
            $broadcaste_date = date('Y-m-d', strtotime('-'.$substrac_day.' day', strtotime($broadcaste_date)));
            
            $data = $this->fetchInsiderTrading( $broadcaste_date, $acq_disp, $company_id, $company_symbol, $broadcaste_date_to, $acq_disp_name=false, $acq_mode=false, $person_category=false, $sum_sec_val_by_comp=false, $sec_val_gt=false, $loop_count );
            
//            echo '<pre>'; print_r($data);
            
//            echo count($data);
            
            if( !empty($data) && count($data) >  0 ){
                
                return $data;
            }
        }
    }
    
    /*
     * Fetch Pledged Data
     */
    function fetchPledgedData( $broadcaste_date, $encumb_p_sortby, $dmat_pldg_p_sortby, $prmtr_hldng_p_sortby, $company_id=false, $company_symbol=false, $broadcaste_date_to=false ){
        
        $this->db->where('status', 1); 
        
        if( !empty($broadcaste_date_to) && $broadcaste_date !== 'all' ){
            
            $this->db->where('broadcaste_date >= ', $broadcaste_date );
            $this->db->where('broadcaste_date <= "' . $broadcaste_date_to . '"');
            
        }else{
            
            if( $broadcaste_date !== 'all' ){
                
                $this->db->where('broadcaste_date', $broadcaste_date);
            }
            
        }
        
        if( !empty($encumb_p_sortby)){
            
            if( $encumb_p_sortby === 'high' ){
                
                $this->db->order_by('perc_promoter_shares_enc', 'desc');
                
            }else if( $encumb_p_sortby === 'low' ){
                
                $this->db->order_by('perc_promoter_shares_enc', 'asc');
            }
        }
        
        if( !empty($dmat_pldg_p_sortby)){
            
            if( $dmat_pldg_p_sortby === 'high' ){
                
                $this->db->order_by('perc_shares_pledged_demat', 'desc');
                
            }else if( $dmat_pldg_p_sortby === 'low' ){
                
                $this->db->order_by('perc_shares_pledged_demat', 'asc');
            }
        }
        
        if( !empty($prmtr_hldng_p_sortby)){
            
            if( $prmtr_hldng_p_sortby === 'high' ){
                
                $this->db->order_by('perc_promoter_holding', 'desc');
                
            }else if( $prmtr_hldng_p_sortby === 'low' ){
                
                $this->db->order_by('perc_promoter_holding', 'asc');
            }
        }
        
        if( !empty($company_id) && !empty($company_symbol) ){
            
            $this->db->where('company_id', $company_id); 
            $this->db->where('company_symbol', $company_symbol);
        }
        
        $query = $this->db->get('share_pledged');
//        echo $this->db->last_query() . '<br/>';
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    /*
     * Fetch Bulk Block Deal data
     */
    
    function fetchBulkBlockDeal( $market_date, $exchange, $deal_type=false, $buy_or_sale, $quantity_traded_sortby, $company_id=false, $company_symbol=false, $market_date_to=false, $client=false ){
        
        $this->db->where('status', 1);         
         
        if( !empty($market_date_to) && $market_date !== 'all' ){
            
            $this->db->where('market_date >= ', $market_date );
            $this->db->where('market_date <= "' . $market_date_to . '"');
            
        }else{
            
            if( $market_date !== 'all' ){
                
                $this->db->where('market_date', $market_date);
            }
            
        }
        
        if( !empty($exchange) && ($exchange !== 'all') ){
            
            $this->db->where('exchange', $exchange);
        }
        
        if( !empty($deal_type) && ($deal_type !== 'all') ){
            
            $this->db->where('bulk_or_block', $deal_type);
        }
        
        if( !empty($buy_or_sale) && ($buy_or_sale !== 'all') ){
            
            $this->db->where('buy_or_sale', $buy_or_sale);
        }
        
        if( !empty($quantity_traded_sortby)){
            
            if( $quantity_traded_sortby === 'high' ){
                
                $this->db->order_by('quantity_traded', 'desc');
                
            }else if( $quantity_traded_sortby === 'low' ){
                
                $this->db->order_by('quantity_traded', 'asc');
            }
        }
        
        if( !empty($company_id) && !empty($company_symbol) ){
            
            $this->db->where('company_id', $company_id); 
            $this->db->where('company_symbol', $company_symbol);
        }
        
        if( !empty($client) ){
            
            $this->db->where('client_name', $client); 
        }
        
        $query = $this->db->get('bulk_block_deal');
//        echo $this->db->last_query() . '<br/>';
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
        }
    }
    
    function fetchSastRegulation29( $broadcaste_date, $acq_or_sale_disp, $promoter_type, $total_share_acq_sortby, $total_share_sale_sortby, $company_id=false, $company_symbol=false, $broadcaste_date_to=false, $acq_saler_name=false ){
//        echo $acq_or_sale_disp; exit;
        $this->db->where('status', 1); 
        
        if( !empty($broadcaste_date_to) && $broadcaste_date !== 'all' ){
            
            $this->db->where('broadcaste_date >= ', $broadcaste_date );
            $this->db->where('broadcaste_date <= "' . $broadcaste_date_to . '"');
            
        }else{
            
            if( $broadcaste_date !== 'all' ){
                
                $this->db->where('broadcaste_date', $broadcaste_date);
            }
            
        }
        
        if( !empty($acq_or_sale_disp) && ($acq_or_sale_disp !== 'all') ){
            
            $this->db->where('acq_or_sale_type', $acq_or_sale_disp);
        }
        
        if( !empty($promoter_type) && ($promoter_type !== 'all') ){
            
            $this->db->where('promoter_type', $promoter_type);
        }
        
        if( !empty($total_share_acq_sortby)){
            
            if( $total_share_acq_sortby === 'high' ){
                
                $this->db->order_by('total_share_acq', 'desc');
                
            }else if( $total_share_acq_sortby === 'low' ){
                
                $this->db->order_by('total_share_acq', 'asc');
            }
        }else if( !empty($total_share_sale_sortby)){
            
            if( $total_share_sale_sortby === 'high' ){
                
                $this->db->order_by('total_share_sale', 'desc');
                
            }else if( $total_share_sale_sortby === 'low' ){
                
                $this->db->order_by('total_share_sale', 'asc');
            }
        }else{
            
            $this->db->order_by('broadcaste_datetime', 'desc');
        }
        
        if( !empty($company_id) && !empty($company_symbol) ){
            
            $this->db->where('company_id', $company_id); 
            $this->db->where('company_symbol', $company_symbol);
        }
        
        if( !empty($acq_saler_name) ){
            
            $this->db->where('name', $acq_saler_name); 
        }
        
        $query = $this->db->get('share_sast_buy_sale');
        
//         echo $this->db->last_query() . '<br/>';
        
        if (count($query->result()) > 0 ) {
        
            return $query->result();
            
        }else{
            
            return false;
            
        }
        
    }
}
    
   
