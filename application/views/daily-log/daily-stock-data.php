<?php 

$this->load->helper('function_helper');
setlocale(LC_MONETARY,"en_IN.utf8");

?>
<style>
    @media (min-width: 1800px){
        div.container {
            max-width: 1800px;
        }
    }
    /* Fixed Table Header Start*/
    thead tr:nth-child(1) th{
        background: white;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    /* Fixed Table Header End*/
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
    .mb-60{margin-bottom: 60px;}
    .mb-30{margin-bottom: 30px;}
    .mb-20{margin-bottom: 20px;}
    .mb-10{margin-bottom: 10px;}
    .mt-60{margin-top: 60px;}
    .mt-20{margin-top: 20px;}
    
    .green-up-arr{
        color: green;
        font-size: 25px;
    }
    .red-down-arr{
        color: red;
        font-size: 25px;
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

#close_price_chart{
    display: block;
    margin: 0 auto;
 }
</style>

<div class="container">
    <?php if( !empty($stock_detail) && count($stock_detail) > 0 ){ ?>
    <?php if(!empty($stock_date_to)){?>
    <h2><?php echo 'Analysis of <b>' . $company_name . '</b> (' . $company_symbol . ') from ' . date('d-M-Y', strtotime($stock_date)) . ' to ' . date('d-M-Y', strtotime($stock_date_to)); ?></h2>
    <?php }else{ ?>
    <h2><?php echo 'Analysis of <b>' . $company_name . '</b> (' . $company_symbol . ') on ' . date('d-M-Y', strtotime($stock_date)); ?></h2>
    <?php } ?>
    <?php } ?>
    <?php if(!empty($no_data_for_manual_date_msg)){ ?> 
        <div class="mt-20">
            <div class="alert alert-danger">
                <strong><?php echo $no_data_for_manual_date_msg; ?></strong> 
            </div>
        </div>
    
    <?php } ?>
    
    <?php if(!empty($sector)){?>
    
    <div class="mb-60">
        <span>
            <b>Sector:</b>
        </span>
        <span>
            <?php echo $sector; ?>
        </span>
    </div>
    
    <?php } ?>
    
    <form method="get" action="<?php echo base_url('daily-log/'); ?>">
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($stock_date) ? date('Y-m-d') : $stock_date; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($stock_date_to) ? date('Y-m-d') : $stock_date_to; ?>"  onchange="changeStockDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

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

        <input type='hidden' class='company_id' name='company_id' value='<?php echo $company_id; ?>'>
        <input type='hidden' class='company_symbol' name='company_symbol' value='<?php echo base64_url_encode($company_symbol); ?>'>
        
        <input type="hidden" class="stock_date" name="stock_date" value="<?php echo empty($stock_date) ? date('Y-m-d') :$stock_date; ?>">
        <input type="hidden" class="stock_date_to" name="stock_date_to" value="<?php echo empty($stock_date_to) ? date('Y-m-d') :$stock_date_to; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
        
        
        <div class="mb-20">
            <a href="<?php echo base_url() . 'stock/cluster-return/' . $company_id . '/' . base64_url_encode($company_symbol); ?>">Quarterly Monthly Weekly Analysis</a>
        </div>
        
        <div class="mb-20">
            <a href="<?php echo base_url() . 'stock/average-by-days/' . $company_id . '/' . base64_url_encode($company_symbol); ?>"> 3, 5, 8, 14, 20 Days Average</a>
        </div>
        
        <div class="mb-20">
            <a href="<?php echo base_url() . 'shareholding/distrubution/' . $company_id . '/' . base64_url_encode($company_symbol); ?>">Share Distribution</a>
        </div>
        <div class="mb-20">
            <a href="<?php echo base_url() . 'share-corporate/insider-trading/' . $company_id . '/' . base64_url_encode($company_symbol) . '/all'; ?>"> Insider Trading</a>
        </div>
        <div class="mb-20">
            <a href="<?php echo base_url() . 'share-corporate/pledged-data/' . $company_id . '/' . base64_url_encode($company_symbol) . '/all'; ?>"> Pledge Data</a>
        </div>
        <div class="mb-20">
            <a href="<?php echo base_url() . 'bulk-block-deal/' . $company_id . '/' . base64_url_encode($company_symbol) . '/all'; ?>"> Bulk Block Deal</a>
        </div>
        <div class="mb-60">
            <a href="<?php echo base_url() . 'whole-day-data/?company_id='.$company_id.'&company_symbol='.base64_url_encode($company_symbol).'&stock_date='.$stock_date_to; ?>"> Intraday Data</a>
        </div>
        
        <div class="row mb-20">
            <div class="col-xl-12 col-1">
                <input type="checkbox" class="show_avg_total_data" id="show_avg_total_data_chkbox" name="show_avg_total_data" value="<?php echo $show_avg_total_data; ?>" <?php echo ($show_avg_total_data== 'yes') ? 'checked' : ''; ?>>
                <label for="show_avg_total_data_chkbox"> Show Only Average And Total Net Value</label><br>            
            </div>
        </div>
        
    </form>
    
    <?php if( !empty($stock_detail) && count($stock_detail) > 0 ){ ?>
    
    <p>Analysis day wise:</p>            
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Open Price</th>
                <th>Last Price</th>
                <th>Close Price</th>
                <th>VWAP</th>
                <th>High Price</th>
                <th>Low Price</th>
                <th>Total Traded Volume</th>
                <th>Delivery Quantity</th>
                <th>Delivery to Traded Quantity</th>
                <th>PE</th>
                <th>Sector PE</th>
                <th>Total Traded Value</th>
                <th>No of Trades</th>
                <th>Volume / No of Trades</th>
                <th>Money Flow</th>
            </tr>
        </thead>
        <tbody>

            <?php 
            
                $total_close = 0;
                $total_vwap = 0;
                $total_traded_volume = 0;
                $total_delivery_quantity = 0;
                $total_traded_value = 0;
                $total_no_of_trades = 0;
                $total_money_flow = 0;
                foreach ($stock_detail AS $stock_detail_key=>$stock_detail_value) { ?>

                <tr class="db_data">
                    <td><?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?></td>
                    <td><?php echo (empty($stock_detail_value->open_price) ? 'NA' : $stock_detail_value->open_price); ?></td>
                    <td><?php echo (empty($stock_detail_value->last_price) ? 'NA' : $stock_detail_value->last_price); ?></td>
                    <td>
                        <?php 
                            $total_close = $total_close + $stock_detail_value->close_price;
                            echo (empty($stock_detail_value->close_price) ? 'NA' : $stock_detail_value->close_price); 
                        ?>
                        
                        <?php if(!empty($stock_detail_value->price_change)){?>
                        
                            <br/>
                            <span class="<?php echo ($stock_detail_value->price_change>0) ? 'col-green' : 'col-red' ?>">
                                <?php echo $stock_detail_value->price_change . " (".$stock_detail_value->price_change_in_p."%) "; ?>
                            </span>
                            <br/>
                        
                        <?php } ?>
                        
                        <?php if( $stock_detail_key!=0 and $stock_detail_value->close_price > $stock_detail[$stock_detail_key-1]->close_price ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Market closing price increases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and $stock_detail_value->close_price < $stock_detail[$stock_detail_key-1]->close_price ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Market closing price decreases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        <?php }?>
                        
                    </td>
                    
                    <td>
                        <?php 
                            $total_vwap = $total_vwap + $stock_detail_value->vwap;                       
                            echo (empty($stock_detail_value->vwap) ? 'NA' : $stock_detail_value->vwap); 
                        ?>
                    
                        <?php if( $stock_detail_key!=0 and $stock_detail_value->vwap > $stock_detail[$stock_detail_key-1]->vwap ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Market VWAP price increases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and $stock_detail_value->vwap < $stock_detail[$stock_detail_key-1]->vwap ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Market VWAP price decreases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        <?php }?>
                    </td>
                    
                    <td><?php echo (empty($stock_detail_value->day_high_price) ? 'NA' : $stock_detail_value->day_high_price); ?></td>
                    <td><?php echo (empty($stock_detail_value->day_high_price) ? 'NA' : $stock_detail_value->day_low_price); ?></td>
                    <td>
                        <?php 
                            $total_traded_volume = $total_traded_volume + $stock_detail_value->total_traded_volume; 
                            echo money_format('%!.0n', $stock_detail_value->total_traded_volume); 
                        ?>
                        
                        <?php if( $stock_detail_key!=0 and $stock_detail_value->total_traded_volume > $stock_detail[$stock_detail_key-1]->total_traded_volume ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Total Traded Volume increases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and $stock_detail_value->total_traded_volume < $stock_detail[$stock_detail_key-1]->total_traded_volume ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Total Traded Volume decreases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        <?php }?>
                        
                        <?php if( $stock_detail_key!=0 ){
                            
                            echo "(" . percentOfTwoNumber( $stock_detail_value->total_traded_volume, $stock_detail[$stock_detail_key-1]->total_traded_volume ) . "%)";
                        
                        }?>
                    </td>
                    
                    <td>
                        <?php
                            $total_delivery_quantity = $total_delivery_quantity + $stock_detail_value->delivery_quantity; 
                            echo money_format('%!.0n', $stock_detail_value->delivery_quantity); 
                        ?>
                        <?php if( $stock_detail_key!=0 and $stock_detail_value->delivery_quantity > $stock_detail[$stock_detail_key-1]->delivery_quantity ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Delivery Quantity increases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and $stock_detail_value->delivery_quantity < $stock_detail[$stock_detail_key-1]->delivery_quantity ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Delivery Quantity decreases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        <?php }?>
                        
                        <?php if( $stock_detail_key!=0 ){
                            
                            echo "(" . percentOfTwoNumber( $stock_detail_value->delivery_quantity, $stock_detail[$stock_detail_key-1]->delivery_quantity ) . "%)";
                        
                        }?>
                        
                    </td>
                    
                    <td>
                        <?php echo $stock_detail_value->delivery_to_traded_quantity; ?>
                        <?php if( $stock_detail_key!=0 and $stock_detail_value->delivery_to_traded_quantity > $stock_detail[$stock_detail_key-1]->delivery_to_traded_quantity ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Delivery to Traded Quantity increases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and $stock_detail_value->delivery_to_traded_quantity < $stock_detail[$stock_detail_key-1]->delivery_to_traded_quantity ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Delivery to Traded Quantity decreases on <?php echo date('d-M-Y', strtotime($stock_detail_value->stock_date)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($stock_detail[$stock_detail_key-1]->stock_date)); ?>)"></i>
                        <?php }?>
                        
                        
                        <?php if( $stock_detail_key!=0 ){
                            
                            echo "(" . percentOfTwoNumber( $stock_detail_value->delivery_to_traded_quantity, $stock_detail[$stock_detail_key-1]->delivery_to_traded_quantity ) . "%)";
                        
                        }?>
                    </td>
                    
                    <td><?php echo (empty($stock_detail_value->pd_symbol_pe) ? 'NA' : $stock_detail_value->pd_symbol_pe); ?></td>
                    <td><?php echo (empty($stock_detail_value->pd_sector_pe) ? 'NA' : $stock_detail_value->pd_sector_pe); ?></td>
                    
                    <td>
                        <?php 
                        
                            $total_traded_value = $total_traded_value + $stock_detail_value->total_traded_value; 
                            echo money_format('%!.0n', $stock_detail_value->total_traded_value);
                        
                            if( $stock_detail_key!=0 ){
                                
                            $ttv_diff_percnt = percentOfTwoNumber( $stock_detail_value->total_traded_value, $stock_detail[$stock_detail_key-1]->total_traded_value );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($ttv_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $ttv_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        $total_no_of_trades = $total_no_of_trades + $stock_detail_value->total_no_of_trades; 
                        echo money_format('%!.0n', $stock_detail_value->total_no_of_trades); ?>
                    </td>
                    <td><?php 
                    
                        echo money_format('%!.0n', $stock_detail_value->volume_by_total_no_of_trade); 
                        
                        if( $stock_detail_key!=0 ){
                            
                            $vbtnt_diff_percnt = percentOfTwoNumber( $stock_detail_value->volume_by_total_no_of_trade, $stock_detail[$stock_detail_key-1]->volume_by_total_no_of_trade) ;
                            
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($vbtnt_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php

                            echo "(" . $vbtnt_diff_percnt . "%)";

                        }                    
                        ?>
                        </span>
                    </td>
                    
                    <?php 
                        
                        $money_flow = 0;
                    
                        if( $stock_detail_key!=0){ 
                        
                            $money_flow_calc = $stock_detail_value->delivery_quantity * $stock_detail_value->vwap;
                            
                            if( $stock_detail_value->close_price > $stock_detail[$stock_detail_key-1]->close_price ) {
                                
                                $money_flow = $money_flow_calc;
                                
                            }else{
                                
                                $money_flow = -$money_flow_calc;
                            }
                            
                            $total_money_flow = $total_money_flow  + $money_flow;
                            
                            $stock_detail[$stock_detail_key]->money_flow = $money_flow;
                            
                        }
                        
                    ?>
                    
                    <td class="<?php echo ($money_flow>0) ? 'col-green' : 'col-red' ?>" >
                        <?php echo number_format($money_flow, 2); ?>
                    </td>
                </tr>

            <?php } ?>
                
                <tr class="avg_total_data">
                    <td>Average</td>
                    <td></td>
                    <td></td>
                    <td><?php echo money_format("%n", $total_close / count($stock_detail)); ?></td>
                    <td><?php echo number_format( $total_vwap / count($stock_detail) , 2); ?></td>                    
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($total_traded_volume / count($stock_detail) , 2); ?></td>
                    <td><?php echo number_format($total_delivery_quantity / count($stock_detail) , 2); ?></td>
                    <td><?php echo number_format( ( ($total_delivery_quantity/$total_traded_volume)*100 ) , 2); ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($total_traded_value / count($stock_detail) , 2); ?></td>
                    <td><?php echo number_format($total_no_of_trades / count($stock_detail) , 2); ?></td>
                    
                    <td><?php echo number_format( ( $total_traded_volume/$total_no_of_trades ) , 2); ?></td>
                    
                    <td><?php echo number_format( $total_money_flow / count($stock_detail) , 2); ?></td>
                    
                </tr>
                
                <tr class="avg_total_data">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td></td>                 
                    <td></td>                 
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($total_traded_volume); ?></td>
                    <td><?php echo number_format($total_delivery_quantity); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format($total_traded_value); ?></td>
                    <td><?php echo number_format($total_no_of_trades); ?></td>
                    <td></td>
                    <td><?php echo number_format($total_money_flow); ?></td>
                </tr>

        </tbody>
    </table>
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available for <?php echo $company_symbol; ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
    
</div>

    <div id="market_running" data-val='' ></div>
    
    <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($stock_detail) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
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
            
            <div class="chart_dsgn" id="total_traded_volume_chart" data-plot_data="total_traded_volume" data-plot_data_name="Traded Volume" data-colorz="blue" data-full_screen="0"></div>             
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
             <div class="chart_dsgn" id="delivery_quantity_chart" data-plot_data="delivery_quantity" data-plot_data_name="Delivery Quantity" data-colorz="blue" data-full_screen="0"></div> 
            
        </div>
        
    </div>
    
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="delivery_to_traded_quantity_chart" data-plot_data="delivery_to_traded_quantity" data-plot_data_name="Delivery to Traded Quantity" data-colorz="blue" data-full_screen="0"></div>             
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
             <div class="chart_dsgn" id="vwap_chart" data-plot_data="vwap" data-plot_data_name="VWAP" data-colorz="blue" data-full_screen="0"></div> 
            
        </div>
        
    </div>
    
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="total_traded_value_chart" data-plot_data="total_traded_value" data-plot_data_name="Total Traded Value" data-colorz="blue" data-full_screen="0"></div>             
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
             <div class="chart_dsgn" id="total_no_of_trades_chart" data-plot_data="total_no_of_trades" data-plot_data_name="Total No of Trades" data-colorz="blue" data-full_screen="0"></div> 
            
        </div>
        
    </div>
    
    <div class="row">
            
        <div class="col-xl-12 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="volume_by_total_no_of_trade_chart" data-plot_data="volume_by_total_no_of_trade" data-plot_data_name="Volume / Total No of Trade" data-colorz="blue" data-full_screen="0"></div>             
            
        </div>
        
    </div>

<script>
    /*
     * @author : ZAHIR
     * DESC: On change stock date
     */
    function changeStockDate(e) {

//        alert(e.target.value);
        $(".stock_date").attr('value', e.target.value);

        $('.apply-btn-actionz').click();
    }

/*
 * @author: ZAHIR
 * DESC: Flatdate picker
 */
$(document).ready(function () {
    flatpickr("#stock_date", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            maxDate: "today",
            "disable": [
            function(date) {
                // return true to disable, disable saturday and sunday
                return (date.getDay() === 0 || date.getDay() === 6);

            }
            ],
            "locale": {
                "firstDayOfWeek": 1 // start week on Monday
            }
    });
});
</script>