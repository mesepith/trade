<?php  $this->load->helper('function_helper'); ?>

<div class="container">
    <?php if (!empty($oc_op_data) && count($oc_op_data) > 0) { ?>
        <h2>Option Chain Option Pain Day Wise Analysis</h2>
    <?php } else { ?>
        <h2>No Data Available</h2>
    <?php } ?>

    <h3>Underlying Date : <?php echo date('d M Y', strtotime($date)); ?></h3>
    
     <form method="get" action="<?php echo base_url('option-chain/op-analysis/day-wise'); ?>">
        <div class="row mb-30 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($date) ? date('Y-m-d') : $date; ?>"  onchange="changeSectorDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            
            <input type="hidden" class="underlying_date_end" name="date" value="<?php echo empty($date) ? date('Y-m-d') : $date; ?>">
            
        </div>
         
         <div class="row">

             <div class="col-xl-2 col-12 mb-10">
                 <input type="submit" class="apply-btn-actionz mb-30" value="Apply">
             </div>

             <div class="col-xl-2 col-12 mb-10">
                 <a href="<?php echo base_url('option-chain/op-analysis/day-wise'); ?>">Reset</a>
             </div>
         </div>
        
            
        <div class="row mb-30">

            <div class="col-xl-4 col-12 mb-30"> 

                <div class="dropdown">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        Custom Condition - <b>
                            <?php 
                            if($custom_condition==="current_exp_bull"){  echo 'Current Expiry Bullish';}
                                else if($custom_condition==="current_exp_bear"){ echo "Current Expiry Bearish";}
                                else if($custom_condition==="next_exp_bull"){ echo "Next Expiry Bullish";}
                                else if($custom_condition==="next_exp_bear"){ echo "Next Expiry Bearish";}
                                else if($custom_condition==="all_exp_bull"){ echo "All Expiry Bullish";}
                                else if($custom_condition==="all_exp_bear"){ echo "All Expiry Bearish";}
                            ?>
                        </b>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="current_exp_bull"){echo 'active';} ?> " data-condition="current_exp_bull" href="javascript:void(0)">Current Expiry Bullish</a>
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="current_exp_bear"){echo 'active';} ?> " data-condition="current_exp_bear" href="javascript:void(0)">Current Expiry Bearish</a>                            
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="next_exp_bull"){echo 'active';} ?> " data-condition="next_exp_bull" href="javascript:void(0)">Next Expiry Bullish</a>
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="next_exp_bear"){echo 'active';} ?> " data-condition="next_exp_bear" href="javascript:void(0)">Next Expiry Bearish</a>                            
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="all_exp_bull"){echo 'active';} ?> " data-condition="all_exp_bull" href="javascript:void(0)">All Expiry Bullish</a>
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="all_exp_bear"){echo 'active';} ?> " data-condition="all_exp_bear" href="javascript:void(0)">All Expiry Bearish</a>                            
                    </div>
                </div>
                '
                <input type="hidden" class="custom_condition" name='custom_condition' <?php if(!empty($custom_condition)) { echo 'value="'.$custom_condition.'"'; } ?> >                    

            </div>

        </div>
        
            
            
    </form>

    <?php if (!empty($oc_op_data) && count($oc_op_data) > 0) { ?>

        <p>Analysis:</p> 

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Company Symbol</th>
                    <th>Underlying Price</th>
                    <th class="col-bold-blue">Current Expiry Date <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Nearest expiry date for underlying date ' . date('d M Y', strtotime($date)); ?>"></i></th>
                    <th class="col-bold-blue">Strike Price 1 
                        <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Strike Price with highest sum of call and put oi for the expiry ' . date('d M Y', strtotime($oc_op_data[0]->current_expiry_date)); ?>">
                        </i>
                    </th>
                    <th class="col-bold-blue">Strike Price 2
                        <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Strike Price with second highest sum of call and put oi for the expiry ' . date('d M Y', strtotime($oc_op_data[0]->current_expiry_date)); ?>">
                        </i>
                    </th>
                    <th class="col-bold-blue">Strike Price 3
                        <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Strike Price with third highest sum of call and put oi for the expiry ' . date('d M Y', strtotime($oc_op_data[0]->current_expiry_date)); ?>">
                        </i>
                    </th>
                    <th class="col-bold-darkcyan">Next Expiry Date <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Next expiry date for underlying date ' . date('d M Y', strtotime($date)); ?>"></i></th>
                    <th class="col-bold-darkcyan">Strike Price 1
                        <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Strike Price with highest sum of call and put oi for the expiry ' . date('d M Y', strtotime($oc_op_data[0]->next_expiry_date)); ?>">
                        </i>
                    </th>
                    <th class="col-bold-darkcyan">
                        Strike Price 2
                        <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Strike Price with second highest sum of call and put oi for the expiry ' . date('d M Y', strtotime($oc_op_data[0]->next_expiry_date)); ?>">
                        </i>
                    </th>
                    <th class="col-bold-darkcyan">
                        Strike Price 3
                        <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Strike Price with third sum of call and put oi for the expiry ' . date('d M Y', strtotime($oc_op_data[0]->next_expiry_date)); ?>">
                        </i>
                    </th>

                </tr>
            </thead>
            <tbody>

                <?php foreach ($oc_op_data AS $oc_op_data) { ?>

                    <tr>                                    

                        <td >
                            <a href="<?php echo base_url() . 'daily-log/?company_id='.$oc_op_data->company_id.'&company_symbol='.base64_url_encode($oc_op_data->company_symbol).'&stock_date='. $date.'&stock_date_to=' . date('Y-m-d'); ?>">
                            <?php echo $oc_op_data->company_symbol; ?>
                            </a>
                        </td>
                        <td><?php echo $oc_op_data->underlying_price; ?></td>
                        <td class="col-bold-blue">
                            <a href="<?php echo base_url() . 'option-chain/stock-info?company_id='.$oc_op_data->company_id.'&company_symbol='.base64_url_encode($oc_op_data->company_symbol).'&sud='. $date.'&sed='. $oc_op_data->current_expiry_date; ?>">
                            <?php echo date('d M Y', strtotime($oc_op_data->current_expiry_date)); ?>
                            </a>
                        </td>                        
                        <td  class="col-bold-blue" data-toggle="tooltip" title="<?php echo 'Strike Price 1 has highest sum of oi for the expiry ' . date('d M Y', strtotime($oc_op_data->current_expiry_date)); ?>">
                            <?php echo $oc_op_data->strike_price_1_current_exp; ?>
                        </td>
                        <td  class="col-bold-blue" data-toggle="tooltip" title="<?php echo 'Strike Price 2 has second highest sum of oi for the expiry ' . date('d M Y', strtotime($oc_op_data->current_expiry_date)); ?>">
                            <?php echo $oc_op_data->strike_price_2_current_exp; ?>
                        </td>
                        <td  class="col-bold-blue" data-toggle="tooltip" title="<?php echo 'Strike Price 3 has third highest sum of oi for the expiry ' . date('d M Y', strtotime($oc_op_data->current_expiry_date)); ?>">
                            <?php echo $oc_op_data->strike_price_3_current_exp; ?>
                        </td>
                        <td class="col-bold-darkcyan">
                            <a href="<?php echo base_url() . 'option-chain/stock-info?company_id='.$oc_op_data->company_id.'&company_symbol='.base64_url_encode($oc_op_data->company_symbol).'&sud='. $date.'&sed='. $oc_op_data->next_expiry_date; ?>">
                            <?php echo date('d M Y', strtotime($oc_op_data->next_expiry_date)); ?>
                            </a>
                        </td>
                        <td  class="col-bold-darkcyan" data-toggle="tooltip" title="<?php echo 'Strike Price 1 has highest sum of oi for the expiry ' . date('d M Y', strtotime($oc_op_data->next_expiry_date)); ?>">
                            <?php echo $oc_op_data->strike_price_1_next_exp; ?>
                        </td>
                        <td  class="col-bold-darkcyan" data-toggle="tooltip" title="<?php echo 'Strike Price 2 has second highest sum of oi for the expiry ' . date('d M Y', strtotime($oc_op_data->next_expiry_date)); ?>">
                            <?php echo $oc_op_data->strike_price_2_next_exp; ?>
                        </td>
                        <td  class="col-bold-darkcyan" data-toggle="tooltip" title="<?php echo 'Strike Price 3 has third highest sum of oi for the expiry ' . date('d M Y', strtotime($oc_op_data->next_expiry_date)); ?>">
                            <?php echo $oc_op_data->strike_price_3_next_exp; ?>
                        </td>


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