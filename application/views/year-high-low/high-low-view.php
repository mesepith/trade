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
    
</style>

<div class="container">
    
    <h1 class="mb-60">52 Week <?php echo ucfirst($high_or_low); ?> Data</h1>
    
    <form method="get" action="<?php echo base_url('year-high-low/' . $high_or_low); ?>">
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($market_date) ? date('Y-m-d') : $market_date; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>
        <?php // echo $market_date; exit;?>
        <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') :$market_date; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <?php if( !empty($year_high_or_low_data) && count($year_high_or_low_data) > 0 ){ ?>
        
    <h3 class="mb-60">Date: <?php echo date('d M Y', strtotime($market_date)); ?> </h3>
    
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Derivative</th>
                    <th>New <?php echo ucfirst($high_or_low); ?></th>
                    <th>LTP</th>
                    <th>Previous <?php echo ucfirst($high_or_low); ?></th>
                    <th>Previous <?php echo ucfirst($high_or_low); ?> Date</th>
                    <th>Previous Close</th>
                    <th>Change</th>
                    <th>Change %</th>
                    
                    
                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($year_high_or_low_data AS $year_high_or_low_data_key=>$year_high_or_low_data_value) { ?>

                <tr>
                    <td>
                        <a href="<?php echo base_url() . 'daily-log/?company_id='.$year_high_or_low_data_value->company_id.'&company_symbol='.$year_high_or_low_data_value->company_symbol.'&stock_date='. $market_date.'&stock_date_to=' . date('Y-m-d'); ?>">
                        
                            <?php echo $year_high_or_low_data_value->company_symbol; ?>  
                            
                        </a>
                    </td>
                    
                    <td>
                        <?php 
                            $oc_url=base_url() . "option-chain/stock-info/".$year_high_or_low_data_value->company_id."/" . $year_high_or_low_data_value->company_symbol;
                            echo !empty($year_high_or_low_data_value->pc_exists) ? 'Future / <a href="'.$oc_url.'">Option</a>' : '' 
                        ?>  
                    </td>
                    <td><?php echo !empty($year_high_or_low_data_value->new_high) ? $year_high_or_low_data_value->new_high : $year_high_or_low_data_value->new_low; ?>  </td>                    
                    <td><?php echo $year_high_or_low_data_value->ltp; ?>  </td>                    
                    <td><?php echo !empty($year_high_or_low_data_value->prev_high) ? $year_high_or_low_data_value->prev_high : $year_high_or_low_data_value->prev_low; ?>  </td>
                    <td><?php echo !empty($year_high_or_low_data_value->prev_high_date) ? date('d M Y', strtotime($year_high_or_low_data_value->prev_high_date)) : date('d M Y', strtotime($year_high_or_low_data_value->prev_low_date)); ?>  </td>
                    <td><?php echo $year_high_or_low_data_value->prev_close; ?>  </td>
                    <td><?php echo $year_high_or_low_data_value->change; ?>  </td>
                    <td><?php echo $year_high_or_low_data_value->pChange; ?>  </td>
                    
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