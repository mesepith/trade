<?php $this->load->helper('function_helper'); ?>
<style>
    /* Fixed Table Header Start*/
    thead tr:nth-child(1) th{
        background: white;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    /* Fixed Table Header End*/
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
    .mb-60{margin-bottom: 60px;}
    .mb-40{margin-bottom: 40px;}
    .mb-30{margin-bottom: 30px;}
    .mb-20{margin-bottom: 20px;}
    .mt-60{margin-top: 60px;}
    .mt-20{margin-top: 20px;}
    
</style>

<div class="container">
    
    <h1 class="mb-40">Daily Volatility Data</h1>    
    
    <form method="get" action="<?php echo base_url('daily-volatility'); ?>">
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

        </div>
        <?php // echo $market_date; exit;?>
        <input type="hidden" class="market_date" name="market_date" value="<?php echo empty($market_date) ? date('Y-m-d') :$market_date; ?>">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
        <div class="row mb-30">
            
            <div class="col-xl-4 col-12 mb-30"> 
            
                <div class="dropdown">
                    <button type="button" class="btn  btn-info dropdown-toggle" data-toggle="dropdown">
                        Sort By <?php if(!empty($daily_volatility_p)){
                            echo ' - Daily Volatility (%) - ' . ucfirst($daily_volatility_p);
                            
                        } 
                        ?>
                    </button>
                    <div class="dropdown-menu">
                        <h5 class="dropdown-header">Daily Volatility(%)</h5>
                        <a class="dropdown-item sort_by" data-sortby="daily_volatility_p" data-select="high" href="javascript:void(0)">High to Low</a>
                        <a class="dropdown-item sort_by" data-sortby="daily_volatility_p" data-select="low" href="javascript:void(0)">Low To High</a>                        
                    </div>

                    <input type="hidden" class="sort_by_selection" name="<?php echo !empty($daily_volatility_p) ? 'daily_volatility_p' : ''; ?>" value="<?php echo !empty($daily_volatility_p) ? $daily_volatility_p : ''; ?>">

                </div>
            </div>
            
        </div>
        
        <div class="row mb-20">
            <div class="col-xl-4 col-12 mb-30">
                <input type="checkbox" id="only_derivative" name="only_derivative" value="yes" <?php echo !empty($only_derivative) ? 'checked' : ''; ?>>
                <label for="only_derivative"> Only Derivative</label><br>
            
            </div>
        
        </div>

    </form>
    
    <?php if( !empty($volatility_data) && count($volatility_data) > 0 ){ ?>
        
    <h3 class="mb-60">Date: <?php echo date('d M Y', strtotime($market_date)); ?> </h3>
    
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Derivative</th>
                    <th>Daily Volatility</th>
                    <th>Daily Volatility %</th>
                    <th>Annual Volatility</th>
                    <th>Annual Volatility %</th>                    
                    
                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($volatility_data AS $volatility_data_key=>$volatility_data_value) { ?>

                <tr>
                    <td>
                        <a href="<?php echo base_url() . 'daily-log/?company_id='.$volatility_data_value->company_id.'&company_symbol='.base64_url_encode($volatility_data_value->company_symbol).'&stock_date='. $market_date.'&stock_date_to=' . date('Y-m-d'); ?>">
                        
                            <?php echo $volatility_data_value->company_symbol; ?>  
                            
                        </a>
                    </td>
                    
                    <td>
                        <?php 
                            
                            $oc_url= base_url() . 'option-chain/stock-info/?company_id='.$volatility_data_value->company_id.'&company_symbol='.base64_url_encode($volatility_data_value->company_symbol).'&sud='.$market_date;
                        
                            $fr_url = base_url() . 'future/stock-info/?company_id='.$volatility_data_value->company_id.'&company_symbol='.base64_url_encode($volatility_data_value->company_symbol).'&sud='.$market_date.'&sud_to='.$market_date;
                            
//                            $oc_url=base_url() . "option-chain/stock-info/".$volatility_data_value->company_id."/" . $volatility_data_value->company_symbol;
                            
                            echo !empty($volatility_data_value->derivative) ? '<a href="'.$fr_url.'">Future</a> / <a href="'.$oc_url.'">Option</a>' : '' 
                        ?>  
                    </td>
                    
                    <td>
                        <a href="<?php echo base_url('daily-volatility-of/'.$volatility_data_value->company_id.'/' . base64_url_encode($volatility_data_value->company_symbol) . '?market_date='.$market_date.'&market_date_to='.date('Y-m-d'));?>">
                        <?php echo $volatility_data_value->daily_volatility; ?>  
                        </a>
                    </td>                    
                    <td><?php echo $volatility_data_value->daily_volatility_p; ?>  </td>                    
                    <td><?php echo $volatility_data_value->annual_volatility; ?>  </td>                    
                    <td><?php echo $volatility_data_value->annual_volatility_p; ?>  </td>                    
                    
                    
                </tr>
                
                <?php } ?>
                
            </tbody>
        </table>
        
    
    <?php }else{ ?>
    
    <div>
        <div class="alert alert-danger">
            <strong>No Data Available, Kindly choose another date </strong> 
        </div>
    </div>
    
    <?php } ?>
    
</div>