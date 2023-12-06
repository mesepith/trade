<!DOCTYPE html>
<html>
<body>
<style>
    .button-red {
        display: block;
        width:250px;
        height: 25px;
        background: red;
        padding: 10px;
        text-align: center;
        border-radius: 5px;
        color: white;
        font-weight: bold;   
        text-decoration: none;
    }
</style>

    <h2>Information Are: </h2>
    
    <ol type="1">
        <li><?php echo 'ENVIRONMENT : ' . ENVIRONMENT;?></li>
        <li><?php echo 'SERVER_NAME : ' . SERVER_NAME;?></li>
        <li><?php echo 'FINAL_DATA_SERVER : ' . FINAL_DATA_SERVER;?></li>
        <?php foreach( $_SERVER AS $server_key=>$server_value){?>
        <li>
            <?php echo $server_key . ' -  ' . $server_value; ?>
        </li>
        <?php } ?>
    </ol>
    
    <div>
        <p>
            Check in NSE to check if Option Data <b><?php echo $data['company_symbol']; ?></b> is still available or not by clicking 
            <a href="https://www.nseindia.com/get-quotes/equity?symbol=<?php echo urlencode($data['company_symbol']); ?>">Here</a>
        </p>
    </div>
    
    <div>
        If Stock <b><?php echo $data['company_symbol']; ?></b> is not present in NSE then make Stock <b><?php echo $data['company_symbol']; ?></b> deactivate by clicking below button
    </div>
    <br/><br/>
    <div>
        <a class='button-red' href="<?php echo PARENT_WEB_SERVER . 'Put_Call/makeOCCompanyInactive/' . $data['company_id'] . '/' . $data['company_symbol'] ; ?>">
            Deactivate <?php echo $data['company_symbol']; ?>
        </a>
    </div>

</body>
</html>

