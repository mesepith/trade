<?php setlocale(LC_MONETARY,"en_IN.utf8"); ?>
<style>
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
    .mt-60{margin-top: 60px;}
    .mb-30{margin-bottom: 30px;}
    .mt-30{margin-top: 30px;}
    .mb-20{margin-bottom: 20px;}
    .mt-20{margin-top: 20px;}
    
    .green-up-arr{
        color: green;
        font-size: 25px;
    }
    .red-down-arr{
        color: red;
        font-size: 25px;
    }
    .pos-val{
        color: green;
    }
    .neg-val{
        color: red;
    }
    .red-up-arr{
        color: red;
        font-size: 25px;
    }
    .green-down-arr{
        color: green;
        font-size: 25px;
    }
    .chart_dsgn{
        width:925px; 
        height:700px;
    }
</style>

<div class="container">
    <h1>FII DII Cash Market Investment Data</h1>
    <form method="get" action="<?php echo base_url('fii-dii/total-investment/'); ?>">
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($invest_date) ? date('Y-m-d') : $invest_date; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($invest_date_to) ? date('Y-m-d') : $invest_date_to; ?>"  onchange="changeStockDate(event, 'to');">
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
        
        <div class="row mb-20">
            <div class="col-xl-12 col-1">
                <input type="checkbox" class="show_avg_total_data" id="show_avg_total_data_chkbox" name="show_avg_total_data" value="<?php echo $show_avg_total_data; ?>" <?php echo ($show_avg_total_data== 'yes') ? 'checked' : ''; ?>>
                <label for="show_avg_total_data_chkbox"> Show Only Average And Total Net Value</label><br>            
            </div>
        </div>
        
        <input type="hidden" class="invest_date" name="invest_date" value="<?php echo empty($invest_date) ? date('Y-m-d') :$invest_date; ?>">
        <input type="hidden" class="invest_date_to" name="invest_date_to" value="<?php echo empty($invest_date_to) ? date('Y-m-d') :$invest_date_to; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <?php if( !empty($total_investment_data_arr) && count($total_investment_data_arr) > 0 ){ ?>
        
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>FII/FPI Buy</th>
                    <th>FII/FPI Sell</th>
                    <th>FII/FPI Net Value</th>
                    <th>DII Buy</th>
                    <th>DII Sell</th>
                    <th>DII Net Value</th>
                </tr>
            </thead>
            <tbody>
                
                <?php 
                    
                    $total_fii_net = 0;
                    $total_dii_net = 0;
                
                    foreach ($total_investment_data_arr AS $total_investment_data_arr_key=>$total_investment_data_arr_value) { 
                ?>

                <tr class="db_data">
                    <td><?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?></td>
                    <td>
                        <?php echo $total_investment_data_arr_value['FII/FPI']['buy_value']; ?>
                        
                        <?php if( (!empty($prev_date)) and $total_investment_data_arr_value['FII/FPI']['buy_value'] > $total_investment_data_arr[$prev_date]['FII/FPI']['buy_value'] ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Buying by FII/FPI increases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        
                        <?php }else if( (!empty($prev_date)) and $total_investment_data_arr_value['FII/FPI']['buy_value'] < $total_investment_data_arr[$prev_date]['FII/FPI']['buy_value'] ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Buying by FII/FPI decreases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        <?php }?>
                        
                    </td>
                    <td>
                        <?php echo $total_investment_data_arr_value['FII/FPI']['sell_value']; ?>
                    
                        <?php if( (!empty($prev_date)) and $total_investment_data_arr_value['FII/FPI']['sell_value'] > $total_investment_data_arr[$prev_date]['FII/FPI']['sell_value'] ) { ?>
                        <i class="fa fa-arrow-up red-up-arr" data-toggle="tooltip" title="Selling by FII/FPI increases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        
                        <?php }else if( (!empty($prev_date)) and $total_investment_data_arr_value['FII/FPI']['sell_value'] < $total_investment_data_arr[$prev_date]['FII/FPI']['sell_value'] ) { ?>
                        <i class="fa fa-arrow-down green-down-arr" data-toggle="tooltip" title="Selling by FII/FPI decreases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        <?php }?>
                        
                    </td>
                    <td>
                        <span class='<?php if( ($total_investment_data_arr_value['FII/FPI']['net_value'])> 0){ echo 'pos-val'; } else{ echo'neg-val';} ?>'>
                            <?php 
                            $total_fii_net = $total_fii_net + $total_investment_data_arr_value['FII/FPI']['net_value'];
                            echo $total_investment_data_arr_value['FII/FPI']['net_value']; 
                            ?>
                        </span>
                        
                        <?php if( (!empty($prev_date)) and $total_investment_data_arr_value['FII/FPI']['net_value'] > $total_investment_data_arr[$prev_date]['FII/FPI']['net_value'] ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Net value of FII/FPI increases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        
                        <?php }else if( (!empty($prev_date)) and $total_investment_data_arr_value['FII/FPI']['net_value'] < $total_investment_data_arr[$prev_date]['FII/FPI']['net_value'] ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Net value of FII/FPI decreases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        <?php }?>
                        
                    </td>
                    <td>
                        <?php echo $total_investment_data_arr_value['DII']['buy_value']; ?>
                        
                        <?php if( (!empty($prev_date)) and $total_investment_data_arr_value['DII']['buy_value'] > $total_investment_data_arr[$prev_date]['DII']['buy_value'] ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Buying by DII increases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        
                        <?php }else if( (!empty($prev_date)) and $total_investment_data_arr_value['DII']['buy_value'] < $total_investment_data_arr[$prev_date]['DII']['buy_value'] ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Buying by DII decreases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        <?php }?>
                        
                    </td>
                    <td>
                        <?php echo $total_investment_data_arr_value['DII']['sell_value']; ?>
                        
                        <?php if( (!empty($prev_date)) and $total_investment_data_arr_value['DII']['sell_value'] > $total_investment_data_arr[$prev_date]['DII']['sell_value'] ) { ?>
                        <i class="fa fa-arrow-up red-up-arr" data-toggle="tooltip" title="Selling by DII increases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        
                        <?php }else if( (!empty($prev_date)) and $total_investment_data_arr_value['DII']['sell_value'] < $total_investment_data_arr[$prev_date]['DII']['sell_value'] ) { ?>
                        <i class="fa fa-arrow-down green-down-arr" data-toggle="tooltip" title="Selling by DII decreases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        <?php }?>
                    
                    </td>
                    <td>
                        <span class='<?php if( ($total_investment_data_arr_value['DII']['net_value'])> 0){ echo 'pos-val'; } else{ echo'neg-val';} ?>'>
                            <?php 
                                $total_dii_net = $total_dii_net + $total_investment_data_arr_value['DII']['net_value'];
                                echo $total_investment_data_arr_value['DII']['net_value']; 
                            ?>
                        </span>
                        
                        <?php if( (!empty($prev_date)) and $total_investment_data_arr_value['DII']['net_value'] > $total_investment_data_arr[$prev_date]['DII']['net_value'] ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Net value of DII increases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        
                        <?php }else if( (!empty($prev_date)) and $total_investment_data_arr_value['DII']['net_value'] < $total_investment_data_arr[$prev_date]['DII']['net_value'] ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Net value of DII decreases on <?php echo date('d-M-Y', strtotime($total_investment_data_arr_key)); ?> compared to previous trading date (<?php echo date('d M Y', strtotime($prev_date)); ?>)"></i>
                        <?php }?>
                    </td>
                    
                    <?php $prev_date = $total_investment_data_arr_key; ?>
                </tr>
                
                <?php } ?>
                
                <tr class="avg_total_data">
                    <td>Average</td>
                    <td></td>
                    <td></td>
                    <td><?php echo money_format("%n", $total_fii_net / count($total_investment_data_arr)); ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo money_format("%n", $total_dii_net / count($total_investment_data_arr)); ?></td>
                    
                </tr>
                <tr class="avg_total_data">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td><?php echo money_format("%n", $total_fii_net ); ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo money_format("%n", $total_dii_net ); ?></td>
                    
                </tr>
                
            </tbody>
        </table>
        
    
    <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($total_investment_data_arr) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
    <div class="row">
            
        <div class="col-xl-12 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="fii_dii_net_chart" data-plot_data="fii_dii_net" data-plot_data_name="FII DII NET" data-colorz="green" data-full_screen="0"></div> 
        </div>
        
    </div>
    
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available, Kindly choose another date </strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>