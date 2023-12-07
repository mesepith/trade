<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
// include_once (dirname(__FILE__) . "/Fetch_Nse_Cookies.php");

class Nse_Contr extends MX_Controller {

    function marketStatus(){
        
        $System_Notification_contr = new System_Notification_Controller();
        
        $url = 'https://www.nseindia.com/api/marketStatus';
        
        $referer = 'https://www.nseindia.com/';
        
        $market_status_arr = $this->curlNse($url,$referer);
        
//        echo '<pre>';
//        print_r($market_status_arr); 
        
        if(empty($market_status_arr)){ $System_Notification_contr->marketStatusNotRecieved(); return; }
        
        foreach($market_status_arr['marketState'] AS $market_status_value){
            
            $tradeDate = date('Y-m-d', strtotime($market_status_value['tradeDate']) );
            
            if( $market_status_value['market'] === "Capital Market" && date('Y-m-d')) {
                
                if( $market_status_value['marketStatus'] === "Open" ){
                    
                    return 'open';
                    
                }else if( $market_status_value['marketStatus'] === "Closed" && $market_status_value['marketStatusMessage'] === "Normal Market has Closed" ){
                    
                    return 'closed';
                }
            }else{
                
                return false;
            }
           
            
        }
        
    }
    
    function curlNse($url, $referer){        
        // echo "<br/> <br/>";
        // echo 'url in curlNse:: '. $url;
        // echo "<br/> <br/>";
        return $this->curlNseShareholding( $url, $referer );
        
        $part1= "'".$url."' ";
        $part2= "'authority: www.nseindia.com' ";
        $part3= "'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/79.0.3945.79 Chrome/79.0.3945.79 Safari/537.36' ";
        $part4= "'accept: */*' ";
        $part5= "'sec-fetch-site: same-origin' ";
        $part6= "'sec-fetch-mode: cors' ";
        $part7= "'referer: ".$referer."' ";
        $part8= "'accept-encoding: gzip, deflate, br' ";
        $part9= "'accept-language: en-GB,en-US;q=0.9,en;q=0.8' ";
        
        $cmd = "curl " . $part1 . "-H " . $part2 . "-H " . $part3 . "-H " . $part4 . "-H " . $part5 . "-H " . $part6 . "-H " . $part7 . "-H " . $part8 . "-H " . $part9 . " --connect-timeout 10 --max-time 30 --compressed";                
        
//        echo $cmd;
        
//        echo '<br/><br/>';
        
        $output = json_decode(exec($cmd) , true);
        
//        echo '<pre>';
//        print_r($output); 
        
        return $output;
        
    }
    
    
    
    function checkMarketIsOpenedToday(){
        
        $url = 'https://www.nseindia.com/api/quote-equity?symbol=' . urlencode(LAST_SERIAL_FAMOUS_STOCK);
        
        $referer = 'https://www.nseindia.com/get-quotes/equity?symbol=' . urlencode(LAST_SERIAL_FAMOUS_STOCK);
        
        $stk_data  = $this->curlNseShareholding($url, $referer);
        
    //    echo '<pre>';
    //    print_r($stk_data);
        
//        echo 'open price : ' . $stk_data['priceInfo']['open'];
//        echo "<br/> <br/>";
//        echo 'lastUpdateTime : ' . date('Y-m-d', strtotime($stk_data['metadata']['lastUpdateTime']));
//        echo "<br/> <br/>";
        
        /* If open price is there and updated date is equal to today date then market is opening */
        if( !empty($stk_data) && !empty($stk_data['priceInfo']['open']) && date('Y-m-d') === date('Y-m-d', strtotime($stk_data['metadata']['lastUpdateTime'])) ){
            
            return trim($stk_data['priceInfo']['close']);
            
        }else{
            
            return 'no';
        }
        
    }
    
