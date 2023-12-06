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
</style>

<div class="container">
    
    <h1>Oi Participant Wise Data</h1>
    
    <form method="get" action="<?php echo base_url('client-activity/oi-participant/'); ?>">
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($market_date) ? date('Y-m-d') : $market_date; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($market_date_to) ? date('Y-m-d') : $market_date_to; ?>"  onchange="changeStockDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>
        
        <div class="row mb-20">
            <div class="col-xl-1 col-2 mb-30">
                <input type="checkbox" class="select_client_type" id="Client_chkbox" name="client_type_chkbox" value="Client" <?php echo ( $client_type_chkbox === "Client" ) ? 'checked' : ''; ?>>
                <label for="Client_chkbox"> Client</label><br>            
            </div>
            <div class="col-xl-1 col-1 mb-30">
                <input type="checkbox" class="select_client_type" id="DII_chkbox" name="client_type_chkbox" value="DII" <?php echo ($client_type_chkbox === "DII" ) ? 'checked' : ''; ?>>
                <label for="DII_chkbox"> DII</label><br>            
            </div>
            <div class="col-xl-1 col-1 mb-30">
                <input type="checkbox" class="select_client_type" id="FII_chkbox" name="client_type_chkbox" value="FII" <?php echo ($client_type_chkbox === "FII" ) ? 'checked' : ''; ?>>
                <label for="FII_chkbox"> FII</label><br>            
            </div>
            <div class="col-xl-1 col-1 mb-30">
                <input type="checkbox" class="select_client_type" id="Pro_chkbox" name="client_type_chkbox" value="Pro" <?php echo ($client_type_chkbox === "Pro" ) ? 'checked' : ''; ?>>
                <label for="Pro_chkbox"> Pro</label><br>            
            </div>
        
        </div>
                 
        <div class="row mb-30">
            
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
        
        <div class="mb-60">
            <div class="row">
                <div class="col-xl-2 col-12 mb-10">
                    Quarterly, Monthly, Weekly Analysis
                </div>
            </div>
            
            <div class="row">
                <div class="col-xl-1 col-12 mb-10">
                    <a href="<?php echo base_url() . 'oi-participant/cluster-return/Client'; ?>">Client</a>
                </div>
                <div class="col-xl-1 col-12 mb-10">
                    <a href="<?php echo base_url() . 'oi-participant/cluster-return/DII'; ?>">DII</a>
                </div>
                <div class="col-xl-1 col-12 mb-10">
                    <a href="<?php echo base_url() . 'oi-participant/cluster-return/FII'; ?>">FII</a>
                </div>
                <div class="col-xl-1 col-12 mb-10">
                    <a href="<?php echo base_url() . 'oi-participant/cluster-return/Pro'; ?>">Pro</a>
                </div>
            </div>
        </div>
        
        
        <div class="row mb-20">
            <div class="col-xl-12 col-1">
                <input type="checkbox" class="show_avg_total_data" id="show_avg_total_data_chkbox" name="show_avg_total_data" value="<?php echo $show_avg_total_data; ?>" <?php echo ($show_avg_total_data== 'yes') ? 'checked' : ''; ?>>
                <label for="show_avg_total_data_chkbox"> Show Only Average And Total Net Value</label><br>            
            </div>
        </div>
        
    </form>
    
    <?php if( !empty($oi_participant_data) && count($oi_participant_data) > 0 ){ ?>
        
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Client Type</th>
                    <th>Future Index Long</th>
                    <th>Future Index Short</th>
                    <th>Future Stock Long</th>
                    <th>Future Stock Short</th>
                    <th>Option Index Call Long</th>
                    <th>Option Index Put Long</th>
                    <th>Option Index Call Short</th>
                    <th>Option Index Put Short</th>
                    <th>Option Stock Call Long</th>
                    <th>Option Stock Put Long</th>
                    <th>Option Stock Call Short</th>
                    <th>Option Stock Put Short</th>
                    <th>Total Long Contracts</th>
                    <th>Total Short Contracts</th>
                </tr>
            </thead>
            <tbody>
                
                <?php 
                
                    $net_non_total_row = 0;
                
                    $total_future_index_long = 0;
                    $total_future_index_short = 0;
                    $total_future_stock_long = 0;
                    $total_future_stock_short = 0;
                    $total_option_index_call_long = 0;
                    $total_option_index_put_long = 0;
                    $total_option_index_call_short = 0;
                    $total_option_index_put_short = 0;
                    $total_option_stock_call_long = 0;
                    $total_option_stock_put_long = 0;
                    $total_option_stock_call_short = 0;
                    $total_option_stock_put_short = 0;
                    $net_total_long_contracts = 0;
                    $net_total_short_contracts = 0;
                    
                    foreach ($oi_participant_data AS $oi_participant_data_key=> $oi_participant_data_value) { 
                        
                        if( $oi_participant_data_value->client_type !== 'TOTAL'){
                            
                            $net_non_total_row++;
                        }
                ?>

                <tr class="db_data">
                    <td><?php echo date('d-M-Y', strtotime($oi_participant_data_value->market_date)); ?></td>
                    <td><?php echo $oi_participant_data_value->client_type; ?></td>
                    <td>
                        <?php 
                            
                            if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                                $total_future_index_long = $total_future_index_long + $oi_participant_data_value->future_index_long;
                            
                            }
                            
                            echo money_format('%!.0n', $oi_participant_data_value->future_index_long); 
                            
                            if( $oi_participant_data_key!=0 ){
                            
                                $fil_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->future_index_long, $oi_participant_data[$oi_participant_data_key-1]->future_index_long );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($fil_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $fil_diff_percnt . "%)";
                        
                        }?>
                        </span>
                            
                    </td>
                    <td>
                        <?php 
                        
                            if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                                $total_future_index_short = $total_future_index_short + $oi_participant_data_value->future_index_short;
                            
                            }
                        
                            echo money_format('%!.0n', $oi_participant_data_value->future_index_short); 
                            
                            if( $oi_participant_data_key!=0 ){
                            
                                $fis_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->future_index_short, $oi_participant_data[$oi_participant_data_key-1]->future_index_short );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($fis_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $fis_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        
                            if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                                $total_future_stock_long = $total_future_stock_long + $oi_participant_data_value->future_stock_long;
                            
                            }
                            
                            echo money_format('%!.0n', $oi_participant_data_value->future_stock_long); 
                            
                        if( $oi_participant_data_key!=0 ){
                            
                                $fsl_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->future_stock_long, $oi_participant_data[$oi_participant_data_key-1]->future_stock_long );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($fsl_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $fsl_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td>
                        <?php 
                            
                            if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                                $total_future_stock_short = $total_future_stock_short + $oi_participant_data_value->future_stock_short;
                            
                            }
                            
                            echo money_format('%!.0n', $oi_participant_data_value->future_stock_short); 
                            
                            if( $oi_participant_data_key!=0 ){
                            
                                $fss_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->future_stock_short, $oi_participant_data[$oi_participant_data_key-1]->future_stock_short );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($fss_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $fss_diff_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    <td>
                        <?php 
                        
                        if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                            $total_option_index_call_long = $total_option_index_call_long + $oi_participant_data_value->option_index_call_long;
                        
                        }
                        
                        echo money_format('%!.0n', $oi_participant_data_value->option_index_call_long); 
                        
                        if( $oi_participant_data_key!=0 ){
                            
                                $oicl_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->option_index_call_long, $oi_participant_data[$oi_participant_data_key-1]->option_index_call_long );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($oicl_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $oicl_diff_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    <td>
                        <?php 
                        
                        if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                            $total_option_index_put_long = $total_option_index_put_long + $oi_participant_data_value->option_index_put_long;
                        
                        }
                        
                        echo money_format('%!.0n', $oi_participant_data_value->option_index_put_long); 
                        
                    
                        if( $oi_participant_data_key!=0 ){
                            
                                $oipl_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->option_index_put_long, $oi_participant_data[$oi_participant_data_key-1]->option_index_put_long );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($oipl_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $oipl_diff_percnt . "%)";
                        
                        }?>Average
                        </span>
                    </td>
                    <td>
                        <?php 
                            if( $oi_participant_data_value->client_type !== 'TOTAL'){
                                
                                $total_option_index_call_short = $total_option_index_call_short + $oi_participant_data_value->option_index_call_short;
                            }
                        
                            echo money_format('%!.0n', $oi_participant_data_value->option_index_call_short);                         
                    
                        if( $oi_participant_data_key!=0 ){
                            
                                $oics_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->option_index_call_short, $oi_participant_data[$oi_participant_data_key-1]->option_index_call_short );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($oics_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $oics_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        
                        if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                            $total_option_index_put_short = $total_option_index_put_short + $oi_participant_data_value->option_index_put_short;
                        
                        }
                    
                        echo money_format('%!.0n', $oi_participant_data_value->option_index_put_short); 
                    
                        if( $oi_participant_data_key!=0 ){
                            
                                $oips_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->option_index_put_short, $oi_participant_data[$oi_participant_data_key-1]->option_index_put_short );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($oips_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $oips_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td>
                        <?php
                        
                        if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                            $total_option_stock_call_long = $total_option_stock_call_long + $oi_participant_data_value->option_stock_call_long;
                        
                        }
                        
                        echo money_format('%!.0n', $oi_participant_data_value->option_stock_call_long); 
                    
                        if( $oi_participant_data_key!=0 ){
                            
                                $oscl_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->option_stock_call_long, $oi_participant_data[$oi_participant_data_key-1]->option_stock_call_long );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($oscl_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $oscl_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        
                            if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                                $total_option_stock_put_long = $total_option_stock_put_long + $oi_participant_data_value->option_stock_put_long;
                            
                            }
                        
                            echo money_format('%!.0n', $oi_participant_data_value->option_stock_put_long); 
                        
                        if( $oi_participant_data_key!=0 ){
                            
                                $ospl_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->option_stock_put_long, $oi_participant_data[$oi_participant_data_key-1]->option_stock_put_long );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($ospl_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $ospl_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        
                            if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                                $total_option_stock_call_short = $total_option_stock_call_short + $oi_participant_data_value->option_stock_call_short;
                            
                            }
                        
                            echo money_format('%!.0n', $oi_participant_data_value->option_stock_call_short); 
                        
                        if( $oi_participant_data_key!=0 ){
                            
                                $oscs_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->option_stock_call_short, $oi_participant_data[$oi_participant_data_key-1]->option_stock_call_short );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($oscs_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $oscs_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        
                        if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                            $total_option_stock_put_short = $total_option_stock_put_short + $oi_participant_data_value->option_stock_put_short;
                        
                        }
                        
                        echo money_format('%!.0n', $oi_participant_data_value->option_stock_put_short); 
                                                
                        if( $oi_participant_data_key!=0 ){
                            
                                $osps_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->option_stock_put_short, $oi_participant_data[$oi_participant_data_key-1]->option_stock_put_short );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($osps_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $osps_diff_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    <td>
                        <?php 
                        
                        if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                            $net_total_long_contracts = $net_total_long_contracts + $oi_participant_data_value->total_long_contracts;
                            
                        }
                        
                        echo money_format('%!.0n', $oi_participant_data_value->total_long_contracts); 
                                                 
                        if( $oi_participant_data_key!=0 ){
                            
                                $ttc_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->total_long_contracts, $oi_participant_data[$oi_participant_data_key-1]->total_long_contracts );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($ttc_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $ttc_diff_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    <td>
                        <?php 
                            
                            if( $oi_participant_data_value->client_type !== 'TOTAL'){
                        
                                $net_total_short_contracts = $net_total_short_contracts + $oi_participant_data_value->total_short_contracts;
                            
                            }
                        
                            echo money_format('%!.0n', $oi_participant_data_value->total_short_contracts); 
                                                  
                        if( $oi_participant_data_key!=0 ){
                            
                            $tsc_diff_percnt = percentOfTwoNumber( $oi_participant_data_value->total_short_contracts, $oi_participant_data[$oi_participant_data_key-1]->total_short_contracts );
                        
                        ?> 
                        
                        <br/>
                        <span class="<?php echo ($tsc_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $tsc_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    
                    
                </tr>
                
                <?php } ?>
                
                <tr class="avg_total_data">
                    <td></td>
                    <td>Average</td>
                    <td><?php echo number_format( $total_future_index_long / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_future_index_short / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_future_stock_long / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_future_stock_short / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_option_index_call_long / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_option_index_put_long / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_option_index_call_short / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_option_index_put_short / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_option_stock_call_long / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_option_stock_put_long / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_option_stock_call_short / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $total_option_stock_put_short / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $net_total_long_contracts / $net_non_total_row , 2); ?></td>    
                    <td><?php echo number_format( $net_total_short_contracts / $net_non_total_row , 2); ?></td>    
                    
                </tr>
                
            </tbody>
        </table>
        
    <?php if( !empty($client_type_chkbox) ){?>
    
    <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($oi_participant_data) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
    <div class="row">
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="future_index_long_short_chart" data-plot_data="future_index_long_short" data-plot_data_name="Future Index Long Short" data-colorz="green" data-full_screen="0"></div>             

        </div>
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="future_stock_long_short_chart" data-plot_data="future_stock_long_short" data-plot_data_name="Future Stock Long Short" data-colorz="green" data-full_screen="0"></div>             

        </div>
        
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="option_index_call_put_long_short_chart" data-plot_data="option_index_call_put_long_short" data-plot_data_name="Option Index Call/Put Long/Short" data-colorz="green" data-full_screen="0"></div>             

        </div>
        
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="option_stock_call_put_long_short_chart" data-plot_data="option_stock_call_put_long_short" data-plot_data_name="Option Stock Call/Put Long/Short" data-colorz="green" data-full_screen="0"></div>             

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