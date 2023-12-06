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

    <div>
        <p>
            Check in NSE to check if Future Data of <b><?php echo $data['company_symbol']; ?></b> is still available or not by clicking 
            <a href="https://www.nseindia.com/get-quotes/equity?symbol=<?php echo urlencode($data['company_symbol']); ?>">Here</a>
        </p>
    </div>
    
    <div>
        If Stock <b><?php echo $data['company_symbol']; ?></b> is not present in NSE then make Stock <b><?php echo $data['company_symbol']; ?></b> deactivate by clicking below button
    </div>

    <br/><br/>
    <div>
        <a class='button-red' href="<?php echo PARENT_WEB_SERVER . 'Fetch_Future_Contr/makeFutureCompanyInactive/' . $data['company_id'] . '/' . $data['company_symbol'] ; ?>">
            Deactivate <?php echo $data['company_symbol']; ?>
        </a>
    </div>

</body>
</html>