    function curlNseShareholding( $url, $referer  ){                

        set_time_limit(10); //in seconds

        // for($i=0; $i<500000000; $i++){}
    //    $url = "https://www.nseindia.com/api/quote-equity?symbol=GAIL";        
    //    $referer = "https://www.nseindia.com/get-quotes/equity?symbol=GAIL";
       
        $this->load->model('fetch_nse_cookies_model');

        $cookie['nseappid'] = $this->fetch_nse_cookies_model->getActiveCookieByType('nseappid');
        $cookie['nsit'] = $this->fetch_nse_cookies_model->getActiveCookieByType('nsit');

        // $url = htmlentities($url);
        // $referer = htmlentities($referer);
        
        //$cmd = "curl $url -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:80.0) Gecko/20100101 Firefox/80.0' -H 'Accept: */*' -H 'Accept-Language: en-US,en;q=0.5' --compressed -H 'Connection: keep-alive' -H 'Referer: $referer' -H 'Cookie: nsit=".$cookie['nsit'].";  nseappid=".$cookie['nseappid']."' -H 'TE: Trailers'";
        //$cmd = "curl '{$url}' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:80.0) Gecko/20100101 Firefox/80.0' -H 'Accept: */*' -H 'Accept-Language: en-US,en;q=0.5' --compressed -H 'Connection: keep-alive' -H 'Referer: {$referer}' -H 'Cookie: nsit={$cookie['nsit']};  nseappid={$cookie['nseappid']}' -H 'TE: Trailers'";

        $url = escapeshellcmd($url);
        $referer = escapeshellcmd($referer);
        $cmd = "curl {$url} -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:80.0) Gecko/20100101 Firefox/80.0' -H 'Accept: */*' -H 'Accept-Language: en-US,en;q=0.5' --compressed -H 'Connection: keep-alive' -H 'Referer: {$referer}' -H 'Cookie: nsit={$cookie['nsit']};  nseappid={$cookie['nseappid']}' -H 'TE: Trailers'";

    //    echo $cmd;
    //    echo "<br/> <br/>";
        
    //    echo '<br/><br/> exec cmd <br/>';
    //    echo exec($cmd);
    //    echo '<br/><br/>';
        // exit;
        
        $output = json_decode(exec($cmd) , true);
        
//        echo '<pre>';
//        print_r($output); 
        
        return $output;
    }
    function curlNseShareholdingOLD( $url, $referer ){                
        
        $part1= "'".$url."' ";
        $part2= "'pragma: no-cache' ";
        $part3= "'accept-encoding: gzip, deflate, br' ";
        $part4= "'accept-language: en-US,en;q=0.9' ";
        $part5= "'user-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36' ";
        $part6= "'accept: */*' ";
        $part7= "'cache-control: no-cache' ";
        $part8= "'authority: www.nseindia.com' ";
        $part9= "'referer: ".$referer."' ";
        
        $cmd = "curl " . $part1 . "-H " . $part2 . "-H " . $part3 . "-H " . $part4 . "-H " . $part5 . "-H " . $part6 . "-H " . $part7 . "-H " . $part8 . "-H " . $part9 . " --connect-timeout 10 --max-time 30 --compressed";                
        
        $output = json_decode(exec($cmd) , true);
        
//        echo '<pre>';
//        print_r($output); 
        
        return $output;
    }
    
    function curlNseOld($url, $referer){        
        
        $part1= "'".$url."' ";
        $part2= "'Connection: keep-alive' ";
        $part3= "'Accept: application/json, text/javascript, */*; q=0.01' ";
        $part4= "'X-Requested-With: XMLHttpRequest' ";
        $part5= "'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/79.0.3945.79 Chrome/79.0.3945.79 Safari/537.36' ";
        $part6= "'Sec-Fetch-Site: same-origin' ";
        $part7= "'Sec-Fetch-Mode: cors' ";
        $part8= "'referer: ".$referer."' ";
        
        $part9= "'Accept-Encoding: gzip, deflate, br' ";
        $part10= "'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8' ";
        
        $cmd = "curl " . $part1 . "-H " . $part2 . "-H " . $part3 . "-H " . $part4 . "-H " . $part5 . "-H " . $part6 . "-H " . $part7 . "-H " . $part8 . "-H " . $part9 . "-H " . $part10 . " --connect-timeout 10 --max-time 30 --compressed";                
        
//        echo $cmd;
        
//        echo '<br/><br/>';
        
        $output = json_decode(exec($cmd) , true);
        
//        echo '<pre>';
//        print_r($output); 
        
        return $output;
        
    }
    
    function curlNseWithCookie($url, $referer, $cookies_arr){        
        
        $sec_ch_ua = '"Google Chrome";v="89", "Chromium";v="89", ";Not A Brand";v="99"';
        
        $part1= "'".$url."' ";
        $part2= "'authority: www.nseindia.com' ";
        $part3= "'sec-ch-ua: ".$sec_ch_ua."' ";
        $part4= "'sec-ch-ua-mobile: ?0' ";
        $part5= "'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114' ";      
        $part6= "'accept: */*' ";       
        $part7= "'sec-fetch-site: same-origin' ";        
        $part8= "'sec-fetch-mode: cors' ";
        $part9= "'sec-fetch-dest: empty' ";        
        $part10= "'referer: ".$referer."' ";
        $part11= "'accept-language: en-GB,en-US;q=0.9,en;q=0.8' ";
        $part12= "'cookie: ". $cookies_arr . " ' ";
        
        $cmd = "curl " . $part1 . "-H " . $part2 . "-H " . $part3 . "-H " . $part4 . "-H " . $part5 . "-H " . $part6 . "-H " . $part7 . "-H " . $part8 . "-H " . $part9. "-H " . $part10. "-H " . $part11. "-H " . $part12 . " --connect-timeout 10 --max-time 30 --compressed";                
//        echo $cmd;
//        echo exec($cmd); 
//        exit;
        
        $output = json_decode(exec($cmd) , true);
        
//        echo '<pre>';
//        print_r($output); 
        
        return $output;
        
    }
}
