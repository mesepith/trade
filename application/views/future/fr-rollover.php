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
</style>
<div class="container">
    
    <h2><?php echo $other_info['company_symbol'] . ' - ' . ucfirst($live); ?></h2>
    <p>
        Underlying Stock: <?php echo $other_info['company_symbol'] . ' As on ' . $other_info['underlying_date_time'] ; ?> 
        <?php if( !empty($fr_data) && count($fr_data) > 0 && !empty($other_info['searching_underlying_date_to']) ){
        echo ' TO ' . date('M d, Y', strtotime($other_info['searching_underlying_date_to']) );
        }?>
    </p>   
    <p>Industry : <?php echo $other_info['industry']; ?></p>
    
    <form method="get" action="<?php echo base_url('future/rollover-log'); ?>">
        
        
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
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($other_info['searching_underlying_date_to']) ? date('Y-m-d') : $other_info['searching_underlying_date_to']; ?>"  onchange="changeStockDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>
                
        <div class="row mb-30 mt-60">
            
            <div id="today_date" data-valz="<?php echo date('Y-m-d'); ?>"></div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_1_week= date('Y-m-d', strtotime("-1 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_1_week; ?>" name="date_period" <?php echo ($date_period===$last_1_week) ? 'checked' : ''; ?>>
                        Last 1 week
                    </label>
                </div>
            </div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_2_week= date('Y-m-d', strtotime("-2 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_2_week; ?>" name="date_period" <?php echo ($date_period===$last_2_week) ? 'checked' : ''; ?>>
                        Last 2 week
                    </label>
                </div>
            </div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_3_week= date('Y-m-d', strtotime("-3 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_3_week; ?>" name="date_period" <?php echo ($date_period===$last_3_week) ? 'checked' : ''; ?>>
                        Last 3 week
                    </label>
                </div>
            </div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_4_week= date('Y-m-d', strtotime("-4 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_4_week; ?>" name="date_period" <?php echo ($date_period===$last_4_week) ? 'checked' : ''; ?>>
                        Last 4 week
                    </label>
                </div>
            </div>
            
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_1_month= date('Y-m-d', strtotime("-1 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_1_month; ?>" name="date_period" <?php echo ($date_period===$last_1_month) ? 'checked' : ''; ?>>
                        Last 1 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_2_month= date('Y-m-d', strtotime("-2 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_2_month; ?>" name="date_period" <?php echo ($date_period===$last_2_month) ? 'checked' : ''; ?>>
                        Last 2 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_3_month= date('Y-m-d', strtotime("-3 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_3_month; ?>" name="date_period" <?php echo ($date_period===$last_3_month) ? 'checked' : ''; ?>>
                        Last 3 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_6_month= date('Y-m-d', strtotime("-6 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_6_month; ?>" name="date_period" <?php echo ($date_period===$last_6_month) ? 'checked' : ''; ?>>
                        Last 6 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_9_month= date('Y-m-d', strtotime("-9 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_9_month; ?>" name="date_period" <?php echo ($date_period===$last_9_month) ? 'checked' : ''; ?>>
                        Last 9 month
                    </label>
                </div>
            </div>
            <div class="col-xl-2 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_12_month= date('Y-m-d', strtotime("-12 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_12_month; ?>" name="date_period" <?php echo ($date_period===$last_12_month) ? 'checked' : ''; ?>>
                        Last 12 month
                    </label>
                </div>
            </div>
            
        </div>        
        
        <div class="row mb-60">
            <div class="col-xl-3 col-1 mb-30">
                <input type="checkbox" class="show_avg_total_data" id="show_avg_total_data_chkbox" name="show_avg_total_data" value="<?php echo $other_info["show_avg_total_data"]; ?>" <?php echo ($other_info["show_avg_total_data"]== 'yes') ? 'checked' : ''; ?>>
                <label for="show_avg_total_data_chkbox"> Show Only Average And Total Data</label><br>            
            </div>
        </div>
        
        <input type='hidden' class='company_id' name='company_id' value='<?php echo $other_info['company_id']; ?>'>
        <input type='hidden' class='company_symbol' name='company_symbol' value='<?php echo base64_url_encode($other_info['company_symbol']); ?>'>
        
        <input type='hidden' class='searching_underlying_date' name='sud' value='<?php echo $other_info['searching_underlying_date']; ?>'>
        <input type='hidden' class='searching_underlying_date_to' name='sud_to' value='<?php echo $other_info['searching_underlying_date_to']; ?>'>      
        
        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
    </form>
 
    <!--<div class="table-responsive">-->
        <table class="table table-bordered p_c_data_table">
            <thead>
                <tr>
                    
                    <?php if( !empty($fr_data) && count($fr_data) > 0 ){ ?>                    
                    <th>Date</th>
                    <?php } ?>                    
                    <th>Rollover</th>
                    <th>Roll Cost</th>
                </tr>

            </thead>
            <tbody>
                <?php 
                    $total_rollover_percentage = 0;
                    $total_roll_cost = 0;
                    
                    foreach($fr_data AS $fr_data_value){ 
                ?>
                <tr class="db_data">
                    <?php if( !empty($fr_data) && count($fr_data) > 0 ){ ?>                    
                    <td><?php echo date('d-M-Y', strtotime($fr_data_value->underlying_date)); ?></td>
                    <?php } ?>
                    
                    <td>
                        <?php 
                            $total_rollover_percentage = $total_rollover_percentage + $fr_data_value->rollover_percentage;
                            echo number_format($fr_data_value->rollover_percentage, 2); 
                        ?> %
                    </td>
                    <td>
                        <?php 
                            $total_roll_cost = $total_roll_cost + $fr_data_value->roll_cost;
                            echo number_format($fr_data_value->roll_cost, 2); 
                        ?> %
                    </td>
                </tr>
                <?php } ?>
                <tr class="avg_total_data">
                    <td>Average</td>
                    <td><?php echo number_format($total_rollover_percentage / count($fr_data) , 2); ?>%</td>
                    <td><?php echo number_format($total_roll_cost / count($fr_data) , 2); ?>%</td>
                </tr>
                
            </tbody>
        </table>
    <!--</div>-->
</div>

<div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($fr_data) , ENT_QUOTES, 'UTF-8'); ?>' ></div>

<div class="row">
            
    <div class="col-xl-6 col-sm-12 col-12">

        <div class="chart_dsgn" id="rollover_percentage_chart" data-plot_data="rollover_percentage" data-plot_data_name="Rollover Percentage" data-colorz="green" data-full_screen="0"></div> 
    </div>
    
    <div class="col-xl-6 col-sm-12 col-12">

        <div class="chart_dsgn" id="roll_cost_chart" data-plot_data="roll_cost" data-plot_data_name="Rollover Cost" data-colorz="green" data-full_screen="0"></div> 
    </div>

</div>
