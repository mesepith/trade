<!DOCTYPE html>
<html>
<body>
<?php

//echo '<pre>';
//print_r($all_good_stock_arr);

if (count($mis_match_company_arr)>0){

?>
    <h2>List of Company with mismatch data on <?php echo date('d M Y'); ?></h2>

        <ol type="1">
            <?php foreach( $mis_match_company_arr AS $mis_match_company_arr_value){?>
            <li>
                <?php echo $mis_match_company_arr_value; ?>
            </li>
            <?php } ?>
        </ol>
    
    

<?php
}else{
?>
<div>No mismatch company found on <?php echo date('d M Y'); ?></div>
<?php
    
}

?>
  

</body>
</html>

