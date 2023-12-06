<?php 

$this->load->helper('function_helper');

?>
<style>
    @media (min-width: 1800px){
        div.container {
            max-width: 1800px;
        }
    }
    .mb-30{margin-bottom: 30px;}
    .mb-60{margin-bottom: 60px;}

    thead tr:nth-child(1) th, thead tr:nth-child(2) th{
        background: white;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    /*sticky header of table end*/
    
/*Date picker start*/
.htm-date-container {
    position: relative;
    float: left;
}

.htm-date {
    border: 1px solid #1cbaa5;
    padding: 5px 10px;
    height: 30px;
    width: 165px;
}

.open-date-button {
    position: absolute;
    top: 3px;
    left: 145px;
    width: 25px;
    height: 25px;
    background: #fff;
    pointer-events: none;
}

.open-date-button button {
    border: none;
    background: transparent;
}
/*Date picker end*/
.call_in_the_money_yes{background-color: yellow;}
/*table.p_c_data_table tr.p_c_data:last-child td{background-color: #fff;}*/
.put_in_the_money_yes{background-color: yellow;}
.strike_price_td{background-color: lightgrey; font-weight: bolder;}
.positive_no{color: green;}
.negative_no{color: red;}

.table td.chng_oi_td_class{
    position: relative;
    width: 50%;
    padding: 10px 80px 0px 13px;
}

.positive_no_w{
    width: 0; 
    height: 0; 
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    border-bottom: 15px solid green;
    position: absolute;
    margin-top: 5px;
    margin-left: 5px;
    right: 4px;
/*    right: 6px;
    bottom: 16px;*/
}
.negative_no_w{
    width: 0; 
    height: 0; 
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;  
    border-top: 15px solid #f00;
    position: absolute;
    margin-top: 5px;
    margin-left: 5px;
    right: 4px;
/*    right: 6px;
    bottom: 16px;*/
}
.col-green{
    color: green; 
 }
 .col-red{
    color: red; 
 }
 
.chart_dsgn{
  width:925px; 
  height:700px;
  /*margin-top:-34px !important;*/
 }
#underlying_price_chart{
/*  width:2100px; 
  height:1200px;
  margin-left: -12%;*/
display: block;
margin: 0 auto;
 }
