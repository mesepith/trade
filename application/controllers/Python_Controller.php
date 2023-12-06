<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Python_Controller extends MX_Controller {
    
    function receivePyStockDataViaApi(){
        
        $post_data = $this->input->post('post_data');
        $raw_data = $this->input->post('raw_data');
        
        $return = $this->receivePyStockData($post_data, $raw_data);
        
        echo $return; exit();
        
    }
    
    public function receivePyStockData($post_data, $raw_data) {
        
        $this->load->model('Stock_data_model');
        
//        $post_data = $this->input->post();
        
//        $this->Stock_data_model->insertStockDataLog( company_id, company_symbol, whole_data, market_running );
        
        $data_log_arr = array();
        $data_log_arr['company_id'] = $post_data['company_id'];
        $data_log_arr['company_symbol'] = $post_data['company_symbol'];
        $data_log_arr['data'] = $raw_data;
        $data_log_arr['exchange_name'] = $post_data['exchange_name'];
        $data_log_arr['market_running'] = $post_data['market_running'];
        $data_log_arr['server'] = $post_data['server'];
        
        $stock_data_log_id = $this->Stock_data_model->insertStockDataLog( $data_log_arr );
        
        if($stock_data_log_id> 0){
            
            $data_log_arr['data'] = $post_data['whole_data'];
            
            $return = $this->designingStockData($data_log_arr, $stock_data_log_id);  
            
            return $return;
        }
        
        
        
    }

    
    function designingStockData( $data_log_arr, $stock_data_log_id ){        
        
        $this->load->model('Stock_data_model');
        
        if($data_log_arr['market_running']){
            
            $stock_data_table = 'stock_data_live';
            
        }else{
            
            $stock_data_table = 'stock_data';
            
        }
        
        $whole_data = json_decode($data_log_arr['data'], true);
        
        $stock_data_arr = array();
        
        $stock_data_arr['stock_data_log_id'] = $stock_data_log_id;
        $stock_data_arr['company_name'] = $whole_data['companyName'];
        $stock_data_arr['series'] = $whole_data['series'];
        
        $stock_data_arr["created_at"] = date("Y-m-d H:i:s");
        $stock_data_arr["created_at_date"] = date("Y-m-d");
        $stock_data_arr["exchange_name"] = $data_log_arr['exchange_name'];
        $stock_data_arr["company_id"] = $data_log_arr['company_id'];
        $stock_data_arr["company_symbol"] = $data_log_arr['company_symbol'];
        
        $stock_data_arr["open_price"] = $whole_data['open'];
        $stock_data_arr["last_price"] = $whole_data['lastPrice'];
        
        $stock_data_arr["close_price"] = $whole_data['closePrice'];
        
        if( $data_log_arr['market_running'] ==0 && $whole_data['closePrice'] ==0 ){
            
            $stock_data_arr["close_price"] = $whole_data['lastPrice'];
        }
        
        
        $stock_data_arr["day_high_price"] = $whole_data['dayHigh'];
        $stock_data_arr["day_low_price"] = $whole_data['dayLow'];
        
        $stock_data_arr["total_traded_volume"] =(!empty($whole_data['totalTradedVolume'])) ? $whole_data['totalTradedVolume']: '';
        $stock_data_arr["delivery_quantity"] = (!empty($whole_data['deliveryQuantity'])) ? $whole_data['deliveryQuantity']: '';
        $stock_data_arr["delivery_to_traded_quantity"] = (!empty($whole_data['deliveryToTradedQuantity'])) ? $whole_data['deliveryToTradedQuantity']: '';
        
        $stock_data_arr["total_buy_quantity"] = (!empty($whole_data['totalBuyQuantity'])) ? $whole_data['totalBuyQuantity']: '';;
        $stock_data_arr["total_sell_quantity"] = (!empty($whole_data['totalSellQuantity'])) ? $whole_data['totalSellQuantity']: '';;
        $stock_data_arr["total_traded_value"] = (!empty($whole_data['totalTradedValue'])) ? $whole_data['totalTradedValue'] : '';
        
        
        $stock_date_timestamp = strtotime($whole_data['secDate']);
        $stock_date_time = date('Y-m-d H:i:s', $stock_date_timestamp);   
        $stock_data_arr['stock_date_time'] = $stock_date_time;
        
        $stock_date = date('Y-m-d', $stock_date_timestamp);   
        $stock_data_arr['stock_date'] = $stock_date;
        
        $stock_time = date('H:i:s', $stock_date_timestamp);   
        $stock_data_arr['stock_time'] = $stock_time;
        
        /*
         * New Column
         */
        
        $stock_data_arr["pd_sector_pe"] = $whole_data['pdSectorPe'];
        $stock_data_arr["pd_symbol_pe"] = $whole_data['pdSymbolPe'];
        $stock_data_arr["pd_sector_ind"] = $whole_data['pdSectorInd'];
        
        $stock_data_arr["price_change"] = $whole_data['change'];
        $stock_data_arr["price_change_in_p"] = $whole_data['pChange'];
        $stock_data_arr["vwap"] = $whole_data['vwap'];
        $stock_data_arr["lower_cp"] = $whole_data['lowerCP'];
        $stock_data_arr["upper_cp"] = $whole_data['upperCP'];
        $stock_data_arr["p_price_band"] = $whole_data['pPriceBand'];
        $stock_data_arr["base_price"] = $whole_data['basePrice'];        
        
        $stock_data_arr["year_week_low"] = $whole_data['yearWeekLow'];
        $stock_data_arr["year_week_low_date"] = $whole_data['yearWeekLowDate'];
        $stock_data_arr["year_week_high"] = $whole_data['yearWeekHigh'];
        $stock_data_arr["year_week_high_date"] = $whole_data['yearWeekHighDate'];
        
        $stock_data_arr["no_block_deals"] = (!empty($whole_data['noBlockDeals'])) ? $whole_data['noBlockDeals'] : '';
        
        $stock_data_arr["total_market_cap"] = (!empty($whole_data['totalMarketCap'])) ? $whole_data['totalMarketCap'] : '';
        
        $stock_data_arr["quantity_traded"] = (!empty($whole_data['quantityTraded'])) ? $whole_data['quantityTraded'] : '';
        
        /* if( $data_log_arr['market_running'] == 0 ){
        
            $stock_data_arr["total_no_of_trades"] = $whole_data['total_no_of_trades'];
            $stock_data_arr["total_traded_value_eod"] = $whole_data['total_traded_value_eod'];
            $stock_data_arr["volume_by_total_no_of_trade"] = $whole_data['volume_by_total_no_of_trade'];
        } */
                
        
        /*New column end*/
        
        $is_stock_data_insert = $this->Stock_data_model->insertStockData( $stock_data_arr, $stock_data_table );
        
//        echo json_encode($is_stock_data_insert);
        
        return $is_stock_data_insert;
        
    }
    
    /*
     * @author: ZAHIR
     * Desc: get put call data
     */
    public function receivePyPutCallData(){
        
        $this->load->model('Put_call_log_model');
        $post_data = $this->input->post();
//        $post_data['price_date_time'] = json_encode(json_decode($post_data['price_date_time']), JSON_UNESCAPED_SLASHES);
//        $post_data['price_date_time'] = json_encode($post_data['price_date_time'], JSON_UNESCAPED_SLASHES);
        $is_put_call_data_insert = $this->Put_call_log_model->insertPutCallDataLog( $post_data );
        
        echo $is_put_call_data_insert;
        exit;
        
//        echo json_encode($post_data); 
//        exit;
        
        $price_date_time_arr = $post_data['price_date_time'];
        
        echo json_encode($price_date_time_arr); exit;
        echo json_encode($post_data['company_symbol']); 
        echo json_encode($whole_data['price_date_time']); exit;
        
    }
    

}
