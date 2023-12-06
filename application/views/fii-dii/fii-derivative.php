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
    .mb-60{margin-bottom: 60px;}
    .mt-60{margin-top: 60px;}
    .mt-20{margin-top: 20px;}
    
    .col-green{
       color: green; 
    }
    .col-red{
       color: red; 
    }
</style>
<div class="container">

    <h1>FII Derivative Data</h1>

    <form method="get" action="<?php echo base_url('fii-dii/fii-derivative/'); ?>">
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
            
            <div class="col-xl-1 col-2 mb-30 form-check">
                Select Source
            </div>
            <div class="col-xl-1 col-1 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_source" value="nsdl" name="source" <?php echo ( $source === "nsdl" ) ? 'checked' : ''; ?>>nsdl
                </label>
            </div>
            <div class="col-xl-1 col-1 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_source" value="nse" name="source" <?php echo ( $source === "nse" ) ? 'checked' : ''; ?>>nse
                </label>
            </div>
            
        </div>
        
        <div class="row mb-20">
            
            <div class="col-xl-1 col-2 mb-30 form-check">
                Select Products
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_product" value="Index Futures" name="product" <?php echo ( $product === "Index Futures" ) ? 'checked' : ''; ?>>Index Futures
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_product" value="Index Options" name="product" <?php echo ( $product === "Index Options" ) ? 'checked' : ''; ?>>Index Options
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_product" value="Stock Futures" name="product" <?php echo ( $product === "Stock Futures" ) ? 'checked' : ''; ?>>Stock Futures
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_product" value="Stock Options" name="product" <?php echo ( $product === "Stock Options" ) ? 'checked' : ''; ?>>Stock Options
                </label>
            </div>
            <div class="col-xl-2 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_product" value="Interest Rate Futures" name="product" <?php echo ( $product === "Interest Rate Futures" ) ? 'checked' : ''; ?>>Interest Rate Futures
                </label>
            </div>
            
        </div>
        
        <div class="row mb-20"> 
            <div class="col-xl-1 col-2">
            <a href="<?php echo base_url(). 'fii-dii/fii-derivative'; ?>">Reset</a> 
            </div>
        </div>


        <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') : $market_date; ?>">
        <input type="hidden" class="market_date_to" name="market_date_to" value="<?php echo empty($market_date_to) ? date('Y-m-d') : $market_date_to; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>


    <?php if (!empty($fii_derivative_data) && count($fii_derivative_data) > 0) { ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Source</th>
                    <th>Derivative Products</th>
                    <th>Buy No Of Contract</th>
                    <th>Buy Amount</th>
                    <th>Sell No Of Contract</th>
                    <th>Sell Amount</th>
                    <th>Oi At End No Of Contract</th>
                    <th>Oi At End Amount</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($fii_derivative_data AS $fii_derivative_data_key => $fii_derivative_data_value) { ?>

                    <tr>
                        <td><?php echo date('d-M-Y', strtotime($fii_derivative_data_value->reporting_date)); ?></td>
                        <td><?php echo $fii_derivative_data_value->source; ?></td>
                        <td><?php echo $fii_derivative_data_value->derivative_products; ?></td>
                        <td>
                            <?php 
                            
                            echo money_format('%!.0n', $fii_derivative_data_value->buy_no_of_contract); 
                            
                            if( !empty($source) && !empty($product) &&  $fii_derivative_data_key!=0 ){
                            
                                $bnc_diff_percnt = percentOfTwoNumber( $fii_derivative_data_value->buy_no_of_contract, $fii_derivative_data[$fii_derivative_data_key-1]->buy_no_of_contract );
                            ?>

                            <br/>
                            <span class="<?php echo ($bnc_diff_percnt>0) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $bnc_diff_percnt . "%)";

                            }?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            
                            echo money_format('%!.0n', $fii_derivative_data_value->buy_amount); 
                            
                            if( !empty($source) && !empty($product) &&  $fii_derivative_data_key!=0 ){
                            
                                $buy_amount_diff_percnt = percentOfTwoNumber( $fii_derivative_data_value->buy_amount, $fii_derivative_data[$fii_derivative_data_key-1]->buy_amount );
                            ?>

                            <br/>
                            <span class="<?php echo ($buy_amount_diff_percnt>0) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $buy_amount_diff_percnt . "%)";

                            }?>
                            </span>
                        </td>
                        <td>
                            <?php
                            
                            echo money_format('%!.0n', $fii_derivative_data_value->sell_no_of_contract); 
                            
                            if( !empty($source) && !empty($product) &&  $fii_derivative_data_key!=0 ){
                            
                                $snc_amount_diff_percnt = percentOfTwoNumber( $fii_derivative_data_value->sell_no_of_contract, $fii_derivative_data[$fii_derivative_data_key-1]->sell_no_of_contract );
                            ?>

                            <br/>
                            <span class="<?php echo ($snc_amount_diff_percnt>0) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $snc_amount_diff_percnt . "%)";

                            }?>
                        </td>
                        <td>
                            <?php 
                            
                            echo money_format('%!.0n', $fii_derivative_data_value->sell_amount); 
                            
                            if( !empty($source) && !empty($product) &&  $fii_derivative_data_key!=0 ){
                            
                                $sell_amount_diff_percnt = percentOfTwoNumber( $fii_derivative_data_value->sell_amount, $fii_derivative_data[$fii_derivative_data_key-1]->sell_amount );
                            ?>

                            <br/>
                            <span class="<?php echo ($sell_amount_diff_percnt>0) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $sell_amount_diff_percnt . "%)";

                            }?>
                                
                        </td>
                        <td>
                            <?php 
                            
                            echo money_format('%!.0n', $fii_derivative_data_value->oi_at_end_no_of_contract); 
                            
                            if( !empty($source) && !empty($product) &&  $fii_derivative_data_key!=0 ){
                            
                                $oi_end_diff_percnt = percentOfTwoNumber( $fii_derivative_data_value->oi_at_end_no_of_contract, $fii_derivative_data[$fii_derivative_data_key-1]->oi_at_end_no_of_contract );
                            ?>

                            <br/>
                            <span class="<?php echo ($oi_end_diff_percnt>0) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $oi_end_diff_percnt . "%)";

                            }?>
                        </td>
                        
                        <td>
                            <?php 
                            echo money_format('%!.0n', $fii_derivative_data_value->oi_at_end_amount); 
                            
                            if( !empty($source) && !empty($product) &&  $fii_derivative_data_key!=0 ){
                            
                                $oi_end_amt_diff_percnt = percentOfTwoNumber( $fii_derivative_data_value->oi_at_end_amount, $fii_derivative_data[$fii_derivative_data_key-1]->oi_at_end_amount );
                            ?>

                            <br/>
                            <span class="<?php echo ($oi_end_amt_diff_percnt>0) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $oi_end_amt_diff_percnt . "%)";

                            }?>
                        </td>


                    </tr>

                <?php } ?>

            </tbody>
        </table>


    <?php } else { ?>

        <div>
            <div class="alert alert-danger">
                <strong>No Data Available, Kindly choose another date </strong> 
            </div>
        </div>

    <?php } ?>


</div>