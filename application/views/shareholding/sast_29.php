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
    
    .sel-sec{
        background: #007bff;
        color: #fff;
    }
</style>

<div class="container">
    
    <h1 class='mb-60'>SAST 29 <?php if( !empty($company_id) && !empty($company_symbol) ){ echo 'of ' . $company_symbol; } ?></h1>
    
    <?php 
            if( !empty($acq_saler_name) ){
                echo '<h3 class="mb-60">Name Of The Acquirer/ Saler : <b>'.$acq_saler_name.' </b> </h3>';
            }
    ?>
    
    <h3 class="mb-30">Broadcast date: 
        <b>
            <?php 
            if( !empty($acq_saler_name) && $broadcaste_date_all === 'all'){
                
                echo 'All';
            }else{
                echo date('d M Y', strtotime($broadcaste_date) ) ; 
            }
        ?>
        </b>
    
    <?php if( ( !empty($company_id) && !empty($company_symbol) || !empty($acq_saler_name) ) && !empty($broadcaste_date_to) ){?>
        
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
            
            <?php if( ( !empty($company_id) && !empty($company_symbol) ) || !empty($acq_saler_name) ){?>
            
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
            
            <div class="acq_saler_name" data-valz="<?php echo $acq_saler_name; ?>"></div>
            
            <?php }else{ ?>
            
            <input type="hidden" class="broadcaste_date" name="broadcaste_date" value="<?php echo empty($broadcaste_date) ? date('Y-m-d') :$broadcaste_date; ?>">
            
            <?php }?>
        </div>  
        
        <div class="row mb-20">

            <div class="col-xl-2 col-4 mb-30 form-check">
                Select Acquisition/ Sale
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_or_sale_disp" value="all" name="acq_or_sale_disp" <?php echo ( $acq_or_sale_disp === "all" ) ? 'checked' : ''; ?>>All
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_or_sale_disp" value="Acquisition" name="acq_or_sale_disp" <?php echo ( $acq_or_sale_disp === "Acquisition" ) ? 'checked' : ''; ?>>Acquisition
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_or_sale_disp" value="Sale" name="acq_or_sale_disp" <?php echo ( $acq_or_sale_disp === "Sale" ) ? 'checked' : ''; ?>>Sale
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_or_sale_disp" value="Others" name="acq_or_sale_disp" <?php echo ( $acq_or_sale_disp === "Others" ) ? 'checked' : ''; ?>>Others
                </label>
            </div>

        </div>
        
        <div class="row mb-20">

            <div class="col-xl-2 col-4 mb-30 form-check">
                Select Promoter Type
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_promoter_type" value="all" name="promoter_type" <?php echo ( $promoter_type === "all" ) ? 'checked' : ''; ?>>All
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_promoter_type" value="Y" name="promoter_type" <?php echo ( $promoter_type === "Y" ) ? 'checked' : ''; ?>>Y
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_promoter_type" value="N" name="promoter_type" <?php echo ( $promoter_type === "N" ) ? 'checked' : ''; ?>>N
                </label>
            </div>

        </div>
        
        
        <div class="row mb-30">

            <div id="today_date" data-valz="<?php echo date('Y-m-d'); ?>"></div>

            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_1_week = date('Y-m-d', strtotime("-1 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_1_week; ?>" name="date_period" <?php echo ($date_period === $last_1_week) ? 'checked' : ''; ?>>
                        Last 1 week
                    </label>
                </div>
            </div>            

            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_2_week = date('Y-m-d', strtotime("-2 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_2_week; ?>" name="date_period" <?php echo ($date_period === $last_2_week) ? 'checked' : ''; ?>>
                        Last 2 week
                    </label>
                </div>
            </div>

            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_3_week = date('Y-m-d', strtotime("-3 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_3_week; ?>" name="date_period" <?php echo ($date_period === $last_3_week) ? 'checked' : ''; ?>>
                        Last 3 week
                    </label>
                </div>
            </div>

            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_4_week = date('Y-m-d', strtotime("-4 week")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_4_week; ?>" name="date_period" <?php echo ($date_period === $last_4_week) ? 'checked' : ''; ?>>
                        Last 4 week
                    </label>
                </div>
            </div>            

            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_1_month = date('Y-m-d', strtotime("-1 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_1_month; ?>" name="date_period" <?php echo ($date_period === $last_1_month) ? 'checked' : ''; ?>>
                        Last 1 month
                    </label>
                </div>
            </div>

            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_2_month = date('Y-m-d', strtotime("-2 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_2_month; ?>" name="date_period" <?php echo ($date_period === $last_2_month) ? 'checked' : ''; ?>>
                        Last 2 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_3_month = date('Y-m-d', strtotime("-3 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_3_month; ?>" name="date_period" <?php echo ($date_period === $last_3_month) ? 'checked' : ''; ?>>
                        Last 3 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_6_month = date('Y-m-d', strtotime("-6 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_6_month; ?>" name="date_period" <?php echo ($date_period === $last_6_month) ? 'checked' : ''; ?>>
                        Last 6 month
                    </label>
                </div>
            </div>
            <div class="col-xl-1 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_9_month = date('Y-m-d', strtotime("-9 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_9_month; ?>" name="date_period" <?php echo ($date_period === $last_9_month) ? 'checked' : ''; ?>>
                        Last 9 month
                    </label>
                </div>
            </div>
            <div class="col-xl-2 col-12 mb-10">
                <div class="form-check">
                    <label class="form-check-label">
                        <?php $last_12_month = date('Y-m-d', strtotime("-12 month")); ?>
                        <input type="radio" class="form-check-input sel_date_period" value="<?php echo $last_12_month; ?>" name="date_period" <?php echo ($date_period === $last_12_month) ? 'checked' : ''; ?>>
                        Last 12 month
                    </label>
                </div>
            </div>

        </div>
          
        <div class="row mb-20">
            
            <div class="col-xl-12 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Value Of Total Share Acquired - <?php echo ucfirst($total_share_acq_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_total_share_acq <?php echo ($total_share_acq_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-total-share-acq-sortby="high">High</a>
                        <a class="dropdown-item sort_by_total_share_acq <?php echo ($total_share_acq_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-total-share-acq-sortby="low">Low</a>
                    </div>
                </div>
            </div>
                        
        </div>
          
        <div class="row mb-20">
            
            <div class="col-xl-12 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Value Of Total Share Sale - <?php echo ucfirst($total_share_sale_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_total_share_sale <?php echo ($total_share_sale_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-total-share-sale-sortby="high">High</a>
                        <a class="dropdown-item sort_by_total_share_sale <?php echo ($total_share_sale_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-total-share-sale-sortby="low">Low</a>
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
                <a href="<?php echo base_url('share-corporate/sast-regulation-29/'.$company_id.'/'.base64_url_encode($company_symbol).'/all'); ?>">All Date</a>
            </div>
            <?php } ?>
        </div>
        
        <?php // echo $market_date; exit;?>
        
        <input type="hidden" class="total_share_acq_sortby" name="total_share_acq_sortby">
        <input type="hidden" class="total_share_sale_sortby" name="total_share_sale_sortby">
        
        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <?php if( !empty($sast_data) && count($sast_data) > 0 ){ ?>
    
    <h4 class="mb-20">Total Trading : <?php echo count($sast_data); ?></h4>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <?php if( ( !empty($company_id) && !empty($company_symbol) || !empty($acq_saler_name) ) && ( !empty($broadcaste_date_to) || $broadcaste_date_all ==='all' ) ){?>
                <th>Broadcast Date Time</th>
                <?php } else{?>
                <th>Broadcast Time</th>
                <?php } ?>
                <th>Company</th>
                <th>Name Of The Acquirer/ Disposer </th>
                <th>Promoter Type </th>
                <th>Acq/Sale Type </th>
                <th>Total Share Acquired </th>
                <th>Total Share Sale </th>
                <th>Total Share After </th>
                <th>Process Start From </th>
                <th>Process Start To </th>
                <th>Mode </th>
                <th>Type </th>
                
                <th>Total Share Acquired %</th>
                <th>Total Share Acquired Diluted %</th>
                <th>Total Share Sale %</th>
                <th>Total Share Sale Diluted %</th>
                <th>Total Share After %</th>
                <th>Total Share After Diluted %</th>
                
                <th>Remarks</th>
                
            </tr>
        </thead>
        <tbody>
            
             <?php foreach ( $sast_data AS $sast_data_key => $sast_data_value ) { ?>
            
            <tr>
                <?php if( ( !empty($company_id) && !empty($company_symbol) || !empty($acq_saler_name) ) && ( !empty($broadcaste_date_to) || $broadcaste_date_all ==='all' ) ){?>
                
                <td><?php echo date('d M Y h:i a ', strtotime($sast_data_value->broadcaste_datetime) ); ?></td>
                
                <td>
                    <a href="<?php echo base_url() . 'daily-log/' . $sast_data_value->company_id . '/' . base64_url_encode($sast_data_value->company_symbol); ?>">
                    <?php echo $sast_data_value->company_symbol; ?>
                    </a>
                </td>
                
                <?php } else{?>
                
                <td><?php echo date('h:i a ', strtotime($sast_data_value->broadcaste_time) ); ?></td>
                
                <td>
                    <a href="<?php echo base_url() . 'share-corporate/sast-regulation-29/' . $sast_data_value->company_id . '/' . base64_url_encode($sast_data_value->company_symbol) . '/' . $sast_data_value->broadcaste_date; ?>">
                    <?php echo $sast_data_value->company_symbol; ?>
                    </a>
                </td>
                
                <?php }?>
                
                
                <td>
                    <a href="<?php echo base_url() . 'share-corporate/sast-regulation-29/acquirer-saler/' . base64_url_encode($sast_data_value->name) . '/all'; ?>">
                    <?php echo $sast_data_value->name; ?>
                    </a>
                </td>
                <td>                    
                    <?php echo $sast_data_value->promoter_type; ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->acq_or_sale_type; ?>                    
                </td>
                <td>                    
                    <?php echo number_format($sast_data_value->total_share_acq); ?>                    
                </td>
                <td>                    
                    <?php echo number_format($sast_data_value->total_share_sale); ?>                    
                </td>
                <td>                    
                    <?php echo number_format($sast_data_value->total_share_after); ?>                    
                </td>
                <td>                    
                    <?php if (!empty($sast_data_value->acq_or_sale_date_from)) { echo date('d M Y', strtotime($sast_data_value->acq_or_sale_date_from) ) ; } ?>                    
                </td>
                <td>                    
                    <?php if (!empty($sast_data_value->acq_or_sale_date_to)) { echo date('d M Y', strtotime($sast_data_value->acq_or_sale_date_to) ) ; } ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->mode; ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->type; ?>                    
                </td>
                
                <td>                    
                    <?php echo $sast_data_value->total_share_acq_p; ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->total_acq_diluted_p; ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->total_share_sale_p; ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->total_sale_diluted_p; ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->total_share_after_p; ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->total_after_diluted_p; ?>                    
                </td>
                <td>                    
                    <?php echo $sast_data_value->remarks; ?>                    
                </td>
            </tr>
             <?php } ?>
            
        </tbody>
    </table>
    
    <?php }else{ ?>
    
    <div class=" mt-60">
        <div class="alert alert-danger">
            <strong>No Data Available</strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>