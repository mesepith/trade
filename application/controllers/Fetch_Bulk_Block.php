<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
include_once (dirname(__FILE__) . "/Nse_Contr.php");
include_once (dirname(__FILE__) . "/Send_Api_Contr.php");
include_once (dirname(__FILE__) . "/Python_Controller.php");

class Fetch_Bulk_Block extends MX_Controller {
    
    function nseBseBulkBlock(){
        
//        echo date('dMY'); exit;
        
        $this->load->model('Companies_model');
        
        $this->load->model('BulkBlock_model');
        
        $bulk_input_arr = array(
            'nse'=>'https://www.nseindia.com/api/historical/bulk-deals?from=04-12-2023&to=11-12-2023&csv=true%22',
            'bse'=>'https://www.bseindia.com/markets/downloads/Bulk_'.date('dMY').'.csv',
        );
        
        foreach( $bulk_input_arr AS $exchange=>$url){
            echo $exchange . '<br/>';
            echo $url . '<br/>';
            $this->fetchBulkBlockDeal($exchange, $url, 'bulk');
        }
        
        $block_input_arr = array(
            'nse'=>'https://www.nseindia.com/api/historical/block-deals?from=04-12-2023&to=11-12-2023&csv=true%22',
            'bse'=>'https://www.bseindia.com/markets/downloads/Block_'.date('dMY').'.csv',
        );
        
        foreach( $block_input_arr AS $exchange=>$url){
            echo $exchange . '<br/>';
            echo $url . '<br/>';
            $this->fetchBulkBlockDeal($exchange, $url, 'block');
        }
        
    }
    
    function fetchBulkBlockDeal( $exchange, $url, $bulk_or_block ){
        
        $Send_Api_Contr = new Send_Api_Contr();
        
        $is_inserted = $Send_Api_Contr->checkTodaysBulkBlockInserted($exchange, $bulk_or_block);
        
        if( $is_inserted > 0 ){ return; }
        
//        echo $is_inserted; exit;
        
        $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
        );
                
        $data = file_get_contents($url, false, $context);        
        
        if ($data === FALSE) {

            echo 'data not found';
            return;
        }
        
        $rows = explode("\n", $data);        
        
