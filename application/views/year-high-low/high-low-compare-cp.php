<?php 

$this->load->helper('function_helper');

?>
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
    
    @media (min-width: 1800px){
        div.container {
            max-width: 1800px;
        }
    }
    
    .mb-60{margin-bottom: 60px;}
    .mb-30{margin-bottom: 30px;}
    .mt-60{margin-top: 60px;}
    .mt-20{margin-top: 20px;}
    
</style>

<div class="container">
    
    <h1 class="mb-60">Compare Closing Price With 52 Week High Low in Percent</h1>
    
    <form method="get" action="<?php echo base_url('year-high-low-compare/current-price/day-wise'); ?>">
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
                        Sort By <?php if(!empty($close_price_cp_with_low)){
                            echo ' - Close Price Diff with 52 Week Low(%) - ' . ucfirst($close_price_cp_with_low);
                            
                        } 
                        if(!empty($close_price_cp_with_high)){
                            echo ' - Close price Diff with 52 Week High(%) - ' . ucfirst($close_price_cp_with_high);
                            
                        } 
                        ?>
                    </button>
                    <div class="dropdown-menu">
                        <h5 class="dropdown-header">Close Price Diff with 52 Week Low(%)</h5>
                        <a class="dropdown-item sort_by" data-sortby="close_price_cp_with_low" data-select="high" href="javascript:void(0)">High to Low</a>
                        <a class="dropdown-item sort_by" data-sortby="close_price_cp_with_low" data-select="low" href="javascript:void(0)">Low To High</a>
                        <h5 class="dropdown-header">Close price Diff with 52 Week High(%)</h5>
                        <a class="dropdown-item sort_by" data-sortby="close_price_cp_with_high" data-select="high" href="javascript:void(0)">High to Low</a>
                        <a class="dropdown-item sort_by" data-sortby="close_price_cp_with_high" data-select="low" href="javascript:void(0)">Low To High</a>
                    </div>

                    <input type="hidden" class="sort_by_selection">

                </div>
            </div>
        
            <div class="col-xl-4 col-12 mb-30"> 
            
                <div class="dropdown">
                    <button type="button" class="btn  btn-info dropdown-toggle" data-toggle="dropdown">
                        Sort By Date<?php if(!empty($year_week_low_date_order)){
                            echo ' - 52 Week Low Date - ' . ucfirst($year_week_low_date_order);
                            
                        } 
                        if(!empty($year_week_high_date_order)){
                            echo ' - 52 Week High Date - ' . ucfirst($year_week_high_date_order);
                            
                        } 
                        ?>
                    </button>
                    <div class="dropdown-menu">
                        <h5 class="dropdown-header">52 Week Low Date</h5>
                        <a class="dropdown-item sort_by_date" data-sortby="year_week_low_date_order" data-select="asc" href="javascript:void(0)">Ascending Order</a>
                        <a class="dropdown-item sort_by_date" data-sortby="year_week_low_date_order" data-select="desc" href="javascript:void(0)">Descending Order</a>
                        <h5 class="dropdown-header">52 Week High Date</h5>
                        <a class="dropdown-item sort_by_date" data-sortby="year_week_high_date_order" data-select="asc" href="javascript:void(0)">Ascending Order</a>
                        <a class="dropdown-item sort_by_date" data-sortby="year_week_high_date_order" data-select="desc" href="javascript:void(0)">Descending Order</a>
                    </div>
                    
                    <?php 
                    
                    $sort_by_selection_name = '';
                    $sort_by_selection_val = '';
                    
                    if(!empty($close_price_cp_with_low)){
                        
                        $sort_by_selection_name = 'close_price_cp_with_low';
                        $sort_by_selection_val = $close_price_cp_with_low;
                        
                    }else if(!empty($close_price_cp_with_high)){
                        
                        $sort_by_selection_name = 'close_price_cp_with_high';
                        $sort_by_selection_val = $close_price_cp_with_high;
                    }
                    
                    ?>
                    
                    <input type="hidden" class="sort_by_selection" name="<?php echo $sort_by_selection_name; ?>" value="<?php echo $sort_by_selection_val; ?>">
                    <input type="hidden" class="sort_by_selection_date">

                </div>
            </div>
            
        </div>
        
    </form>
    
    <?php if( !empty($year_high_or_low_data) && count($year_high_or_low_data) > 0 ){ ?>
        
    <h3 class="mb-60">Date: <?php echo date('d M Y', strtotime($market_date)); ?> </h3>
    
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Derivative</th>
                    <th>Close Price</th>
                    <th>52 Week Low</th>
                    <th>52 Week Low Date</th>
                    <th>52 Week High</th>
                    <th>52 Week High Date</th>
                    <th>Close price Diff with 52 Week Low(%)</th>
                    <th>Close price Diff with 52 Week High(%)</th>
                    
                    
                </tr>
            </thead>
            <tbody>
                
                <?php foreach ($year_high_or_low_data AS $year_high_or_low_data_key=>$year_high_or_low_data_value) { ?>

                <tr>
                    <td>
                        <a href="<?php echo base_url() . 'daily-log/?company_id='.$year_high_or_low_data_value['company_id'].'&company_symbol='.base64_url_encode($year_high_or_low_data_value['company_symbol']).'&stock_date='. $market_date.'&stock_date_to=' . date('Y-m-d'); ?>">
                        
                            <?php echo $year_high_or_low_data_value['company_symbol']; ?>  
                            
                        </a>
                    </td>
                    
                    <td>
                        <?php 
                        
                            $oc_url= base_url() . 'option-chain/stock-info/?company_id='.$year_high_or_low_data_value['company_id'].'&company_symbol='.base64_url_encode($year_high_or_low_data_value['company_symbol']).'&sud='.$market_date;
                        
                            $fr_url = base_url() . 'future/stock-info/?company_id='.$year_high_or_low_data_value['company_id'].'&company_symbol='.base64_url_encode($year_high_or_low_data_value['company_symbol']).'&sud='.$market_date.'&sud_to='.$market_date;
                            
//                            $oc_url=base_url() . "option-chain/stock-info/".$year_high_or_low_data_value['company_id']."/" . base64_url_encode($year_high_or_low_data_value['company_symbol']);
                            
                            echo !empty($year_high_or_low_data_value['pc_exists']) ? '<a href="'.$fr_url.'">Future</a> / <a href="'.$oc_url.'">Option</a>' : '' 
                        ?>  
                    </td>
                    
                    <td><?php echo $year_high_or_low_data_value['close_price'];?></td>
                    <td><?php echo $year_high_or_low_data_value['year_week_low'];?></td>
                    
                    <td><?php echo date('d M Y', strtotime($year_high_or_low_data_value['year_week_low_date']));?></td>
                    
                    <td><?php echo $year_high_or_low_data_value['year_week_high'];?></td>
                    
                    <td><?php echo date('d M Y', strtotime($year_high_or_low_data_value['year_week_high_date']));?></td>
                    
                    <td><?php echo number_format($year_high_or_low_data_value['close_price_diff_with_low_percent'], 2); ?> %</td>
                    <td><?php echo number_format($year_high_or_low_data_value['close_price_diff_with_high_percent'], 2); ?> %</td>
                    
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