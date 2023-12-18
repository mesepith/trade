<?php $this->load->helper('function_helper'); ?>

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
    .mb-10{margin-bottom: 10px;}
    .mb-20{margin-bottom: 20px;}
    .mt-60{margin-top: 60px;}
    .mt-20{margin-top: 20px;}
    
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
    <?php if( !empty($sector_name) ){ ?>
    <h2><?php echo 'Analysis of <b>' . $sector_data[0]->index_name . '</b> on ' . $stock_date; ?></h2>
    <?php } ?>
    <?php if(!empty($no_data_for_manual_date_msg)){ ?> 
        <div class="mt-20">
            <div class="alert alert-danger">
                <strong><?php echo $no_data_for_manual_date_msg; ?></strong> 
            </div>
        </div>
    
    <?php } ?>
    
    <form method="get" action="<?php echo base_url('sectors/log/'); ?>">
        <div class="row mb-30 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($stock_date) ? date('Y-m-d') :$stock_date; ?>"  onchange="changeSectorDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($stock_date_to) ? date('Y-m-d') :$stock_date_to; ?>"  onchange="changeSectorDate(event, 'to');">
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
        
        <input type='hidden' class='sector_id' name='sector_id' value='<?php echo $sector_id; ?>'>
        <input type='hidden' class='sector_name' name='sector_name' value='<?php echo $sector_name; ?>'>

        <input type="hidden" class="sector_date" name="sector_date" value="<?php echo empty($stock_date) ? date('Y-m-d') :$stock_date; ?>">
        <input type="hidden" class="sector_date_to" name="sector_date_to" value="<?php echo empty($stock_date_to) ? date('Y-m-d') :$stock_date_to; ?>">

        <input type="submit" class="apply-btn-actionz mb-30" value="Apply">
        
        <div class="mb-20">
            <a href="<?php echo base_url() . 'sectors/cluster-return/' . $sector_id . '/' . $sector_name; ?>">Quarterly Monthly Weekly Analysis</a>
        </div>
        
        <div class="mb-60">
            <a href="<?php echo base_url() . 'sector/average-by-days/' . $sector_id . '/' . $sector_name; ?>"> 3, 5, 8, 14, 20 Days Average</a>
        </div>

    </form>
    
    <?php //echo '<pre>'; print_r($sector_data); 
    if( !empty($sector_data) && count($sector_data) > 0 ){ ?>
    
    <p>Analysis:</p>            
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Open Price</th>
                <th>High Price</th>
                <th>Low Price</th>
                <th>LTP</th>
                <th>Change</th>
                <th>Change in Percent</th>
                <th>Year Change in Percent</th>
                <th>Month Change in Percent</th>
                <th>Year High Price</th>
                <th>Year Low Price</th>
                <th>Advances</th>
                <th>Declines</th>
                <th>Trade Value Sum</th>
                <th>Trade Volume Sum</th>
            </tr>
        </thead>
        <tbody>

            <?php 
                $total_ltp = 0;
                $total_change = 0;
                $total_change_in_percent = 0;
                $total_year_change_in_p = 0;
                $total_month_change_in_p = 0;
                $total_advances = 0;
                $total_declines = 0;
                $total_trade_value_sum = 0;
                $total_trade_volume_sum = 0;
                foreach ($sector_data AS $sector_data_value) { 
            ?>

                <tr class="db_data">
                    <td><?php echo date('d M Y', strtotime($sector_data_value->stock_date_time)); ?></td>
                    
                    <td><?php echo number_format( $sector_data_value->open_price, 2); ?></td>
                    <td><?php echo number_format( $sector_data_value->high_price, 2); ?></td>
                    <td><?php echo number_format( $sector_data_value->low_price, 2); ?></td>
                    <td>
                        <?php 
                            $total_ltp = $total_ltp + $sector_data_value->ltp;
                            echo number_format( $sector_data_value->ltp, 2); 
                        ?>
                    </td>
                    <td>
                        <?php 
                            $total_change = $total_change + $sector_data_value->change;
                            echo number_format( $sector_data_value->change, 2); 
                        ?>
                    </td>
                    
                    <td>
                        <?php 
                            $total_change_in_percent = $total_change_in_percent + $sector_data_value->change_in_percent;
                            echo $sector_data_value->change_in_percent; 
                        ?>
                    </td>
                    <td>
                        <?php 
                            $total_year_change_in_p = $total_year_change_in_p + $sector_data_value->year_change_in_percent;
                            echo $sector_data_value->year_change_in_percent; 
                        ?>
                    </td>
                    <td>
                        <?php 
                            $total_month_change_in_p = $total_month_change_in_p + $sector_data_value->month_change_in_percent;
                            echo $sector_data_value->month_change_in_percent; 
                        ?>
                    </td>
                    
                    <td><?php echo number_format( $sector_data_value->year_high_price, 2); ?></td>
                    <td><?php echo number_format( $sector_data_value->year_low_price, 2); ?></td>                    
                    
                    <td>
                        <?php 
                            $total_advances = $total_advances + $sector_data_value->advances;
                            echo $sector_data_value->advances; 
                        ?>
                    </td>
                    <td>
                        <?php 
                            $total_declines = $total_declines + $sector_data_value->declines;
                            echo $sector_data_value->declines; 
                        ?>
                    </td>
                    
                    <td>
                        <?php 
                            $total_trade_value_sum = $total_trade_value_sum + $sector_data_value->trade_value_sum;
                            echo number_format($sector_data_value->trade_value_sum,2); 
                        ?>
                    </td>
                    <td>
                        <?php 
                            $total_trade_volume_sum = $total_trade_volume_sum + $sector_data_value->trade_volume_sum;
                            echo indianNumberFormat($sector_data_value->trade_volume_sum); 
                        ?>
                    </td>
                </tr>

            <?php } ?>
                
                <tr class="avg_total_data">
                    <td>Average</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format( $total_ltp / count($sector_data) , 2); ?></td>
                    <td><?php echo number_format( $total_change / count($sector_data) , 2); ?></td>
                    <td><?php echo number_format( $total_change_in_percent / count($sector_data) , 2); ?></td>
                    <td><?php echo number_format( $total_year_change_in_p / count($sector_data) , 2); ?></td>
                    <td><?php echo number_format( $total_month_change_in_p / count($sector_data) , 2); ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format( $total_advances / count($sector_data) , 2); ?></td>
                    <td><?php echo number_format( $total_declines / count($sector_data) , 2); ?></td>
                    <td><?php echo number_format( $total_trade_value_sum / count($sector_data) , 2); ?></td>
                    <td><?php echo number_format( $total_trade_volume_sum / count($sector_data) , 2); ?></td>
                    
                </tr>
                
                <tr class="avg_total_data">
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format( $total_change , 2); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo number_format( $total_trade_value_sum , 2); ?></td>
                    <td><?php echo number_format( $total_trade_volume_sum , 2); ?></td>
                    
                </tr>

        </tbody>
    </table>
    
    <div id="chart-sec">
        <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($sector_data) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
        <div class="row">

            <div class="col-xl-12 col-sm-12 col-12">

                <div class="chart_dsgn" id="close_price_chart" data-plot_data="close_price" data-plot_data_name="Close Price" data-colorz="green" data-full_screen="0"></div> 
            </div>

        </div>
        <div class="row">

            <div class="col-xl-6 col-sm-12 col-12">

                <div class="chart_dsgn" id="trade_value_sum_chart" data-plot_data="trade_value_sum" data-plot_data_name="Traded Value Sum" data-colorz="green" data-full_screen="0"></div> 
            </div>

            <div class="col-xl-6 col-sm-12 col-12">

                <div class="chart_dsgn" id="trade_volume_sum_chart" data-plot_data="trade_volume_sum" data-plot_data_name="Traded Volume Sum" data-colorz="green" data-full_screen="0"></div> 
            </div>

        </div>
        <div class="row">

            <div class="col-xl-6 col-sm-12 col-12">

                <div class="chart_dsgn" id="change_chart" data-plot_data="change" data-plot_data_name="Change" data-colorz="green" data-full_screen="0"></div> 
            </div>

            <div class="col-xl-6 col-sm-12 col-12">

                <div class="chart_dsgn" id="change_in_percent_chart" data-plot_data="change_in_percent" data-plot_data_name="Change %" data-colorz="green" data-full_screen="0"></div> 
            </div>

        </div>
    </div>
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available for <?php echo $sector_name; ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>

