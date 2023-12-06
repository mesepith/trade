<?php  $this->load->helper('function_helper'); ?>
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
.mb-30{margin-bottom: 30px;}
</style>
<div class="container">
    
    <div class="heading mb-20">
    
    <?php if (!empty($oc_pd_iv_data) && count($oc_pd_iv_data) > 0) { ?>
        <h1>Option Chain Implied Volatility And Premium Decay Live Day Wise Analysis ( <?php echo ucfirst($bull_or_bear); ?> )</h1>
        <p>It shows common <?php echo $bull_or_bear; ?> data comes from the analysis of Implied Volatility And Premium Decay </p>
    <?php } else { ?>
        <h1>No Data Available</h1>
    <?php } ?>
        
    </div>
        
    <h3>Date : <?php echo date('d M Y', strtotime($date)); ?></h3>
    <h3 class="mb-30">Time : <?php echo date('h:i a', strtotime($time) ); ?></h3>
    
    <form method="get" action="<?php echo base_url('option-chain/iv-pd-analysis/live/' .$bull_or_bear); ?>">
        <div class="row mb-30">
            
            <div class="col-xl-3 col-sm-7 col-12">
                Select Underlying Date
            </div>
            
            <div class="col-xl-3 col-sm-6 col-12"> 
                
                
                <div class="col-xl-2 col-12 mb-30 htm-date-container"> 
                    <input class="htm-date" type="date" value="<?php echo $date; ?>"  onchange="changeUnderLyingDate(event);" max="<?php echo date('Y-m-d')?>">
                    <span class="open-date-button">
                        <button type="button">📅</button>
                    </span>
                </div>
                
                
            </div>
            
        </div>
        
        <?php if(!empty($script_start_time_arr)){ ?>
        
        <div class="row mb-30">
        
            <div class="col-xl-3 col-12 mb-30"> 

                <div class="dropdown">
                   <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                       <span>Time : </span><span><?php echo $time; ?></span>
                   </button>
                   <div class="dropdown-menu">
                   <?php foreach($script_start_time_arr AS $script_start_time_arr_value){?>
                     <a class="dropdown-item change_script_start_time" data-searching_underlying_date='<?php echo $date; ?>' data-searching_script_start_time='<?php echo $script_start_time_arr_value->script_start_time; ?>' href="javascript:void(0)">
                         <?php echo $script_start_time_arr_value->script_start_time; ?>
                     </a>
                   <?php }?>
                   </div>
                 </div>

            </div>
            
            
            
        </div>
        
        <?php } ?>
        
        <input type='hidden' class='searching_script_start_time' name='sst' value='<?php echo $time; ?>'>
        
        <input type='hidden' class='searching_underlying_date' name='sud' value='<?php echo $date; ?>'>
        
        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
    </form>
    
    
    <?php if (!empty($oc_pd_iv_data) && count($oc_pd_iv_data) > 0) { ?>
        
    <p>Analysis Type : <b><?php echo ucfirst($bull_or_bear); ?></b></p> 
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Company Symbol</th>
                    <th>Option Chain</th>
                    <th>IV Analysis</th>
                    <th>PD Analysis</th>

                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($oc_pd_iv_data AS $oc_pd_iv_data_val) { ?>
                    
                    <tr>                                    

                        <td>                            
                            <a href="<?php echo base_url() . 'whole-day-data/?company_id='.$oc_pd_iv_data_val["company_id"].'&company_symbol='.base64_url_encode($oc_pd_iv_data_val["company_symbol"]).'&stock_date='. date('Y-m-d'); ?>">
                            
                                <?php echo $oc_pd_iv_data_val["company_symbol"]; ?>
                                
                            </a>
                            
                        </td>
                        
                        <td>
                            <a href="<?php echo base_url() . 'option-chain/stock-info/'.$oc_pd_iv_data_val["company_id"] . '/' . base64_url_encode($oc_pd_iv_data_val["company_symbol"]) . '/live'; ?>">OC Link</a>
                        </td>
                        
                        <td>
                            <a href="<?php echo base_url() . 'option-chain/iv-analysis/'.$oc_pd_iv_data_val["company_id"] . '/' . base64_url_encode($oc_pd_iv_data_val["company_symbol"]); ?>">IV Link</a>
                        </td>
                        
                        <td>
                            <a href="<?php echo base_url() . 'option-chain/pd-analysis/'.$oc_pd_iv_data_val["company_id"] . '/' . base64_url_encode($oc_pd_iv_data_val["company_symbol"]); ?>">PD Link</a>
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
    
        
</div>

<script>
/*
 * @author : ZAHIR
 * DESC: On change under lying date
 */
function changeUnderLyingDate(e){
    
//  alert(e.target.value);
  $(".searching_underlying_date").attr('value', e.target.value);  
  
  $('.apply-btn-actionz').click();
}

    /*
 * @author: ZAHIR
 * DESC: on select of script start time
 */

$(document).on('click', '.change_script_start_time', function () {
    
    var searching_underlying_date = $(this).data('searching_underlying_date');
       
    var searching_script_start_time = $(this).data('searching_script_start_time');              
    
    $(".searching_underlying_date").attr('value', searching_underlying_date);
    
    $(".searching_script_start_time").attr('value', searching_script_start_time);

    
    $('.apply-btn-actionz').click();
});
</script>