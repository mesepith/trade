<?php

/*
 * @author : ZAHIR
 * DESC: Option chain , premium decay input data
 */

class Oc_pd_input_model extends CI_Model {
     /*
     * @author: ZAHIR
     * DESC: insert Option chain input data
     */
    
    function insertOcPdInpData($inp_data_arr ){
        
        $this->load->model('Db_error_log');
        
        $db_debug = $this->db->db_debug; //save setting

        $this->db->db_debug = FALSE;
        
        
        $this->db->insert('oc_pd_input', $inp_data_arr);
        
        $insert_id = $this->db->insert_id();
        
        
        $errorz = $this->db->error();
        
        if (!empty($errorz) && ( ($errorz['code'] !== 0) || !empty($errorz['message']) )) {

            $error_db_data = array();

            $error_db_data['language'] = 'php';
            
            $error_db_data['controller_name'] = $this->router->fetch_class();

            $error_db_data['controller_methode_name'] = $this->router->fetch_method();

            $error_db_data['model_name'] = 'Oc_pd_input_model';

            $error_db_data['model_methode_name'] = 'insertOcPdInpData';

            $error_db_data['data'] = json_encode($inp_data_arr);

            $error_db_data['query'] = $this->db->last_query();

            $error_db_data['error_code'] = $errorz['code'];

            $error_db_data['error_message'] = $errorz['message'];

            $error_db_data["created_at"] = date("Y-m-d H:i:s");

            $this->Db_error_log->insertDbErrorLog($error_db_data);
            
            $insert_id = false;
        }

        $this->db->db_debug = $db_debug; //restore setting
        
        return $insert_id;
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Insert premium data
     */
    
    function premiumInsert( $company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $oc_pd_input_id, $premium_arr ){
        
        foreach($premium_arr AS $put_or_call => $strike_price_premium_arr){
            
            foreach($strike_price_premium_arr AS $strike_price=>$premium_arr){
                
                foreach( $premium_arr AS $premium_arr_val ){
                    
                    $data = array();
                    
                    $data['company_id'] = $company_id;
                    $data['company_symbol'] = $company_symbol;
                    $data['underlying_date_start'] = $underlying_date_start;
                    $data['underlying_date_end'] = $underlying_date_end;
                    $data['expiry_date'] = $expiry_date;
                    $data['oc_pd_input'] = $oc_pd_input_id;
                    $data['created_at'] = date("Y-m-d H:i:s");
                    
                    
                    $data['put_or_call'] = $put_or_call;
                    $data['sp_with_highest_oi'] = $strike_price;
                    
                    if($put_or_call==='put'){
                        
                        if(!empty($premium_arr_val['puts_ltp'])){$data['ltp'] = $premium_arr_val['puts_ltp'];}
                        
                    }else if($put_or_call==='call'){
                        
                        if(!empty($premium_arr_val['calls_ltp'])){$data['ltp'] = $premium_arr_val['calls_ltp'];}
                    }
                    if(!empty($premium_arr_val['min_condition'])){
                        
                        $data['min_market_price'] = $premium_arr_val['min_condition'];
                        
                    }
                    if(!empty($premium_arr_val['max_condition'])){
                        
                        $data['max_market_price'] = $premium_arr_val['max_condition'];
                    
                    }
                    
                    $this->db->insert('oc_pd_premium', $data);
                    
                }
                
            }
            
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: premium Decay Insert
     */
    function premiumDecayInsert( $company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $oc_pd_input_id, $premium_decay_no_arr, $put_or_call ){
        
        foreach( $premium_decay_no_arr AS $premium_decay_no_arr_val ){
            
            $data = array();
            
            $data['company_id'] = $company_id;
            $data['company_symbol'] = $company_symbol;
            $data['underlying_date_start'] = $underlying_date_start;
            $data['underlying_date_end'] = $underlying_date_end;
            $data['expiry_date'] = $expiry_date;
            $data['oc_pd_input'] = $oc_pd_input_id;            
            $data['put_or_call'] = $put_or_call;
            $data['created_at'] = date("Y-m-d H:i:s");
            
            $data['decay'] = $premium_decay_no_arr_val;
            
            $this->db->insert('oc_pd_values', $data);
            
        }
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Insert average of decay
     */
    function insertAvgDecay($company_id, $company_symbol, $underlying_date_start, $underlying_date_end , $expiry_date, $oc_pd_input_id, $put_average_pd, $call_average_pd, $market_running=false, $underlying_time=false, $script_start_time=false){
        
        $data['company_id'] = $company_id;
        $data['company_symbol'] = $company_symbol;
        $data['underlying_date_start'] = $underlying_date_start;
        $data['underlying_date_end'] = $underlying_date_end;
        $data['expiry_date'] = $expiry_date;
        $data['oc_pd_input'] = $oc_pd_input_id;  
        $data['created_at'] = date("Y-m-d H:i:s");
        
        $data["market_running"] = $market_running;
        $data["underlying_time_end"] = $underlying_time;
        $data["script_start_time"] = empty($script_start_time) ? '' : $script_start_time;
        
        $data['put_avg_decay'] = $put_average_pd;
        $data['call_avg_decay'] = $call_average_pd;
        
        $this->db->insert('oc_pd_avg_decay', $data);
        
    }
    
}
    
   
