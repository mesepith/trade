<?php

/*
 * @author : ZAHIR
 */

class Companies_model extends CI_Model {
    /*
     * @author : ZAHIR
     * DESC : list All Companies
     */

    function listAllCompanies() {

        $this->db->where('status', 1);
        $this->db->select('*');
        $this->db->order_by('symbol');
        $query = $this->db->get('companies');

        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }
    /*
     * @author : ZAHIR
     * DESC : list All Companies for crawl
     */

    function listAllCompaniesforCrawl() {

        $this->db->where('status', 1);
        $this->db->select('*');
        $query = $this->db->get('companies');

        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }
    /*
     * @author : ZAHIR
     * DESC : list All Companies
     */

    function listAllCompaniesByLimit( $limit ) {

        $this->db->where('status', 1);
        if($limit>0){
            $this->db->limit($limit, 10);
        }
        $this->db->select('*');
        $query = $this->db->get('companies');
        
//        echo $this->db->last_query(); exit;

        if (count($query->result()) > 0) {

            $data = $query->result();

            return $query->result();
        } else {

            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: check if company is already exist
     */
    function checkCompanyAlreadyExist( $each_company_data ){
        
//        foreach($each_company_data AS $each_company_data_key=>$each_company_data_value){
//            
//            $this->db->where($each_company_data_key, $each_company_data_value);
//        
//        }
        
        $this->db->where('id', $each_company_data['id']);
        $this->db->or_where('name', $each_company_data['name']);
        $this->db->or_where('symbol', $each_company_data['symbol']);
        $this->db->where('status', $each_company_data['status']);
        
//        exit;
        
        $this->db->select('*');
        $query = $this->db->get('companies');
        
//        echo $this->db->last_query(); exit;

        if (count($query->result()) > 0) {

            return true;
        } else {

            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: delete company
     */
    
    function deleteCompany( $each_company_data ){
        
        foreach($each_company_data AS $each_company_data_key=>$each_company_data_value){
        
            $this->db->where($each_company_data_key, $each_company_data_value);
            
        }
        
        return $this->db->delete('companies');
    }
    
    /*
     * @author: ZAHIR
     * DESC: insert company
     */
    function insertCompanies( $each_company_data ){
        
        $this->db->insert('companies', $each_company_data);
        return $insert_id = $this->db->insert_id();
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get Company Id by company Symbol
     */
    
    function getActiveInactiveCompanyBySymbol($company_symbol ){
         
        $this->db->where('symbol', $company_symbol); 
//        $this->db->select('id');
        $query = $this->db->get('companies');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0];
        
        }else{
            
            return false;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Get Company Id by company Symbol
     */
    
    function getCompanyIdBySymbol($company_symbol ){
        
        $this->db->where('status', 1); 
        $this->db->where('symbol', $company_symbol); 
        $this->db->select('id');
        $query = $this->db->get('companies');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return 0;
        }
        
    }
    /*
     * @author: ZAHIR
     * DESC: Get Company Id by company Symbol
     */
    
    function getCompanyIdAndIndexIdBySymbol($company_symbol ){
        
        $this->db->group_start();
        $this->db->where('status', 1);          
        $this->db->or_where('status', 3); #status 3 means index
        $this->db->group_end();
        
        $this->db->where('symbol', $company_symbol); 
        $this->db->select('id');
        $query = $this->db->get('companies');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0]->id;
        
        }else{
            
            return 0;
        }
        
    }
    /*
     * @author: ZAHIR
     * DESC: get Company Id and Symbol By name
     */
    
    function getCompanyIdAndSymbolByName($company_name ){
        
        $this->db->group_start();
        $this->db->where('status', 1);          
        $this->db->or_where('status', 3); #status 3 means index
        $this->db->group_end();
        
        $this->db->where('name', $company_name); 
        $this->db->select('id, symbol');
        $query = $this->db->get('companies');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0];
        
        }else{
            
            return 0;
        }
        
    }
    /*
     * @author: ZAHIR
     * DESC: Get Company Info by company Symbol
     */
    
