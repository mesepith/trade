<?php

include_once (dirname(__FILE__) . "/Nse_Contr.php");


class Chart_Fetch_Contr extends MX_Controller {
    
    function curlNaseChart( $company_symbol, $period_type, $intraday_minute, $CDDate1=false, $CDDate2=false ){
        
        $period_type_arr = array('historic'=>'1', 'intraday'=>2);
        
        $periodicity_arr = array('0'=>1, '5'=>2, '15'=>3 , '30'=>4, '60'=>5);
        
        $period_type = $period_type_arr[$period_type];
        
        $periodicity = $periodicity_arr[$intraday_minute];
        
        if( $period_type === 'intraday'){
            
            $CDDate1 = date('d-m-Y', strtotime('-3 years'));
        
            $CDDate2 = date('d-m-Y');
            
        }else{
            
            $CDDate1 = date('d-m-Y');
        
            $CDDate2 = date('d-m-Y');
        }
        
//        echo $period_type . ' ' . $periodicity . '<br/>'; exit;
        
        $url = 'https://www1.nseindia.com/ChartApp/install/charts/data/GetHistoricalNew.jsp';
        
        $part1= "'".$url."' ";
        $part2= "'Pragma: no-cache' ";
        $part3= "'Origin: https://www1.nseindia.com' ";
        $part4= "'Accept-Encoding: gzip, deflate, br' ";
        $part5= "'Accept-Language: en-US,en;q=0.9' ";
        $part6= "'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36' ";
        $part7= "'Content-Type: application/x-www-form-urlencoded' ";
        $part8= "'accept: */*' ";
        $part9= "'Cache-Control: no-cache' ";
        $part10= "'Referer: https://www1.nseindia.com/ChartApp/install/charts/mainpage.jsp' ";
        $part11= "'Connection: keep-alive' ";
        
        $data_arr = "'Instrument=FUTSTK&CDSymbol=".urlencode($company_symbol)."&Segment=CM&Series=EQ&CDExpiryMonth=1&FOExpiryMonth=1&IRFExpiryMonth=31-12-2020&CDIntraExpiryMonth=27-03-2020&FOIntraExpiryMonth=26-03-2020&IRFIntraExpiryMonth=&CDDate1=".$CDDate1."&CDDate2=".$CDDate2."&PeriodType=".$period_type."&Periodicity=".$periodicity."&ct0=g1|1|1&ct1=g2|2|1&ctcount=2&time=".time()."'";
        
        $cmd = "curl " . $part1 . "-H " . $part2 . "-H " . $part3 . "-H " . $part4 . "-H " . $part5 . "-H " . $part6 . "-H " . $part7 . "-H " . $part8 . "-H " . $part9 . "-H " . $part10 . "-H " . $part11  . " --data " . $data_arr . " --compressed";                
//        echo $cmd;
        
//        echo '<br/>';
        
//        $output = json_decode(exec($cmd) , true);
        
        $output = exec($cmd);
        
         
        
        return $output;
    }
    
    function getChartData( ){
        
        $company_symbol = 'ITC';
        
        echo $company_symbol . '<br/>';
        
        $period_type = 'intraday';
        $intraday_minute = 5; // For historic data it is  0 (zero)
        
        $curl_data = $this->curlNaseChart( $company_symbol, $period_type, $intraday_minute );
        
        $time_wise_data = explode('~', $curl_data);
        
        $chart_data_arr = array();
        
        foreach( $time_wise_data AS $time_wise_data_key=>$time_wise_data_val){
            
            if( $time_wise_data_key === 0 ){ continue; }
            
            $each_segment_data = explode('|', $time_wise_data_val);
            
            if( empty($each_segment_data[0]) ){ continue; }
            
            $date_time_arr = explode(' ', $each_segment_data[0]);

            $chart_data_arr[$date_time_arr[0]][$date_time_arr[1]]['time'] = $date_time_arr[1];
            $chart_data_arr[$date_time_arr[0]][$date_time_arr[1]]['open'] = $each_segment_data[1];
            $chart_data_arr[$date_time_arr[0]][$date_time_arr[1]]['high'] = $each_segment_data[2];
            $chart_data_arr[$date_time_arr[0]][$date_time_arr[1]]['low'] = $each_segment_data[3];
            $chart_data_arr[$date_time_arr[0]][$date_time_arr[1]]['close'] = $each_segment_data[4];
            $chart_data_arr[$date_time_arr[0]][$date_time_arr[1]]['volume'] = $each_segment_data[5];
            $chart_data_arr[$date_time_arr[0]][$date_time_arr[1]]['cumulative_vol'] = $each_segment_data[6];

            
        }
        
//        echo '<pre>';
//        print_r($chart_data_arr);
        
        return $chart_data_arr;
        
    }
    