</style>
<div class="container">
    
    <h2><?php echo $other_info['company_symbol']; ?></h2>
    <p>Underlying Stock: <?php echo $other_info['company_symbol'] . ' with Strike Price ' . $other_info['strike_price']  . ' As on ' . date('d M Y', strtotime($other_info['searching_underlying_date']) ) ; ?> IST</p>                                                                                      
    
    
    <!--<div class="table-responsive">-->
        <table class="table table-bordered p_c_data_table">
            <thead>
                <tr>
                    <th colspan="10">CALLS</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th colspan="10">PUTS</th>
                </tr>
                <tr>
                    <th></th>
                    <th>OI</th>
                    <th>Change In OI</th>
                    <th>Volume</th>
                    <th>Change In OI/Volume</th>
                    <th>IV</th>
                    <th>Money Flow</th>
                    <th>LTP</th>
                    <th>Net Change</th>
                    <th class="d-none d-lg-table-cell">Total Buy Quantity</th>
                    <th class="d-none d-lg-table-cell">Total Sell Quantity</th>
                    <th>Time</th>
                    <th>Underlying  Price</th>
                    <th class="d-none d-lg-table-cell">Total Sell Quantity</th>
                    <th class="d-none d-lg-table-cell">Total Buy Quantity</th> 
                    <th>Net Change</th>
                    <th>LTP</th>
                    <th>Money Flow</th>
                    <th>IV</th>
                    <th>Change In OI/Volume</th>
                    <th>Volume</th>
                    <th>Change In OI</th>
                    <th>OI</th>
                    <th></th>
                </tr>

            </thead>
            <tbody>
                <?php foreach($oc_data AS $oc_data_key=>$oc_data_value){                    
                        
                    $call_td_class = '';
                    $put_td_class = '';                        
                    
                ?>
                <tr class="p_c_data">
                    <td>
                    <td class="<?php echo $call_td_class; ?>">
                        <?php 
                        echo money_format('%!.0n',$oc_data_value->calls_oi);
                        
                            if( $oc_data_key!=0 ){
                            
                                $calls_oi_percnt = percentOfTwoNumber( $oc_data_value->calls_oi, $oc_data[$oc_data_key-1]->calls_oi );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($calls_oi_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $calls_oi_percnt . "%)";
                        
                        }?>
                        </span>
                    
                    <?php 
                    
                        $calls_chng_oi_color_class = '';
                        
                        if($oc_data_value->calls_chng_in_oi > 0.00){
                            
                            $calls_chng_oi_color_class = 'positive_no_w';
                            
                        }else if($oc_data_value->calls_chng_in_oi < 0.00){
                            
                            $calls_chng_oi_color_class = 'negative_no_w';
                        }
                    ?>
                    <td class="<?php echo $call_td_class; ?> chng_oi_td_class">
                        <span>
                            <?php 
                            
                            echo money_format('%!.0n',$oc_data_value->calls_chng_in_oi); 
                            if( $oc_data_key!=0 ){
                            
                                $calls_chng_in_oi_percnt = percentOfTwoNumber( $oc_data_value->calls_chng_in_oi, $oc_data[$oc_data_key-1]->calls_chng_in_oi );
                            ?>
                        
                            <br/>
                            <span class="<?php echo ($calls_chng_in_oi_percnt>0) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $calls_chng_in_oi_percnt . "%)";

                            }?>
                            </span>
                        
                        </span>
                        <span class="<?php echo $calls_chng_oi_color_class; ?>"></span>
                    </td>
                    <td class="<?php echo $call_td_class; ?>">
                        <?php 
                        
                        echo money_format('%!.0n',$oc_data_value->calls_volume); 
                        
                        if( $oc_data_key!=0 ){
                            
                                $calls_volume_percnt = percentOfTwoNumber( $oc_data_value->calls_volume, $oc_data[$oc_data_key-1]->calls_volume );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($calls_volume_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $calls_volume_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    
                    <td class="<?php echo $call_td_class; ?>">
                        
                        <?php
                            
                            $oc_data[$oc_data_key]->call_chng_in_oi_by_vol = 0;
                        
                            try{
                                
                                if( !empty($oc_data_value->calls_volume)){
                                    
                                    $call_chng_in_oi_by_vol = round ( ( $oc_data_value->calls_chng_in_oi/$oc_data_value->calls_volume ) , 2);
                                    
                                    echo $call_chng_in_oi_by_vol;
                                    
                                    $oc_data[$oc_data_key]->call_chng_in_oi_by_vol = $call_chng_in_oi_by_vol;
                                    
                                    if( !empty($prev_call_chng_in_oi_by_vol)){
                                        
                                        $call_chng_in_oi_by_vol_percnt = percentOfTwoNumber( $call_chng_in_oi_by_vol, $prev_call_chng_in_oi_by_vol );
                                    
                                    
                                    ?>
                        
                                    <br/>
                                    <span class="<?php echo ($call_chng_in_oi_by_vol_percnt>0) ? 'col-green' : 'col-red' ?>">

                                    <?php

                                        echo "(" . $call_chng_in_oi_by_vol_percnt . "%)";

                                    }?>
                                    </span>
                                    
                                    <?php
                                    
                                    $prev_call_chng_in_oi_by_vol = $call_chng_in_oi_by_vol;
                                    
                                }else{
                                    echo 'NA';
                                }
                                                                

                            }catch(Exception $e) {
                                echo 'NA';
                            }                        
                        ?>
                    
                    </td>
                    
                    <td class="<?php echo $call_td_class; ?>">
                        <?php 
                        
                        echo $oc_data_value->calls_iv; 
                                                
                        if( $oc_data_key!=0 ){
                            
                                $calls_iv_percnt = percentOfTwoNumber( $oc_data_value->calls_iv, $oc_data[$oc_data_key-1]->calls_iv );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($calls_iv_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $calls_iv_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    
                    <?php
                        $money_flow_calls = 0;
                        
                        if( $oc_data_key !=0){ 
                            
                            $calc_avg_ltp_calls = ($oc_data_value->calls_ltp + $oc_data[$oc_data_key-1]->calls_ltp)/2;
                            
                            $money_flow_calc_calls = $oc_data_value->calls_chng_in_oi * $calc_avg_ltp_calls;
                            
                            if( $oc_data_value->calls_ltp > $oc_data[$oc_data_key-1]->calls_ltp ) {
                                
                                $money_flow_calls = abs($money_flow_calc_calls);
                                
                            }else{
                                
                                $money_flow_calls = -abs($money_flow_calc_calls);
                            }
                                                        
                            $oc_data[$oc_data_key]->money_flow_calls = $money_flow_calls;
                        }
                        
                    ?>
                    
                    <td class="<?php echo ($money_flow_calls>0) ? 'col-green' : 'col-red' ?>" >
                        <?php echo number_format($money_flow_calls, 2); ?>
                    </td>
                    
                    <td class="<?php echo $call_td_class; ?>">
                        <?php 
                        echo $oc_data_value->calls_ltp;
                                                
                        if( $oc_data_key!=0 ){
                            
                                $calls_ltp_percnt = percentOfTwoNumber( $oc_data_value->calls_ltp, $oc_data[$oc_data_key-1]->calls_ltp );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($calls_ltp_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $calls_ltp_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    <?php 
                    
                        $calls_net_chng_color_class = '';
                        
                        if($oc_data_value->calls_net_chng > 0.00){
                            
                            $calls_net_chng_color_class = 'positive_no';
                            
                        }else if($oc_data_value->calls_net_chng < 0.00){
                            
                            $calls_net_chng_color_class = 'negative_no';
                        }
                    ?>
                    <td class="<?php echo $call_td_class . ' ' . $calls_net_chng_color_class; ?>">
                        <?php 
                            echo $oc_data_value->calls_net_chng;                         
                            
                            if( $oc_data_key!=0 ){
                            
                                $calls_net_chng_percnt = percentOfTwoNumber( $oc_data_value->calls_net_chng, $oc_data[$oc_data_key-1]->calls_net_chng );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($calls_net_chng_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $calls_net_chng_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td class="d-none d-lg-table-cell <?php echo $call_td_class; ?>">
                        <?php 
                        
                        echo money_format('%!.0n',$oc_data_value->calls_total_buy_quantity); 
                        
                        if( $oc_data_key!=0 ){
                            
                                $calls_tbq_percnt = percentOfTwoNumber( $oc_data_value->calls_total_buy_quantity, $oc_data[$oc_data_key-1]->calls_total_buy_quantity );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($calls_tbq_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $calls_tbq_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td class="d-none d-lg-table-cell <?php echo $call_td_class; ?>">
                        <?php 
                            echo money_format('%!.0n',$oc_data_value->calls_total_sell_quantity); 
                        
                        if( $oc_data_key!=0 ){
                            
                            $calls_tsq_percnt = percentOfTwoNumber( $oc_data_value->calls_total_sell_quantity, $oc_data[$oc_data_key-1]->calls_total_sell_quantity );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($calls_tsq_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $calls_tsq_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td class="strike_price_td">
                        
                        <?php 
                            if( !empty($live) && $live === 'live' ){
                                
                                echo $oc_data_value->underlying_time; 
                            }else{
                                
                                echo date('d M Y', strtotime($oc_data_value->underlying_date) ); 
                                
                            }
                        ?>
                    </td>
                    <td class="strike_price_td">
                        <?php echo $oc_data_value->underlying_price; ?>
                    </td>
                    <td class="d-none d-lg-table-cell <?php echo $put_td_class; ?>">
                        <?php 
                        echo money_format('%!.0n',$oc_data_value->puts_total_sell_quantity); 
                        
                        if( $oc_data_key!=0 ){
                            
                            $puts_tsq_percnt = percentOfTwoNumber( $oc_data_value->puts_total_sell_quantity, $oc_data[$oc_data_key-1]->puts_total_sell_quantity );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($puts_tsq_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $puts_tsq_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td class="d-none d-lg-table-cell <?php echo $put_td_class; ?>">
                        <?php 
                        echo money_format('%!.0n',$oc_data_value->puts_total_buy_quantity);
                        
                        if( $oc_data_key!=0 ){
                            
                            $puts_tbq_percnt = percentOfTwoNumber( $oc_data_value->puts_total_buy_quantity, $oc_data[$oc_data_key-1]->puts_total_buy_quantity );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($puts_tbq_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $puts_tbq_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <?php 
                    
                        $puts_net_chng_color_class = '';
                        
                        if($oc_data_value->puts_net_chng > 0.00){
                            
                            $puts_net_chng_color_class = 'positive_no';
                            
                        }else if($oc_data_value->puts_net_chng < 0.00){
                            
                            $puts_net_chng_color_class = 'negative_no';
                        }
                    ?>
                    <td class="<?php echo $put_td_class . ' ' . $puts_net_chng_color_class; ?>">
                        <?php 
                        
                        echo $oc_data_value->puts_net_chng; 
                        
                        if( $oc_data_key!=0 ){
                            
                            $puts_net_chng_percnt = percentOfTwoNumber( $oc_data_value->puts_net_chng, $oc_data[$oc_data_key-1]->puts_net_chng );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($puts_net_chng_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $puts_net_chng_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td class="<?php echo $put_td_class; ?>">
                        <?php 
                        echo $oc_data_value->puts_ltp; 
                        
                        if( $oc_data_key!=0 ){
                            
                            $puts_ltp_percnt = percentOfTwoNumber( $oc_data_value->puts_ltp, $oc_data[$oc_data_key-1]->puts_ltp );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($puts_ltp_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $puts_ltp_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    
                    <?php
                        $money_flow_puts = 0;
                        
                        if( $oc_data_key !=0){ 
                            
                            $calc_avg_ltp_puts = ($oc_data_value->puts_ltp + $oc_data[$oc_data_key-1]->puts_ltp)/2;
                            
                            $money_flow_calc_puts = $oc_data_value->puts_chng_in_oi * $calc_avg_ltp_puts;
                            
                            if( $oc_data_value->puts_ltp > $oc_data[$oc_data_key-1]->puts_ltp ) {
                                
                                $money_flow_puts = abs($money_flow_calc_puts);
                                
                            }else{
                                
                                $money_flow_puts = -abs($money_flow_calc_puts);
                            }
                            
                            $oc_data[$oc_data_key]->money_flow_puts = $money_flow_puts;
                            
                        }
                        
                    ?>
                    
                    <td class="<?php echo ($money_flow_puts>0) ? 'col-green' : 'col-red' ?>" >
                        <?php echo number_format($money_flow_puts, 2); ?>
                    </td>
                    
                    <td class="<?php echo $put_td_class; ?>">
                        <?php 
                        
                        echo $oc_data_value->puts_iv; 
                                                
                        if( $oc_data_key!=0 ){
                            
                            $puts_iv_percnt = percentOfTwoNumber( $oc_data_value->puts_iv, $oc_data[$oc_data_key-1]->puts_iv );
                        ?>
                        
                        <br/>
                        
                        <span class="<?php echo ($puts_iv_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $puts_iv_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    
                    <td class="<?php echo $put_td_class; ?>">
                    
                        <?php
                        
                            $oc_data[$oc_data_key]->put_chng_in_oi_by_vol = 0;
                                    
                            try{
                                
                                if( !empty($oc_data_value->puts_volume)){
                                
                                    $put_chng_in_oi_by_vol = round( ( $oc_data_value->puts_chng_in_oi / $oc_data_value->puts_volume )  , 2 );
                                    
                                    echo $put_chng_in_oi_by_vol;
                                    
                                    $oc_data[$oc_data_key]->put_chng_in_oi_by_vol = $put_chng_in_oi_by_vol;
                                    
                                    if( !empty($prev_put_chng_in_oi_by_vol)){
        
                                        $put_chng_in_oi_by_vol_percnt = percentOfTwoNumber( $put_chng_in_oi_by_vol, $prev_put_chng_in_oi_by_vol );

                                    ?>

                                    <br/>
                                    <span class="<?php echo ($put_chng_in_oi_by_vol_percnt>0) ? 'col-green' : 'col-red' ?>">

                                    <?php

                                        echo "(" . $put_chng_in_oi_by_vol_percnt . "%)";

                                    }?>
                                    </span>

                                    <?php
                                    
                                    $prev_put_chng_in_oi_by_vol = $put_chng_in_oi_by_vol;
                                    
                                }else{
                                    
                                    echo 'NA';
                                }
                                                                

                            }catch(Exception $e) {
                                
                                echo 'NA';
                            }                        
                        ?>
                        
                    </td>
                    
                    <td class="<?php echo $put_td_class; ?>">
                        <?php 
                            
                        echo money_format('%!.0n',$oc_data_value->puts_volume); 
                                                
                        if( $oc_data_key!=0 ){
                            
                            $puts_volume_percnt = percentOfTwoNumber( $oc_data_value->puts_volume, $oc_data[$oc_data_key-1]->puts_volume );
                        ?>
                        
                        <br/>
                        
                        <span class="<?php echo ($puts_volume_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $puts_volume_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <?php 
                    
                        $puts_chng_oi_color_class = '';
                        
                        if($oc_data_value->puts_chng_in_oi > 0){
                            
                            $puts_chng_oi_color_class = 'positive_no_w';
                            
                        }else if($oc_data_value->puts_chng_in_oi < 0){
                            
                            $puts_chng_oi_color_class = 'negative_no_w';
                        }
                    ?>
                    <td class="<?php echo $put_td_class; ?> chng_oi_td_class">
                        <span>
                            <?php 
                            
                            echo money_format('%!.0n',$oc_data_value->puts_chng_in_oi);                             
                                                   
                            if( $oc_data_key!=0 ){

                                $puts_chng_in_oi_percnt = percentOfTwoNumber( $oc_data_value->puts_chng_in_oi, $oc_data[$oc_data_key-1]->puts_chng_in_oi );
                            ?>

                            <br/>

                            <span class="<?php echo ($puts_chng_in_oi_percnt>0) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $puts_chng_in_oi_percnt . "%)";

                            }?>
                            </span>
                            
                        </span>
                        <span class="<?php echo $puts_chng_oi_color_class; ?>"></span>
                    </td>
                    <td class="<?php echo $put_td_class; ?>">
                        <?php
                        
                        echo money_format('%!.0n',$oc_data_value->puts_oi); 
                                               
                        if( $oc_data_key!=0 ){

                            $puts_oi_percnt = percentOfTwoNumber( $oc_data_value->puts_oi, $oc_data[$oc_data_key-1]->puts_oi );
                        ?>

                        <br/>

                        <span class="<?php echo ($puts_oi_percnt>0) ? 'col-green' : 'col-red' ?>">

                        <?php

                            echo "(" . $puts_oi_percnt . "%)";

                        }?>
                        </span>
                    </td>
                    <td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    
    <!-- Line Chart Start-->
    <div id="chart_data" data-all='<?php echo json_encode($oc_data); ?>' ></div>
    <div id="market_running" data-val='<?php echo $live; ?>' ></div>
      
    <div class="row">
            
        <div class="col-xl-12 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="underlying_price_chart" data-plot_data="underlying_price" data-plot_data_name="Underlying Price" data-colorz="green" data-full_screen="0"></div> 
        </div>
        
    </div>
    
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="money_flow_calls_chart" data-plot_data="money_flow_calls" data-plot_data_name="Money Flow Calls" data-colorz="red" data-full_screen="0"></div>             
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
             <div class="chart_dsgn" id="money_flow_puts_chart" data-plot_data="money_flow_puts" data-plot_data_name="Money Flow Puts" data-colorz="blue" data-full_screen="0"></div> 
            
        </div>
        
    </div>
    
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="calls_ltp_chart" data-plot_data="calls_ltp" data-plot_data_name="Calls LTP" data-colorz="red" data-full_screen="0"></div>             
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
             <div class="chart_dsgn" id="puts_ltp_chart" data-plot_data="puts_ltp" data-plot_data_name="Puts LTP" data-colorz="blue" data-full_screen="0"></div> 
            
        </div>
        
    </div>
    
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="calls_iv_chart" data-plot_data="calls_iv" data-plot_data_name="Calls IV" data-colorz="red" data-full_screen="0"></div>
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="puts_iv_chart" data-plot_data="puts_iv" data-plot_data_name="Puts IV" data-colorz="blue" data-full_screen="0"></div>
            
        </div>
        
    </div>
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="calls_oi_chart" data-plot_data="calls_oi" data-plot_data_name="Calls OI" data-colorz="red" data-full_screen="0"></div> 
        </div>
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="puts_oi_chart" data-plot_data="puts_oi" data-plot_data_name="Puts OI" data-colorz="blue" data-full_screen="0"></div> 
        </div>
        
    </div>
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="calls_volume_chart" data-plot_data="calls_volume" data-plot_data_name="Calls Volume" data-colorz="red" data-full_screen="0"></div> 
        </div>
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="puts_volume_chart" data-plot_data="puts_volume" data-plot_data_name="Puts Volume" data-colorz="blue" data-full_screen="0"></div> 
        </div>
        
    </div>
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="call_chng_in_oi_by_vol_chart" data-plot_data="call_chng_in_oi_by_vol" data-plot_data_name="Calls Change In OI / Volume" data-colorz="red" data-full_screen="0"></div> 
        </div>
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="put_chng_in_oi_by_vol_chart" data-plot_data="put_chng_in_oi_by_vol" data-plot_data_name="Puts Change In OI / Volume" data-colorz="blue" data-full_screen="0"></div> 
        </div>
        
    </div>
    
    <!-- Line Chart End-->
    
    
</div>
