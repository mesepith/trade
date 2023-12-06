<?php $this->load->helper('function_helper'); ?>

<div class="container">
    <?php if( !empty($oc_pd_data) && count($oc_pd_data) > 0 ){ ?>
    <h2><?php echo 'Analysis of <b>' . $company_symbol . '</b> - Premium Decay : ' . $live; ?></h2>
    <?php } ?>
    
    
    <form method="get" action="<?php echo base_url('option-chain/pd-analysis/'); ?>">
        <div class="row mb-30 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date From:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($underlying_date_end) ? date('Y-m-d') :$underlying_date_end; ?>"  onchange="changeSectorDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($underlying_date_end_to) ? date('Y-m-d') :$underlying_date_end_to; ?>"  onchange="changeSectorDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>
                
        <div class="row mb-60 mt-60">
            
            <div class="col-xl-12 col-12">
            
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <?php echo empty($searching_expiry_date) ? 'Select Expiry Date' : 'Expiry Date - ' . date('d M Y', strtotime($searching_expiry_date) ); ?>
                    </button>
                    <div class="dropdown-menu sector-dropdown-menu">
                        
                        <?php foreach( $expiry_date_arr AS $expiry_date_arr_val ){?>
                        
                        <a class="dropdown-item select_expiry <?php echo ( $searching_expiry_date === $expiry_date_arr_val) ? 'sel-sec' :'' ?>" href="javascript:void(0)" data-expiry="<?php echo $expiry_date_arr_val; ?>">
                            <?php echo date('d M Y', strtotime($expiry_date_arr_val) ); ?>
                        </a>
                        
                        <?php } ?>
                        
                        <a class="dropdown-item select_expiry <?php echo ( $searching_expiry_date === '') ? 'sel-sec' :'' ?>"" href="javascript:void(0)" data-expiry="">
                            All Expiry Date                       
                        </a>
                        
                        
                    </div>
                </div>
                
            </div>
            
        </div>
        

        <input type='hidden' class='company_id' name='company_id' value='<?php echo $company_id; ?>'>
        <input type='hidden' class='company_symbol' name='company_symbol' value='<?php echo base64_url_encode($company_symbol); ?>'>

        <input type="hidden" class="underlying_date_end" name="underlying_date_end" value="<?php echo empty($underlying_date_end) ? date('Y-m-d') :$underlying_date_end; ?>">
        <input type="hidden" class="underlying_date_end_to" name="underlying_date_end_to" value="<?php echo empty($underlying_date_end_to) ? date('Y-m-d') :$underlying_date_end_to; ?>">

        <input type='hidden' name='live' value='<?php echo $live; ?>'>
        
        <input type="hidden" class="expiry" name="expiry" value="">
        
        <input type="submit" class="apply-btn-actionz mb-30" value="Apply">

    </form>
    
    <?php if(empty($live)){ ?>
    
    <div class="row mb-30">
        <div class="col-xl-2 col-12 mb-10">
            <a href="<?php echo base_url() . 'option-chain/pd-analysis/' . $company_id . '/' . base64_url_encode($company_symbol) . '/live'; ?>">Live Analysis</a>
        </div>
    </div>
    
    <?php } ?>
    
    <?php 
    if( !empty($oc_pd_data) && count($oc_pd_data) > 0 ){ ?>
    
    <a class="mt-20" href="#chart_start">Go to Chart</a>
    
    <p>Analysis:</p>            
    
    <table class="table table-striped">
        <thead>
            <tr>
                
                <th>Expiry Date</th>
                <th>Underlying Date Start</th>
                <th>Underlying Date End</th>
                <th>Put Average Decay</th>
                <th>Call Average Decay</th>
               
            </tr>
        </thead>
        <tbody>

            <?php foreach ($oc_pd_data AS $oc_pd_data_key=>$oc_pd_data_value) { ?>

                <tr>
                    <td><?php echo date('d M Y', strtotime($oc_pd_data_value->expiry_date)); ?></td>
                    <td><?php echo date('d M Y', strtotime($oc_pd_data_value->underlying_date_start)); ?></td>
                    <td>
                        <?php echo date('d M Y', strtotime($oc_pd_data_value->underlying_date_end)); ?>
                        <?php if(!empty($live)){ echo ', ' . date('h:i a', strtotime($oc_pd_data_value->underlying_time_end)); } ?>
                    </td>
                    
                    <td><?php echo $oc_pd_data_value->put_avg_decay; ?></td>
                    <td><?php echo $oc_pd_data_value->call_avg_decay; ?></td>
                    
                    
                </tr>

            <?php } ?>

        </tbody>
    </table>
        
    <!-- Chart Start -->
    
    <div id="market_running" data-val='<?php echo $live; ?>' ></div>
    
    <div id="chart_data" data-all='<?php echo htmlspecialchars(json_encode($oc_pd_data) , ENT_QUOTES, 'UTF-8'); ?>' ></div>
    
    <div class="row" id="chart_start">
            
        <div class="col-xl-12 col-sm-12 col-12">
            
            <div class="chart_dsgn" id="avg_decay_chart" data-plot_data="avg_decay" data-plot_data_name="Average Decay" data-colorz="green" data-full_screen="0"></div> 
        </div>
        
    </div>
    
    <!-- Chart End -->    
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available for <?php echo $company_symbol; ?></strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>

