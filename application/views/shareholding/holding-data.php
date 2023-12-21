<?php $this->load->helper('function_helper'); ?>
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
     
     table.table-striped tbody tr.fpi-cat{
         background: #28a745;
         color: #fff;
     }
</style>
<div class="container">
    
    <?php $this->load->view('shareholding/pattern-heading'); ?>
    
    <?php if( !empty($share_data) && count($share_data) > 0 ){ ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>CATEGORY</th>
                <th>CATEGORY OF SHAREHOLDER</th>
                <th>NOS. OF SHAREHOLDERS</th>
                <th>NO. OF FULLY PAID UP EQUITY SHARES HELD </th>
                <th>TOTAL NOS. SHARES HELD </th>
                <th>SHAREHOLDING AS A % OF TOTAL NO. OF SHARES (CALCULATED AS PER SCRR, 1957) AS A % OF (A+B+C2) </th>
                <th>NO OF VOTING RIGHTS : CLASS X </th>
                <th>NO OF VOTING RIGHTS : TOTAL </th>
                <th>TOTAL AS A % OF (A+B+C) </th>
                <th>SHAREHOLDING , AS A % ASSUMING FULL CONVERSION OF CONVERTIBLE SECURITIES ( AS A PERCENTAGE OF DILUTED SHARE CAPITAL) AS A % OF (A+B+C2) </th>
                <th>NUMBER OF EQUITY SHARES HELD IN DEMATERIALIZED FORM </th>
            </tr>
        </thead>
        <tbody>
            
             <?php foreach ( $share_data AS $share_data_key => $share_data_value ) { ?>
            
            <tr class="<?php echo ($share_data_value->shareholder_category=== 'Mutual Funds/' || $share_data_value->shareholder_category=== 'Foreign Portfolio Investors' || $share_data_value->shareholder_category=== 'Foreign Portfolio Investor') ? 'fpi-cat' : ''; ?>">
                <td><?php echo $share_data_value->category; ?></td>
                <td>
                    <?php echo $share_data_value->shareholder_category; ?>
                </td>
                <td><?php echo indianNumberFormat($share_data_value->shareholders_no); ?></td>
                <td><?php echo indianNumberFormat($share_data_value->fully_paid_up_equity_shares_no); ?></td>
                <td><?php echo indianNumberFormat($share_data_value->total_shares); ?></td>
                <td><?php echo $share_data_value->share_in_p_a; ?></td>
                <td><?php echo indianNumberFormat($share_data_value->no_of_voting_right); ?></td>
                <td><?php echo indianNumberFormat($share_data_value->total_no_of_voting_right); ?></td>
                <td><?php echo $share_data_value->voting_share_p; ?></td>
                <td><?php echo $share_data_value->share_in_p_b; ?></td>
                <td><?php echo indianNumberFormat($share_data_value->no_of_shares_demat_form); ?></td>
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