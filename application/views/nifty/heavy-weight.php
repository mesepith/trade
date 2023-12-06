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
    
    .col-h-green{
        background: green;
        color: white;
        font-weight: bolder;
    }
    .col-h-red{
        background: red;
        color: white;
        font-weight: bolder;
    }
    
    .col-green{
        color: green;
    }
    .col-red{
        color: red;
    }
</style>
<div class="container">
    
    <h1 class='mb-60'>Nifty Heavy Weight: <?php if( !empty($company_id) && !empty($company_symbol) ){ echo 'of ' . $company_symbol; } ?></h1>
    
    <h3 class="mb-30">Market date: <b><?php echo date('d M Y', strtotime($market_date) ) ; ?></b>   
        
     </h3>
    
    <form method="get" action="<?php echo base_url($url); ?>">
        <div class="row mb-60 mt-60">

            <div class="col-xl-2 col-12 mb-10">
                Select Date:
            </div>
            <div class="col-xl-3 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz" readonly="readonly" value="<?php echo empty($market_date) ? date('Y-m-d') : $market_date; ?>"  onchange="changeStockDate(event, 'from');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            
            <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') :$market_date; ?>">            

        </div>
        
         <div class="row mb-20">
            
            <div class="col-xl-12 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Quantity Weightage - <?php echo ucfirst($weightage_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_weightage <?php echo ($weightage_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-weightage-sortby="high">High</a>
                        <a class="dropdown-item sort_by_weightage <?php echo ($weightage_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-weightage-sortby="low">Low</a>
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
                <a href="<?php echo base_url('nifty-heavy-weight-stocks/'.$company_id.'/'.base64_url_encode($company_symbol).'/all'); ?>">All Date</a>
            </div>
            <?php } ?>
        </div>
        
        <?php // echo $market_date; exit;?>
        
        <input type="hidden" class="weightage_sortby" name="weightage_sortby">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <?php if( !empty($nifty_heavy_stocks) && count($nifty_heavy_stocks) > 0 ){ ?>
    
    <h4 class="mb-20">Total : <?php echo count($nifty_heavy_stocks); ?></h4>
    
    <table class="table table-striped">
        <thead>
            <tr>
                
                <th>Company</th>
                <?php $count=0; foreach ( $nifty_heavy_stocks AS $nifty_heavy_stocks_key => $nifty_heavy_stocks_value ) { if($count > 0){continue;} ?>
                <?php foreach($nifty_heavy_stocks_value AS $date=>$value ){ ?>
                <th><?php echo date('d M Y', strtotime($date) ); ?></th>
                <?php } ?>
                <?php $count++; }?>
               
            </tr>
        </thead>
        <tbody>
            
             <?php foreach ( $nifty_heavy_stocks AS $stock_name => $nifty_heavy_stocks_value ) { $prev_value = ''; ?>
            
            <tr>
                <td>
                    <?php
                        $comp_id_symb = explode('#*', $stock_name);
                        $company_symbol = $comp_id_symb[0];
                        $company_id = $comp_id_symb[1];
                    ?>
                    <a href="<?php echo base_url() . 'daily-log/' . $company_id . '/' . base64_url_encode($company_symbol); ?>">
                    <?php echo $company_symbol; ?>
                    </a>
                </td>
                <?php foreach($nifty_heavy_stocks_value AS $date=>$value ){  ?>
                
                <th class="<?php echo ($prev_value==='') ? '' : ( ( $value > $prev_value ) ? 'col-green' : 'col-red' ); ?>"><?php echo $value ; ?></th>
                <?php $prev_value = $value; } ?>
                
            </tr>
             <?php } ?>
            
        </tbody>
    </table>
    
    <?php }else{ ?>
    
    <div class=" mt-60">
        <div class="alert alert-danger">
            <strong>No Data Available, Please choose another Date</strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>