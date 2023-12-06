<?php 

$this->load->helper('function_helper');

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
    .mb-10{margin-bottom: 10px;}
    .mb-20{margin-bottom: 20px;}
    .mb-30{margin-bottom: 30px;}
    .mb-60{margin-bottom: 60px;}
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

table tbody tr.Total_row{
    background: green;
    color: white;
}
</style>

<div class="container">
    
    <h1>Exchange Top 10 Clearing Member</h1>
    
    <form method="get" action="<?php echo base_url('top-10-exchange-clearing-member/'); ?>">
        <div class="row mb-10 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-10 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($market_date) ? date('Y-m-d') : $market_date; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-10 htm-date-container"> 
                <input <?php echo ( $enable_to_date_chkbox == 'yes' ? '' : "disabled='disabled'"); ?> class="htm-date date_flat_pickz date_flat_pickz_to"  readonly="readonly" value="<?php echo empty($market_date_to) ? date('Y-m-d') : $market_date_to; ?>"  onchange="changeStockDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>
        
        <div class="row mb-60 mt-20">
            <div class="col-xl-3 col-1 mb-30">
                <input type="checkbox" class="enable_to_date" id="enable_to_date_chkbox" name="enable_to_date_chkbox" value="<?php echo $enable_to_date_chkbox; ?>" <?php echo ($enable_to_date_chkbox === "yes" ) ? 'checked' : ''; ?>>
                <label for="enable_to_date_chkbox"> Enable 'Select Date To'</label><br>            
            </div>
        </div>
        
        <div class="row mb-60">
            
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
        
        <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') :$market_date; ?>">
        <input type="hidden" class="market_date_to" name="market_date_to" value="<?php echo empty($market_date_to) ? date('Y-m-d') :$market_date_to; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">        
        
        <div class="row mb-20">
            <div class="col-xl-12 col-1">
                <input type="checkbox" class="show_avg_total_data" id="show_avg_total_data_chkbox" name="show_avg_total_data" value="<?php echo $show_avg_total_data; ?>" <?php echo ($show_avg_total_data== 'yes') ? 'checked' : ''; ?>>
                <label for="show_avg_total_data_chkbox"> Show Only Average And Total Net Value</label><br>            
            </div>
        </div>
        
    </form>
    
    <?php if( !empty($exchng_top_clr_membr) && count($exchng_top_clr_membr) > 0 ){ ?>
        
    <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <?php if($enable_to_date_chkbox == 'no'){ ?>
                    <th>Serial No</th>
                    <?php }?>
                    
                    <th>Index Futures Volume</th>
                    <th>Index Futures Turnover</th>
                    
                    <th>Stock Futures Volume</th>
                    <th>Stock Futures Turnover</th>
                    
                    <th>Index Option Volume</th>
                    <th>Index Option Turnover</th>
                    <th>Index Option Turnover Premium</th>
                    
                    <th>Stock Option Volume</th>
                    <th>Stock Option Turnover</th>
                    <th>Stock Option Turnover Premium</th>
                    
                    <th>Total Volume</th>
                    <th>Total Turnover</th>
                </tr>
            </thead>
            <tbody>
                
                <?php 
                
                $net_non_total_row = 0;
                
                $total_index_futures_vol = 0;
                $total_index_futures_trnvr = 0;
                
                $total_stock_futures_vol = 0;
                $total_stock_futures_trnvr = 0;
                
                $total_index_option_vol = 0;
                $total_index_option_trnvr = 0;
                $total_index_option_trnvr_prm = 0;
                
                $total_stock_option_vol = 0;
                $total_stock_option_trnvr = 0;
                $total_stock_option_trnvr_prm = 0;
                
                $net_total_volume = 0;
                $net_total_turnover = 0;
                
                foreach ($exchng_top_clr_membr AS $exchng_top_clr_membr_key=> $exchng_top_clr_membr_value) {
                    
                    $serial_no = ( ( $enable_to_date_chkbox !=='yes' && $exchng_top_clr_membr_value->serial_no == 11) ? 'Total' : $exchng_top_clr_membr_value->serial_no); 
                    
                ?>
                <tr class="<?php echo $serial_no . '_row'; ?> db_data">
                    <td><?php echo date('d M Y', strtotime($exchng_top_clr_membr_value->market_date)); ?></td>
                    <?php if($enable_to_date_chkbox == 'no'){ ?>
                    <td><?php echo $serial_no; ?></td>
                    <?php }?>
                    
                    <td> <?php echo number_format($exchng_top_clr_membr_value->index_futures_vol, 2); ?></td>
                    <td> <?php echo number_format($exchng_top_clr_membr_value->index_futures_trnvr, 2); ?></td>
                    
                    <td> <?php echo number_format($exchng_top_clr_membr_value->stock_futures_vol, 2); ?></td>
                    <td> <?php echo number_format($exchng_top_clr_membr_value->stock_futures_trnvr, 2); ?></td>
                    
                    <td> <?php echo number_format($exchng_top_clr_membr_value->index_option_vol, 2); ?></td>
                    <td> <?php echo number_format($exchng_top_clr_membr_value->index_option_trnvr, 2); ?></td>
                    <td> <?php echo number_format($exchng_top_clr_membr_value->index_option_trnvr_prm, 2); ?></td>
                    
                    <td> <?php echo number_format($exchng_top_clr_membr_value->stock_option_vol, 2); ?></td>
                    <td> <?php echo number_format($exchng_top_clr_membr_value->stock_option_trnvr, 2); ?></td>
                    <td> <?php echo number_format($exchng_top_clr_membr_value->stock_option_trnvr_prm, 2); ?></td>
                    
                    <td>
                        <?php
                            $total_volume = ($exchng_top_clr_membr_value->index_futures_vol + $exchng_top_clr_membr_value->stock_futures_vol + $exchng_top_clr_membr_value->index_option_vol + $exchng_top_clr_membr_value->stock_option_vol );
                            echo number_format( $total_volume );
                            $exchng_top_clr_membr[$exchng_top_clr_membr_key]->total_volume = $total_volume
                        ?>
                    </td>
                    
                    <td>
                        <?php
                            $total_turnover = ($exchng_top_clr_membr_value->index_futures_trnvr + $exchng_top_clr_membr_value->stock_futures_trnvr + $exchng_top_clr_membr_value->index_option_trnvr + $exchng_top_clr_membr_value->index_option_trnvr_prm + $exchng_top_clr_membr_value->stock_option_trnvr + $exchng_top_clr_membr_value->stock_option_trnvr_prm );
                            echo number_format( $total_turnover, 2 );
                            $exchng_top_clr_membr[$exchng_top_clr_membr_key]->total_turnover = $total_turnover
                        ?>
                    </td>
                    
                    <?php
                                        
                    if( $serial_no !== 'Total' ){
                        $net_non_total_row++;
                        
                        $total_index_futures_vol = $total_index_futures_vol + $exchng_top_clr_membr_value->index_futures_vol;                    
                        $total_index_futures_trnvr = $total_index_futures_trnvr + $exchng_top_clr_membr_value->index_futures_trnvr;                    
                        
                        $total_stock_futures_vol = $total_stock_futures_vol + $exchng_top_clr_membr_value->stock_futures_vol;                    
                        $total_stock_futures_trnvr = $total_stock_futures_trnvr+ $exchng_top_clr_membr_value->stock_futures_trnvr;                    
                        
                        $total_index_option_vol = $total_index_option_vol + $exchng_top_clr_membr_value->index_option_vol;                    
                        $total_index_option_trnvr = $total_index_option_trnvr + $exchng_top_clr_membr_value->index_option_trnvr;                    
                        $total_index_option_trnvr_prm = $total_index_option_trnvr_prm + $exchng_top_clr_membr_value->index_option_trnvr_prm;                    
                        
                        $total_stock_option_vol = $total_stock_option_vol + $exchng_top_clr_membr_value->stock_option_vol;                    
                        $total_stock_option_trnvr = $total_stock_option_trnvr + $exchng_top_clr_membr_value->stock_option_trnvr;                    
                        $total_stock_option_trnvr_prm = $total_stock_option_trnvr_prm + $exchng_top_clr_membr_value->stock_option_trnvr_prm; 
                        
                        $net_total_volume = $net_total_volume + $total_volume;
                        $net_total_turnover = $net_total_turnover + $total_turnover;
                        
                    }
                    
                    ?>
                    
                </tr>
                
                <?php } ?>
                
                <tr>
                    
                    <td>Average of <?php echo $net_non_total_row; ?> Data</td>
                    <?php if($enable_to_date_chkbox == 'no'){ ?>
                    <td></td>
                    <?php } ?>
                    <td><?php echo number_format( ( $total_index_futures_vol/$net_non_total_row ) , 2); ?></td>
                    <td><?php echo number_format( ( $total_index_futures_trnvr/$net_non_total_row ) , 2); ?></td>
                    
                    <td><?php echo number_format( ( $total_stock_futures_vol/$net_non_total_row ) , 2); ?></td>
                    <td><?php echo number_format( ( $total_stock_futures_trnvr/$net_non_total_row ) , 2); ?></td>
                    
                    <td><?php echo number_format( ( $total_index_option_vol/$net_non_total_row ) , 2); ?></td>
                    <td><?php echo number_format( ( $total_index_option_trnvr/$net_non_total_row ) , 2); ?></td>
                    <td><?php echo number_format( ( $total_index_option_trnvr_prm/$net_non_total_row ) , 2); ?></td>
                    
                    <td><?php echo number_format( ( $total_stock_option_vol/$net_non_total_row ) , 2); ?></td>
                    <td><?php echo number_format( ( $total_stock_option_trnvr/$net_non_total_row ) , 2); ?></td>
                    <td><?php echo number_format( ( $total_stock_option_trnvr_prm/$net_non_total_row ) , 2); ?></td>
                    
                    <td><?php echo number_format( ( $net_total_volume/$net_non_total_row ) , 2); ?></td>
                    <td><?php echo number_format( ( $net_total_turnover/$net_non_total_row ) , 2); ?></td>
                </tr>
                
            </tbody>
        </table>
        
    <?php if( $enable_to_date_chkbox ==='yes' ){?>
    
    <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($exchng_top_clr_membr) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
    <div class="row">
        
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="total_volume_chart" data-plot_data="total_volume" data-plot_data_name="Total Volume" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="total_turnover_chart" data-plot_data="total_turnover" data-plot_data_name="Total Turnover" data-colorz="green" data-full_screen="0"></div>             

        </div>
        
        
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="index_futures_vol_chart" data-plot_data="index_futures_vol" data-plot_data_name="Index Futures Volume" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="index_futures_trnvr_chart" data-plot_data="index_futures_trnvr" data-plot_data_name="Index Futures Turnover" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="stock_futures_vol_chart" data-plot_data="stock_futures_vol" data-plot_data_name="Stock Futures Volume" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="stock_futures_trnvr_chart" data-plot_data="stock_futures_trnvr" data-plot_data_name="Stock Futures Turnover" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-4 col-sm-12 col-12">

            <div class="chart_dsgn" id="index_option_vol_chart" data-plot_data="index_option_vol" data-plot_data_name="Index Option Volume" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-4 col-sm-12 col-12">

            <div class="chart_dsgn" id="index_option_trnvr_chart" data-plot_data="index_option_trnvr" data-plot_data_name="Index Option Turnover" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-4 col-sm-12 col-12">

            <div class="chart_dsgn" id="index_option_trnvr_prm_chart" data-plot_data="index_option_trnvr_prm" data-plot_data_name="Index Option Turnover Premium" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-4 col-sm-12 col-12">

            <div class="chart_dsgn" id="stock_option_vol_chart" data-plot_data="stock_option_vol" data-plot_data_name="Stock Option Volume" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-4 col-sm-12 col-12">

            <div class="chart_dsgn" id="stock_option_trnvr_chart" data-plot_data="stock_option_trnvr" data-plot_data_name="Stock Option Turnover" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-4 col-sm-12 col-12">

            <div class="chart_dsgn" id="stock_option_trnvr_prm_chart" data-plot_data="stock_option_trnvr_prm" data-plot_data_name="Stock Option Turnover Premium" data-colorz="green" data-full_screen="0"></div>             

        </div>
        
    </div>
    
    <?php } ?>
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available, Kindly choose another date </strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>