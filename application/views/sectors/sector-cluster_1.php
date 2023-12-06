<style>
    .close_tbl{
        cursor: pointer;
    }
</style>
<script>
$(document).on('click', '.close_tbl', function () {
    
    if($(this).data('hide') === 0 ){
        
        $("#" +  $(this).data('valz')).hide(); 
        $(this).data('hide', 1);
        
    }else{
        
        $("#" +  $(this).data('valz')).show(); 
        $(this).data('hide', 0);
    }
});
</script>
<div class="container">
<?php if (!empty($avg_data)) { ?>

    <div class="avg_return">
        
        <?php foreach($avg_data AS $tb_column=> $avg_data_arr){ ?>
        
        <h3><?php echo ucfirst(str_replace('_', ' ', $tb_column)); ?></h3>

        <div class="row mt-60">
            
            <div class="col-xl-3 col-sm-12 col-12">
                <span class="close_tbl" data-valz="<?php echo $tb_column . '_quarter'; ?>" data-hide="0">X</span>
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

            <div class="col-xl-3 col-sm-12 col-12">
                <span class="close_tbl" data-valz="<?php echo $tb_column . '_month'; ?>" data-hide="0">X</span>
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

            <div class="col-xl-3 col-sm-12 col-12">
                <span class="close_tbl" data-valz="<?php echo $tb_column . '_week'; ?>" data-hide="0">X</span>
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

        </div>
        
        <?php } ?>
    </div>

<?php } ?>

</div>