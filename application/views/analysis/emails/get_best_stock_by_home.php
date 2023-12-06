<!DOCTYPE html>
<html>
<body>
<?php

//echo '<pre>';
//print_r($all_good_stock_arr);

if (count($all_good_stock_arr)>0){

?>
    <h2>List of Best Stock by Home Page Logic on <?php echo date('d M Y'); ?></h2>

        <ol type="1">
            <?php foreach( $all_good_stock_arr AS $stock_name){?>
            <li>
                <a href="<?php echo base_url() . 'daily-log/?company_id='.$nestedData['company_arr'][$stock_name]['company_id'].'&company_symbol='.$stock_name.'&stock_date='.$nestedData['from_date'].'&stock_date_to='.$nestedData['to_date'];?>">
                    <?php echo $stock_name; ?>
                </a>
            </li>
            <?php } ?>
        </ol>
    
    <div><?php echo 'Analysis from ' . date('d-M-Y', strtotime($nestedData['from_date'])) . ' to ' . date('d-M-Y', strtotime($nestedData['to_date'])); ?></div>

<?php
}else{
?>
<div>No Best Stock Found on <?php echo date('d M Y'); ?></div>
<?php
    
}

?>
  

</body>
</html>

