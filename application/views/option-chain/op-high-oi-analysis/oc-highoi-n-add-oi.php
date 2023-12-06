<?php  $this->load->helper('function_helper'); ?>
<div class="container">
    <?php if (!empty($oc_high_oi_n_add_oi_data) && count($oc_high_oi_n_add_oi_data) > 0) { ?>
        <h2>Option Chain : Highest OI And Highest Change in OI -  Day Wise Analysis</h2>
    <?php } else { ?>
        <h2>No Data Available</h2>
    <?php } ?>

        
    <h3>Underlying Date : <?php echo date('d M Y', strtotime($date)); ?></h3>
    
    
    <form method="get" action="<?php echo base_url('option-chain/high-oi-and-high-change-in-oi'); ?>">
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
                 <a href="<?php echo base_url('option-chain/high-oi-and-high-change-in-oi'); ?>">Reset</a>
             </div>
         </div>
        
        
        <div class="row mb-30">

            <div class="col-xl-4 col-12 mb-30"> 

                <div class="dropdown">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        Custom Condition - <b>
                            <?php 
                            if($custom_condition==="bull"){  echo 'Bullish';}
                                else if($custom_condition==="bear"){ echo "Bearish";}
                            ?>
                        </b>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="bull"){echo 'active';} ?> " data-condition="bull" href="javascript:void(0)">Bullish</a>
                        <a class="dropdown-item apply_condition <?php if($custom_condition==="bear"){echo 'active';} ?> " data-condition="bear" href="javascript:void(0)">Bearish</a>                           
                    </div>
                </div>
                '
                <input type="hidden" class="custom_condition" name='custom_condition' <?php if(!empty($custom_condition)) { echo 'value="'.$custom_condition.'"'; } ?> >                    

            </div>

        </div>
            
    </form>

    
    
    <?php if (!empty($oc_high_oi_n_add_oi_data) && count($oc_high_oi_n_add_oi_data) > 0) { ?>

        <p>Analysis:</p> 
        
        <!--<div class="table-responsive">-->
        
            <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Company Symbol</th>
                    <th>Underlying Price</th>
                    
                    <?php for( $i=1; $i <= $total_expiry_count; $i++ ) {?>
                    
                    <th>Expiry <?php echo $i; ?></th>
                    <th>SP<?php echo $i; ?> in Call
                    <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Strike PriceFor Expiry ' . $i . ' in call side'; ?>">
                    </i>
                    </th>
                    <th>SP<?php echo $i; ?> in Put
                    <i class="fa fa-question-circle" data-toggle="tooltip" title="<?php echo 'Strike PriceFor Expiry ' . $i . ' in put side'; ?>">
                    </i>
                    </th>
                    
                    <?php } ?>
                    

                </tr>
            </thead>
            <tbody>

                <?php foreach ($oc_high_oi_n_add_oi_data AS $oc_high_oi_n_add_oi_data) { ?>

                    <tr>                                    

                        <td>   
                            <a href="<?php echo base_url() . 'daily-log/?company_id='.$oc_high_oi_n_add_oi_data['company_id'].'&company_symbol='.base64_url_encode($oc_high_oi_n_add_oi_data['company_symbol']).'&stock_date='. $date.'&stock_date_to=' . date('Y-m-d'); ?>">
                            <?php echo $oc_high_oi_n_add_oi_data['company_symbol']; ?>
                            </a>
                        </td>
                        <td>                            
                            <?php echo $oc_high_oi_n_add_oi_data['underlying_price']; ?>
                        </td> 
                        
                        <?php foreach($oc_high_oi_n_add_oi_data['data'] AS $oc_high_oi_n_add_oi_data_val){ ?>
                        
                        <td>
                            <a href="<?php echo base_url() . 'option-chain/stock-info?company_id='.$oc_high_oi_n_add_oi_data['company_id'].'&company_symbol='.base64_url_encode($oc_high_oi_n_add_oi_data['company_symbol']).'&sud='. $date.'&sed='. $oc_high_oi_n_add_oi_data_val['expiry_date']; ?>">
                            <?php echo date('d M Y', strtotime($oc_high_oi_n_add_oi_data_val['expiry_date'])); ?>
                            </a>
                        </td>
                        <td><?php echo $oc_high_oi_n_add_oi_data_val['strike_price_in_call']; ?></td>
                        <td><?php echo $oc_high_oi_n_add_oi_data_val['strike_price_in_put']; ?></td>
                        
                        <?php } ?>

                    </tr>

                <?php } ?>


            </tbody>
        </table>
            
        <!--</div>-->

    <?php } else { ?>

        <div>
            <div class="alert alert-danger">
                <strong>No Data Available </strong> 
            </div>
        </div>

    <?php } ?>
        
</div>