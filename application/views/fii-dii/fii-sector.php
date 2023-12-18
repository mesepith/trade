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
    
    .sector-dropdown-menu{
        height:400px;
        overflow-y:auto;
     }
     .sel-sec{
         background: #007bff;
         color: #fff;
     }
</style>
<div class="container">

    <h1>FII Sector Investment Data</h1>

    <form method="get" action="<?php echo base_url('fii-dii/fii-sectore-invest/'); ?>">
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
        
        <div class="row mb-60 mt-60">
            
            <div class="col-xl-12 col-12">
            
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <?php echo empty($sector) ? 'Select Sector' : 'Sector - ' . $sector; ?>
                    </button>
                    <div class="dropdown-menu sector-dropdown-menu">
                        
                        <?php foreach( $fii_investing_sectors AS $fii_investing_sectors_val ){?>
                        
                        <a class="dropdown-item select_sector <?php echo ( $sector === $fii_investing_sectors_val->sector_name) ? 'sel-sec' :'' ?>" href="javascript:void(0)" data-sector="<?php echo $fii_investing_sectors_val->sector_name; ?>">
                            <?php echo $fii_investing_sectors_val->sector_name; ?>
                        </a>
                        
                        <?php } ?>
                    </div>
                </div>
                
            </div>
            
        </div>
        
        <div class="row mb-20"> 
            <div class="col-xl-1 col-2">
            <a href="<?php echo base_url(). 'fii-dii/fii-sectore-invest/'; ?>">Reset</a> 
            </div>
        </div>


        <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') : $market_date; ?>">
        <input type="hidden" class="market_date_to" name="market_date_to" value="<?php echo empty($market_date_to) ? date('Y-m-d') : $market_date_to; ?>">
        <input type="hidden" class="sector" name="sector" value="<?php echo empty($sector) ? '' : $sector; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <?php

        if (!empty($fii_sector_data) && count($fii_sector_data) > 0 && !empty($sector ) ) {
        // Create an associative array to store the sum of each metric for each report_date and sector_name
        $sum_by_date_and_sector = array();

        // Loop through the array and accumulate the values
        foreach ($fii_sector_data as $item) {
            $report_date = $item->report_date;
            $sector_name = $item->sector_name;
            $equity = $item->equity;
            $debt = $item->debt;
            $debt_vrr = $item->debt_vrr;
            $hybrid = $item->hybrid;
            $total = $item->total;

            // If the report_date and sector_name combination is not already in the sum array, initialize it to 0
            if (!isset($sum_by_date_and_sector[$report_date][$sector_name])) {
                $sum_by_date_and_sector[$report_date][$sector_name] = array(
                    'equity' => 0,
                    'debt' => 0,
                    'debt_vrr' => 0,
                    'hybrid' => 0,
                    'total' => 0,
                );
            }

            // Accumulate the values for the current report_date and sector_name
            $sum_by_date_and_sector[$report_date][$sector_name]['equity'] += $equity;
            $sum_by_date_and_sector[$report_date][$sector_name]['debt'] += $debt;
            $sum_by_date_and_sector[$report_date][$sector_name]['debt_vrr'] += $debt_vrr;
            $sum_by_date_and_sector[$report_date][$sector_name]['hybrid'] += $hybrid;
            $sum_by_date_and_sector[$report_date][$sector_name]['total'] += $total;
        }

        // Now $sum_by_date_and_sector contains the sum of each metric for each report_date and sector_name
        // Create a new array with the same structure as $fii_sector_data
        $sum_array = array();
        foreach ($sum_by_date_and_sector as $report_date => $sectors) {
            foreach ($sectors as $sector_name => $sums) {
                $sum_array[] = (object) array(
                    'report_date' => $report_date,
                    'sector_name' => $sector_name,
                    'investment_type' => 'All',
                    'equity' => $sums['equity'],
                    'debt' => $sums['debt'],
                    'debt_vrr' => $sums['debt_vrr'],
                    'hybrid' => $sums['hybrid'],
                    'total' => $sums['total'],
                );
            }
        }
        // Now $sum_equity_by_date contains the sum of equity for each report_date
        // echo '<pre>';  print_r($sum_array); exit;
        $fii_sector_data = $sum_array;
        }
        ?>

    <?php if (!empty($fii_sector_data) && count($fii_sector_data) > 0) { ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Investment Type</th>
                    <th>Sector</th>
                    <th>Equity</th>
                    <th>Debt</th>
                    <th>Debt VRR</th>
                    <th>Hybrid</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($fii_sector_data AS $fii_sector_data_key => $fii_sector_data_value) { ?>

                    <tr>
                        <td><?php echo date('d-M-Y', strtotime($fii_sector_data_value->report_date)); ?></td>
                        <td><?php echo $fii_sector_data_value->investment_type; ?></td>
                        <td><?php echo $fii_sector_data_value->sector_name; ?></td>
                        <td>
                            <?php
                            
                            echo number_format($fii_sector_data_value->equity); 
                            
                            if( !empty($sector) &&  $fii_sector_data_key!=0 ){
                            
                                $equity_diff_percnt = percentOfTwoNumber( $fii_sector_data_value->equity, $fii_sector_data[$fii_sector_data_key-1]->equity );
                            ?>

                            <br/>
                            <span class="<?php echo ($equity_diff_percnt>0 && $fii_sector_data_value->equity>$fii_sector_data[$fii_sector_data_key-1]->equity) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $equity_diff_percnt . "%)";

                            }?>
                            </span> 
                        </td>
                        <td>
                            <?php 
                            
                            echo number_format($fii_sector_data_value->debt); 
                            
                            if( !empty($sector) &&  $fii_sector_data_key!=0 ){
                            
                                $debt_diff_percnt = percentOfTwoNumber( $fii_sector_data_value->debt, $fii_sector_data[$fii_sector_data_key-1]->debt );
                            ?>

                            <br/>
                            <span class="<?php echo ($debt_diff_percnt>0 && $fii_sector_data_value->debt>$fii_sector_data[$fii_sector_data_key-1]->debt) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $debt_diff_percnt . "%)";

                            }?>
                            </span>   
                        </td>
                        <td>
                        <?php 
                            
                            echo number_format($fii_sector_data_value->debt_vrr); 
                        ?>
                        </td>
                        <td>
                            <?php 
                            
                            echo number_format($fii_sector_data_value->hybrid); 
                            
                            if( !empty($sector) &&  $fii_sector_data_key!=0 ){
                                
                                $hybrid_diff_percnt = percentOfTwoNumber( $fii_sector_data_value->hybrid, $fii_sector_data[$fii_sector_data_key-1]->hybrid );
                            ?>

                            <br/>
                            <span class="<?php echo ($hybrid_diff_percnt>0 && $fii_sector_data_value->hybrid>$fii_sector_data[$fii_sector_data_key-1]->hybrid) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $hybrid_diff_percnt . "%)";

                            }?>
                            </span> 
                        </td>
                        <td>
                            <?php 
                            
                            echo number_format($fii_sector_data_value->total); 
                            
                            if( !empty($sector) &&  $fii_sector_data_key!=0 ){
                                
                                $total_diff_percnt = percentOfTwoNumber( $fii_sector_data_value->total, $fii_sector_data[$fii_sector_data_key-1]->total );
                            ?>

                            <br/>
                            <span class="<?php echo ($total_diff_percnt>0 && $fii_sector_data_value->total>$fii_sector_data[$fii_sector_data_key-1]->total) ? 'col-green' : 'col-red' ?>">

                            <?php

                                echo "(" . $total_diff_percnt . "%)";

                            }?>
                            </span> 
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