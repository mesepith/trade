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
    
    <h1>Category Wise Turnover - <?php echo ucwords($trading_type); ?></h1>
    
    <form method="get" action="<?php echo base_url('category-wise-turnover/' . $trading_type ); ?>">
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
        
        <?php
        
        $cat_nse_dervtv= array('Mutual_chkbox'=>'Mutual Funds', 'Proprietory_chkbox'=>'Proprietory Trades', 'Others_chkbox'=>'Others');
        $cat_cash_nse= array('BNK_chkbox'=>'BNK', 'DFI_chkbox'=>'DFI', 'Proprietory_chkbox'=>'Proprietory Trades', 'OTHERS_chkbox'=>'OTHERS');
        $cat_cash_bse = array('Clients_chkbox'=>'Clients', 'Nri_chkbox'=>'Nri', 'DII_chkbox'=>'DII');
        $cat_cash_nsdl = array('FII_chkbox'=>'FII');
        
        $cat_cash = array_merge($cat_cash_nse, $cat_cash_bse, $cat_cash_nsdl);
        
        if( $trading_type === 'derivative' ){
            
            $cat_arr = $cat_nse_dervtv;
            
        }else if( $trading_type === 'cash' ){
            
            $cat_arr = $cat_cash;
        }
        
        ?>
        
        <div class="row mb-20">
            
            <?php foreach ($cat_arr AS $cat_arr_key=>$cat_arr_val) {?>
            
            <div class="col-xl-1 col-2 mb-30">
                <input type="radio" class="select_category" id="<?php echo $cat_arr_key; ?>" name="category_chkbox" value="<?php echo $cat_arr_val; ?>" <?php echo ( $category_chkbox === $cat_arr_val ) ? 'checked' : ''; ?>>
                <label for="<?php echo $cat_arr_key; ?>"> <?php echo $cat_arr_val; ?></label><br>            
            </div>
            
            <?php } ?>
            
        </div>
                
<!--        <div class="row mb-20">
            <div class="col-xl-1 col-2 mb-30">
                <input type="radio" class="select_category" id="Mutual_chkbox" name="category_chkbox" value="Mutual Funds" <?php echo ( $category_chkbox === "Mutual Funds" ) ? 'checked' : ''; ?>>
                <label for="Mutual_chkbox"> Mutual Funds</label><br>            
            </div>
            <div class="col-xl-2 col-1 mb-30">
                <input type="radio" class="select_category" id="Proprietory_chkbox" name="category_chkbox" value="Proprietory Trades" <?php echo ($category_chkbox === "Proprietory Trades" ) ? 'checked' : ''; ?>>
                <label for="Proprietory_chkbox"> Proprietory Trades </label><br>            
            </div>
            <div class="col-xl-1 col-1 mb-30">
                <input type="radio" class="select_category" id="Others_chkbox" name="category_chkbox" value="Others" <?php echo ($category_chkbox === "Others" ) ? 'checked' : ''; ?>>
                <label for="Others_chkbox"> Others</label><br>            
            </div>
        
        </div>-->
        
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
    
    <?php if( !empty($cat_wise_trnvr) && count($cat_wise_trnvr) > 0 ){ ?>
        
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category Type</th>
                    <th>Buy Value</th>
                    <th>Sell Value</th>
                    <th>Net Value</th>
                    <th>Exchange</th>
                </tr>
            </thead>
            <tbody>
                
                <?php 
                
                $total_buy = 0 ;
                $total_sale = 0;
                $total_net = 0;
                
                foreach ($cat_wise_trnvr AS $cat_wise_trnvr_key=> $cat_wise_trnvr_value) {
                ?>

                <tr class="db_data">
                    <td><?php echo date('d M Y', strtotime($cat_wise_trnvr_value->market_date)); ?></td>
                    <td><?php echo $cat_wise_trnvr_value->category; ?></td>                    
                    <td>
                    <?php 
                        $total_buy = $total_buy + $cat_wise_trnvr_value->buy_value;
                        echo number_format($cat_wise_trnvr_value->buy_value, 2); 
                    ?>
                    </td>
                    <td>
                        <?php 
                        $total_sale = $total_sale + $cat_wise_trnvr_value->sell_value;
                        echo number_format($cat_wise_trnvr_value->sell_value, 2); 
                        ?>
                    </td>
                    <td>
                        <?php 
                        $net = $cat_wise_trnvr_value->buy_value - $cat_wise_trnvr_value->sell_value;
                        $total_net = $total_net + $net;
                        echo number_format( $net , 2); 
                        ?>
                    </td>
                    
                    <td><?php echo $cat_wise_trnvr_value->exchange; ?></td>
                    
                </tr>
                
                <?php } ?>
                
                <tr>
                    <td></td>
                    <td>Average</td>
                    <td><?php echo number_format( ( $total_buy/count($cat_wise_trnvr) ) , 2); ?></td>
                    <td><?php echo number_format( ( $total_sale/ count($cat_wise_trnvr) ) , 2); ?></td>
                    <td><?php echo number_format( ( $total_net/count($cat_wise_trnvr) ) , 2); ?></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td>Total</td>
                    <td><?php echo number_format( $total_buy , 2); ?></td>
                    <td><?php echo number_format( $total_sale , 2); ?></td>
                    <td><?php echo number_format( $total_net , 2); ?></td>
                </tr>                
                
            </tbody>
        </table>
        
    <?php if( !empty($category_chkbox) ){?>
    
    <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($cat_wise_trnvr) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
    <div class="row">
        <div class="col-xl-6 col-sm-12 col-12">

            <div class="chart_dsgn" id="buy_sale_chart" data-plot_data="buy_sale" data-plot_data_name="Buy Sale" data-colorz="green" data-full_screen="0"></div>             

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