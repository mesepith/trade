
<div class="container">
    <?php if (!empty($oc_iv_data) && count($oc_iv_data) > 0) { ?>
        <h2>Option Chain Implied Volatility Day Wise Analysis</h2>
    <?php } else { ?>
        <h2>No Data Available</h2>
    <?php } ?>

    <h3>Date : <?php echo date('d M Y', strtotime($date)); ?></h3>


    <form method="get" action="<?php echo base_url($action_url); ?>">
        <div class="row mb-30 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($date) ? date('Y-m-d') : $date; ?>"  onchange="changeSectorDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>

        <input type="hidden" class="underlying_date" name="date" value="<?php echo empty($date) ? date('Y-m-d') : $date; ?>">
        
        
        <div class="row mb-30">
            
            
            <?php
                $bullish_probability_min = !empty($bullish_probability_min) ? $bullish_probability_min : 0;
                $bullish_probability_max = !empty($bullish_probability_max) ? $bullish_probability_max : 100;
            ?>
            
            <div class="col-xl-4 col-12 mb-30">                 
                
                <p>
                    <label for="bullish_probability_slider">
                        <span>Bullish Probability Range:</span><span class="dtq__selected_date">  : </span>
                    </label>
                    <input type="text" id="bullish_probability_slider" style="border:0; color:#d062cc; font-weight:bold; font-size: 26px;">
                </p>
                <div id="slider-3" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 0%; width: 100%;"></div><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;"></a><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 100%;"></a></div>                                
                
                <input type="hidden" <?php if(!empty($bullish_probability_min) ||(!empty($bullish_probability_max))){ echo 'name="bullish_probability_min"';} ?> class="bullish_probability_min" value="<?php echo $bullish_probability_min; ?>">
                <input type="hidden" <?php if(!empty($bullish_probability_min) ||(!empty($bullish_probability_max))){ echo 'name="bullish_probability_max"';} ?> class="bullish_probability_max" value="<?php echo $bullish_probability_max; ?>">
            </div>
            
        </div>
        
        
        <div class="row mb-30">
            
            
            <?php
                $bearish_probability_min = !empty($bearish_probability_min) ? $bearish_probability_min : 0;
                $bearish_probability_max = !empty($bearish_probability_max) ? $bearish_probability_max : 100;
            ?>
            
            <div class="col-xl-4 col-12 mb-30">                 
                
                <p>
                    <label for="bearish_probability_slider">
                        <span>Bearish Probability Range:</span>
                    </label>
                    <input type="text" id="bearish_probability_slider" style="border:0; color:#d062cc; font-weight:bold; font-size: 26px;">
                </p>
                <div id="bearish-slider-3" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" aria-disabled="false"><div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 0%; width: 100%;"></div><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;"></a><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 100%;"></a></div>                                
                
                <input type="hidden" <?php if(!empty($bearish_probability_min) ||(!empty($bearish_probability_max))){ echo 'name="bearish_probability_min"';} ?> class="bearish_probability_min" value="<?php echo $bearish_probability_min; ?>">
                <input type="hidden" <?php if(!empty($bearish_probability_min) ||(!empty($bearish_probability_max))){ echo 'name="bearish_probability_max"';} ?> class="bearish_probability_max" value="<?php echo $bearish_probability_max; ?>">
            </div>
            
        </div>
        
        
        <input type="submit" class="apply-btn-actionz mb-30" value="Apply">
        
        <div class="row mb-30">
        
            <div class="col-xl-4 col-12 mb-30"> 
            
                <div class="dropdown">
                    <button type="button" class="btn  btn-info dropdown-toggle" data-toggle="dropdown">
                        Sort By <?php if(!empty($bullish_probability)){ echo ' - Bullish Probability - ' . $bullish_probability;} if(!empty($bearish_probability)){ echo ' - Bearish Probability - ' . $bearish_probability;} ?>
                    </button>
                    <div class="dropdown-menu">
                        <h5 class="dropdown-header">Bullish Probability</h5>
                        <a class="dropdown-item sort_by" data-sortby="bullish_probability" data-select="high" href="javascript:void(0)">High to Low</a>
                        <a class="dropdown-item sort_by" data-sortby="bullish_probability" data-select="low" href="javascript:void(0)">Low To High</a>
                        <h5 class="dropdown-header">Bearish Probability</h5>
                        <a class="dropdown-item sort_by" data-sortby="bearish_probability" data-select="high" href="javascript:void(0)">High to Low</a>
                        <a class="dropdown-item sort_by" data-sortby="bearish_probability" data-select="low" href="javascript:void(0)">Low To High</a>
                    </div>

                    <input type="hidden" class="sort_by_selection">

                </div>
            </div>
            
            <div class="col-xl-4 col-12 mb-30"> 
            
                <div class="dropdown">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        Custom Condition - <b><?php if($custom_condition==="bullgtbear"){ echo 'Bullish Probability > Bearish Probability';}else if($custom_condition==="beargtbull"){ echo "Bearish Probability > Bullish Probability";}?></b>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="bullgtbear"){echo 'active';} ?> " data-condition="bullgtbear" href="javascript:void(0)">Bullish Probability > Bearish Probability</a>
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="beargtbull"){echo 'active';} ?>" data-condition="beargtbull" href="javascript:void(0)">Bearish Probability > Bullish Probability</a>
                    </div>
                </div>
                '
                <input type="hidden" class="custom_condition" name='custom_condition' <?php if(!empty($custom_condition)) { echo 'value="'.$custom_condition.'"'; } ?> >
                
            </div>
            
        </div>
        
        
        <?php if($live){ ?>
        
        <div class="row mb-30">
            
            <div class="col-xl-3 col-12 mb-30"> 
                
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Time : </span><span><?php echo $script_start_time; ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($script_start_time_result_arr AS $script_start_time_result_arr_value){?>                      
                        <a class="dropdown-item change_script_start_time" data-script_start_time="<?php echo $script_start_time_result_arr_value->script_start_time; ?>">
                          <?php echo $script_start_time_result_arr_value->script_start_time; ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            
            <input type='hidden' class='script_start_time' name='script_start_time' value='<?php echo $script_start_time; ?>'>
            
        </div>
        
        <?php } ?>
        
        
    </form>
    
    <div class="row  mb-30">
        <?php if(!empty($oc_iv_data[0]->trading_days)){ ?>
        <div><b>Trading Days left on <?php echo date('d M Y', strtotime($date)) . ' is ' . $oc_iv_data[0]->trading_days; ?></b></div>
        <?php }?>
        
    </div>
    <div class="row  mb-30">
        
        <?php if(!empty($oc_iv_data[0]->expiry_date)){ ?>
        <div><b>Expiry Date <?php echo date('d M Y', strtotime($oc_iv_data[0]->expiry_date)); ?></b></div>
        <?php }?>
        
    </div>

    <?php if (!empty($oc_iv_data) && count($oc_iv_data) > 0) { ?>

        <p>Analysis:</p>            

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Company Symbol</th>
                    <th>Underlying Price</th>
                    <th>Strike Price</th>
                    <th>Calls IV</th>
                    <th>Puts IV</th>
                    <th>Strike Price With Highest Oi in call</th>
                    <th>Strike Price With Highest Oi in put</th>
                    <th class="<?php if(!empty($bearish_probability)){ echo 'filter-column-col';} ?>" >Bearish Probability</th>
                    <th>Close Above Target Bearish</th>
                    <th class="<?php if(!empty($bullish_probability)){ echo 'filter-column-col';} ?>">Bullish Probability</th>
                    <th>Close Above Target Bullish</th>

                </tr>
            </thead>
            <tbody>

                <?php foreach ($oc_iv_data AS $oc_iv_data_value) { ?>

                    <tr>                                    

                        <td>
                            
                            <a href="<?php echo base_url() . 'daily-log/?company_id='.$oc_iv_data_value->company_id.'&company_symbol='.$oc_iv_data_value->company_symbol.'&stock_date='. $date.'&stock_date_to=' . date('Y-m-d'); ?>">
                            
                                <?php echo $oc_iv_data_value->company_symbol; ?>
                                
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo base_url() . 'option-chain/stock-info?company_id='.$oc_iv_data_value->company_id.'&company_symbol='.$oc_iv_data_value->company_symbol.'&sud='. $date.'&sed='. $oc_iv_data[0]->expiry_date; ?>">
                                <?php echo $oc_iv_data_value->underlying_price; ?>
                            </a>
                        </td>
                        <td>  <?php echo $oc_iv_data_value->strike_price; ?> </td>
                        <td><?php echo $oc_iv_data_value->calls_iv; ?></td>
                        <td><?php echo $oc_iv_data_value->puts_iv; ?></td>
                        <td><?php echo $oc_iv_data_value->strike_price_with_highest_oi_in_call; ?></td>
                        <td><?php echo $oc_iv_data_value->strike_price_with_highest_oi_in_put; ?></td>
                        <td class="<?php if(!empty($bearish_probability)){ echo 'filter-column-col';} ?>" ><?php echo $oc_iv_data_value->bearish_probability; ?></td>
                        <td><?php echo $oc_iv_data_value->close_above_target_bearish; ?></td>
                        <td class="<?php if(!empty($bullish_probability)){ echo 'filter-column-col';} ?>"><?php echo $oc_iv_data_value->bullish_probability; ?></td>
                        <td><?php echo $oc_iv_data_value->close_above_target_bullish; ?></td>


                    </tr>

                <?php } ?>

            </tbody>
        </table>

    <?php } else { ?>

        <div>
            <div class="alert alert-danger">
                <strong>No Data Available </strong> 
            </div>
        </div>

    <?php } ?>

</div>