    function getCompanyAndIndexInfoBySymbol($company_symbol ){
        
        $this->db->group_start();
        $this->db->where('status', 1);          
        $this->db->or_where('status', 3); #status 3 means index
        $this->db->group_end();
        
        $this->db->where('symbol', $company_symbol); 
//        $this->db->select('id');
        $query = $this->db->get('companies');
        
        if (count($query->result()) > 0 && $query->result()[0]->id >= 1 ) {
        
            $data = $query->result();

            return $data[0];
        
        }else{
            
            return 0;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: make company inactive
     */
    
    function makeCompanyInactive( $company_id, $company_symbol ){
        
        $this->db->where('id', $company_id);
        $this->db->where('symbol', $company_symbol);
        $this->db->update('companies', array('status' => 2,'updated_at'=> date("Y-m-d H:i:s") ));
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Last inserted Company In Final Stock data
     */
    function lastInsertedCompany(){
        
        $this->db->where('status', 1); 
        $this->db->where('stock_date', date('Y-m-d'));
        $this->db->select('company_id');
        $this->db->order_by('id desc');
        $this->db->limit('1');
        $query = $this->db->get('stock_data');
//        echo $this->db->last_query();
        
        if (count($query->result()) > 0 && !empty( $query->result()[0]->company_id ) && $query->result()[0]->company_id > 0) {
        
            return $query->result()[0]->company_id;
            
        }else{
            
            return 0;
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Last inserted Company In Final Stock data
     */
    function lastInsertedStkCrawledCompany(){
        
        $this->db->where('status', 1); 
        $this->db->where('stock_date', date('Y-m-d'));
//        $this->db->where('stock_date', '2020-04-17');
        $this->db->select('company_id');
        $this->db->order_by('id desc');
        $this->db->limit('1');
        $this->db->select('company_id, updated_at');
        $query = $this->db->get('stock_data');
//        echo $this->db->last_query();
        
        if (count($query->result()) > 0 && !empty( $query->result()[0]->company_id ) && $query->result()[0]->company_id > 0) {
        
            return $query->result()[0];
            
        }else{
            
            return 0;
        }
        
    }
    
    /*
     * Fetch company list which are not inserted in todays final stock_data table
     */
    
    function listFinalNonInsertedCompanies( $company_id ){
        
        $this->db->where('status', 1); 
            
        $this->db->where('id >', $company_id);
        
        $this->db->select('*');
//        $this->db->limit('1'); #delete it after test
        $query = $this->db->get('companies');
        
        return $query->result();
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: check if company is already exist
     */
    /*
    function checkCompanyExistInFutureByIdAndSymbol( $company_id, $company_symbol ){
        
        $this->db->where('company_id', $company_id);
        $this->db->or_where('company_symbol', $company_symbol);
        $this->db->where('status', 1);
        
        $this->db->select('*');
        $query = $this->db->get('future_companies');

        if (count($query->result()) > 0) {

            return true;
        } else {

            return false;
        }
        
    } */
    
    /*
     * Get all future company data with status
     */
    function getActiveInactiveFutureCompanyBySymbol( $company_symbol ){
        
        $this->db->where('company_symbol', $company_symbol); 
        $query = $this->db->get('future_companies');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0];
        
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Insert future company
     */
    function insertFutureCompany( $company_id, $company_symbol, $company_name, $stk_or_index ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        $data['company_id'] = $company_id;
        $data['company_name'] = $company_name;
        $data['company_symbol'] = $company_symbol;
        $data['stock_or_index'] = $stk_or_index;

        $this->db->insert('future_companies', $data);
    }
    
    /*
     * Get all option company data with status
     */
    function getActiveInactiveOptionCompanyBySymbol( $company_symbol ){
        
        $this->db->where('company_symbol', $company_symbol); 
        $query = $this->db->get('put_call_companies');
        
        if (count($query->result()) > 0 && $query->result()[0]->id > 0 ) {
        
            $data = $query->result();

            return $data[0];
        
        }else{
            
            return false;
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: Insert option company
     */
    function insertOptionCompany( $company_id, $company_symbol, $company_name, $stk_or_index ){
        
        $data["created_at"] = date("Y-m-d H:i:s");
        $data['company_id'] = $company_id;
        $data['company_name'] = $company_name;
        $data['company_symbol'] = $company_symbol;
        $data['stock_or_index'] = $stk_or_index;

        $this->db->insert('put_call_companies', $data);
    }
    
    /*
     * Fetch Today inserted company list
     */
    
    function listTodayCMCompanies( $company_id ){
        
        $this->db->where('status', 1); 
            
        $this->db->where('company_id >', $company_id);
        
        $this->db->where('stock_date', date('Y-m-d'));
//        $this->db->where('stock_date', '2020-06-15');
        
        $this->db->select('company_symbol, company_id');
//        $this->db->limit('1'); #delete it after test
        $query = $this->db->get('stock_data');
        
        return $query->result();
        
    }

}
