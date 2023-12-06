<?php
setlocale(LC_MONETARY,"en_IN.utf8");
$this->load->helper('function_helper');
?>
<style>
    @media (min-width: 1800px){
        div.container {
            max-width: 1800px;
        }
    }
    .mb-30{margin-bottom: 30px;}
    .mb-60{margin-bottom: 60px;}

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
    
    <h2><?php echo 'Future Company Analysis on ' . date('d M Y', strtotime($other_info['searching_underlying_date']) ) . ' , for the Expiry ' . date('d M Y', strtotime($other_info['searching_expiry_date']) ); ?></h2>
    
    <form method="get" action="<?php echo base_url('future/day-wise-analysis'); ?>">
        
        
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Underlying Date:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($other_info['searching_underlying_date']) ? date('Y-m-d') : $other_info['searching_underlying_date']; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
        </div>
        
        
        <div class="row">
            
            <div class="col-xl-2 col-12 mb-30">
                Select
            </div>
            
            <div class="col-xl-3 col-12 mb-30"> 
                
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Expiry Date : </span><span><?php echo date('d M Y', strtotime($other_info['searching_expiry_date'])); ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($other_info['expiry_dates'] AS $expiry_dates_value){?>
                      <a class="dropdown-item change_expiry_date" data-searching_underlying_date='<?php echo $other_info['searching_underlying_date']; ?>' data-searching_expiry_date='<?php echo $expiry_dates_value->expiry_date; ?>' href="javascript:void(0)">
                          <?php echo date('d M Y', strtotime($expiry_dates_value->expiry_date) ); ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            
        </div>
        
        
         <div class="row mb-20">
            
            <div class="col-xl-2 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Turnover - <?php echo ucfirst($turnover_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_turnover <?php echo ($turnover_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-turnover-sortby="high">High</a>
                        <a class="dropdown-item sort_by_turnover <?php echo ($turnover_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-turnover-sortby="low">Low</a>
                    </div>
                </div>
            </div>
             
            <div class="col-xl-2 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Volume - <?php echo ucfirst($volume_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_volume <?php echo ($turnover_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-volume-sortby="high">High</a>
                        <a class="dropdown-item sort_by_volume <?php echo ($volume_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-volume-sortby="low">Low</a>
                    </div>
                </div>
            </div>
             
            <div class="col-xl-1 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort OI - <?php echo ucfirst($oi_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_oi <?php echo ($oi_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-oi-sortby="high">High</a>
                        <a class="dropdown-item sort_by_oi <?php echo ($oi_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-oi-sortby="low">Low</a>
                    </div>
                </div>
            </div>
             
            <div class="col-xl-2 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Change OI - <?php echo ucfirst($change_oi_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_change_oi <?php echo ($change_oi_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-change-oi-sortby="high">High</a>
                        <a class="dropdown-item sort_by_change_oi <?php echo ($change_oi_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-change-oi-sortby="low">Low</a>
                    </div>
                </div>
            </div>
             
            <div class="col-xl-2 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Change OI % - <?php echo ucfirst($change_oi_p_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_change_oi_p <?php echo ($change_oi_p_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-change-oi-p-sortby="high">High</a>
                        <a class="dropdown-item sort_by_change_oi_p <?php echo ($change_oi_p_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-change-oi-p-sortby="low">Low</a>
                    </div>
                </div>
            </div>
               
            <div class="col-xl-2 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Daily Volatility - <?php echo ucfirst($daily_volatility_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_daily_volatility <?php echo ($daily_volatility_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-daily-volatility-sortby="high">High</a>
                        <a class="dropdown-item sort_by_daily_volatility <?php echo ($daily_volatility_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-daily-volatility-sortby="low">Low</a>
                    </div>
                </div>
            </div>
             
        </div> 
        
        <input type='hidden' class='searching_underlying_date' name='sud' value='<?php echo $other_info['searching_underlying_date']; ?>'>
        
        <input type='hidden' class='searching_expiry_date' name='sed' value='<?php echo $other_info['searching_expiry_date']; ?>'>        
        
        <input type="hidden" class="turnover_sortby" name="turnover_sortby">
        <input type="hidden" class="volume_sortby" name="volume_sortby">
        <input type="hidden" class="oi_sortby" name="oi_sortby">
        <input type="hidden" class="change_oi_sortby" name="change_oi_sortby">        
        <input type="hidden" class="change_oi_p_sortby" name="change_oi_p_sortby">
        <input type="hidden" class="daily_volatility_sortby" name="daily_volatility_sortby">
        
        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
    </form>
 
    <!--<div class="table-responsive">-->
        <table class="table table-bordered p_c_data_table">
            <thead>
                <tr>
                    
                    <th>Underlying Asset</th>
                    <th>Open Price</th>
                    <th>High Price</th>
                    <th>Low Price</th>
                    <th>Close Price</th>
                    <th>Prev Price</th>
                    <th>Last Price</th>
                    <th>No of contract Traded</th>
                    <th>Total Turnover (Lakhs) (Total Traded Value) (Premium Turnover)</th>
                    <th>Total Buy Quantity</th>
                    <th>Total Sell Quantity</th>                   
                    <th>VMAP</th>
                    <th>OI</th>
                    <th>Daily Volatility</th>
                    <th>Annual Volatility</th>
                    <th>Market Wide Position Limits</th>

            </thead>
            <tbody>
                <?php 
                    
                    foreach($fr_data AS $fr_data_value){ 
                ?>
                <tr class="db_data">
                    <td><?php echo $fr_data_value->company_symbol; ?></td>
                    <td><?php echo money_format("%n",$fr_data_value->open_price); ?></td>
                    <td><?php echo money_format("%n",$fr_data_value->high_price); ?></td>
                    <td><?php echo money_format("%n",$fr_data_value->low_price); ?></td>
                    <td>
                        <?php
                        
                        echo money_format("%n",$fr_data_value->close_price); 
                        ?>
                    </td>
                    <td><?php echo money_format("%n",$fr_data_value->prev_price); ?></td>
                    
                    <td>
                        <?php echo money_format("%n",$fr_data_value->last_price); ?>
                        
                        <?php if(!empty($fr_data_value->change)){?>
                        
                            <br/>
                            <span class="<?php echo ($fr_data_value->change>0) ? 'col-green' : 'col-red' ?>">
                                <?php echo $fr_data_value->change . " (".$fr_data_value->p_change."%) "; ?>
                            </span>
                            <br/>
                        
                        <?php } ?>
                    </td>
                    
                    <td><?php 
                        
                        echo number_format($fr_data_value->no_of_contracts_traded); 
                    ?></td>                    
                    <td>
                        <?php 
                        
                        echo money_format("%n",$fr_data_value->total_turnover); 
                        ?>
                    </td>                                        
                    <td><?php 
                        
                        echo number_format($fr_data_value->total_buy_quantity); 
                    ?></td>
                    <td><?php 
                        
                        echo number_format($fr_data_value->total_sell_quantity); 
                    ?></td>                                                           
                    
                    <td>
                        <?php 
                        
                        echo money_format("%n",$fr_data_value->vmap); 
                        ?>
                    </td>
                    <td>
                        <?php 
                        
                        echo number_format($fr_data_value->oi); 
                        
                        if(!empty($fr_data_value->change_in_oi)){?>
                        
                            <br/>
                            <span class="<?php echo ($fr_data_value->change_in_oi>0) ? 'col-green' : 'col-red' ?>">
                                <?php echo number_format($fr_data_value->change_in_oi) . " (".$fr_data_value->p_change_in_oi."%) "; ?>
                            </span>
                            <br/>
                        
                        <?php } ?>
                    </td>    
                    <td><?php 
                            
                            echo number_format($fr_data_value->daily_volatility,2); 
                        ?>
                    </td>
                    <td>
                        <?php 
                            
                            echo number_format($fr_data_value->annual_volatility,2); 
                        ?>
                    </td>
                    <td><?php echo number_format($fr_data_value->market_wide_position_limits); ?></td>
                </tr>
                <?php } ?>
                
            </tbody>
        </table>
    <!--</div>-->
</div>