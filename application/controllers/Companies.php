<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/System_Notification_Controller.php");

class Companies extends MX_Controller {

    public function fetchDataByLimit( $limit ) {
        
        $this->load->model('Companies_model');

        $company_list = $this->Companies_model->listAllCompaniesByLimit( $limit );
        
//        echo '<pre>';
//        print_r($company_list);
        
        echo json_encode($company_list); 
        exit;
    }
    public function fetchAllData(  ) {
        
        $this->load->model('Companies_model');

        $company_list = $this->Companies_model->listAllCompanies();
        
//        echo '<pre>';
//        print_r($company_list);
        
        echo json_encode($company_list); 
        exit;
    }
    
    /*
     * @author: ZAHIR
     * DESC: insert companies data
     */
    public function insertData( $limit ) {
        
        ini_set('max_execution_time', 0); 

        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');
        
        ob_start();
        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $client = new GuzzleHttp\Client();
        
        $this->load->model('Companies_model');
        
        $company_list = json_decode(($client->request('GET', PARENT_WEB_SERVER . 'companies/fetch-data-by-limit/' . $limit)->getBody()->getContents()), true);   
        
        
        foreach($company_list AS $each_company_data){
            
            $is_company_exist = $this->Companies_model->checkCompanyAlreadyExist( $each_company_data );
            
            /*
             * if company exist then delete it
             */
            if( $is_company_exist ){
                
                $is_deleted = $this->Companies_model->deleteCompany( $each_company_data );
            }
            
            $is_inserted = $this->Companies_model->insertCompanies( $each_company_data );            
            
        }
        
        $new_limit = $limit +10;
        
        if($new_limit >25){ exit;}
        
        $newURL = base_url() . 'companies/insert-data/' . $new_limit;
        
        header('Location: '. $newURL);
        sleep(2);
        die();
    }
    
