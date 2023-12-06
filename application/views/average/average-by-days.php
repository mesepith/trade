<style>
@media (min-width: 1800px){
        div.container {
            max-width: 1800px;
        }
    }
.mb-60{margin-bottom: 60px;}
.mb-30{margin-bottom: 30px;}
.mt-60{margin-top: 60px;}
.chart_dsgn{
    width:590px; 
    height:500px;
}

</style>
<div class="container">
    
    <h2 class="mb-30"><?php echo 'Analysis of <b>' . ucwords(str_replace('-', ' ', $report_name)) . '</b> ' . ' by Average'; ?> </h2>
    
    <form method="get" action="<?php echo base_url($url); ?>">
        
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
        
       <div class="row mb-60 mt-60">
            
            <div class="col-xl-12 col-12">
            
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle selc-clm" data-toggle="dropdown">
                        <?php echo empty($tb_column) ? 'Select Column' : 'Column - ' . ucfirst(str_replace('_', ' ', $tb_column)); ?>
                    </button>
                    <div class="dropdown-menu sector-dropdown-menu">
                        
                        <?php foreach( $tb_column_arr AS $tb_column_val ){?>
                        
                        <a class="dropdown-item select_tb_clm <?php echo ( $tb_column === $tb_column_val ) ? 'sel-sec' :'' ?>" href="javascript:void(0)" data-tb-clm="<?php echo $tb_column_val; ?>">                            
                            <?php echo ucfirst(str_replace('_', ' ', $tb_column_val)); ?>
                        </a>
                        
                        <?php } ?>
                    </div>
                </div>
                
            </div>
            
        </div>
       <div class="row mb-60 mt-60">
            
            <div class="col-xl-12 col-12">
            
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle selc-tb-or-chart" data-toggle="dropdown">
                        Show Only Table or Chart- 
                    </button>
                    <div class="dropdown-menu sector-dropdown-menu">
                        
                        <a class="dropdown-item select_tbl_or_chart" href="javascript:void(0)" data-tb-or-chart="table">                            
                            Table
                        </a>
                        <a class="dropdown-item select_tbl_or_chart" href="javascript:void(0)" data-tb-or-chart="chart">                            
                            Chart
                        </a>
                        
                    </div>
                </div>
                
            </div>
            
        </div>
        
        <input type="hidden" class="tb_column" name="tb_column" value="<?php echo empty($tb_column) ? '' : $tb_column; ?>">
        
        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
    </form>
    
    
    <?php if (!empty($avg_data)) { ?>
    
    <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($avg_data) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
    <div class="avg_return">
        
    <?php foreach( $avg_data AS $avg_by_days=> $tb_column_n_value){ ?>
        
        <h2 class="mb-30"><?php echo $avg_by_days; ?> Days Average</h2>
        
        <div class="row mb-60">
        
            <?php foreach( $tb_column_n_value AS $tb_column=> $avg_data_arr){ ?>
            
            <div class="col-xl-1 col-sm-12 col-12 table_only">
                
                <table class="table table-striped all_clmn_tb tb_clm_<?php echo $tb_column; ?>">
                        <tr>
                            <th><?php echo ucfirst(str_replace('_', ' ', $tb_column)); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                            
                        <?php foreach ($avg_data_arr AS $value) { ?>
                        <tr>
                            <td><?php echo number_format($value, 2); ?></td>
                        </tr>
                        
                        <?php } ?>
                    </tbody>
                    
                </table>
                
            </div>
            
            <?php } ?>
            
        <!--</div>-->
        
        <!--<div class="chart-sec">-->
            
        <!--<div class="row">-->
        
        <?php foreach( $tb_column_n_value AS $tb_column=> $avg_data_arr){ ?>
                
            <div class="col-xl-6 col-sm-12 col-12 chart_only all_clmn_tb tb_clm_<?php echo $tb_column; ?>">

                <div class="chart_dsgn" id="<?php echo $avg_by_days .'_' . $tb_column . '_chart'; ?>" data-plot_data="<?php echo $tb_column; ?>" data-plot_data_name="<?php echo $tb_column; ?>" data-colorz="green" data-full_screen="0"></div> 
            </div>

        <?php } ?>
        
        </div>
            
        <!--</div>-->
                
    <?php } ?>
        
    </div>
    
 
    
    
    <?php } ?>
    
</div>