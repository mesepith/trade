<?php 

$this->load->helper('function_helper');
setlocale(LC_MONETARY,"en_IN.utf8");

?>
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
    .mt-20{margin-top: 20px;}
    
    .green-up-arr{
        color: green;
        font-size: 25px;
    }
    .red-down-arr{
        color: red;
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

    #close_price_chart{
        display: block;
        margin: 0 auto;
    }
</style>

<div class="container">
    <?php if( !empty($stock_detail) && count($stock_detail) > 0 ){ ?>
    <h2><?php echo 'Analysis of <b>' . $company_name . '</b> (' . $company_symbol . ') on ' . date('d-M-Y', strtotime($stock_date)); ?></h2>
    <?php } ?>
    <?php if(!empty($no_data_for_manual_date_msg)){ ?> 
        <div class="mt-20">
            <div class="alert alert-danger">
                <strong><?php echo $no_data_for_manual_date_msg; ?></strong> 
            </div>
        </div>
    
    <?php } ?>
    
    <form method="get" action="<?php echo base_url('whole-day-data/'); ?>">
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-30">
                Select Date
            </div>

            <div class="col-xl-3 col-12 mb-30"> 


                <div class="col-xl-2 col-12 mb-30 htm-date-container"> 
                    <input class="htm-date" id="stock_date" readonly="readonly" value="<?php echo empty($stock_date) ? date('Y-m-d') :$stock_date; ?>"  onchange="changeStockDate(event);">
                    <span class="open-date-button">
                        <button type="button">📅</button>
                    </span>
                </div>


            </div>

        </div>

        <input type='hidden' class='company_id' name='company_id' value='<?php echo $company_id; ?>'>
        <input type='hidden' class='company_symbol' name='company_symbol' value='<?php echo base64_url_encode($company_symbol); ?>'>

        <input type="hidden" class="stock_date" name="stock_date" value="">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <?php if( !empty($stock_detail) && count($stock_detail) > 0 ){ ?>
    
    <p>Analysis time wise:</p>            
    <p>Open Price : <?php echo $open_price; ?></p> 
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Time</th>
                <th>Last Price</th>
                <th>VWAP</th>
                <th>Total Traded Volume</th>
                <th>Delivery Quantity</th>
                <th>Delivery to Traded Quantity</th>
                <th>Total Buy Quantity</th>
                <th>Total sell Quantity</th>
                <th>Total Traded Value</th>
                <th>Money Flow</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($stock_detail AS $stock_detail_key=>$stock_detail_value) { ?>

                <tr>
                    <td><?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?></td>
                    <td>
                        <?php echo (empty($stock_detail_value->last_price) ? 'NA' : $stock_detail_value->last_price); ?>
                        
                        <?php if(!empty($stock_detail_value->price_change)){?>
                        
                            <br/>
                            <span class="<?php echo ($stock_detail_value->price_change>0) ? 'col-green' : 'col-red' ?>">
                                <?php echo $stock_detail_value->price_change . " (".$stock_detail_value->price_change_in_p."%) "; ?>
                            </span>
                            <br/>
                        
                        <?php } ?>
                    
                        <?php if( $stock_detail_key!=0 and (!empty($stock_detail_value->last_price)) and $stock_detail_value->last_price > $stock_detail[$stock_detail_key-1]->last_price ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Last price increases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and (!empty($stock_detail_value->last_price)) and $stock_detail_value->last_price < $stock_detail[$stock_detail_key-1]->last_price ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Last price decreases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        <?php }?>
                    
                    </td>
                    <td>
                        <?php echo (empty($stock_detail_value->vwap) ? 'NA' : $stock_detail_value->vwap); ?>
                    
                        <?php if( $stock_detail_key!=0 and (!empty($stock_detail_value->vwap)) and $stock_detail_value->vwap > $stock_detail[$stock_detail_key-1]->vwap ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Last price increases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and (!empty($stock_detail_value->vwap)) and $stock_detail_value->last_price < $stock_detail[$stock_detail_key-1]->vwap ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Last price decreases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        <?php }?>
                    
                    </td>
                    <td>
                        <?php echo money_format('%!.0n', $stock_detail_value->total_traded_volume); ?>
                        
                        <?php if( $stock_detail_key!=0 and $stock_detail_value->total_traded_volume > $stock_detail[$stock_detail_key-1]->total_traded_volume ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Total Traded Volume increases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and $stock_detail_value->total_traded_volume < $stock_detail[$stock_detail_key-1]->total_traded_volume ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Total Traded Volume decreases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        <?php }?>
                        
                        <?php if( $stock_detail_key!=0 ){
                            
                            echo "(" . percentOfTwoNumber( $stock_detail_value->total_traded_volume, $stock_detail[$stock_detail_key-1]->total_traded_volume ) . "%)";
                        
                        }?>
                    
                    </td>
                    <td>
                        <?php echo money_format('%!.0n', $stock_detail_value->delivery_quantity); ?>
                    
                        <?php if( $stock_detail_key!=0 and $stock_detail_value->delivery_quantity > $stock_detail[$stock_detail_key-1]->delivery_quantity ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Delivery Quantity increases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and $stock_detail_value->delivery_quantity < $stock_detail[$stock_detail_key-1]->delivery_quantity ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Delivery Quantity decreases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        <?php }?>
                        
                        <br/>
                        <?php if( $stock_detail_key!=0 ){
                            
                            echo "(" . percentOfTwoNumber( $stock_detail_value->delivery_quantity, $stock_detail[$stock_detail_key-1]->delivery_quantity ) . "%)";
                        
                        }?>
                        
                    </td>
                    <td>
                        <?php echo $stock_detail_value->delivery_to_traded_quantity; ?>
                        
                        <?php if( $stock_detail_key!=0 and $stock_detail_value->delivery_to_traded_quantity > $stock_detail[$stock_detail_key-1]->delivery_to_traded_quantity ) { ?>
                        <i class="fa fa-arrow-up green-up-arr" data-toggle="tooltip" title="Delivery to Traded Quantity increases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        
                        <?php }else if( $stock_detail_key!=0 and $stock_detail_value->delivery_to_traded_quantity < $stock_detail[$stock_detail_key-1]->delivery_to_traded_quantity ) { ?>
                        <i class="fa fa-arrow-down red-down-arr" data-toggle="tooltip" title="Delivery to Traded Quantity decreases at <?php echo date('h:i:s A', strtotime($stock_detail_value->stock_time)); ?> compared to previous trading time (<?php echo date('h:i:s A', strtotime($stock_detail[$stock_detail_key-1]->stock_time)); ?>)"></i>
                        <?php }?>
                        
                        <br/>
                        <?php if( $stock_detail_key!=0 ){
                            
                            echo "(" . percentOfTwoNumber( $stock_detail_value->delivery_to_traded_quantity, $stock_detail[$stock_detail_key-1]->delivery_to_traded_quantity ) . "%)";
                        
                        }?>
                        
                    </td>
                    <td>
                        <?php 
                            echo money_format('%!.0n', $stock_detail_value->total_buy_quantity); 
                            
                            if( $stock_detail_key!=0 ){
                            
                                $tbq_diff_percnt = percentOfTwoNumber( $stock_detail_value->total_buy_quantity, $stock_detail[$stock_detail_key-1]->total_buy_quantity );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($tbq_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $tbq_diff_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    <td>
                        <?php 
                            echo money_format('%!.0n', $stock_detail_value->total_sell_quantity); 
                            
                            if( $stock_detail_key!=0 ){
                            
                                $tsq_diff_percnt = percentOfTwoNumber( $stock_detail_value->total_sell_quantity, $stock_detail[$stock_detail_key-1]->total_sell_quantity );
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($tsq_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $tsq_diff_percnt . "%)";
                        
                        }?>
                        </span>
                        
                    </td>
                    <td>
                        <?php 
                        
                            echo money_format('%!.0n', $stock_detail_value->total_traded_value);
                            
                            if( $stock_detail_key!=0 ){
                            
                                $ttv_diff_percnt = percentOfTwoNumber( $stock_detail_value->total_traded_value, $stock_detail[$stock_detail_key-1]->total_traded_value );
                            
                        ?>
                        
                        <br/>
                        <span class="<?php echo ($ttv_diff_percnt>0) ? 'col-green' : 'col-red' ?>">
                        
                        <?php
                            
                            echo "(" . $ttv_diff_percnt . "%)";
                        
                        }?>
                        </span>
                    </td>
                    
                    <?php 
                        
                        $money_flow = 0;
                    
                        if( $stock_detail_key!=0){ 
                        
                            $money_flow_calc = $stock_detail_value->delivery_quantity * $stock_detail_value->vwap;
                            
                            if( $stock_detail_value->last_price > $stock_detail[$stock_detail_key-1]->last_price ) {
                                
                                $money_flow = $money_flow_calc;
                                
                            }else{
                                
                                $money_flow = -$money_flow_calc;
                            }
                            
                            $stock_detail[$stock_detail_key]->money_flow = $money_flow;
                            
                        }
                        
                    ?>
                    
                    <td class="<?php echo ($money_flow>0) ? 'col-green' : 'col-red' ?>" >
                        <?php echo number_format($money_flow, 2); ?>
                    </td>
                    
                </tr>

            <?php } ?>

        </tbody>
    </table>
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available for <?php echo $company_symbol; ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>

<div id="market_running" data-val='live' ></div>

<div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($stock_detail) , ENT_QUOTES, 'UTF-8'); ?>' ></div>

<!-- Line Chart Start-->
<div class="row">
            
        <div class="col-xl-12 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="close_price_chart" data-plot_data="close_price" data-plot_data_name="Close Price" data-colorz="green" data-full_screen="0"></div> 
        </div>
        
    </div>
    
    <div class="row">
        
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="money_flow_chart" data-plot_data="money_flow" data-plot_data_name="Money Flow" data-colorz="blue" data-full_screen="0"></div>             
            
        </div>
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="total_traded_volume_chart" data-plot_data="total_traded_volume" data-plot_data_name="Traded Volume" data-colorz="blue" data-full_screen="0"></div>             
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
             <div class="chart_dsgn" id="delivery_quantity_chart" data-plot_data="delivery_quantity" data-plot_data_name="Delivery Quantity" data-colorz="blue" data-full_screen="0"></div> 
            
        </div>
        
    </div>
    
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="delivery_to_traded_quantity_chart" data-plot_data="delivery_to_traded_quantity" data-plot_data_name="Delivery to Traded Quantity" data-colorz="blue" data-full_screen="0"></div>             
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
             <div class="chart_dsgn" id="vwap_chart" data-plot_data="vwap" data-plot_data_name="VWAP" data-colorz="blue" data-full_screen="0"></div> 
            
        </div>
        
    </div>
    
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="total_buy_quantity_chart" data-plot_data="total_buy_quantity" data-plot_data_name="Total Buy Quantity" data-colorz="green" data-full_screen="0"></div>             
            
        </div>
        <div class="col-xl-6 col-sm-12 col-12">
            
             <div class="chart_dsgn" id="total_sell_quantity_chart" data-plot_data="total_sell_quantity" data-plot_data_name="Total Sell Quantity" data-colorz="red" data-full_screen="0"></div> 
            
        </div>
        
    </div>
    <div class="row">
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="total_buy_sell_quantity_chart" data-plot_data="total_buy_sell_quantity" data-plot_data_name="Total Buy Sell" data-colorz="green" data-full_screen="0"></div>             
            
        </div>
            
        <div class="col-xl-6 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="total_traded_value_chart" data-plot_data="total_traded_value" data-plot_data_name="Total Traded Value" data-colorz="blue" data-full_screen="0"></div>             
            
        </div>
        
    </div>
<!-- Line Chart End-->
<script>
    /*
     * @author : ZAHIR
     * DESC: On change stock date
     */
    function changeStockDate(e) {

//        alert(e.target.value);
        $(".stock_date").attr('value', e.target.value);

        $('.apply-btn-actionz').click();
    }

/*
 * @author: ZAHIR
 * DESC: Flatdate picker
 */
$(document).ready(function () {
    flatpickr("#stock_date", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            maxDate: "today",
            "disable": [
            function(date) {
                // return true to disable, disable saturday and sunday
                return (date.getDay() === 0 || date.getDay() === 6);

            }
            ],
            "locale": {
                "firstDayOfWeek": 1 // start week on Monday
            }
    });
});
</script>