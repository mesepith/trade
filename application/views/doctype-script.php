<!doctype html>
<html>

    <head>
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
       <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/bootstrap.min.css' ?>">
       <link href = "<?php echo base_url() . 'assets/plugin/slider/jquery-ui.css' ?>" rel = "stylesheet">
       <script src="<?php echo base_url() . 'assets/js/jquery.min.js' ?>"></script>
       <script src="<?php echo base_url() . 'assets/js/popper.min.js' ?>"></script>
       <script src="<?php echo base_url() . 'assets/js/bootstrap.min.js' ?>"></script>
        <script src = "<?php echo base_url() . 'assets/plugin/slider/jquery-ui.js' ?>"></script>
       
        <?php
        if (!empty($css)) {

            foreach ($css as $style) {
                ?>

                <link rel="stylesheet" href="<?php echo base_url(). $style ?>">

    <?php }
}
?>
        
    </head>