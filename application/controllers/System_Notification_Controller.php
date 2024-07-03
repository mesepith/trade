<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class System_Notification_Controller extends MX_Controller {
    
    /*
     * @author: ZAHIR
     * DESC: Check if disk space is less than 95 % or not
     */
    
    function diskSpaceNotify(){
        
        if( ENVIRONMENT === "development" ){
            
            $partition_name = 'sda1';
            
        }else{
            
            $partition_name = 'vda1';
            
        }
        
        $check_uses_percentage = exec("df -hl | grep ".$partition_name." | awk 'BEGIN{print ".'Usez'."} {percent+=$5;} END{print percent}' | column -t");
        
        echo 'uses_percentage : ' . $check_uses_percentage;
        
        if( $check_uses_percentage > 90 ){
            
            $disk_space_notify_arr = $this->checkDiskSpace();

            $this->diskSpaceSendEmail( $disk_space_notify_arr );
            
        }
        
        
    }
    
    /*
     * @author: ZAHIR
     * DESC: Send mail if disk space is less than 95 % 
     */
    
    function diskSpaceSendEmail($data) {
    
        $link = base_url(). 'System_Notification_Controller/checkDiskSpace';
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Disk space size is minimum of scaleway server 1';                
        $mail_data['message']= 'Disk space percentage is ' . $data['total_Uses_in_perchantage'] . ' , <br/>' . $link;
        send_mailz($mail_data);
        
        
        return;
    }
    
    /*
     * @author: ZAHIR
     * DESC: Check disk space
     */
    
    function checkDiskSpace() {
        
        if( ENVIRONMENT === "development" ){
            
            $partition_name = '/dev/sda1';
            
        }else{
            
            $partition_name = '/dev/vda1';
            
        }
        
	$disk_space = exec("df -hl " . $partition_name);
        
	$explode_disk_space = explode(" ", $disk_space);
        
        $disk_arr = array();
        
        $count=0;
        
        for( $i=0; $i<count($explode_disk_space); $i++){
            
            if( ( !empty($explode_disk_space[$i]) ) ){
                
                $count++;
                
                $disk_arr[$count] = $explode_disk_space[$i];
                
            }
            
        }
        
	$disk_space_arr = array();
	
	$total_size = $disk_arr[2];
	
	$disk_space_arr['total_size'] = $total_size;
	
	$used = $disk_arr[3];
	
	$disk_space_arr['used'] = $used;
	
	$available = $disk_arr[4];
	
	$disk_space_arr['available'] = $available;
	
	$perchantage_of_uses = $disk_arr[5];
	
	$disk_space_arr['total_Uses_in_perchantage'] = $perchantage_of_uses;
	
	echo '<pre>';
	print_r($disk_space_arr);
        
        return $disk_space_arr;
        
    }
    
    /*
     * @authir: ZAHIR
     * DESC: Notify while nse forbidden
     */
    function nseForbidden(){
        
        if(!empty($this->input->post())){
            
            $post_data = $this->input->post();
            $message = 'NSE blocked server : ' . json_encode($post_data);
            
        }else{
            
            $message = 'NSE blocked server';
        }
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'NSE Forbidden 403';   
        $mail_data['message']= $message;
        send_mailz($mail_data);
        
    }
    
    /*
     * @authir: ZAHIR
     * DESC: Notify while mysql connection fail
     */
    function mysqlConnectFail(){
        
        if(!empty($this->input->post())){
            
            $post_data = $this->input->post();
            $message = 'Mysql Connection Fail : ' . json_encode($post_data);
            
        }else{
            
            $message = 'Mysql Connection Fail';
        }
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Mysql Connection Fail';   
        $mail_data['message']= $message;
        send_mailz($mail_data);
        
    }
    
    function stkFailInsertApi($company_symbol, $responseBodyAsString, $raw_data, $url){
        
        $email_data['data'] = array($company_symbol, $responseBodyAsString, $raw_data, $url);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail while sending stock for insert through Api : ' . $company_symbol;   
        $mail_data['message']= $this->load->view('notify/emails/stock_fail_insert', $email_data, true);
        send_mailz($mail_data);
        
    }
    
    function stkFailCrawling($company_id, $company_symbol){        
        
        $email_data['data'] = array('company_id'=>$company_id, 'company_symbol'=>$company_symbol);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail while crawling: ' . $company_symbol . ' - ' . SERVER_NAME;   

        $mail_data['message']= $this->load->view('notify/emails/stock_fail_crawl', $email_data, true);
        send_mailz($mail_data);
        
    }
    
    function makeStkInactive($company_symbol){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Stock is set to inactive: ' . $company_symbol;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function putCallFailCrawl($company_id, $company_symbol){        
        
        $email_data['data'] = array('company_id'=>$company_id, 'company_symbol'=>$company_symbol);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail Put Call crawling: ' . $company_symbol . ' - ' . SERVER_NAME;   

        $mail_data['message']= $this->load->view('notify/emails/option_fail_crawl', $email_data, true);
        send_mailz($mail_data);
        
    }
    
    function putCallNoRecord($company_id, $company_symbol){    
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';     
        $msg = "<a href='https://www.nseindia.com/get-quotes/equity?symbol=".urlencode($company_symbol)."'>Check Here</a>";
        $mail_data['subject'] = 'Empty Put Call Record: ' . $company_symbol . ' - ' . SERVER_NAME;   

        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    /*  
     * @author: ZAHIR
     * Option chain company inactive mail
     */
    function makeOCCompanyInactive($company_symbol){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Option Chain Company set to inactive: ' . $company_symbol;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function marketStatusNotRecieved(){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Market Status Not recieved from NSE';   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function failReadCompanyList(){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail Read Companay List(CSV from NSE';   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function inActiveCompanyAddedInNSE($company_symbol, $company_name, $status){                
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'In Active company is added in NSE(CSV) : ' . $company_symbol ;  
        
        $msg = "<a href='https://www.nseindia.com/get-quotes/equity?symbol=".urlencode($company_symbol)."'>Check Here To Check in NSE</a>";
        
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    
    function failReadLotSize( $derivativeType){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail Read Lot Size of market - ' . $derivativeType;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    function lotSizevalueNotSame( $derivativeType, $index_or_company_symbol){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Lot Size value is different of market - ' . $derivativeType . ' - ' . $index_or_company_symbol;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    function noLotSizeFound( $lot_arr ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' , DATA : ' . json_encode($lot_arr);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'No Lot SIze Found - ' . $lot_arr['company_symbol'] . ' - On Processing Put Call Data';   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function failReadDerivativeCompanyList($derivative_type, $error){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' , Error : ' . json_encode($error);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail Read '.$derivative_type.' Companay List(NSE)';   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function inActiveCompanyAddedInFutureNSE($company_symbol, $company_name, $status){                
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'In Active company is added in Future(NSE) : ' . $company_symbol ;  
        
        $msg = "<a href='https://www.nseindia.com/get-quotes/equity?symbol=".urlencode($company_symbol)."'>Check Here To Check in NSE</a>";
        
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function inActiveCompanyAddedInOptionNSE($company_symbol, $company_name, $status){                
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'In Active company is added in Option(NSE) : ' . $company_symbol ;  
        
        $msg = "<a href='https://www.nseindia.com/get-quotes/equity?symbol=".urlencode($company_symbol)."'>Check Here To Check in NSE</a>";
        
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function failReadDailyAnnualyVolatility( $url ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' <br/> URL ' . $url;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail - Read volatility for daily and annually';   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function failReadParticipantOi( $url, $market_date ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' <br/> URL ' . $url;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail - Read Participant wise Open Interest - ' . $market_date;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function failReadParticipantVolume( $url, $market_date ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' <br/> URL ' . $url;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail - Read Participant wise Volume - ' . $market_date;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function failReadFiiDerivativeNse( $url, $market_date ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' <br/> URL ' . $url;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail - Read Fii Derivative Data from Nse - ' . $market_date;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    function concertShareholderFound( $company_symbol, $market_date ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' <br/>';
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Concert Share holder Found - ' . $company_symbol . ' - ' . $market_date;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
    
    function futureFailCrawl($company_id, $company_symbol){        
        
        $email_data['data'] = array('company_id'=>$company_id, 'company_symbol'=>$company_symbol);
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail Future crawling: ' . $company_symbol . ' - ' . SERVER_NAME;   

        $mail_data['message']= $this->load->view('notify/emails/future_fail_crawl', $email_data, true);
        send_mailz($mail_data);
        
    }
    
    
    /*  
     * @author: ZAHIR
     * Future company inactive mail
     */
    function makeFutureCompanyInactive($company_symbol){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Future Company set to inactive: ' . $company_symbol;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
     
    function failReadNiftyTopWeightageStock( $url, $market_date ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' <br/> URL ' . $url;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail - Read Nifty Top 10 Stock - ' . $market_date;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
         
    function failReadNseTopClearingMember( $url, $market_date ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' <br/> URL ' . $url;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Fail - Read NSE Top 10 Clearing Members Volume and turnover - ' . $market_date;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
     
    function failRead( $url, $market_date, $subject ){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , SERVER_NAME: ' . SERVER_NAME . ' , FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER . ' <br/> URL ' . $url;
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = $subject . ' - ' . $market_date;   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }

    function stockNotInserted(){        
        
        $msg = 'ENVIRONMENT : ' . ENVIRONMENT . ' , TODAYS STOCK IS NOT INSERTED: ';
        
        $this->load->helper('smtp_helper');
        $mail_data['name']= 'Zahir ';
        $mail_data['from']= 'developer@zybloom.com';
        $mail_data['to']='zahiralam19cse@gmail.com';               
        $mail_data['subject'] = 'Todays Stock is not inserted: ';   
        $mail_data['message']= $msg;
        send_mailz($mail_data);
        
    }
    
}