    function gtochasticRsi(){
        
        $chart_data_arr = $this->getChartData();
        
        $time_arr = array();
        $high_arr = array();        
        $low_arr = array();
        $close_arr = array();
        
        $count=0;
        
        foreach( $chart_data_arr AS $date=> $chart_data_arr_val ){
            
            foreach( $chart_data_arr_val AS $time=> $price_vol_arr ){
                
                $time_arr[$count] = $price_vol_arr['time'];
                $high_arr[$count] = $price_vol_arr['high'];                
                $low_arr[$count] = $price_vol_arr['low'];
                $close_arr[$count] = $price_vol_arr['close'];
                
                $count++;
            }
            
        }
         
        
        $open_high_low_close_arr['time'] = $time_arr;
        $open_high_low_close_arr['high'] = $high_arr;
        $open_high_low_close_arr['low'] = $low_arr;
        $open_high_low_close_arr['close'] = $close_arr;
        
//        echo json_encode($open_high_low_close_arr); 
//        exit;
        
        $stoch_arr = trader_stoch($high_arr, $low_arr, $close_arr, $fastk_period=14, $slowk_period=3, $slowk_matype=0, $slowd_period=3, $slowd_matype=0);
        
       
        echo '############### PHP stochastsic RSI ##############';
        echo '<pre>';
        print_r($stoch_arr);
        
        /*$custom_stochastic_rsi = $this->customStochasticRsi($close_arr);        
        echo '<pre>';
        print_r($custom_stochastic_rsi);*/
    }
    
    public function fetchChartData(){
        
        /*
         * All 5 minutes extract from 0 to 60 minutes
         */
        
        $minute_range_arr = range(0,60);
        
        $analysis_time_period_arr = array();
        
        $calucate_for_minute = 5;
        
        foreach( $minute_range_arr AS $minute_range_val){
            
            if( $minute_range_val%$calucate_for_minute !=0 ){ continue; }
            
            $analysis_time_period_arr[] = $minute_range_val;
        }
        
        /*
         * All 5 minutes extract from 0 to 60 minutes End
         */
        
        $Nse_Contr = new Nse_Contr();
        
        $company_symbol = 'GOLDBEES';
        
        echo $company_symbol;
        echo '<br/>';
        
        $url = 'https://www.nseindia.com/api/chart-databyindex?index='.$company_symbol.'EQN';
        
        $referer = 'https://www.nseindia.com/get-quotes/equity?symbol='.$company_symbol;
        
        $return  = $Nse_Contr->curlNse($url, $referer);    
        
//        echo '<pre>'; print_r($return);
        
        $graph_data_arr = $return['grapthData'];
        
        $graph_date_time_arr = array();
        
        $analysis_time_include = array();
        
        $count=0;
        
        foreach( $graph_data_arr AS $key=>$graph_data_val ){
            
            $graph_hour = gmdate('H', trim($graph_data_val[0])/1000 );
            $graph_minute = gmdate('i', trim($graph_data_val[0])/1000 );
            
            $graph_hour_n_minute = $graph_hour .'-'. $graph_minute;
            
            if (!in_array($graph_minute, $analysis_time_period_arr)){ continue; } #include this graph_minute to our 5 minutes array
            if (in_array($graph_hour_n_minute, $analysis_time_include)){ continue; } #if this graph minutes is already included then , no need to include from now on
            
//            $graph_date_time_arr[$count]['timestamp'] = $graph_data_val[0];
//            $graph_date_time_arr[$count]['date_time'] = gmdate('d/m/Y H:i:s', trim($graph_data_val[0])/1000 );
//            $graph_date_time_arr[$count]['close'] = $graph_data_val[1];
            $graph_date_time_arr[$count] = $graph_data_val[1];
            
            $analysis_time_include[] = $graph_hour_n_minute;
            
            $count++;
        }
//        echo '<pre>'; print_r($analysis_time_include);
//        echo '<pre>'; print_r($graph_date_time_arr); exit;
        
//        $macd = $this->macdz($graph_date_time_arr);
//        $macd = $this->phpMacd($graph_date_time_arr);
//        $rsi = $this->phpRsi($graph_date_time_arr);
//        $stochastic_rsi  = $this->phpStochasticRsi($graph_date_time_arr);
        $stochastic_rsi  = $this->customStochasticRsi($graph_date_time_arr);
        
//        echo '<pre>'; print_r($macd);
//        echo '<pre>'; print_r($stochastic_rsi);
        
    }
    
