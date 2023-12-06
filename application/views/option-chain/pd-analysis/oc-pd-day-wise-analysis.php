<div class="container">
    <?php if (!empty($oc_pd_data) && count($oc_pd_data) > 0) { ?>
        <h2>Option Chain Premium Decay Day Wise Analysis</h2>
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
            
            <input type="hidden" class="underlying_date_end" name="date" value="<?php echo empty($date) ? date('Y-m-d') : $date; ?>">
            
            <input type="submit" class="apply-btn-actionz mb-30" value="Apply">
            
            
            
            <div class="row mb-30">

                 <div class="col-xl-4 col-12 mb-30"> 
            
                <div class="dropdown">
                    <button type="button" class="btn  btn-info dropdown-toggle" data-toggle="dropdown">
                        Sort By <?php if(!empty($put_avg_decay)){ echo ' - Put Avg Decay - ' . $put_avg_decay;} if(!empty($call_avg_decay)){ echo ' - Call Avg Decay - ' . $call_avg_decay;} ?>
                    </button>
                    <div class="dropdown-menu">
                        <h5 class="dropdown-header">Put Avg Decay</h5>
                        <a class="dropdown-item sort_by" data-sortby="put_avg_decay" data-select="high" href="javascript:void(0)">High to Low</a>
                        <a class="dropdown-item sort_by" data-sortby="put_avg_decay" data-select="low" href="javascript:void(0)">Low To High</a>
                        <h5 class="dropdown-header">Call Avg Decay</h5>
                        <a class="dropdown-item sort_by" data-sortby="call_avg_decay" data-select="high" href="javascript:void(0)">High to Low</a>
                        <a class="dropdown-item sort_by" data-sortby="call_avg_decay" data-select="low" href="javascript:void(0)">Low To High</a>
                    </div>

                    <input type="hidden" class="sort_by_selection">

                </div>
            </div>

                <div class="col-xl-4 col-12 mb-30"> 

                    <div class="dropdown">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                            Custom Condition - <b><?php if($custom_condition==="putgtcall"){ echo 'Put Avg Decay > Call Avg Decay';}else if($custom_condition==="callgtput"){ echo "Call Avg Decay > Put Avg Decay";}?></b>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item apply_condition <?php if($custom_condition==="putgtcall"){echo 'active';} ?> " data-condition="putgtcall" href="javascript:void(0)">Put Avg Decay > Call Avg Decay</a>
                            <a class="dropdown-item apply_condition <?php if($custom_condition==="callgtput"){echo 'active';} ?> " data-condition="callgtput" href="javascript:void(0)">Call Avg Decay > Put Avg Decay</a>                            
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
        
        <?php if (!empty($oc_pd_data) && count($oc_pd_data) > 0) { ?>
        
        <p>Analysis:</p> 
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Company Symbol</th>
                    <th>Expiry Date</th>
                    <th>Underlying Date Start</th>
                    <th>Put Avg Decay</th>
                    <th>Call Avg Decay</th>

                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($oc_pd_data AS $oc_pd_data) { ?>

                    <tr>                                    

                        <td>
                            
                            <a href="<?php echo base_url() . 'daily-log/?company_id='.$oc_pd_data->company_id.'&company_symbol='.$oc_pd_data->company_symbol.'&stock_date='. $date.'&stock_date_to=' . date('Y-m-d'); ?>">
                            
                                <?php echo $oc_pd_data->company_symbol; ?>
                                
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo base_url() . 'option-chain/stock-info?company_id='.$oc_pd_data->company_id.'&company_symbol='.$oc_pd_data->company_symbol.'&sud='. $oc_pd_data->underlying_date_end.'&sed='. $oc_pd_data->expiry_date; ?>">
                                
                                <?php echo date('d M Y', strtotime($oc_pd_data->expiry_date)); ?>
                                
                            </a>
                        </td>
                        <td><?php echo date('d M Y', strtotime($oc_pd_data->underlying_date_start)); ?></td>
                        <td><?php echo $oc_pd_data->put_avg_decay; ?></td>
                        <td><?php echo $oc_pd_data->call_avg_decay; ?></td>


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