    /*
     * @author: ZAHIR
     * DESC: match Company With Prime Server
     */
    function matchCompanyWithPrimeServer(){
        
        ini_set('max_execution_time', 0); 

        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');
        
        ob_start();
        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
//                echo '<pre>';
//        print_r($_SERVER['SERVER_ADDR']); exit;
        $client = new GuzzleHttp\Client();
        
        $prime_server_company_list = json_decode(($client->request('GET', PARENT_WEB_SERVER . 'companies/fetch-all-data')->getBody()->getContents()), true);
        
        $this->load->model('Companies_model');

        $this_server_company_list = json_decode(json_encode($this->Companies_model->listAllCompanies()), true);
        
        if( count($prime_server_company_list) != count($this_server_company_list) ){
            
            $mail_subject = 'Company count data is mismatch with server : ' . $_SERVER['SERVER_ADDR'];
            $mail_body = 'Prime server company count is :  ' . count($prime_server_company_list) . ' , while this server with ip '.$_SERVER['SERVER_ADDR'].' company count is : ' . count($this_server_company_list);
            $this->companyCountMisMatchNotify($mail_subject, $mail_body);
            
            exit;
            
        }
        
//        echo '<pre>';
//        print_r($prime_server_company_list);
//        
//        echo '<pre>';
//        print_r($this_server_company_list);
        
        $mis_match_company_arr = array();
        
        foreach($prime_server_company_list AS $prime_server_company_arr_key=>$prime_server_company_arr_value){
            
            $prime_server_company_id = $prime_server_company_arr_value['id'];
//            echo '<br/>';
            
            $prime_server_company_name = $prime_server_company_arr_value['name'];
            $prime_server_company_symbol = $prime_server_company_arr_value['symbol'];
            $prime_server_company_exchange_name = $prime_server_company_arr_value['exchange_name'];
            
            $this_server_company_id = $this_server_company_list[$prime_server_company_arr_key]['id'];
//            echo '<br/>';
            
            $this_server_company_name = $this_server_company_list[$prime_server_company_arr_key]['name'];
            $this_server_company_symbol = $this_server_company_list[$prime_server_company_arr_key]['symbol'];
            $this_server_company_exchange_name = $this_server_company_list[$prime_server_company_arr_key]['exchange_name'];
            
//            echo '<br/>';
            
            if( ($prime_server_company_id != $this_server_company_id) || ($prime_server_company_name !=$this_server_company_name ) || ($prime_server_company_symbol != $this_server_company_symbol) || ( $prime_server_company_exchange_name != $this_server_company_exchange_name ) ){
                
                $mail_body = 'Prime server company id ' . $prime_server_company_id . ' and symbol '.$prime_server_company_symbol.' is not matching with server having ip ' . $_SERVER['SERVER_ADDR'] . ' with company id ' . $this_server_company_id . ' and symbol ' . $this_server_company_symbol;
                
                $mis_match_company_arr[] = $mail_body;
                
//                exit;
                
            }                        
            
        }
        
        if( count($mis_match_company_arr) > 0 ){
                
            $mail_subject = 'Company data is mismatch with server : ' . $_SERVER['SERVER_ADDR'];
            
            $data['mis_match_company_arr'] = $mis_match_company_arr;
            $this->companyDataMisMatchNotify($mail_subject, $data);
            
            echo 'There is mismatch of companies data between servers';
            
            exit;

        }
        
        echo 'Everything is fine';
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: if company data count is not match among other server then notify
     */
    
    function companyCountMisMatchNotify($mail_subject, $mail_body){
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ampstart.co';              
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = $mail_subject;                
        $mail_data['message']= $mail_body;
        send_mailz($mail_data);
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: if company data is not match among other server then notify
     */
    
    function companyDataMisMatchNotify($mail_subject, $data){
        
        $html = $this->load->view('notify/emails/company-data-mismatch', $data, TRUE);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'care@ampstart.co';              
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = $mail_subject;                
        $mail_data['message']= $html;
        send_mailz($mail_data);
        
    }
    
    
    /*
     * @author: ZAHIR
     * Insert get derivative company list from NSE
     */
    
    function getDerivativeCompanyFromNse(){
        
        $System_Notification_contr = new System_Notification_Controller();
        
        $this->insertDerivativeCompany('future', $System_Notification_contr);
        $this->insertDerivativeCompany('option', $System_Notification_contr);
        
    }
    
    /*
     * @author: ZAHIR
     * Insert future company list
     */
    
    function insertDerivativeCompany( $derivative_type, $System_Notification_contr ){                
        
        try{
            
            $this->load->model('Companies_model');

            $post_data = $this->input->post();

            $data = json_decode($post_data['company_list'], true);

            $company_list_arr = $data['data'];

            foreach( $company_list_arr AS $company_list_val ){

                $index_or_company_id = $company_list_val[0];
                $index_or_company_name = $company_list_val[1];
                $index_or_company_symbol = $company_list_val[2];

                if ( filter_var($index_or_company_id, FILTER_VALIDATE_INT) === false || ($index_or_company_id === $index_or_company_name) && ($index_or_company_name === $index_or_company_symbol) ) {                                
                }else{

//                    $company_id = $this->Companies_model->getCompanyIdAndIndexIdBySymbol($index_or_company_symbol);
                    $company_info = $this->Companies_model->getCompanyAndIndexInfoBySymbol($index_or_company_symbol);

                    if( empty($company_info) ){ continue; }
                    
                    $company_id = !empty($company_info->id) ? $company_info->id : 0;
                    $cm_company_status = !empty($company_info->status) ? $company_info->status : 0; # cash market company status, if status is 3 then it is index
                    
                    $stk_or_index = ($cm_company_status == 1) ? 1 : (($cm_company_status == 3) ? 2 : 0); # we will set 1 means equity and 2 means index
                    
                    if( $company_id > 0 ){

                        if( $derivative_type === "future" ) {

                            $company_data = $this->Companies_model->getActiveInactiveFutureCompanyBySymbol($index_or_company_symbol);

                            if( !empty($company_data) && $company_data->status != 1 ){

                                $System_Notification_contr->inActiveCompanyAddedInFutureNSE( $index_or_company_symbol, $index_or_company_name, $company_data->status );
                                continue;

                            }else if(empty($company_data)){

                                $this->Companies_model->insertFutureCompany($company_id, $index_or_company_symbol, $index_or_company_name, $stk_or_index);
                                
                                echo $derivative_type . ' ' . $index_or_company_symbol . ' New Company *** ';
                                
                                echo "\n\r";
                                
                            }else{
                                echo $derivative_type . ' ' . $index_or_company_symbol . ' already exists ---';
                                
                                echo "\n\r";
                            }


                        }else if( $derivative_type === "option" ){

                            $company_data = $this->Companies_model->getActiveInactiveOptionCompanyBySymbol($index_or_company_symbol);

                            if( !empty($company_data) && $company_data->status != 1 ){

                                $System_Notification_contr->inActiveCompanyAddedInOptionNSE( $index_or_company_symbol, $index_or_company_name, $company_data->status );
                                continue;

                            }else if(empty($company_data)){
                                
                                $this->Companies_model->insertOptionCompany($company_id, $index_or_company_symbol, $index_or_company_name, $stk_or_index);
                                
                                echo $derivative_type . ' ' . $index_or_company_symbol . ' New Company *** ';
                                
                                echo "\n\r";
                                
                            }else{
                                
                                echo $derivative_type . ' ' . $index_or_company_symbol . ' already exists ---';
                                
                                echo "\n\r";
                            }

                        }else{ return; }

                    }

                }

            }
        
        } catch (Exception $e) {
            
            echo 'errz ' ;  
            echo '<pre>'; print_r($e);
            
            $System_Notification_contr->failReadDerivativeCompanyList($derivative_type, $e);
        }
        
        echo 'the end in sub function';
        echo "\n\r";
    }
    
    /*
     * @author: ZAHIR
     * Insert New company list from NSE
     */
    function readCompanyListFromCSV2020(){
        
        $System_Notification_contr = new System_Notification_Controller();
        
        $this->load->model('Companies_model');
        
        try{
            
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );
            
//            $data = file_get_contents("https://archives.nseindia.com/content/equities/EQUITY_L.csv");
            
            $data = file_get_contents("https://archives.nseindia.com/content/equities/EQUITY_L.csv", false, $context);
            
            if($data === FALSE) {
                
                echo 'data not found'; $System_Notification_contr->failReadCompanyList(); return;
            }
            
            $rows = explode("\n", $data);
            
            if( empty($rows)){ return; }
            
            $company_arr = array();
            
            foreach ($rows as $row_key=>$row_value) {
                
                if( $row_key == 0 || empty($row_value) ){ continue; }
                
                echo 'csv key ' . $row_key . ' <br/>';
                echo '<pre>'; print_r(str_getcsv($row_value));
                
                if( empty(str_getcsv($row_value)) ){ continue; }
                
                $company_symbol = str_getcsv($row_value);
                
                if( !empty($company_symbol[0])){
                    
                    echo '$company_symbol[0] : ' . $company_symbol[0] . ' <br/>';
                    
                    $company_data = $this->Companies_model->getActiveInactiveCompanyBySymbol($company_symbol[0]);
                    
                    if( !empty($company_data) && $company_data->status != 1 ){
                        
                        $System_Notification_contr->inActiveCompanyAddedInNSE( $company_symbol[0], $company_symbol[1], $company_data->status );
                        continue;
                    }
                    
                    $company_id = $company_data->id;
                    
                    echo '$company_id : ' . $company_id . ' <br/>';
                    
                    if( $company_id > 0 ){ continue; }
                    
                    $each_company_arr = array();
                    
                    $each_company_arr['symbol'] = $company_symbol[0];
                    $each_company_arr['name'] = $company_symbol[1];
                    $each_company_arr['exchange_name'] = 'nse';
                    $each_company_arr['created_at'] = date('Y-m-d H:i:s');
                    
                    $last_inserted_id = $this->Companies_model->insertCompanies($each_company_arr);
                    
                    if($last_inserted_id > 0 ){
                        
                        echo 'successfully inserted ' . $company_symbol[0];
                    }else{
                        
                        echo 'insertion fail '. $company_symbol[0];
                    }
                    
                
                }
            }
            
        } catch (Exception $e) {
            
            echo 'errz ' ;  
            echo '<pre>'; print_r($e);
            
            $System_Notification_contr->failReadCompanyList();
        }
        
        exit;
    }
    
    /*
     * @author: ZAHIR
     * Insert New company list from NSE
     */
    function readCompanyListFromCSV(){
        
        $this->load->model('Companies_model');
        
        try{
            
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );
            
            $data = file_get_contents("https://archives.nseindia.com/content/equities/EQUITY_L.csv", false, $context);
            
            if($data === FALSE) {
                
                echo 'data not found <br/>'; 
                echo 'System_Notification_contr <br/>'; return;
            }
            
            echo '<pre>'; print_r($data);
            
            $rows = explode("\n", $data);
            
            if( empty($rows)){ return; }
            
            $company_csv_arr = array();
            
            foreach ($rows as $row_key=>$row_value) {
                
                if( $row_key == 0 || empty($row_value) ){ continue; }
                
                echo 'csv key ' . $row_key . ' <br/>';
                echo '<pre>'; print_r(str_getcsv($row_value));
                
                if( empty(str_getcsv($row_value)) ){ continue; }
                
                $company_symbol = str_getcsv($row_value);
                
                if( !empty($company_symbol[0])){
                    
                    $company_csv_arr[] = $company_symbol[0];
                    
                    echo '$company_symbol[0] : ' . $company_symbol[0] . ' <br/>';
                    
                    $company_data = $this->Companies_model->getActiveInactiveCompanyBySymbol($company_symbol[0]);
                    
                    if( !empty($company_data) && $company_data->status != 1 ){
                        
                        echo 'System_Notification_contr inActiveCompanyAddedInNSE <br/>';
                        continue;
                    }
                    
                    $company_id = 0;
                    
                    if( !empty( $company_data->id ) ){
                    
                        $company_id = $company_data->id;

                        echo '$company_id : ' . $company_id . ' <br/>';
                    
                    }
                    
                    if( $company_id > 0 ){ continue; }
                    
                    $each_company_arr = array();
                    
                    $each_company_arr['symbol'] = $company_symbol[0];
                    $each_company_arr['name'] = $company_symbol[1];
                    $each_company_arr['exchange_name'] = 'nse';
                    $each_company_arr['created_at'] = date('Y-m-d H:i:s');
                    
                    $last_inserted_id = $this->Companies_model->insertCompanies($each_company_arr);
                    
                    if($last_inserted_id > 0 ){
                        
                        echo 'successfully inserted ' . $company_symbol[0];
                    }else{
                        
                        echo 'insertion fail '. $company_symbol[0];
                    }
                    
                
                }
            }
            
            echo "<br/> ### company_csv_arr ###";
            echo '<pre>'; print_r($company_csv_arr); 
            
            $company_db_data = $this->Companies_model->listAllCompanies();
            
            $company_db_arr = array();
            foreach( $company_db_data AS $db_data ){
                
                $company_db_arr[] = $db_data->symbol;
                
               
                if (in_array($db_data->symbol, $company_csv_arr)) {
                    
                    echo "Match found of ".$db_data->symbol." means it is active";
                    echo  "\n";
                } else {
                    echo "Match not found of ".$db_data->symbol." means it is not active ";
                    echo  "\n";
                    $this->Companies_model->makeCompanyInactive($db_data->id, $db_data->symbol);
                }
                
               
            }
            
            echo "<br/> ### company_db_arr ###";
            echo '<pre>'; print_r($company_db_arr); 
            
        } catch (Exception $e) {
            
            echo 'errz ' ;  
            echo '<pre>'; print_r($e);
            echo 'System_Notification_contr failReadCompanyList <br/>';
        }
        
        exit;
    }
}
