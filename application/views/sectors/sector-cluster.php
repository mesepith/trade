<style>
    .mb-60{margin-bottom: 60px;}
    .mb-30{margin-bottom: 30px;}
    .mt-60{margin-top: 60px;}
</style>
<div class="container">
    
    <h2 class="mb-30"><?php echo 'Analysis of <b>' . $report_name . '</b> '; ?></h2>
    
<?php if (!empty($avg_data)) { ?>

    <div class="avg_return">
        
        <!-- Quarter Start -->
        
        <h2 class="mb-30">Quarterly Analysis</h2>
        
        <div class="row mb-60">
            
        <?php foreach($avg_data AS $tb_column=> $avg_data_arr){ ?>
        
            <div class="col-xl-3 col-sm-12 col-12">
                
                <h3><?php echo ucfirst(str_replace('_', ' ', $tb_column)); ?></h3>
                
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
        
        </div>
        
        <!-- Quarter End -->
        
        <!-- Montly Start -->
        
        <h2 class="mb-30">Monthly Analysis</h2>
        
        <div class="row mb-60">
        
        <?php foreach($avg_data AS $tb_column=> $avg_data_arr){ ?>
        
              <div class="col-xl-3 col-sm-12 col-12">
                <h3><?php echo ucfirst(str_replace('_', ' ', $tb_column)); ?></h3>
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
        
        </div>
        
        <!-- Montly End -->
        
        <!-- Weekly Start -->
        
        <h2 class="mb-30">Weekly Analysis</h2>
        
        <div class="row mb-60">
        
        <?php foreach($avg_data AS $tb_column=> $avg_data_arr){ ?>
        
              <div class="col-xl-3 col-sm-12 col-12">
                <h3><?php echo ucfirst(str_replace('_', ' ', $tb_column)); ?></h3>
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
        
        </div>
        
        <!-- Weekly End -->
        
    </div>

<?php } ?>

</div>