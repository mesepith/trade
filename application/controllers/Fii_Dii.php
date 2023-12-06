<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fii_Dii extends MX_Controller {

    public function totalInvestOftradingActivity(  ) {
        
        $post_data = $this->input->post();

        $fii_get_data = json_decode($post_data["fii_dii_activity_data"], true);
        
        $data =array();
        
        $this->load->model('Fii_dii_model');
        
        foreach( $fii_get_data['data'] AS $value){
            
            $data["investor_type"] = trim( str_replace("*", "", $value[0]) );
            
            $invest_date = $value[1];        
            $invest_date_str_to_time = strtotime($invest_date);
            $invest_date_format = date('Y-m-d', $invest_date_str_to_time); 
            $data["investment_date"] = $invest_date_format;
            
            $data["buy_value"] = $value[2];
            $data["sell_value"] = $value[3];
            $data["net_value"] = $value[4];
            
            $this->Fii_dii_model->insertTotalInvestOftradingActivity( $data );
            
        }
        
        echo 'success';
        
        
//        $this->breakInvestorsActivityData($fii_get_data);
//        echo $fii_get_data;
        exit;
        
        $fii_get_data = json_decode($post_data['fii_data'], true);
        $this->breakInvestorsActivityData($fii_get_data);
        
        $dii_get_data = json_decode($post_data['dii_data'], true);
        $this->breakInvestorsActivityData($dii_get_data);
        
//        echo 1; exit;
        echo json_encode($dii_get_data["data"][0][1]); exit;
    }
    
    function breakInvestorsActivityData( $fii_dii_data ){
        
        $this->load->model('Fii_dii_model');
        
//        $fii_dii_data = $get_data["data"][0][1];
        
        $data["investor_type"] = $fii_dii_data["data"][0][0];
        
        
        $invest_date = $fii_dii_data["data"][0][1];
        
        $invest_date_str_to_time = strtotime($invest_date);
        $invest_date_format = date('Y-m-d', $invest_date_str_to_time); 
            
        $data["investment_date"] = $invest_date_format;
        
        $data["buy_value"] = $fii_dii_data["data"][0][2];
        $data["sell_value"] = $fii_dii_data["data"][0][3];
        $data["net_value"] = $fii_dii_data["data"][0][4];
        
//        $this->Fii_dii_model->insertTotalInvestOftradingActivity( $data );
        
    }
    
    /*
     * Fetch FII DII cash investment data from nse  
     * https://www.nseindia.com/all-reports/historical-equities-fii-fpi-dii-trading-activity
     */
    function fiiDiiCashInvestment(){
        
        include_once (dirname(__FILE__) . "/Nse_Contr.php");
        
        $url = 'https://www.nseindia.com/api/fiidiiTradeReact';
        $referer = 'https://www.nseindia.com/all-reports/historical-equities-fii-fpi-dii-trading-activity';
        
        $Nse_Contr = new Nse_Contr(); 
        
        $data_return_arr = $Nse_Contr->curlNse($url, $referer);
        
        if( empty($data_return_arr)){ return; }
        
        echo 'rows ' ;  
        echo '<pre>'; print_r($data_return_arr);
        
        $this->load->model('Fii_dii_model');
        
        foreach( $data_return_arr AS $value){
            
            $data = array();
            
            $data["investor_type"] = trim( str_replace("*", "", $value['category']) );
            
            $invest_date = $value['date'];        
            $invest_date_str_to_time = strtotime($invest_date);
            $invest_date_format = date('Y-m-d', $invest_date_str_to_time); 
            $data["investment_date"] = $invest_date_format;
            
            $data["buy_value"] = $value['buyValue'];
            $data["sell_value"] = $value['sellValue'];
            $data["net_value"] = $value['netValue'];
            
            $this->Fii_dii_model->insertTotalInvestOftradingActivity( $data );
        }
    }
    
    /*
     * @author: ZAHIR
     * DESC: get sectorwise investment data of fpi/fpi from NSDL
     */
    
    function getNsdlSectoreInvestDataofFii(){
        
        $this->load->model('Fii_dii_model');
        
        $insert_data = array();
        
        $post_data = $this->input->post();
        $date = trim($this->input->post('date'));
        
        $format_date = str_replace(',', '-', $date);
        $format_date = str_replace(' ', '-', $format_date);
        
        $insert_data['report_date'] = date('Y-m-d', strtotime($date));
//        $insert_data['report_date'] = '2019-08-15';
        
//        echo json_encode($post_data['json_data']); 
        
        $data = json_decode($post_data['json_data'], true);
        
        $total_column = count($data['columns']);
        $total_rows = count($data['index']);
        
//        echo $filename . '-total-column-' . $total_column . '-total-rows-' . $total_rows;
        
        for( $row=3; $row<=$total_rows; $row++){
            
            if(empty($data['data'][$row])){continue;}
            
            $sectors_investment_data = $data['data'][$row];
            
            if(empty($sectors_investment_data[1])){continue;}
            
            if( (empty($sectors_investment_data[18])) && empty($sectors_investment_data[19]) && empty($sectors_investment_data[20]) && empty($sectors_investment_data[21])){
                
                continue;
            }
            
            $insert_data['sector_name'] = trim($sectors_investment_data[1]);
            $insert_data['equity'] = ( !empty($sectors_investment_data[18]) ) ? trim($sectors_investment_data[18]) : 0;
            $insert_data['debt'] = (!empty($sectors_investment_data[19])) ? trim($sectors_investment_data[19]) : 0;
            $insert_data['hybrid'] = (!empty($sectors_investment_data[20])) ? trim($sectors_investment_data[20]) : 0;
            $insert_data['total'] = (!empty($sectors_investment_data[21])) ? trim($sectors_investment_data[21]) : 0;
            $insert_data['source'] = 'nsdl';
            
//            echo $sectore_name . '- ' . $equity . ' - ' . $debt . ' - ' . $hybrid . ' - ' . $total . ' - ' . $db_date;
            
            $return = $this->Fii_dii_model->insertNsdlSectoreInvestDataofFii( $insert_data );
            
            
        }
        
        if( $return> 0 ){
            
            $filename = PROJECT_DOCUMENT_ROOT . '/assets/fii-dii/nsdl-sector-invest/' . $format_date . '.json';
            file_put_contents($filename, $post_data['json_data']);
            
        }
        echo $return; exit;
        
    }
    
    /*
     * @author: ZAHIR
     * DESc: insert Fii Derivative Data
     */

    function insertFiiDerivativeData(){
        
        $post_data = $this->input->post();
        
        $this->load->model('Fii_dii_model');
        
//        echo '<pre>'; print_r($post_data['fii_derivative_data']); exit;
        
        $data = json_decode($post_data['fii_derivative_data'], true);
        
        echo '<pre>'; print_r($data['data']);
        
        foreach( $data['data'] AS $key=>$derivative_data){
            
            $insert_data = array();
            
            $reporting_date = $derivative_data[0];
            
//            var_dump($this->validateDate($reporting_date));
            
            $is_valid_date = $this->validateDate($reporting_date) . "\r\n";
            
            if( $is_valid_date ==1 ){
                
                $valid_date = $reporting_date;
//                continue; }
            
                $insert_data['reporting_date'] = date('Y-m-d', strtotime($reporting_date));

                echo '$insert_data ' . $insert_data['reporting_date'] . "\r\n";

                $insert_data['derivative_products'] = str_replace(',', '', $derivative_data[1]);
                $insert_data['buy_no_of_contract'] = str_replace(',', '', $derivative_data[2]);
                $insert_data['buy_amount'] = str_replace(',', '', $derivative_data[3]);
                $insert_data['sell_no_of_contract'] = str_replace(',', '', $derivative_data[4]);
                $insert_data['sell_amount'] = str_replace(',', '', $derivative_data[5]);
                $insert_data['oi_at_end_no_of_contract'] = str_replace(',', '', $derivative_data[6]);
                $insert_data['oi_at_end_amount'] = str_replace(',', '', $derivative_data[7]);
            
            }else if( $key == 5 || $key == 6 ||$key == 7 ||$key == 8 ){
                
                $insert_data['reporting_date'] = date('Y-m-d', strtotime($valid_date));
                
                $insert_data['derivative_products'] = str_replace(',', '', $derivative_data[0]);
                $insert_data['buy_no_of_contract'] = str_replace(',', '', $derivative_data[1]);
                $insert_data['buy_amount'] = str_replace(',', '', $derivative_data[2]);
                $insert_data['sell_no_of_contract'] = str_replace(',', '', $derivative_data[3]);
                $insert_data['sell_amount'] = str_replace(',', '', $derivative_data[4]);
                $insert_data['oi_at_end_no_of_contract'] = str_replace(',', '', $derivative_data[5]);
                $insert_data['oi_at_end_amount'] = str_replace(',', '', $derivative_data[6]);
                
            }else{
                
                continue;
            }
            
            echo '<pre>'; print_r($insert_data);
            
            $return = $this->Fii_dii_model->insertFiiDerivativeData( $insert_data );
        }
        
    }
    
    /*
     * Read Fii derivative data from nse for all date
     */
    function readFiiDerivativeNseForAllDate(){
        
        require PROJECT_DOCUMENT_ROOT . '/application/libraries/simplexls/SimpleXLS.php';
        
        // Start date
	$date = '2019-01-01';
	// End date
	$end_date = '2020-03-16';

	while (strtotime($date) <= strtotime($end_date)) {
                echo "$date\n <br/>";
                
                $market_date = date("dmY", strtotime($date) );                                
                
                echo "$market_date\n <br/>";
                
                $this->readFiiDerivativeNse($date);
                
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
	}
    }
    
    /*
     * Read Fii derivative data from nse
     */
    
    function readFiiDerivativeNse( $input_market_date=false ){        
        
        include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
        
        $System_Notification_contr = new System_Notification_Controller();
        
        if( !empty ($input_market_date) ){
            
            $input_market_date = $input_market_date;
            
        }else{
            
            $input_market_date = date ("Y-m-d");
            
        }
        
        require PROJECT_DOCUMENT_ROOT . '/application/libraries/simplexls/SimpleXLS.php';
        
        // set path to temp directory
        $temp_directory = PROJECT_DOCUMENT_ROOT . '/assets/fii-dii/fii-derivative-nse';

        // set direct url to mp3
//        $derivative_url = "https://www1.nseindia.com/content/fo/fii_stats_17-Mar-2020.xls";
        $derivative_url = "https://www1.nseindia.com/content/fo/fii_stats_" . date("d-M-Y", strtotime($input_market_date)) . ".xls";

        // set name of mp3
        $name = 'derivative-data';                        
        
        $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );
        
        // download file to temp directory
        $data = file_put_contents($temp_directory.'/'.$name.'.xls',file_get_contents($derivative_url, false, $context));
        
//        echo '<pre>';
//        print_r($data); exit;
        
        if($data == FALSE) {

            echo 'data not found'; $System_Notification_contr->failReadFiiDerivativeNse($derivative_url, $input_market_date); return;
        }
        
        if ($xls = SimpleXLS::parse($temp_directory . '/' . $name . '.xls')) {

//            echo '<pre>';
//            print_r($xls->rows());
            
            foreach( $xls->rows() AS $key=>$each_derivative_segment){
                
                echo '$key :' . $key;
                
                if( $key === 3 || $key === 4 || $key === 5 || $key === 6 ){ 
                    
                    $insert_data['reporting_date'] = date('Y-m-d', strtotime( $input_market_date ));
                    
                    $insert_data['derivative_products'] = ucwords( strtolower($each_derivative_segment[0]) );

                    $insert_data['buy_no_of_contract'] = $each_derivative_segment[1];
                    $insert_data['buy_amount'] = $each_derivative_segment[2];

                    $insert_data['sell_no_of_contract'] = $each_derivative_segment[3];
                    $insert_data['sell_amount'] = $each_derivative_segment[4];

                    $insert_data['oi_at_end_no_of_contract'] = $each_derivative_segment[5];
                    $insert_data['oi_at_end_amount'] = $each_derivative_segment[6];
                    
                    $insert_data['source'] = 'nse';

                    echo '<pre>';
                    print_r($insert_data);
                    
                    $this->load->model('Fii_dii_model');
                    $this->Fii_dii_model->insertFiiDerivativeData( $insert_data );
                    
                }
                
            }
            
        } else {
            echo SimpleXLS::parseError();

            echo 'fail';
        }
    }
    
    /*
     * function to check if date is valid date
     */
    function validateDate($date, $format = 'd-M-Y'){
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
    
    /*
     * @author: ZAHIR
     * DESc: insert Fii Derivative Data
     */

    function insertFiiCashData(){
        
        $this->load->helper('function_helper');
        
        $post_data = $this->input->post();
        
        $this->load->model('Fii_dii_model');
        
//        echo '<pre>'; print_r($post_data['fii_derivative_data']); exit;
        
        $data = json_decode($post_data['fii_cash_data'], true);
        
//        echo '<pre>'; print_r($data['data']);
        
//        exit;
        
        foreach( $data['data'] AS $key=>$cash_data){
            
            if( !empty($cash_data[1]) && $cash_data[1] === 'Equity' && !empty($cash_data[2]) && $cash_data[2] === 'Stock Exchange' ){
                
            }else{
                
                continue;
            }
            
            $insert_data = array();
            
            $reporting_date = trim($cash_data[0]);
            
//            var_dump($this->validateDate($reporting_date));
            
            $is_valid_date = validateDate($reporting_date);
            
            if( $is_valid_date ==1 ){
                
                $valid_date = $reporting_date;
//                continue; }
            
                $insert_data['market_date'] = date('Y-m-d', strtotime($reporting_date));

                echo '$insert_data ' . $insert_data['market_date'] . "\r\n";

                $insert_data["category"] = 'FII';
                $insert_data["buy_value"] = trim($cash_data[3]);
                $insert_data["sell_value"] = trim($cash_data[4]);

                $insert_data['exchange'] = 'nsdl';
                $insert_data['trading_type'] = 'cash';
                $insert_data["created_at"] = date("Y-m-d H:i:s");
            
            }else{
                
                continue;
            }
            
            echo '<pre>'; print_r($insert_data);
            
            $return = $this->Fii_dii_model->categoryWiseTurnover($insert_data);
        }
        
    }
}
