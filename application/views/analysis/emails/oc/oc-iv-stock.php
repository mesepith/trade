<?php  $this->load->helper('function_helper'); ?>

<!DOCTYPE html>
<html>
    <body>
        <div class="container">
            <?php if (!empty($oc_iv_data) && count($oc_iv_data) > 0) { ?>

                <p>Analysis:</p>            

                <table class="table table-bordered" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black">Company Symbol</th>
                            <th style="border: 1px solid black">Underlying Price</th>
                            <th style="border: 1px solid black">Strike Price</th>
                            <th style="border: 1px solid black">Calls IV</th>
                            <th style="border: 1px solid black">Puts IV</th>
                            <th style="border: 1px solid black">Strike Price With Highest Oi in call</th>
                            <th style="border: 1px solid black">Strike Price With Highest Oi in put</th>
                            <th style="border: 1px solid black" class="<?php if (!empty($bearish_probability)) {
                echo 'filter-column-col';
            } ?>" >Bearish Probability</th>

                            <th style="border: 1px solid black" class="<?php if (!empty($bullish_probability)) {
                echo 'filter-column-col';
            } ?>">Bullish Probability</th>


                        </tr>
                    </thead>
                    <tbody>

    <?php foreach ($oc_iv_data AS $oc_iv_data_value) { ?>

                            <tr>                                    

                                <td>

                                    <a href="<?php echo PARENT_WEB_SERVER . 'daily-log/?company_id=' . $oc_iv_data_value->company_id . '&company_symbol=' . base64_url_encode($oc_iv_data_value->company_symbol). '&stock_date=' . $date . '&stock_date_to=' . date('Y-m-d'); ?>">

        <?php echo $oc_iv_data_value->company_symbol; ?>

                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo PARENT_WEB_SERVER . 'option-chain/stock-info?company_id=' . $oc_iv_data_value->company_id . '&company_symbol=' . base64_url_encode($oc_iv_data_value->company_symbol) . '&sud=' . $date . '&sed=' . $oc_iv_data[0]->expiry_date; ?>">
        <?php echo $oc_iv_data_value->underlying_price; ?>
                                    </a>
                                </td>
                                <td>  <?php echo $oc_iv_data_value->strike_price; ?> </td>
                                <td><?php echo $oc_iv_data_value->calls_iv; ?></td>
                                <td><?php echo $oc_iv_data_value->puts_iv; ?></td>
                                <td><?php echo $oc_iv_data_value->strike_price_with_highest_oi_in_call; ?></td>
                                <td><?php echo $oc_iv_data_value->strike_price_with_highest_oi_in_put; ?></td>
                                <td class="<?php if (!empty($bearish_probability)) {
            echo 'filter-column-col';
        } ?>" ><?php echo $oc_iv_data_value->bearish_probability; ?></td>

                                <td class="<?php if (!empty($bullish_probability)) {
            echo 'filter-column-col';
        } ?>"><?php echo $oc_iv_data_value->bullish_probability; ?></td>



                            </tr>

    <?php } ?>

                    </tbody>
                </table>

            <?php } else { ?>

                <div>
                    <div class="alert alert-danger">
                        <strong>No Data Available </strong> 
                    </div>
                </div>

<?php } ?>

        </div>
    </body>
</html>

