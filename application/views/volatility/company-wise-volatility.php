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
    .mb-20{margin-bottom: 20px;}
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
</style>

<div class="container">
    <?php if( !empty($volatility_data) && count($volatility_data) > 0 ){ ?>
    <?php if(!empty($stock_date_to)){?>
    <h2><?php echo 'Volatility of <b>' . $company_symbol . '</b> from ' . date('d-M-Y', strtotime($market_date)) . ' to ' . date('d-M-Y', strtotime($market_date_to)); ?></h2>
    <?php }else{ ?>
    <h2><?php echo 'Volatility of <b>' . $company_symbol . '</b> on ' . date('d-M-Y', strtotime($market_date)); ?></h2>
    <?php } ?>
    <?php } ?>
    <?php if(empty($volatility_data)){ ?> 
        <div class="mt-20">
            <div class="alert alert-danger">
                <strong><?php echo 'No Data Available'; ?></strong> 
            </div>
        </div>
    
    <?php } ?>   
    
    <form method="get" action="<?php echo base_url('daily-volatility-of/' . $company_id . '/' . $company_symbol); ?>">
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
        
        <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') :$market_date; ?>">
        <input type="hidden" class="market_date_to" name="market_date_to" value="<?php echo empty($market_date_to) ? date('Y-m-d') :$market_date_to; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <div class="mb-20">
    <?php
        if( !empty($derivative)){
            
            $oc_url= base_url() . 'option-chain/stock-info/?company_id='.$company_id.'&company_symbol='.base64_url_encode($company_symbol).'&sud='.$market_date_to;
            
//            $oc_url=base_url() . "option-chain/stock-info/".$company_id."/" . $company_symbol;
            
            $fr_url = base_url() . 'future/stock-info/?company_id='.$company_id.'&company_symbol='.base64_url_encode($company_symbol).'&sud='.$market_date.'&sud_to='.$market_date_to;
            
            echo '<a href="'.$fr_url.'">Future</a> / <a href="'.$oc_url.'">Option</a>';
        }
    ?>
    </div>
    <div class="mb-20">
        <a href="<?php echo base_url() . 'daily-log/?company_id='.$company_id.'&company_symbol='.base64_url_encode($company_symbol).'&stock_date='. $market_date.'&stock_date_to=' . date('Y-m-d'); ?>">

            CM 

        </a>
    </div>
    
   <?php if( !empty($volatility_data) && count($volatility_data) > 0 ){ ?>
        
    <h3 class="mb-60">Date: <?php echo date('d M Y', strtotime($market_date)); ?> </h3>
    
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>Market Date</th>
                    <th>Daily Volatility</th>
                    <th>Daily Volatility %</th>
                    <th>Annual Volatility</th>
                    <th>Annual Volatility %</th>                    
                    
                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($volatility_data AS $volatility_data_key=>$volatility_data_value) { ?>

                <tr>
                    
                    <td><?php echo date('d M Y', strtotime($volatility_data_value->market_date)); ?>  </td>                    
                    <td><?php echo $volatility_data_value->daily_volatility; ?>  </td>                    
                    <td><?php echo $volatility_data_value->daily_volatility_p; ?>  </td>                    
                    <td><?php echo $volatility_data_value->annual_volatility; ?>  </td>                    
                    <td><?php echo $volatility_data_value->annual_volatility_p; ?>  </td>                    
                    
                    
                </tr>
                
                <?php } ?>
                
            </tbody>
        </table>
        
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available, Kindly choose another date </strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>

