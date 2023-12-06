<?php $this->load->helper('function_helper'); ?>

<style>
    
    @media (min-width: 1800px){
        div.container {
            max-width: 1800px;
        }
    }
    
    /*Date picker start*/
    .htm-date-container {
        position: relative;
        float: left;
    }

    .htm-date {
        border: 1px solid #1cbaa5;
        padding: 5px 10px;
        height: 30px;
        width: 165px;
    }

    .open-date-button {
        position: absolute;
        top: 3px;
        left: 145px;
        width: 25px;
        height: 25px;
        background: #fff;
        pointer-events: none;
    }

    .open-date-button button {
        border: none;
        background: transparent;
    }
    /*Date picker end*/
    
    /* Fixed Table Header Start*/ 
   thead tr:nth-child(1) th{
        background: white;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    /* Fixed Table Header End*/
    
    .mt-60{margin-top: 60px;}
    .mb-60{margin-bottom: 60px;}
    .mb-30{margin-bottom: 30px;}
    .mb-20{margin-bottom: 20px;}
    .mb-10{margin-bottom: 10px;}
    
    .sel-enc-p{
        background: #007bff;
        color: #fff;
    }
</style>
<div class="container">
    
    <h1 class='mb-60'>Pledged Data <?php if( !empty($company_id) && !empty($company_symbol) ){ echo 'of ' . $company_symbol; } ?></h1>
    
    <h3 class="mb-30">Broadcast date: <b><?php echo date('d M Y', strtotime($broadcaste_date) ) ; ?></b>
    
    <?php if( !empty($company_id) && !empty($company_symbol) && !empty($broadcaste_date_to) ){?>
        
        to <b><?php echo date('d M Y', strtotime($broadcaste_date_to) ) ; ?></b>
        
    <?php } ?>
        
     </h3>
    
    <form method="get" action="<?php echo base_url($url); ?>">
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($broadcaste_date) ? date('Y-m-d') : $broadcaste_date; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            
            <?php if( !empty($company_id) && !empty($company_symbol) ){?>
            
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($broadcaste_date_to) ? date('Y-m-d') : $broadcaste_date_to; ?>"  onchange="changeStockDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            
            <input type="hidden" class="company_id" value="<?php echo $company_id; ?>">
            
            <input type="hidden" class="broadcaste_date" name="broadcaste_date" value="<?php echo empty($broadcaste_date_all) ? date('Y-m-d') :$broadcaste_date_all; ?>">
            
            <input type="hidden" class="broadcaste_date_to" name="broadcaste_date_to" value="<?php echo empty($broadcaste_date_to) ? date('Y-m-d') :$broadcaste_date_to; ?>">
            
            
            <?php }else{ ?>
            
            <input type="hidden" class="broadcaste_date" name="broadcaste_date" value="<?php echo empty($broadcaste_date) ? date('Y-m-d') :$broadcaste_date; ?>">
            
            <?php }?>

        </div>
        
        <div class="row mb-20">
            
            <div class="col-xl-3 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        % Of Promoter Holding - <?php echo ucfirst($prmtr_hldng_p_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_prmtr_hldng_p_val <?php echo ($prmtr_hldng_p_sortby==='high') ? 'sel-enc-p' : ''; ?>" href="javascript:void(0)" data-prmtr-hldng-p-sortby="high">High</a>
                        <a class="dropdown-item sort_by_prmtr_hldng_p_val <?php echo ($prmtr_hldng_p_sortby==='low') ? 'sel-enc-p' : ''; ?>" href="javascript:void(0)" data-prmtr-hldng-p-sortby="low">Low</a>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        % Of Promoter Shares Encumbered - <?php echo ucfirst($encumb_p_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_encumb_p_val <?php echo ($encumb_p_sortby==='high') ? 'sel-enc-p' : ''; ?>" href="javascript:void(0)" data-encumb-p-sortby="high">High</a>
                        <a class="dropdown-item sort_by_encumb_p_val <?php echo ($encumb_p_sortby==='low') ? 'sel-enc-p' : ''; ?>" href="javascript:void(0)" data-encumb-p-sortby="low">Low</a>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        % Of Shares Pledged Demat- <?php echo ucfirst($dmat_pldg_p_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_dmat_pldg_p_val <?php echo ($dmat_pldg_p_sortby==='high') ? 'sel-enc-p' : ''; ?>" href="javascript:void(0)" data-dmat-pldg-p-sortby="high">High</a>
                        <a class="dropdown-item sort_by_dmat_pldg_p_val <?php echo ($dmat_pldg_p_sortby==='low') ? 'sel-enc-p' : ''; ?>" href="javascript:void(0)" data-dmat-pldg-p-sortby="low">Low</a>
                    </div>
                </div>
            </div>
                        
        </div>
        
        <div class="row mb-20">
            
            <div class="col-xl-1 col-6 mb-30 form-check">
                <a href="<?php echo base_url($url); ?>">Reset</a>
            </div>
            
            <?php if( !empty($company_id) && !empty($company_symbol) ){?>
            <div class="col-xl-1 col-6 mb-30 form-check">
                <a href="<?php echo base_url('share-corporate/pledged-data/'.$company_id.'/'.base64_url_encode($company_symbol).'/all'); ?>">All Date</a>
            </div>
            <?php } ?>
        </div>
        
        <?php // echo $market_date; exit;?>
        
        <input type="hidden" class="prmtr_hldng_p_sortby" name="prmtr_hldng_p_sortby">
        <input type="hidden" class="encumb_p_sortby" name="encumb_p_sortby">
        <input type="hidden" class="dmat_pldg_p_sortby" name="dmat_pldg_p_sortby">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <?php if( !empty($pledged_data) && count($pledged_data) > 0 ){ ?>
    
    <h4 class="mb-20">Total Result : <?php echo count($pledged_data); ?></h4>
    
    <table class="table table-bordered">
        <thead>
            <tr id="first_row">
                <th width="10%" style="left: -1px;"></th>
                <th width="8%"></th>
                <th width="8%"></th>
                <th width="16%" colspan="2" class="text-center">Total Promoter Holding</th>
                <th width="10%"></th>
                <th width="24%" colspan="3" class="text-center">Promoter shares Encumbered as of last quarter</th>
                <th width="8%"></th>
                <th width="24%" colspan="3" class="text-center">No. of shares pledged in the depository system</th>
            </tr>
            <tr id="second_row">
                <?php if( !empty($company_id) && !empty($company_symbol) && ( !empty($broadcaste_date_to) || $broadcaste_date_all ==='all' ) ){?>
                <th>Broadcast Date Time</th>
                <?php } else{?>
                <th>Broadcast Time</th>
                <?php } ?>
                <th>Company</th>
                <th>Total No. Of Issued Shares </th>
                <th>No. Of Shares </th>
                <th>Holding %</th>
                
                <th>Total Public Holding</th>
                
                <th>No. Of Shares</th>
                <th>% Of Promoter Shares</th>
                <th>% Of Total Shares</th>
                
                <th>Disclosure Made By Promoters</th>
                
                <th>No. Of Shares Pledged</th>
                <th>Total No. Of Demat Shares</th>
                <th>(%) Pledge/ Demat</th>
                
              
            </tr>
        </thead>
        <tbody>
            
             <?php foreach ( $pledged_data AS $pledged_data_key => $pledged_data_value ) { ?>
            
            <tr>
                <?php if( !empty($company_id) && !empty($company_symbol) && ( !empty($broadcaste_date_to) || $broadcaste_date_all ==='all' ) ){?>
                
                <td><?php echo date('d M Y h:i a ', strtotime($pledged_data_value->broadcaste_datetime) ); ?></td>
                
                <td>
                    <a href="<?php echo base_url() . 'daily-log/' . $pledged_data_value->company_id . '/' . base64_url_encode($pledged_data_value->company_symbol); ?>">
                    <?php echo $pledged_data_value->company_symbol; ?>
                    </a>
                </td>
                
                <?php } else{?>
                
                <td><?php echo date('h:i a ', strtotime($pledged_data_value->broadcaste_time) ); ?></td>
                
                <td>
                    <a href="<?php echo base_url() . 'share-corporate/pledged-data/' . $pledged_data_value->company_id . '/' . base64_url_encode($pledged_data_value->company_symbol) . '/' . $pledged_data_value->broadcaste_date; ?>">
                    <?php echo $pledged_data_value->company_symbol; ?>
                    </a>
                </td>
                
                <?php }?>                
                
                <td><?php echo number_format($pledged_data_value->tot_issued_shares); ?></td>
                <td><?php echo number_format($pledged_data_value->tot_promoter_holding); ?></td>
                <td><?php echo number_format($pledged_data_value->perc_promoter_holding, 2); ?></td>
                
                <td><?php echo number_format($pledged_data_value->tot_public_holding); ?></td>
                
                <td><?php echo number_format($pledged_data_value->tot_promoter_shares_enc); ?></td>
                <td><?php echo number_format($pledged_data_value->perc_promoter_shares_enc); ?></td>
                <td><?php echo number_format($pledged_data_value->perc_tot_shares_enc, 2); ?></td>
                
                <td><?php echo ($pledged_data_value->disclosure_to_date=='1970-01-01' ) ? '' : date('d M Y', strtotime($pledged_data_value->disclosure_to_date) ); ?></td>
                
                <td><?php echo number_format($pledged_data_value->num_shares_pledged_demat); ?></td>
                <td><?php echo number_format($pledged_data_value->tot_demat_shares); ?></td>
                <td><?php echo number_format($pledged_data_value->perc_shares_pledged_demat, 2); ?></td>
                
                
            </tr>
             <?php } ?>
            
        </tbody>
    </table>
    
    <?php }else{ ?>
    
    <div class=" mt-60">
        <div class="alert alert-danger">
            <strong>No Data Available for Date <?php echo date('d M Y', strtotime($broadcaste_date) ); ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>