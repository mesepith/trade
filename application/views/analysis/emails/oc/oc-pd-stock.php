<?php  $this->load->helper('function_helper'); ?>
<html>
    <body>
        
        <?php if (!empty($oc_pd_data) && count($oc_pd_data) > 0) { ?>
        
        <p>Analysis:</p> 
        
        <table class="table table-striped" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid black">Company Symbol</th>
                    <th style="border: 1px solid black">Expiry Date</th>
                    <th style="border: 1px solid black">Underlying Date Start</th>
                    <th style="border: 1px solid black">Put Avg Decay</th>
                    <th style="border: 1px solid black">Call Avg Decay</th>

                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($oc_pd_data AS $oc_pd_data) { ?>

                    <tr>                                    

                        <td>
                            
                            <a href="<?php echo PARENT_WEB_SERVER . 'daily-log/?company_id='.$oc_pd_data->company_id.'&company_symbol='.base64_url_encode($oc_pd_data->company_symbol).'&stock_date='. $date.'&stock_date_to=' . date('Y-m-d'); ?>">
                            
                                <?php echo $oc_pd_data->company_symbol; ?>
                                
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo PARENT_WEB_SERVER . 'option-chain/stock-info?company_id='.$oc_pd_data->company_id.'&company_symbol='.base64_url_encode($oc_pd_data->company_symbol).'&sud='. $oc_pd_data->underlying_date_end.'&sed='. $oc_pd_data->expiry_date; ?>">
                                
                                <?php echo date('d M Y', strtotime($oc_pd_data->expiry_date)); ?>
                                
                            </a>
                        </td>
                        <td><?php echo date('d M Y', strtotime($oc_pd_data->underlying_date_start)); ?></td>
                        <td><?php echo $oc_pd_data->put_avg_decay; ?></td>
                        <td><?php echo $oc_pd_data->call_avg_decay; ?></td>


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
        
    </body>
</html>