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
    
    <?php if( !empty($share_data) && count($share_data) > 0 ){ ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Significant Beneficial Owner (SBO) Name</th>
                <th>Nationality of SBO</th>
                <th>SBO Pan</th>
                <th>SBO Passport</th>
                <th>Registered Owner (RO) Name</th>
                <th>Nationality of RO</th>
                <th>RO Pan</th>
                <th>RO Passport</th>
                <th>SBO Share % </th>
                <th>SBO Voting Right % </th>                
                <th>Rights On Distribution(%) Dividend OR Any Other Distribution</th>
                <th>Exercise Of Control</th> 
                <th>Exercise Of Significant Influence</th>
                <th>Date Of Creation / Acquisition Of Significant Beneficial Interest#</th>
            </tr>
        </thead>
        <tbody>
            
             <?php foreach ( $share_data AS $share_data_key => $share_data_value ) { ?>
            
            <tr>
                <td><?php echo $share_data_value->sbo_name; ?></td>
                <td><?php echo $share_data_value->sbo_nationality; ?></td>
                <td><?php echo $share_data_value->sbo_pan; ?></td>
                <td><?php echo $share_data_value->sbo_passport; ?></td>
                <td><?php echo $share_data_value->regis_owner_name; ?></td>
                <td><?php echo $share_data_value->regis_owner_nationality; ?></td>
                <td><?php echo $share_data_value->regis_owner_pan; ?></td>
                <td><?php echo $share_data_value->regis_owner_passport; ?></td>
                <td><?php echo $share_data_value->regis_owner_share; ?></td>
                <td><?php echo $share_data_value->regis_owner_vote_right; ?></td>
                <td><?php echo $share_data_value->regis_owner_rights; ?></td>
                <td><?php echo $share_data_value->exec_control; ?></td>
                <td><?php echo $share_data_value->exec_sign_influ; ?></td>
                <td><?php echo date('d M Y', strtotime($share_data_value->creation_acq_date)); ?></td>
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