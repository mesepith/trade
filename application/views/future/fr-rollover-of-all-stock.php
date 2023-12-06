<?php
$this->load->helper('function_helper');
?>
<style>

    thead tr:nth-child(1) th, thead tr:nth-child(2) th{
        background: white;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    /*sticky header of table end*/
    
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
.mb-30{margin-bottom: 30px;}

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
 
 .avg_total_data{
     border: 2px solid green;
 }
 
.sel-sec{
    background: #007bff;
    color: #fff;
}
</style>
<div class="container">
    
    <h2><?php echo 'Future Rollover on ' . date('d M Y', strtotime($other_info['searching_underlying_date']) ); ?></h2>
    
    <form method="get" action="<?php echo base_url('future/rollover/day-wise-analysis'); ?>">
                
        <div class="row mb-60 mt-60">

            <div class="col-xl-3 col-12 mb-10">
                Select Underlying Date:
            </div>
            <div class="col-xl-3 col-11 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($other_info['searching_underlying_date']) ? date('Y-m-d') : $other_info['searching_underlying_date']; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
        </div>
        
        <div class="row mb-20">
            
            <div class="col-xl-2 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Rollover - <?php echo ucfirst($rollover_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_rollover <?php echo ($rollover_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-rollover-sortby="high">High</a>
                        <a class="dropdown-item sort_by_rollover <?php echo ($rollover_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-rollover-sortby="low">Low</a>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-2 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Roll Cost - <?php echo ucfirst($rollcost_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_rollcost <?php echo ($rollcost_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-rollcost-sortby="high">High</a>
                        <a class="dropdown-item sort_by_rollcost <?php echo ($rollcost_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-rollcost-sortby="low">Low</a>
                    </div>
                </div>
            </div>
            
        </div>
        
        <input type='hidden' class='searching_underlying_date' name='sud' value='<?php echo $other_info['searching_underlying_date']; ?>'>
        
        <input type="hidden" class="rollover_sortby" name="rollover_sortby">
        <input type="hidden" class="rollcost_sortby" name="rollcost_sortby">
        
        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
    </form>
 
    <!--<div class="table-responsive">-->
        <table class="table table-bordered p_c_data_table">
            <thead>
                <tr>
                    
                    <th>Underlying Asset</th>
                    <th>Underlying Price</th>
                    <th>Rollover</th>
                    <th>Roll Cost</th>

            </thead>
            <tbody>
                <?php 
                    
                    foreach($fr_rollover_data AS $fr_data_value){ 
                ?>
                <tr class="db_data">
                    <td>
                        <a href="<?php echo base_url('future/rollover-log/' . $fr_data_value->company_id . '/' . base64_url_encode($fr_data_value->company_symbol) ); ?>">
                            <?php echo $fr_data_value->company_symbol; ?>
                        </a>
                    </td>
                    <td><?php echo number_format($fr_data_value->underlying_price, 2); ?></td>
                    <td><?php echo number_format($fr_data_value->rollover_percentage, 2); ?> %</td>
                    <td><?php echo number_format($fr_data_value->roll_cost, 2); ?> %</td>
                    
                    
                </tr>
                <?php } ?>
                
            </tbody>
        </table>
    <!--</div>-->
</div>