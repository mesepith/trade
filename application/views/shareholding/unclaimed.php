<style>
    
    @media (min-width: 1800px){
        div.container {
            max-width: 1800px;
        }
    }
    
    .mt-60{margin-top: 60px;}
    .mb-60{margin-bottom: 60px;}
    .mb-20{margin-bottom: 20px;}
    
    .sel-share-type, .sel-pub-date{
         background: #007bff;
         color: #fff;
     }
</style>
<div class="container">
    
    <?php $this->load->view('shareholding/pattern-heading'); ?>
    
    <h2>Details of Unclaimed shares</h2>
    
    <?php if( !empty($share_data) && count($share_data) > 0 ){ ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Shareholder Type</th>
                <th>No. Of Shareholders</th>
                <th>No Of Shares Held</th>
            </tr>
        </thead>
        <tbody>
            
             <?php foreach ( $share_data AS $share_data_key => $share_data_value ) { ?>
            
            <tr>
                <td><?php echo $share_data_value->shares_type; ?></td>
                <td><?php echo  money_format('%!.0n', $share_data_value->no_of_shareholders); ?></td>
                <td><?php echo money_format('%!.0n', $share_data_value->no_of_shares); ?></td>
            </tr>
             <?php } ?>
            
        </tbody>
    </table>
    
    <?php }else{ ?>
    
    <div class=" mt-60">
        <div class="alert alert-danger">
            <strong>No Data Available for <?php echo $company_symbol; ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>