<?php $this->load->helper('function_helper'); ?>

<div class="container">
    <?php if( !empty($oc_iv_data) && !empty($oc_iv_data) && count($oc_iv_data) > 0 ){ ?>
    <h2><?php echo 'Analysis of <b>' . $company_name . '</b> - Implied Volatility : ' . $live ?></h2>
    <?php } ?>
    
    
    <form method="get" action="<?php echo base_url('option-chain/iv-analysis/'); ?>">
        <div class="row mb-30 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($underlying_date) ? date('Y-m-d') :$underlying_date; ?>"  onchange="changeSectorDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($underlying_date_to) ? date('Y-m-d') :$underlying_date_to; ?>"  onchange="changeSectorDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>

        <div class="row mb-60 mt-60">
            
            <div class="col-xl-12 col-12">
            
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <?php echo empty($searching_expiry_date) ? 'Select Expiry Date' : 'Expiry Date - ' . date('d M Y', strtotime($searching_expiry_date) ); ?>
                    </button>
                    <div class="dropdown-menu sector-dropdown-menu">
                        
                        <?php foreach( $expiry_date_arr AS $expiry_date_arr_val ){?>
                        
                        <a class="dropdown-item select_expiry <?php echo ( $searching_expiry_date === $expiry_date_arr_val) ? 'sel-sec' :'' ?>" href="javascript:void(0)" data-expiry="<?php echo $expiry_date_arr_val; ?>">
                            <?php echo date('d M Y', strtotime($expiry_date_arr_val) ); ?>
                        </a>
                        
                        <?php } ?>
                        
                        <a class="dropdown-item select_expiry <?php echo (  empty($searching_expiry_date) ) ? 'sel-sec' :'' ?>"" href="javascript:void(0)" data-expiry="">
                            All Expiry Date                       
                        </a>
                        
                        
                    </div>
                </div>
                
            </div>
            
        </div>
        
        <input type='hidden' class='company_id' name='company_id' value='<?php echo $company_id; ?>'>
        <input type='hidden' class='company_symbol' name='company_symbol' value='<?php echo base64_url_encode($company_symbol); ?>'>

        <input type="hidden" class="underlying_date" name="underlying_date" value="<?php echo empty($underlying_date) ? date('Y-m-d') :$underlying_date; ?>">
        <input type="hidden" class="underlying_date_to" name="underlying_date_to" value="<?php echo empty($underlying_date_to) ? date('Y-m-d') :$underlying_date_to; ?>">

        <input type='hidden' name='live' value='<?php echo $live; ?>'>
        
        <input type="hidden" class="expiry" name="expiry" value="">
        
        <input type="submit" class="apply-btn-actionz mb-30" value="Apply">

    </form>
    
    <?php if(empty($live)){ ?>
    
    <div class="row mb-30">
        <div class="col-xl-2 col-12 mb-10">
            <a href="<?php echo base_url() . 'option-chain/iv-analysis/' . $company_id . '/' . base64_url_encode($company_symbol) . '/live'; ?>">Live Analysis</a>
        </div>
    </div>
    
    <?php } ?>
    
    <?php 
    if( !empty($oc_iv_data) && count($oc_iv_data) > 0 ){ ?>
    
    <a href="#chart_start">Go to Chart</a>
    
    <p class="mt-20">Analysis:</p>            
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Underlying Date</th>
                <th>Company Symbol</th>
                <th>Trading Days</th>
                <th>Expiry Date</th>
                <th>Underlying Price</th>
                <th>Strike Price</th>
                <th>Calls IV</th>
                <th>Puts IV</th>
                <th>Strike Price With Highest Oi in call</th>
                <th>Strike Price With Highest Oi in put</th>
                <th>Bearish Probability</th>
                <th>Close Above Target Bearish</th>
                <th>Bullish Probability</th>
                <th>Close Above Target Bullish</th>
               
            </tr>
        </thead>
        <tbody>

            <?php foreach ($oc_iv_data AS $oc_iv_data_key=>$oc_iv_data_value) { ?>

                <tr>
                    <td><?php echo date('d M Y', strtotime($oc_iv_data_value->underlying_date)); ?></td>
                    
                    <td><?php echo $oc_iv_data_value->company_symbol; ?></td>
                    <td><?php echo $oc_iv_data_value->trading_days; ?></td>
                    <td><?php echo date('d M Y', strtotime($oc_iv_data_value->expiry_date)); ?></td>
                    <td><?php echo $oc_iv_data_value->underlying_price; ?></td>
                    <td><?php echo $oc_iv_data_value->strike_price; ?></td>
                    <td><?php echo $oc_iv_data_value->calls_iv; ?></td>
                    <td><?php echo $oc_iv_data_value->puts_iv; ?></td>
                    <td><?php echo $oc_iv_data_value->strike_price_with_highest_oi_in_call; ?></td>
                    <td><?php echo $oc_iv_data_value->strike_price_with_highest_oi_in_put; ?></td>
                    <td>
                        <?php echo $oc_iv_data_value->bearish_probability; ?>
                        <?php if( $oc_iv_data_key!=0 and $oc_iv_data_value->bearish_probability > $oc_iv_data[$oc_iv_data_key-1]->bearish_probability ) { ?>
                        <i class="fa fa-arrow-up red-bull" data-toggle="tooltip" title="Bearish Probability increases compared to previous trading date (<?php echo date('d M Y', strtotime($oc_iv_data[$oc_iv_data_key-1]->underlying_date)); ?>)"></i>
                        <?php }?>
                    </td>
                    <td><?php echo $oc_iv_data_value->close_above_target_bearish; ?></td>
                    <td>
                        <?php echo $oc_iv_data_value->bullish_probability; ?>
                        <?php if( $oc_iv_data_key!=0 and $oc_iv_data_value->bullish_probability > $oc_iv_data[$oc_iv_data_key-1]->bullish_probability ) { ?>
                        <i class="fa fa-arrow-up green-bull" data-toggle="tooltip" title="Bullish Probability increases compared to previous trading date (<?php echo date('d M Y', strtotime($oc_iv_data[$oc_iv_data_key-1]->underlying_date)); ?>)"></i>
                        <?php }?>
                    </td>
                    <td><?php echo $oc_iv_data_value->close_above_target_bullish; ?></td>
                    
                    
                </tr>

            <?php } ?>

        </tbody>
    </table>
    
    <!-- Chart Start -->
    
    <div id="market_running" data-val='<?php echo $live; ?>' ></div>
    
    <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($oc_iv_data) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
    <div class="row" id="chart_start">
            
        <div class="col-xl-12 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="bull_bear_probability_chart" data-plot_data="bull_bear_probability" data-plot_data_name="Bull Bear Probability" data-colorz="green" data-full_screen="0"></div> 
        </div>
        
    </div>
    
    <!-- Chart End -->
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available for <?php echo $company_name; ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>

