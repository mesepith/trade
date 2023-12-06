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
</style>
<div class="container">
    
    <h1 class='mb-60'>Bulk Block Deal: <?php if( !empty($company_id) && !empty($company_symbol) ){ echo 'of ' . $company_symbol; } ?></h1>
    
    <?php 
            if( !empty($client) ){
                echo '<h3 class="mb-60">Client Name : <b>'. ucwords(strtolower($client) ).' </b> </h3>';
            }
    ?>
    
    <h3 class="mb-30">Market date: 
        <b>
        <?php 
            if( !empty($client) && $market_date_all === 'all'){
                
                echo 'All';
            }else{
                echo date('d M Y', strtotime($market_date) ) ; 
            }
        ?>
        </b>
    
    <?php if( ( !empty($company_id) && !empty($company_symbol ) || !empty($client) ) && !empty($market_date_to) ){?>
        
        to <b><?php echo date('d M Y', strtotime($market_date_to) ) ; ?></b>
        
    <?php } ?>
        
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
            
            <?php if( ( !empty($company_id) && !empty($company_symbol) )  || !empty($client) ){?>
            
            <div class="col-xl-2 mb-10">Select Date To: </div>
            <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($market_date_to) ? date('Y-m-d') : $market_date_to; ?>"  onchange="changeStockDate(event, 'to');">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            
            <input type="hidden" class="company_id" value="<?php echo $company_id; ?>">
            
            <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date_all) ? date('Y-m-d') :$market_date_all; ?>">
            
            <input type="hidden" class="market_date_to" name="market_date_to" value="<?php echo empty($market_date_to) ? date('Y-m-d') :$market_date_to; ?>">
            
            <div class="client_name" data-valz="<?php echo $client; ?>"></div>
            
            <?php }else{ ?>
            
            <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') :$market_date; ?>">
            
            <?php }?>

        </div>
         
        <div class="row mb-20">
            
            <div class="col-xl-1 col-4 mb-30 form-check">
                Exchange
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_exchange" value="nse" name="exchange" <?php echo ( $exchange === "nse" ) ? 'checked' : ''; ?>>NSE
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_exchange" value="bse" name="exchange" <?php echo ( $exchange === "bse" ) ? 'checked' : ''; ?>>BSE
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_exchange" value="all" name="exchange" <?php echo ( $exchange === "all" ) ? 'checked' : ''; ?>>ALL
                </label>
            </div>
            
        </div>
         
        <div class="row mb-20">
            
            <div class="col-xl-1 col-4 mb-30 form-check">
                Deal Type
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_deal" value="bulk" name="deal_type" <?php echo ( $deal_type === "bulk" ) ? 'checked' : ''; ?>>BULK
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_deal" value="block" name="deal_type" <?php echo ( $deal_type === "block" ) ? 'checked' : ''; ?>>BLOCK
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_deal" value="all" name="deal_type" <?php echo ( $deal_type === "all" ) ? 'checked' : ''; ?>>ALL
                </label>
            </div>
            
        </div>
         
        <div class="row mb-20">
            
            <div class="col-xl-1 col-4 mb-30 form-check">
                Buy or Sale
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_buy_or_sale" value="buy" name="buy_or_sale" <?php echo ( $buy_or_sale === "buy" ) ? 'checked' : ''; ?>>BUY
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_buy_or_sale" value="sell" name="buy_or_sale" <?php echo ( $buy_or_sale === "sell" ) ? 'checked' : ''; ?>>SELL
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_buy_or_sale" value="all" name="buy_or_sale" <?php echo ( $buy_or_sale === "all" ) ? 'checked' : ''; ?>>ALL
                </label>
            </div>
            
        </div>
        
        <div class="row mb-20">
            
            <div class="col-xl-12 col-12 mb-30 form-check">
                <div class="dropdown">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        Sort Quantity Traded - <?php echo ucfirst($quantity_traded_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_quantity_traded <?php echo ($quantity_traded_sortby==='high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-quantity-traded-sortby="high">High</a>
                        <a class="dropdown-item sort_by_quantity_traded <?php echo ($quantity_traded_sortby==='low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-quantity-traded-sortby="low">Low</a>
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
                <a href="<?php echo base_url('bulk-block-deal/'.$company_id.'/'.base64_url_encode($company_symbol).'/all'); ?>">All Date</a>
            </div>
            <?php } ?>
        </div>
        
        <?php // echo $market_date; exit;?>
        
        <input type="hidden" class="quantity_traded_sortby" name="quantity_traded_sortby">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>
    
    <?php if( !empty($bulk_block_data) && count($bulk_block_data) > 0 ){ ?>
    
    <h4 class="mb-20">Total : <?php echo count($bulk_block_data); ?></h4>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <?php if( ( !empty($company_id) && !empty($company_symbol ) || !empty($client) ) && ( !empty($market_date_to) || $market_date_all ==='all' ) ){?>
                <th>Market Date</th>
                <?php } ?>
                <th>Company</th>
                <th>Deal Type</th>
                <th>Exchange</th>
                <th>Client Name</th>
                <th>Buy / Sale</th>
                <th>Quantity Traded</th>
                <th>Trade Price</th>
               
            </tr>
        </thead>
        <tbody>
            
             <?php foreach ( $bulk_block_data AS $bulk_block_data_key => $bulk_block_data_value ) { ?>
            
            <tr>
                <?php if( ( !empty($company_id) && !empty($company_symbol ) || !empty($client) ) && ( !empty($market_date_to) || $market_date_all ==='all' ) ){?>
                
                <td><?php echo date('d M Y', strtotime($bulk_block_data_value->market_date) ); ?></td>
                
                <td>
                    <a href="<?php echo base_url() . 'daily-log/' . $bulk_block_data_value->company_id . '/' . base64_url_encode($bulk_block_data_value->company_symbol); ?>">
                    <?php echo $bulk_block_data_value->company_symbol; ?>
                    </a>
                </td>
                
                
                <?php }else{?>
                
                <td>
                    <a href="<?php echo base_url() . 'bulk-block-deal/' . $bulk_block_data_value->company_id . '/' . base64_url_encode($bulk_block_data_value->company_symbol) . '/' . $bulk_block_data_value->market_date; ?>">
                    <?php echo $bulk_block_data_value->company_symbol; ?>
                    </a>
                </td>
                
                <?php }?>
                
                <td><?php echo ucwords($bulk_block_data_value->bulk_or_block); ?></td>                                                
                <td><?php echo strtoupper($bulk_block_data_value->exchange); ?></td>                                                
                <td>
                    
                    <a href="<?php echo base_url() . 'bulk-block-deal/client/' . base64_url_encode($bulk_block_data_value->client_name) . '/all'; ?>">
                    <?php echo ucwords(strtolower($bulk_block_data_value->client_name));  ?>
                    </a>
                </td>
                
                <td class="<?php echo ($bulk_block_data_value->buy_or_sale==='buy') ? 'col-h-green' : 'col-h-red';?>"><?php echo ucwords($bulk_block_data_value->buy_or_sale); ?></td>
                
                <td><?php echo number_format($bulk_block_data_value->quantity_traded); ?></td>
                <td><?php echo number_format($bulk_block_data_value->trade_price, 2); ?></td>
                
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