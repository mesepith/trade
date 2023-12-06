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
    /*margin-left:-30% !important;*/
    /*margin-top:-7% !important;*/
}
</style>
<div class="container">
    
    <h2 class="mb-30"><?php echo 'Analysis of <b>' . ucwords(str_replace('-', ' ', $report_name)) . '</b> '; ?></h2>
    
<?php if (!empty($avg_data)) { ?>
    
    <div class="avg_return">
        
        <!-- Quarter Start -->
        
        <h2 class="mb-30">Quarterly Analysis</h2>
        
        <div class="row mb-60">
            
        <?php
            
            $quarter_chart_data_arr = array();
            
            foreach($avg_data AS $tb_column=> $avg_data_arr){ ?>
        
            <div class="col-xl-2 col-sm-12 col-12">
                
                <h5><?php echo ucfirst(str_replace('_', ' ', $tb_column)); ?></h5>
                
                <table id="<?php echo $tb_column . '_quarter'; ?>" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Quarter</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($avg_data_arr['quarter'] AS $quarter_year => $quarter_first_key_val) {
                            foreach ($quarter_first_key_val AS $quarter_no => $quarter_val) {

                                $quarter_chart_data_arr[str_replace('_', ' ', $tb_column) . ' quarter'][$quarter_year . ', ' . $quarter_no . ' Quarter'] =  $quarter_val;
                                ?>  
                                <tr>
                                    <td><?php echo $quarter_year . ', ' . $quarter_no . ' Quarter'; ?></td>
                                    <td><?php echo number_format($quarter_val, 2); ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>

            </div> 

        <?php } ?>
            
            <div id="quarter_chart_data" data-all='<?php echo htmlspecialchars(json_encode($quarter_chart_data_arr) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
        
        </div>
        
        <div id="quarter-chart-sec">
            
            <div class="row">
                
                <?php foreach ($quarter_chart_data_arr AS $chart_name => $chart_val) {?>
                
                <div class="col-xl-6 col-sm-12 col-12">

                    <div class="chart_dsgn" id="<?php echo $chart_name . '_chart'; ?>" data-plot_data="close_price" data-plot_data_name="Close Price" data-colorz="green" data-full_screen="0"></div> 
                </div>
                
                <?php } ?>

            </div>
            
        </div>
        
        <!-- Quarter End -->
        
        <!-- Montly Start -->
        
        <h2 class="mb-30">Monthly Analysis</h2>
        
        <div class="row mb-60">
        
        <?php 
        
            $monthly_chart_data_arr = array();
            
            foreach($avg_data AS $tb_column=> $avg_data_arr){ ?>
        
              <div class="col-xl-2 col-sm-12 col-12">
                <h5><?php echo ucfirst(str_replace('_', ' ', $tb_column)); ?></h5>
                <table id="<?php echo $tb_column . '_month'; ?>" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($avg_data_arr['month'] AS $month_year => $month_first_key_val) {
                            foreach ($month_first_key_val AS $month_name => $month_val) {
                                
                                $monthly_chart_data_arr[str_replace('_', ' ', $tb_column) . ' monthly'][$month_year . ', ' . $month_name] =  $month_val;
                                
                                ?>  
                                <tr>
                                    <td><?php echo $month_year . ', ' . $month_name; ?></td>
                                    <td><?php echo number_format($month_val,2); ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>

            </div> 

        
        <?php } ?>
        
            <div id="monthly_chart_data" data-all='<?php echo htmlspecialchars(json_encode($monthly_chart_data_arr) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
            
        </div>
        
        <div id="monthly-chart-sec">
            
            <div class="row">
                
                <?php foreach ($monthly_chart_data_arr AS $chart_name => $chart_val) {?>
                
                <div class="col-xl-6 col-sm-12 col-12">

                    <div class="chart_dsgn" id="<?php echo $chart_name . '_chart'; ?>" data-plot_data="close_price" data-plot_data_name="Close Price" data-colorz="green" data-full_screen="0"></div> 
                </div>
                
                <?php } ?>

            </div>
            
        </div>
        
        <!-- Montly End -->
        
        <!-- Weekly Start -->
        
        <h2 class="mb-30">Weekly Analysis</h2>
        
        <div class="row mb-60">
        
        <?php 
        
            $weekly_chart_data_arr = array();
            
            foreach($avg_data AS $tb_column=> $avg_data_arr){ ?>
        
              <div class="col-xl-2 col-sm-12 col-12">
                <h5><?php echo ucfirst(str_replace('_', ' ', $tb_column)); ?></h5>
                <table id="<?php echo $tb_column . '_week'; ?>" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Week No</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($avg_data_arr['week'] AS $weeks_year => $weeks_first_key_val) {
                            foreach ($weeks_first_key_val AS $month_name => $weeks_second_key_val) {
                                foreach ($weeks_second_key_val AS $week_no => $week_val) {
                                    
                                    $weekly_chart_data_arr[str_replace('_', ' ', $tb_column) . ' weekly'][$weeks_year . ', ' . $month_name . ' , ' . $week_no] =  $week_val;
                                    
                                    ?>  
                                    <tr>
                                        <td><?php echo $weeks_year . ', ' . $month_name . ' , ' . $week_no; ?></td>
                                        <td><?php echo number_format($week_val,2); ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                        }
                        ?>

                    </tbody>
                </table>

            </div>

        
        <?php } ?>
        
            <div id="weekly_chart_data" data-all='<?php echo htmlspecialchars(json_encode($weekly_chart_data_arr) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
            
        </div>
        
        <div id="weekly-chart-sec">
            
            <div class="row">
                
                <?php foreach ($weekly_chart_data_arr AS $chart_name => $chart_val) {?>
                
                <div class="col-xl-6 col-sm-12 col-12">

                    <div class="chart_dsgn" id="<?php echo $chart_name . '_chart'; ?>" data-plot_data="close_price" data-plot_data_name="Close Price" data-colorz="green" data-full_screen="0"></div> 
                </div>
                
                <?php } ?>

            </div>
            
        </div>
        <!-- Weekly End -->
        
    </div>

<?php } ?>

</div>