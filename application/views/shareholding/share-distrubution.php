<?php $this->load->helper('function_helper'); ?>
<style>
    
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
    .mb-20{margin-bottom: 20px;}
    .mb-60{margin-bottom: 60px;}
    .mt-60{margin-top: 60px;}
    .mt-20{margin-top: 20px;}
    
    .col-green{
       color: green; 
    }
    .col-red{
       color: red; 
    }
</style>
<div class="container">
    <h1>Share Distribution of <?php echo $company_symbol; ?></h1>
    
    <form method="get" action="<?php echo base_url('shareholding/distrubution/' . $company_id . '/' . base64_url_encode($company_symbol)); ?>">
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($market_date) ? date('Y-m-d') : $market_date; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($market_date_to) ? date('Y-m-d') : $market_date_to; ?>"  onchange="changeStockDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div> 
        
        <div class="row mb-20">
            <div class="col-xl-1 col-2">
                <input type="checkbox" class="select_all_date" id="all_date_chkbox" name="all_date_chkbox" value="all" <?php echo ($all_date_chkbox==='all') ? 'checked' :''; ?>>
                <label for="all_date_chkbox"> All</label><br>            
            </div>
        
        </div>
        
        <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') : $market_date; ?>">
        <input type="hidden" class="market_date_to" name="market_date_to" value="<?php echo empty($market_date_to) ? date('Y-m-d') : $market_date_to; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
    </form>
    
    <?php if( !empty($share_distrubution) && count($share_distrubution) > 0 ){ ?>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Promoter %</th>
                <th>Public</th>
                <th>Shares Underlying Drs</th>
                <th>Shares Held By Employee Trusts </th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($share_distrubution AS $share_distrubution_key=>$share_distrubution_value) { ?>

                <tr>
                    <td>
                        <a href="<?php echo base_url() . 'shareholding/declaration/' . $company_id . '/' . base64_url_encode($company_symbol) .'/' . $share_distrubution_value->market_date .'/' . base64_url_encode($share_distrubution_value->record_id); ?>">
                            <?php echo date('d-M-Y', strtotime($share_distrubution_value->market_date)); ?>
                        </a>
                    </td>
                    <td><?php echo $share_distrubution_value->promoter; ?></td>
                    <td><?php echo $share_distrubution_value->public; ?></td>
                    <td><?php echo $share_distrubution_value->underlying_drs; ?></td>
                    <td><?php echo $share_distrubution_value->employee_trusts; ?></td>
                    
                    
                </tr>

            <?php } ?>

        </tbody>
    </table>
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available for <?php echo $company_symbol; ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>