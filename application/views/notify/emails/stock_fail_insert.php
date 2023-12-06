<!DOCTYPE html>
<html>
<body>

    <h2>Information Are: </h2>
    
    <ol type="1">
        <?php foreach( $data AS $data_value){?>
        <li>
            <?php echo $data_value; ?>
        </li>
        <?php } ?>
    </ol>

</body>
</html>

