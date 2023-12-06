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
    
    <h2>Declaration: The Listed entity has submitted the following declaration.</h2>
    
    <?php if( !empty($share_data) && count($share_data) > 0 ){ ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Particulars</th>
                <th>Promoter & Promoter Group</th>
                <th>Public</th>
                <th>Non Promoter Non Public</th>
            </tr>
        </thead>
        <tbody>
            
             <?php foreach ( $share_data AS $share_data_key => $share_data_value ) { ?>
            
            <tr>
                <td><?php echo $share_data_value->question; ?></td>
                <td><?php echo $share_data_value->promoter_group; ?></td>
                <td><?php echo $share_data_value->public; ?></td>
                <td><?php echo $share_data_value->non_public; ?></td>
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