<?php  $this->load->helper('function_helper'); ?>

<html>
    <body>
        
        <?php if (!empty($oc_pd_iv_data) && count($oc_pd_iv_data) > 0) { ?>
            
            <table class="table table-striped" style="border-collapse: collapse;">
                
                <thead>
                    
                    <tr>
                        <th style="border: 1px solid black">Company Symbol</th>
                        <th style="border: 1px solid black">Option Chain</th>
                        <th style="border: 1px solid black">IV Analysis</th>
                        <th style="border: 1px solid black">PD Analysis</th>

                    </tr>
                    
                </thead>
                <tbody>
                    
                    <?php foreach ($oc_pd_iv_data AS $oc_pd_iv_data_val) { ?>
                    
                    <tr>                                    

                        <td>
                            
                            <a href="<?php echo PARENT_WEB_SERVER . 'daily-log/?company_id='.$oc_pd_iv_data_val["company_id"].'&company_symbol='.base64_url_encode($oc_pd_iv_data_val["company_symbol"]).'&stock_date='. date('Y-m-d').'&stock_date_to=' . date('Y-m-d'); ?>">
                            
                                <?php echo $oc_pd_iv_data_val["company_symbol"]; ?>
                                
                            </a>
                            
                        </td>
                        
                        <td>
                            <a href="<?php echo PARENT_WEB_SERVER . 'option-chain/stock-info/'.$oc_pd_iv_data_val["company_id"] . '/' . base64_url_encode($oc_pd_iv_data_val["company_symbol"]); ?>">OC Link</a>
                        </td>
                        
                        <td>
                            <a href="<?php echo PARENT_WEB_SERVER . 'option-chain/iv-analysis/'.$oc_pd_iv_data_val["company_id"] . '/' . base64_url_encode($oc_pd_iv_data_val["company_symbol"]); ?>">IV Link</a>
                        </td>
                        
                        <td>
                            <a href="<?php echo PARENT_WEB_SERVER . 'option-chain/pd-analysis/'.$oc_pd_iv_data_val["company_id"] . '/' . base64_url_encode($oc_pd_iv_data_val["company_symbol"]); ?>">PD Link</a>
                        </td>
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