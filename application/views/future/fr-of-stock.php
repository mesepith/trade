<?php
setlocale(LC_MONETARY,"en_IN.utf8");
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

#close_price_chart{
    display: block;
    margin: 0 auto;
 }
 
 .avg_total_data{
     border: 2px solid green;
 }
</style>
<div class="container">
    
    <h2><?php echo $other_info['company_symbol'] . ' - ' . ucfirst($live); ?></h2>
    <p>
        Underlying Stock: <?php echo $other_info['company_symbol'] . ' ' . $other_info['underlying_price']  . ' As on ' . $other_info['underlying_date_time'] ; ?> 
        <?php if( !empty($fr_data) && count($fr_data) > 0 && !empty($other_info['searching_underlying_date_to']) ){
        echo ' TO ' . date('d M Y', strtotime($other_info['searching_underlying_date_to']) );
        }?>
    </p>   
    <p>Industry : <?php echo $other_info['industry']; ?></p>
    <p>Volume Freeze Quantity : <?php echo number_format($other_info['volume_freeze_quantity']) ; ?></p>
    
    <form method="get" action="<?php echo base_url('future/stock-info/' .$live); ?>">
        
        
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Underlying Date:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($other_info['searching_underlying_date']) ? date('Y-m-d') : $other_info['searching_underlying_date']; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($other_info['searching_underlying_date_to']) ? date('Y-m-d') : $other_info['searching_underlying_date_to']; ?>"  onchange="changeStockDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>
        
        
        <div class="row">
            
            <div class="col-xl-2 col-12 mb-30">
                Select
            </div>
            
            <div class="col-xl-3 col-12 mb-30"> 
                
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Expiry Date : </span><span><?php echo date('d M Y', strtotime($other_info['searching_expiry_date'])); ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($other_info['expiry_dates'] AS $expiry_dates_value){?>
                      <a class="dropdown-item change_expiry_date" data-searching_underlying_date='<?php echo $other_info['searching_underlying_date']; ?>' data-searching_expiry_date='<?php echo $expiry_dates_value->expiry_date; ?>' href="javascript:void(0)">
                          <?php echo date('d M Y', strtotime($expiry_dates_value->expiry_date) ); ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            
            <?php if($live){ ?>
            
            <div class="col-xl-3 col-12 mb-30"> 
                
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Underlying Time : </span><span><?php echo $other_info['searching_underlying_time']; ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($other_info['underlying_time'] AS $underlying_time_value){?>
                      <a class="dropdown-item change_underlying_time" data-searching_underlying_date='<?php echo $other_info['searching_underlying_date']; ?>' data-searching_expiry_date='<?php echo $other_info['searching_expiry_date']; ?>' data-searching_underlying_time='<?php echo $underlying_time_value->underlying_time; ?>' href="javascript:void(0)">
                          <?php echo $underlying_time_value->underlying_time; ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            
            <input type='hidden' class='searching_underlying_time' name='sut' value='<?php echo $other_info['searching_underlying_time']; ?>'>
            
            <?php } ?>
            
        </div>
        
        <div class="row mb-30 mt-60">
            
            <div id="today_date" data-valz="<?php echo date('Y-m-d'); ?>"></div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_1_week= date('Y-m-d', strtotime("-1 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_1_week; ?>" name="date_period" <?php echo ($date_period===$last_1_week) ? 'checked' : ''; ?>>
                        Last 1 week
                    </label>
                </div>
            </div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_2_week= date('Y-m-d', strtotime("-2 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_2_week; ?>" name="date_period" <?php echo ($date_period===$last_2_week) ? 'checked' : ''; ?>>
                        Last 2 week
                    </label>
                </div>
            </div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_3_week= date('Y-m-d', strtotime("-3 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_3_week; ?>" name="date_period" <?php echo ($date_period===$last_3_week) ? 'checked' : ''; ?>>
                        Last 3 week
                    </label>
                </div>
            </div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_4_week= date('Y-m-d', strtotime("-4 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_4_week; ?>" name="date_period" <?php echo ($date_period===$last_4_week) ? 'checked' : ''; ?>>
                        Last 4 week
                    </label>
                </div>
            </div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_1_month= date('Y-m-d', strtotime("-1 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_1_month; ?>" name="date_period" <?php echo ($date_period===$last_1_month) ? 'checked' : ''; ?>>
                        Last 1 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_2_month= date('Y-m-d', strtotime("-2 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_2_month; ?>" name="date_period" <?php echo ($date_period===$last_2_month) ? 'checked' : ''; ?>>
                        Last 2 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_3_month= date('Y-m-d', strtotime("-3 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_3_month; ?>" name="date_period" <?php echo ($date_period===$last_3_month) ? 'checked' : ''; ?>>
                        Last 3 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_6_month= date('Y-m-d', strtotime("-6 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_6_month; ?>" name="date_period" <?php echo ($date_period===$last_6_month) ? 'checked' : ''; ?>>
                        Last 6 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_9_month= date('Y-m-d', strtotime("-9 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_9_month; ?>" name="date_period" <?php echo ($date_period===$last_9_month) ? 'checked' : ''; ?>>
                        Last 9 month
                    </label>
                </div>
            </div>
            <div class="col-xl-2 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_12_month= date('Y-m-d', strtotime("-12 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_12_month; ?>" name="date_period" <?php echo ($date_period===$last_12_month) ? 'checked' : ''; ?>>
                        Last 12 month
                    </label>
                </div>
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-xl-3 col-1 mb-30">
                <input type="checkbox" class="get_all_expiry_date" id="get_all_expiry_date_chkbox" name="get_all_expiry_date" value="<?php echo $other_info["get_all_expiry_date"]; ?>" <?php echo ($other_info["get_all_expiry_date"]=== 'yes') ? 'checked' : ''; ?>>
                <label for="get_all_expiry_date_chkbox"> Get All Expiry Data</label><br>            
            </div>
        </div>
        
        <div class="row mb-30">
            <div class="col-xl-3 col-1">
                <input type="checkbox" class="show_avg_total_data" id="show_avg_total_data_chkbox" name="show_avg_total_data" value="<?php echo $other_info["show_avg_total_data"]; ?>" <?php echo ($other_info["show_avg_total_data"]== 'yes') ? 'checked' : ''; ?>>
                <label for="show_avg_total_data_chkbox"> Show Only Average And Total Data</label><br>            
            </div>
        </div>
        
        <input type='hidden' class='company_id' name='company_id' value='<?php echo $other_info['company_id']; ?>'>
        <input type='hidden' class='company_symbol' name='company_symbol' value='<?php echo base64_url_encode($other_info['company_symbol']); ?>'>
        
        <input type='hidden' class='searching_underlying_date' name='sud' value='<?php echo $other_info['searching_underlying_date']; ?>'>
        <input type='hidden' class='searching_underlying_date_to' name='sud_to' value='<?php echo $other_info['searching_underlying_date_to']; ?>'>
        
        <input type='hidden' class='searching_expiry_date' name='sed' value='<?php echo $other_info['searching_expiry_date']; ?>'>        
        
        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
    </form>
    
    <div class="row mb-60">
    
        <a href="<?php echo base_url('future/rollover-log/' . $other_info['company_id'] . '/' . base64_url_encode($other_info['company_symbol']) ); ?>">
            Rollover Percent
        </a>
    </div>
 
    <!--<div class="table-responsive">-->
        <table class="table table-bordered p_c_data_table">
            <thead>
                <tr>
                    
                    <?php if( !empty($fr_data) && count($fr_data) > 0 ){ ?>                    
                    <th>Date</th>
                    <?php } ?>
                    <?php if( $other_info["get_all_expiry_date"] === 'yes' ){ ?>                    
                    <th>Expiry Date</th>
                    <?php } ?>
                    <th>Open Price</th>
                    <th>High Price</th>
                    <th>Low Price</th>
                    <th>Close Price</th>
                    <th>Prev Price</th>
                    <th>Last Price</th>
                    <th>No of contract Traded</th>
                    <th>Total Turnover (Lakhs) (Total Traded Value) (Premium Turnover)</th>
                    <th>Total Buy Quantity</th>
                    <th>Total Sell Quantity</th>                 
                    <th>VMAP</th>
                    <th>OI</th>
                    <th>Settlement Price</th>
                    <th>Daily Volatility</th>
                    <th>Annual Volatility</th>                    
                    <th>Money Flow</th>
                    <th>Market Wide Position Limits</th>
                    <th>Client Wise Position Limits</th>
                    <th>IV</th>
                </tr>

            </thead>
            <tbody>
                <?php 
                    $total_close = 0;
                    $total_no_of_contracts_traded = 0;
                    $total_turnover = 0;
                    $total_buy_quantity = 0;
                    $total_sell_quantity = 0;
                    $total_vmap = 0;
                    $total_oi = 0;
                    $total_change_in_oi = 0;
                    $total_daily_volatility = 0;
                    $total_annual_volatility = 0;
                    $total_money_flow = 0;
                    
                    foreach($fr_data AS $fr_data_key=>$fr_data_value){ 
                ?>
                <tr class="db_data">
                    <?php if( !empty($fr_data) && count($fr_data) > 0 ){ ?>                    
                    <td><?php echo date('d-M-Y', strtotime($fr_data_value->underlying_date)); ?></td>
                    <?php } ?>
                    <?php if( $other_info["get_all_expiry_date"] === 'yes' ){ ?>  
                    <td><?php echo date('d-M-Y', strtotime($fr_data_value->expiry_date)); ?></td>
                    <?php } ?>
                    <td><?php echo money_format("%n",$fr_data_value->open_price); ?></td>
                    <td><?php echo money_format("%n",$fr_data_value->high_price); ?></td>
                    <td><?php echo money_format("%n",$fr_data_value->low_price); ?></td>
                    <td>
                        <?php
                        $total_close = $total_close + $fr_data_value->close_price;
                        echo money_format("%n",$fr_data_value->close_price); 
                        ?>
                    </td>
                    <td><?php echo money_format("%n",$fr_data_value->prev_price); ?></td>
                    
                    <td>
                        <?php echo money_format("%n",$fr_data_value->last_price); ?>
                        
                        <?php if(!empty($fr_data_value->change)){?>
                        
                            <br/>
                            <span class="<?php echo ($fr_data_value->change>0) ? 'col-green' : 'col-red' ?>">
                                <?php echo $fr_data_value->change . " (".$fr_data_value->p_change."%) "; ?>
                            </span>
                            <br/>
                        
                        <?php } ?>
                    </td>
                    
                    <td><?php 
                        $total_no_of_contracts_traded = $total_no_of_contracts_traded + $fr_data_value->no_of_contracts_traded;
                        echo number_format($fr_data_value->no_of_contracts_traded); 
                    ?></td>                    
                    <td>
                        <?php 
                        $total_turnover = $total_turnover + $fr_data_value->total_turnover;
                        echo money_format("%n",$fr_data_value->total_turnover); 
                        ?>
                    </td>                                        
                    <td><?php 
                        $total_buy_quantity = $total_buy_quantity + $fr_data_value->total_buy_quantity;
                        echo number_format($fr_data_value->total_buy_quantity); 
                    ?></td>
                    <td><?php 
                        $total_sell_quantity = $total_sell_quantity + $fr_data_value->total_sell_quantity;
                        echo number_format($fr_data_value->total_sell_quantity); 
                    ?></td>
                                                                              
                    
                    <td>
                        <?php 
                        $total_vmap = $total_vmap + $fr_data_value->vmap;
                        echo money_format("%n",$fr_data_value->vmap); 
                        ?>
                    </td>
                    <td>
                        <?php 
                        
                        $total_oi = $total_oi + $fr_data_value->oi;
                        $total_change_in_oi = $total_change_in_oi + $fr_data_value->change_in_oi;
                        echo number_format($fr_data_value->oi); 
                        
                        if(!empty($fr_data_value->change_in_oi)){?>
                        
                            <br/>
                            <span class="<?php echo ($fr_data_value->change_in_oi>0) ? 'col-green' : 'col-red' ?>">
                                <?php echo number_format($fr_data_value->change_in_oi) . " (".$fr_data_value->p_change_in_oi."%) "; ?>
                            </span>
                            <br/>
                        
                        <?php } ?>
                    </td>
                    
                    <td><?php echo money_format("%n",$fr_data_value->settlement_price); ?></td>      
                    <td><?php 
                            $total_daily_volatility = $total_daily_volatility + $fr_data_value->daily_volatility;
                            echo number_format($fr_data_value->daily_volatility,2); 
                        ?>
                    </td>
                    <td>
                        <?php 
                            $total_annual_volatility = $total_annual_volatility + $fr_data_value->annual_volatility;
                            echo number_format($fr_data_value->annual_volatility,2); 
                        ?>
                    </td>
                    
                    
                    <?php
                        $money_flow = 0;
                        
                        if( $fr_data_key!=0){ 
                            
                            $money_flow_calc = $fr_data_value->change_in_oi * $fr_data_value->vmap;
                            
                            if( $fr_data_value->close_price > $fr_data[$fr_data_key-1]->close_price ) {
                                
                                $money_flow = abs($money_flow_calc);
                                
                            }else{
                                
                                $money_flow = -abs($money_flow_calc);
                            }
                            
                            $total_money_flow = $total_money_flow  + $money_flow;
                            
                            $fr_data[$fr_data_key]->money_flow = $money_flow;
                            
                        }
                    ?>
                    
                    <td class="<?php echo ($money_flow>0) ? 'col-green' : 'col-red' ?>" >
                        <?php echo number_format($money_flow, 2); ?>
                    </td>
                    
                    <td><?php echo number_format($fr_data_value->market_wide_position_limits); ?></td>
                    <td><?php echo number_format($fr_data_value->client_wise_position_limits); ?></td>                    
                    <td><?php echo number_format($fr_data_value->iv); ?></td>
                    
                </tr>
                <?php } ?>
                <tr class="avg_total_data">
                    <td>Average</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo money_format("%n", $total_close / count($fr_data)); ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($total_no_of_contracts_traded / count($fr_data)); ?></td>
                    <td><?php echo money_format("%n", $total_turnover / count($fr_data)); ?></td>
                    <td><?php echo number_format($total_buy_quantity / count($fr_data)); ?></td>
                    <td><?php echo number_format($total_sell_quantity / count($fr_data)); ?></td>
                    <td><?php echo money_format("%n", $total_vmap / count($fr_data)); ?></td>
                    <td>
                        <?php 
                        echo number_format($total_oi / count($fr_data)) . '<br/>(' .number_format( $total_change_in_oi/count($fr_data), 2 ) . ')'; 
                        ?>
                    </td>
                    <td></td>
                    <td><?php echo number_format($total_daily_volatility / count($fr_data) ,2); ?></td>
                    <td><?php echo number_format($total_annual_volatility / count($fr_data) ,2); ?></td>
                    <td><?php echo number_format($total_money_flow / count($fr_data) ,2); ?></td>
                    
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="avg_total_data">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($total_no_of_contracts_traded); ?></td>
                    <td><?php echo money_format("%n", $total_turnover); ?></td>
                    <td><?php echo number_format($total_buy_quantity); ?></td>
                    <td><?php echo number_format($total_sell_quantity); ?></td>
                    <td></td>
                    <td>
                        <?php 
                        echo number_format($total_oi); 
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    
                    <td><?php echo number_format($total_money_flow); ?></td>
                    
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    <!--</div>-->
</div>

<?php if( $other_info["get_all_expiry_date"] !=='yes' && count($fr_data) > 0 ){ ?>

<div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($fr_data) , ENT_QUOTES, 'UTF-8'); ?>' ></div>

<div class="row">
            
    <div class="col-xl-12 col-sm-12 col-12">

        <div class="chart_dsgn" id="close_price_chart" data-plot_data="close_price" data-plot_data_name="Close Price" data-colorz="green" data-full_screen="0"></div> 
    </div>

</div>

<div class="row">
            
    <div class="col-xl-6 col-sm-12 col-12">

         <div class="chart_dsgn" id="money_flow_chart" data-plot_data="money_flow" data-plot_data_name="Money Flow" data-colorz="blue" data-full_screen="0"></div>             

    </div>
    
    <div class="col-xl-6 col-sm-12 col-12">

        <div class="chart_dsgn" id="oi_chart" data-plot_data="oi" data-plot_data_name="OI" data-colorz="blue" data-full_screen="0"></div>             

    </div>
    
    <div class="col-xl-6 col-sm-12 col-12">

         <div class="chart_dsgn" id="no_of_contracts_traded_chart" data-plot_data="no_of_contracts_traded" data-plot_data_name="No of contract Traded" data-colorz="blue" data-full_screen="0"></div> 

    </div>
    <div class="col-xl-6 col-sm-12 col-12">

        <div class="chart_dsgn" id="total_turnover_chart" data-plot_data="total_turnover" data-plot_data_name="Total Turnover (Lakhs)" data-colorz="blue" data-full_screen="0"></div>             

    </div>
    <div class="col-xl-6 col-sm-12 col-12">

         <div class="chart_dsgn" id="traded_volume_chart" data-plot_data="traded_volume" data-plot_data_name="Traded Volum" data-colorz="blue" data-full_screen="0"></div> 

    </div>
    
    <div class="col-xl-6 col-sm-12 col-12">

        <div class="chart_dsgn" id="vmap_chart" data-plot_data="vmap" data-plot_data_name="VMAP" data-colorz="blue" data-full_screen="0"></div>             

    </div>
    <div class="col-xl-6 col-sm-12 col-12">

        <div class="chart_dsgn" id="total_buy_sell_quantity_chart" data-plot_data="total_buy_sell_quantity" data-plot_data_name="Total Buy Sell" data-colorz="green" data-full_screen="0"></div>             

    </div>
    
    <div class="col-xl-6 col-sm-12 col-12">

        <div class="chart_dsgn" id="daily_volatility_chart" data-plot_data="daily_volatility" data-plot_data_name="Daily Volatility" data-colorz="blue" data-full_screen="0"></div>             

    </div>
    <div class="col-xl-6 col-sm-12 col-12">

        <div class="chart_dsgn" id="annual_volatility_chart" data-plot_data="annual_volatility" data-plot_data_name="Annual Volatility" data-colorz="blue" data-full_screen="0"></div>             

    </div>

</div>

<?php } ?>