        foreach ($rows as $row_key=>$bulk_block_csv_row_value) {
            
            if( $row_key === 0 ){continue;}
            
            $csv_inp_data_arr= str_getcsv($bulk_block_csv_row_value);                         
            
           echo '<pre>';
           print_r($csv_inp_data_arr); exit;
            
            if( empty($csv_inp_data_arr) || empty($csv_inp_data_arr[0]) || empty($csv_inp_data_arr[1]) ){

                continue;
            }
            
            $bulk_block_arr = array();
            
            if( $exchange === 'nse'){
                
                $market_date = date('Y-m-d', strtotime(trim($csv_inp_data_arr[0])));
                
                $company_symbol = trim($csv_inp_data_arr[1]);
                
                $buy_or_sale = strtolower(trim($csv_inp_data_arr[4]));
                
                $bulk_block_arr['remarks'] = trim($csv_inp_data_arr[7]);
                
            }else if( $exchange === 'bse'){
                
                $bse_inp_market_date = trim($csv_inp_data_arr[0]);
                
                $bse_market_date = str_replace('/', '-', $bse_inp_market_date);
                
                $market_date = date('Y-m-d', strtotime($bse_market_date));                
                
                $company_symbol = trim($csv_inp_data_arr[2]);
                
                $buy_or_sale_symbol = strtolower(trim($csv_inp_data_arr[4]));
                
                if( $buy_or_sale_symbol === "p" ){
                    
                    $buy_or_sale = 'buy';
                    
                }else if( $buy_or_sale_symbol === "s" ){
                    
                    $buy_or_sale = 'sale';
                }else{
                    
                    $buy_or_sale = $buy_or_sale_symbol;
                }
            }
            
            if( $market_date !== date("Y-m-d") ){ continue; }
            
            $company_model_loaded = 'yes';
            $company_id = $Send_Api_Contr->getCompanyIdAndIndexIdBySymbol( $company_symbol, $company_model_loaded );
            
            if( empty($company_id) ){ continue; }
                          
            $bulk_block_arr['market_date'] = $market_date;
            $bulk_block_arr['company_symbol'] = $company_symbol;
            $bulk_block_arr['company_id'] = $company_id;
            $bulk_block_arr['client_name'] = trim($csv_inp_data_arr[3]);
            $bulk_block_arr['buy_or_sale'] = $buy_or_sale;
            $bulk_block_arr['quantity_traded'] = trim($csv_inp_data_arr[5]);
            $bulk_block_arr['trade_price'] = trim($csv_inp_data_arr[6]);
            
            $bulk_block_arr['bulk_or_block'] = $bulk_or_block;
            $bulk_block_arr['exchange'] = $exchange;
            
            $bulk_block_arr["created_at"] = date("Y-m-d H:i:s");
            
            
            $Send_Api_Contr->inserBulkBlockDeal($bulk_block_arr);
                
        }
    }

    public function crawlBulkBlock($url, $referer, $bulk_or_block, $exchange ){

        $Send_Api_Contr = new Send_Api_Contr();
        $this->load->model('BulkBlock_model');

        $is_inserted = $Send_Api_Contr->checkTodaysBulkBlockInserted($exchange, $bulk_or_block);

        // echo $bulk_or_block . '  is_inserted ' . $is_inserted; exit;
        
        if( $is_inserted > 0 ){ echo $bulk_or_block . ' of ' . $exchange . ' is already inserted '; return; }

        $Python_contr = new Python_Controller();
        $Nse_Contr = new Nse_Contr();

        $Python_contr->executeCookieScript();

        $data_return_arr = $Nse_Contr->curlNse($url, $referer);

        echo '$url : ' . $url . '<br/>';


        if( !empty($data_return_arr) && !empty($data_return_arr['data']) && !empty($data_return_arr['data']) ){

            $bulk_block_arr = array();

            foreach($data_return_arr['data'] AS $key=>$deal_value ){

                $company_symbol = $deal_value['BD_SYMBOL'];

                $company_id = $Send_Api_Contr->getCompanyIdAndIndexIdBySymbol( $company_symbol, $company_model_loaded=false );

                // echo ' $company_id : ' . $company_id . '<br/>';

                $market_date = date('Y-m-d', strtotime(trim($deal_value['BD_DT_DATE'])));

                $bulk_block_arr['market_date'] = $market_date;
                $bulk_block_arr['company_symbol'] = $company_symbol;
                $bulk_block_arr['company_id'] = $company_id;
                $bulk_block_arr['client_name'] = trim($deal_value['BD_CLIENT_NAME']);
                $bulk_block_arr['buy_or_sale'] = strtolower(trim($deal_value['BD_BUY_SELL']));
                $bulk_block_arr['quantity_traded'] = trim($deal_value['BD_QTY_TRD']);
                $bulk_block_arr['trade_price'] = trim($deal_value['BD_TP_WATP']);

                $bulk_block_arr['bulk_or_block'] = $bulk_or_block;
                $bulk_block_arr['exchange'] = $exchange;

                $bulk_block_arr["created_at"] = date("Y-m-d H:i:s");
                $bulk_block_arr["updated_at"] = date("Y-m-d H:i:s");

                $Send_Api_Contr->inserBulkBlockDeal($bulk_block_arr);

                echo '<pre>';
                print_r($bulk_block_arr);
            }

        }
    }

    function fetchNseBlockDeal(){

        $currentDate = date('d-m-Y');
        $dateOneWeekBefore = date('d-m-Y', strtotime('-1 week', strtotime($currentDate)));

        $url = 'https://www.nseindia.com/api/historical/block-deals?from='.$currentDate.'&to=' . $currentDate;
        $referer = 'https://www.nseindia.com/report-detail/display-bulk-and-block-deals';

        $this->crawlBulkBlock($url, $referer, 'block', 'nse');
    }

    function fetchNseBulkDeal(){

        $currentDate = date('d-m-Y');
        $dateOneWeekBefore = date('d-m-Y', strtotime('-1 week', strtotime($currentDate)));
        
        $url = 'https://www.nseindia.com/api/historical/bulk-deals?from='.$currentDate.'&to=' . $currentDate;
        $referer = 'https://www.nseindia.com/report-detail/display-bulk-and-block-deals';

        $this->crawlBulkBlock($url, $referer, 'bulk', 'nse');
    }
    
}
