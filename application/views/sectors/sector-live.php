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
    .mb-60{margin-bottom: 60px;}
    .mb-30{margin-bottom: 30px;}
    .mb-10{margin-bottom: 10px;}
    .mt-60{margin-top: 60px;}
    .mt-20{margin-top: 20px;}
    .col-green{
       color: green; 
    }
    .col-red{
       color: red; 
    }
    .nifty-green{
       color: green; 
    }
    .nifty-red{
       color: red; 
    }
    
    .chart_dsgn{
        width:925px; 
        height:700px;
        /*margin-top:-34px !important;*/
    }

    div#ltp_chart{
        display: block;
        margin: 0 auto;
        width: 100%;
        height:1000px;
    }
</style>


<div class="container">
    <p>Coming Soon</p>
    
    <!--<div id="nifty-green-red" class='nifty-green-red <?php // echo ($sector_data[$total-1]->change>0) ? 'col-green' : 'col-red' ?>'>-->
    <div id="nifty-green-red" class=''>
    
        <h4>Nifty <span class="nifty_latest_price"></span></h4>
    <div class="nifty_latest_chng">
        
       
        
    </div>
    
    </div>
    
    <div class='chart_sec mb-60 '>
        
        <div id="market_running" data-val='live' ></div>

        <div class="row">

            <div class="col-xl-12 col-sm-12 col-12">

                <div class="chart_dsgn" id="ltp_chart" data-plot_data="ltp" data-plot_data_name="LTP" data-colorz="green" data-full_screen="0"></div> 
            </div>

        </div>
        
    </div>
    
    <?php if( !empty($sector_name) ){ ?>
        <?php if( !empty($sector_data) && is_array($sector_data) ){ ?>
            <h2><?php echo 'Analysis of <b>' . $sector_data[0]->index_name . '</b> on ' . date('d M Y', strtotime($stock_date) ); ?></h2>
        <?php } ?>

    <?php } ?>
    
    <form method="get" action="<?php echo base_url(); ?>">
        <div class="row mb-30 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($stock_date) ? date('Y-m-d') :$stock_date; ?>"  onchange="changeSectorDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>

        <input type='hidden' class='sector_id' name='sector_id' value='<?php echo $sector_id; ?>'>
        <input type='hidden' class='sector_name' name='sector_name' value='<?php echo $sector_name; ?>'>

        <input type="hidden" class="sector_date" name="sector_date" value="<?php echo empty($stock_date) ? date('Y-m-d') :$stock_date; ?>">

        <input type="submit" class="apply-btn-actionz mb-30" value="Apply">

    </form>
    
    <?php //echo '<pre>'; print_r($sector_data); 
    if( !empty($sector_data) && count($sector_data) > 0 ){ ?>
    
    <p>Analysis:</p>            
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Open Price</th>
                <th>High Price</th>
                <th>Low Price</th>
                <th>LTP</th>
                <th>Change</th>
                <th>Change in Percent</th>
                <th>Year Change in Percent</th>
                <th>Month Change in Percent</th>
                <th>Year High Price</th>
                <th>Year Low Price</th>
                <th>Advances</th>
                <th>Declines</th>
                <th>Trade Value Sum</th>
                <th>Trade Volume Sum</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($sector_data AS $sector_data_value) { ?>

                <tr>
                    <td><?php echo date('d M Y', strtotime($sector_data_value->stock_date_time)); ?></td>
                    
                    <td><?php echo number_format($sector_data_value->open_price,2); ?></td>
                    <td><?php echo number_format($sector_data_value->high_price,2); ?></td>
                    <td><?php echo number_format($sector_data_value->low_price,2); ?></td>
                    
                    <td><?php echo number_format($sector_data_value->ltp,2); ?></td>
                    <td><?php echo number_format($sector_data_value->change,2); ?></td>
                    
                    <td><?php echo $sector_data_value->change_in_percent; ?></td>
                    <td><?php echo $sector_data_value->year_change_in_percent; ?></td>
                    <td><?php echo $sector_data_value->month_change_in_percent; ?></td>
                    
                    <td><?php echo number_format($sector_data_value->year_high_price,2); ?></td>
                    <td><?php echo number_format($sector_data_value->year_low_price,2); ?></td>                    
                    
                    <td><?php echo $sector_data_value->advances; ?></td>
                    <td><?php echo $sector_data_value->declines; ?></td>
                    
                    <td><?php echo number_format($sector_data_value->trade_value_sum,2); ?></td>
                    <td><?php echo money_format('%!.0n', $sector_data_value->trade_volume_sum); ?></td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
   
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available for <?php echo $sector_name; ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>