    function macdz($data, $ema1 = 12, $ema2 = 26, $signal = 9) {

        $smoothing_constant_1 = 2 / ($ema1 + 1);
        $smoothing_constant_2 = 2 / ($ema2 + 1);
        $previous_EMA1 = null;
        $previous_EMA2 = null;

        $ema1_value = null;
        $ema2_value = null;

        $macd_array = array();

        //loop data
        foreach ($data as $key => $row) {

            //ema 1
            if ($key >= $ema1) {

                //first 
                if (!isset($previous_EMA1)) {
                    $sum = 0;
                    for ($i = $key - ($ema1 - 1); $i <= $key; $i++)
                        $sum += $data[$i]['close'];
                    //calc sma
                    $sma = $sum / $ema1;

                    //save
                    $previous_EMA1 = $sma;
                    $ema1_value = $sma;
                } else {
                    //ema formula
                    $ema = ($data[$key]['close'] - $previous_EMA1) * $smoothing_constant_1 + $previous_EMA1;

                    //save
                    $previous_EMA1 = $ema;
                    $ema1_value = $ema;
                }
            }

            //ema 2
            if ($key >= $ema2) {

                //first 
                if (!isset($previous_EMA2)) {
                    $sum = 0;
                    for ($i = $key - ($ema2 - 1); $i <= $key; $i++)
                        $sum += $data[$i]['close'];
                    //calc sma
                    $sma = $sum / $ema2;

                    //save
                    $previous_EMA2 = $sma;
                    $ema2_value = $sma;
                } else {
                    //ema formula
                    $ema = ($data[$key]['close'] - $previous_EMA2) * $smoothing_constant_2 + $previous_EMA2;

                    //save
                    $previous_EMA2 = $ema;
                    $ema2_value = $ema;
                }
            }

            //check if we have 2 values to calc MACD Line
            if (isset($ema1_value) && isset($ema2_value)) {

                $macd_line = $ema1_value - $ema2_value;

                //add to front
                array_unshift($macd_array, $macd_line);

                //pop back if too long
                if (count($macd_array) > $signal)
                    array_pop($macd_array);

                //save
                $data[$key]['val'] = $macd_line;
            }

            //have enough data to calc signal sma
            if (count($macd_array) == $signal) {

                //k moving average 
                $sum = array_reduce($macd_array, function($result, $item) {
                    $result += $item;
                    return $result;
                }, 0);

                $sma = $sum / $signal;

                //save
                $data[$key]['val2'] = $sma;
            }
        }
        return $data;
    }
    
    function phpMacd( $data ){
        echo 'phpMacd Input';
        echo '<pre>'; print_r($data);
        
        $macd_result = trader_macd( $data, 12, 26, 9); 
        
        echo 'macd_result';
        echo '<pre>'; print_r($macd_result);
        return $macd_result;
    }
    
    function phpRsi( $data ){
        
        echo 'phpRsi Input';
        echo '<pre>'; print_r($data);
        
        $rsi_result = trader_rsi( $data , 14);
        
        echo 'rsi_result';
        echo '<pre>'; print_r($rsi_result);
        
        return $rsi_result;
    }
    
    function customStochasticRsi( $close_price_arr, $period=14 ){
        
        $rsi_arr = $this->phpRsi($close_price_arr);
//        return;
        reset($rsi_arr); $first_rsi_key = key($rsi_arr);
        
        echo '$first_rsi_key : ' . $first_rsi_key . '<br/><br/>';
        
        foreach( $rsi_arr AS $rsi_key => $rsi_val ){
            
            $start_rsi_key = $rsi_key - ($period - 1);
                                   
            if( $start_rsi_key >= $first_rsi_key ){
                
                echo '<br/>';
                echo 'find min and max of RSI . $start_rsi_key' . $start_rsi_key . ' , end rsi_key : ' . $rsi_key . ' , $rsi_val : '.$rsi_val.' <br/>';
                
                $min=$rsi_arr[$start_rsi_key];
                $max=$rsi_arr[$rsi_key];
                
                for( $i= $start_rsi_key; $i<= $rsi_key; $i++   ){
                    
                    echo ' $i . ' . $i . ' <br/>';
                                                            
                    if( $min > $rsi_arr[$i] ) {

                        $min = $rsi_arr[$i];
                    }

                    if( $max < $rsi_arr[$i] ) {

                        $max = $rsi_arr[$i];
                    }              
                    
                }
                
                $StochRSI = ( ($rsi_val - $min) / ($max - $min) );
                echo ' min: ' . $min . ' , max: ' . $max  . ' , $rsi_val : '.$rsi_val.' , $StochRSI: '.$StochRSI.' <br/>';      
                
            }else{
                
                 echo '$start_rsi_key : ' . $start_rsi_key . ' , $rsi_key : ' . $rsi_key . ' <br/>';
            }
        }
    }
    
    function phpStochasticRsi( $data ){
       echo 'phpStochasticRsi Input';
        echo '<pre>'; print_r($data);
        
        $stochrsi = trader_stochrsi( $data, 14, 3, 3);
//        var_dump($stochrsi);
        echo 'stochrsi';
        echo '<pre>'; print_r($stochrsi);
        return $stochrsi;
    }
}
