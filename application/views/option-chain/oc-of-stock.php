<?php  $this->load->helper('function_helper'); ?>
<style>
    @media (min-width: 1800px){
        div.container {
            max-width: 1800px;
        }
    }
    .mb-30{margin-bottom: 30px;}
    .mb-60{margin-bottom: 60px;}
    .mt-60{margin-top: 60px;}

    thead tr:nth-child(1) th, thead tr:nth-child(2) th{
        background: white;
        position: -webkit-sticky;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    /*sticky header of table end*/
    
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
.call_in_the_money_yes{background-color: yellow;}
table.p_c_data_table tr.p_c_data:last-child td{background-color: #fff;}
.put_in_the_money_yes{background-color: yellow;}
.strike_price_td{background-color: lightgrey; font-weight: bolder;}
.positive_no{color: green;}
.negative_no{color: red;}

.table td.chng_oi_td_class{
    position: relative;
    width: 50%;
    padding: 10px 80px 0px 13px;
}

.positive_no_w{
    width: 0; 
    height: 0; 
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;
    border-bottom: 15px solid green;
    position: absolute;
    margin-top: 5px;
    margin-left: 5px;
    right: 4px;
/*    right: 6px;
    bottom: 16px;*/
}
.negative_no_w{
    width: 0; 
    height: 0; 
    border-left: 15px solid transparent;
    border-right: 15px solid transparent;  
    border-top: 15px solid #f00;
    position: absolute;
    margin-top: 5px;
    margin-left: 5px;
    right: 4px;
/*    right: 6px;
    bottom: 16px;*/
}

td.container-histo{
    /*width: 100%;*/
    background-color: black;
    padding: 10px 80px 0px 13px;
}
.histo {
    text-align: right;
    padding-top: 10px;
    padding-bottom: 10px;
    color: white;
}
.histo-call-put {background-color: #4CAF50;}

/* Slider Start */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.option-sell-tips-table th.yellow, td.yellow{background-color: yellow; font-weight: bolder;}
.option-sell-tips-table th.orange, td.orange{background-color: orange; font-weight: bolder;}
.option-sell-tips-table th.sky-blue, td.sky-blue{background-color: skyblue; font-weight: bolder;}
.option-sell-tips-table th.lime-green, td.lime-green{background-color: limegreen; font-weight: bolder;}
/* Slider End */
</style>
<div class="container">
    
    <h2><?php echo $other_info['company_name'] . ' - ' . ucfirst($live); ?></h2>
    <p>Underlying Stock: <?php echo $other_info['company_name'] . ' ' . $other_info['underlying_price']  . ' As on ' . $other_info['underlying_date_time'] ; ?> IST</p>                                                                                      
    
    <form method="get" action="<?php echo base_url('option-chain/stock-info/' .$live); ?>">
        <div class="row mb-60">
            
            <div class="col-xl-2 col-sm-6 col-12 mb-30">
                Select Underlying Date
            </div>
            
            <div class="col-xl-3 col-sm-6 col-12 mb-30"> 
                
                
                <div class="col-xl-2 col-12 mb-30 htm-date-container"> 
                    <input class="htm-date" type="date" value="<?php echo $other_info['searching_underlying_date']; ?>"  onchange="changeUnderLyingDate(event);" max="<?php echo date('Y-m-d')?>">
                    <span class="open-date-button">
                        <button type="button">📅</button>
                    </span>
                </div>
                
                
            </div>
            
        </div>
        <div class="row mb-60">
            
            <div class="col-xl-2 col-12 mb-30">
                Select
            </div>
            
            <div class="col-xl-3 col-12 mb-30"> 
                
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Expiry Date : </span><span><?php echo $other_info['searching_expiry_date']; ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($other_info['expiry_dates'] AS $expiry_dates_value){?>
                      <a class="dropdown-item change_expiry_date" data-searching_underlying_date='<?php echo $other_info['searching_underlying_date']; ?>' data-searching_expiry_date='<?php echo $expiry_dates_value->expiry_date; ?>' href="javascript:void(0)">
                          <?php echo $expiry_dates_value->expiry_date; ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            
            <?php if($live){ ?>
            
            <div class="col-xl-3 col-12 mb-30"> 
                
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Underlying Time : </span><span><?php echo $other_info['searching_underlying_time']; ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($other_info['underlying_time'] AS $underlying_time_value){?>
                      <a class="dropdown-item change_underlying_time" data-searching_underlying_date='<?php echo $other_info['searching_underlying_date']; ?>' data-searching_expiry_date='<?php echo $other_info['searching_expiry_date']; ?>' data-searching_underlying_time='<?php echo $underlying_time_value->underlying_time; ?>' href="javascript:void(0)">
                          <?php echo $underlying_time_value->underlying_time; ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            
            <input type='hidden' class='searching_underlying_time' name='sut' value='<?php echo $other_info['searching_underlying_time']; ?>'>
            
            <?php } ?>
            
        </div>
        
        <input type='hidden' class='company_id' name='company_id' value='<?php echo $other_info['company_id']; ?>'>
        <input type='hidden' class='company_symbol' name='company_symbol' value='<?php echo base64_url_encode($other_info['company_symbol']); ?>'>
        
        <input type='hidden' class='searching_underlying_date' name='sud' value='<?php echo $other_info['searching_underlying_date']; ?>'>
        <input type='hidden' class='searching_expiry_date' name='sed' value='<?php echo $other_info['searching_expiry_date']; ?>'>        
        
        <input type="submit" class="d-none apply-btn-actionz" value="Apply">
        
    </form>
    
    <?php if( !empty($selling_tips)){ ?>
    
    <div class='row'>
        <div class="col-xl-2 col-6 mb-30">
            <a href='#op-sel-tip'>Go to Option Selling Tips</a>
        </div>
    </div>
    
    <?php } ?>
    
    <div class="row">
        
        <div class="col-xl-1 col-6 mb-30">
        
            <label class="switch">
                <input type="checkbox" class="show_hide_bid_ask_qty_col">
                <span class="slider round"></span>
            </label>
            
        </div>
        <div class="col-xl-2 col-6 mb-30">
        
            Show Bid Ask Quantity
            
        </div>
        
    </div>
    
    <div class="row">
        
        <div class="col-xl-1 col-6 mb-30">
        
            <label class="switch">
                <input type="checkbox" checked class="show_hide_oi_vol_col">
                <span class="slider round"></span>
            </label>
            
        </div>
        <div class="col-xl-2 col-6 mb-30">
        
            Hide Oi and Volume Column
            
        </div>
        
    </div>
    
    <div class="row">
        
        <div class="col-xl-1 col-12 mb-30">
            <button type="button" class="btn btn-light" onClick="zoomInBody();">Zoom In +</button>
        </div>
        
        <div class="col-xl-1 col-12 mb-30">
            <button type="button" class="btn btn-light" onClick="zoomOutBody();">Zoom Out -</button>
        </div>
        
    </div>
    
    <!--<div class="table-responsive">-->
    <div class="option-table">
    
        <table class="table table-bordered p_c_data_table">
            <thead>
                <tr>
                    <th colspan="12" class="text-center">CALLS</th>
                    <th>&nbsp;</th>
                    <th colspan="12" class="text-center">PUTS</th>
                </tr>
                <tr>
                    <th></th>
                    
                    <th class="oi-vol">OI</th>
                    <th class="oi-vol">Change In OI</th>
                    <th class="oi-vol">Volume</th>
                    <th class="oi-vol">Change In OI/Volume</th>
                    
                    <th class="option_greek">Delta</th>
                    <th class="option_greek">Gamma</th>
                    <th class="option_greek">Vega</th>
                    <th class="option_greek">Theta</th>
                    <th class="option_greek">Rho</th>
                    
                    <th class="option_greek">Theta LTP Decay %</th>
                    
                    <th>IV</th>
                    <th>LTP</th>
                    <th>Net Change</th>
                    <th class="bid-ask d-none">Bid Qty</th>
                    <th class="bid-ask d-none">Bid Price</th>
                    <th class="bid-ask d-none">Ask Price</th>
                    <th class="bid-ask d-none">Ask Qty</th>
                    <th>Strike Price</th>
                    <th class="bid-ask d-none">Bid Qty</th>
                    <th class="bid-ask d-none">Bid Price</th>
                    <th class="bid-ask d-none">Ask Price</th>
                    <th class="bid-ask d-none">Ask Qty</th>
                    <th>Net Change</th>
                    <th>LTP</th>
                    <th>IV</th>
                    
                    <th class="option_greek">Theta LTP Decay %</th>
                    
                    <th class="option_greek">Rho</th>
                    <th class="option_greek">Theta</th>
                    <th class="option_greek">Vega</th>
                    <th class="option_greek">Gamma</th>
                    <th class="option_greek">Delta</th>
                    
                    
                    <th class="oi-vol">Change In OI/Volume</th>
                    <th class="oi-vol">Volume</th>
                    <th class="oi-vol">Change In OI</th>
                    <th class="oi-vol">OI</th>
                    
                    <th></th>
                </tr>

            </thead>
            <tbody>
                <?php foreach($oc_data AS $oc_data_value){
                    if( $oc_data_value->strike_price < $other_info['underlying_price'] ){
                        
                        $call_td_class = 'call_in_the_money_yes';
                        $put_td_class = 'put_in_the_money_no';
                        
                    }else{
                        
                        $call_td_class = 'call_in_the_money_no';
                        $put_td_class = 'put_in_the_money_yes';
                    }
                ?>
                <tr class="p_c_data <?php echo 'each_row_' . $oc_data_value->id; ?>">
                    <td class="container-histo">
                        <?php $calls_oi_p = number_format( ( ($oc_data_value->calls_oi/$other_info["total_calls_oi"]) * 100), 2); ?>
                        <div class="histo histo-call-put" style="width: <?php echo ($calls_oi_p>=100) ? 0 : $calls_oi_p*10; ?>%">
                        <?php echo $calls_oi_p ; ?>%
                        </div>
                    </td>
                    <td class="oi-vol <?php echo $call_td_class; ?>"><?php echo money_format('%!.0n',$oc_data_value->calls_oi); ?></td>
                    <?php 
                    
                        $calls_chng_oi_color_class = '';
                        
                        if($oc_data_value->calls_chng_in_oi > 0.00){
                            
                            $calls_chng_oi_color_class = 'positive_no_w';
                            
                        }else if($oc_data_value->calls_chng_in_oi < 0.00){
                            
                            $calls_chng_oi_color_class = 'negative_no_w';
                        }
                    ?>
                    <td class="oi-vol <?php echo $call_td_class; ?> chng_oi_td_class">
                        <span><?php echo money_format('%!.0n',$oc_data_value->calls_chng_in_oi); ?></span>
                        <span class="<?php echo $calls_chng_oi_color_class; ?>"></span>
                    </td>
                    <td class="oi-vol <?php echo $call_td_class; ?>"><?php echo money_format('%!.0n',$oc_data_value->calls_volume); ?></td>
                    
                    <td class="oi-vol <?php echo $call_td_class; ?>">
                        
                        <?php
                            try{
                                
                                if( !empty($oc_data_value->calls_volume)){
                                    
                                    echo round ( ( $oc_data_value->calls_chng_in_oi/$oc_data_value->calls_volume ) , 2);
                                    
                                }else{
                                    echo 'NA';
                                }
                                                                

                            }catch(Exception $e) {
                                echo 'NA';
                            }                        
                        ?>
                    
                    </td>
                    
                    <td class="option_greek calls_delta <?php echo $call_td_class; ?>"></td>
                    <td class="option_greek calls_gamma <?php echo $call_td_class; ?>"></td>
                    <td class="option_greek calls_vega <?php echo $call_td_class; ?>"></td>
                    <td class="option_greek calls_theta <?php echo $call_td_class; ?>"></td>
                    <td class="option_greek calls_rho <?php echo $call_td_class; ?>"></td>
                    
                    <td class="option_greek calls_theta_ltp_decay_p <?php echo $call_td_class; ?>"></td>
                    
                    <td class="<?php echo $call_td_class; ?>"><?php echo $oc_data_value->calls_iv; ?></td>
                    <td class="<?php echo $call_td_class; ?>"><?php echo $oc_data_value->calls_ltp; ?></td>
                    <?php 
                    
                        $calls_net_chng_color_class = '';
                        
                        if($oc_data_value->calls_net_chng > 0.00){
                            
                            $calls_net_chng_color_class = 'positive_no';
                            
                        }else if($oc_data_value->calls_net_chng < 0.00){
                            
                            $calls_net_chng_color_class = 'negative_no';
                        }
                    ?>
                    <td class="<?php echo $call_td_class . ' ' . $calls_net_chng_color_class; ?>"><?php echo $oc_data_value->calls_net_chng; ?></td>
                    <td class="bid-ask d-none <?php echo $call_td_class; ?>"><?php echo money_format('%!.0n',$oc_data_value->calls_bid_qty); ?></td>
                    <td class="bid-ask d-none <?php echo $call_td_class; ?>"><?php echo $oc_data_value->calls_bid_price; ?></td>
                    <td class="bid-ask d-none <?php echo $call_td_class; ?>"><?php echo $oc_data_value->calls_ask_price; ?></td>
                    <td class="bid-ask d-none <?php echo $call_td_class; ?>"><?php echo money_format('%!.0n',$oc_data_value->calls_ask_qty); ?></td>
                    <td class="strike_price_td">
                        <a href="<?php echo base_url() . "option-chain/strike-price-log/" . $other_info['company_id'] . '/' . base64_url_encode($other_info['company_symbol']) . '/' . $other_info['searching_underlying_date'] . '/' . $other_info['searching_expiry_date'] . '/' .$oc_data_value->strike_price. '/' .$live; ?>">
                        <?php echo ($oc_data_value->strike_price=='0.00') ? '' :$oc_data_value->strike_price; ?>
                        </a>
                    </td>
                    <td class="bid-ask d-none <?php echo $put_td_class; ?>"><?php echo money_format('%!.0n',$oc_data_value->puts_bid_qty); ?></td>
                    <td class="bid-ask d-none <?php echo $put_td_class; ?>"><?php echo $oc_data_value->puts_bid_price; ?></td>
                    <td class="bid-ask d-none <?php echo $put_td_class; ?>"><?php echo $oc_data_value->puts_ask_price; ?></td>
                    <td class="bid-ask d-none <?php echo $put_td_class; ?>"><?php echo money_format('%!.0n',$oc_data_value->puts_ask_qty); ?></td>
                    <?php 
                    
                        $puts_net_chng_color_class = '';
                        
                        if($oc_data_value->puts_net_chng > 0.00){
                            
                            $puts_net_chng_color_class = 'positive_no';
                            
                        }else if($oc_data_value->puts_net_chng < 0.00){
                            
                            $puts_net_chng_color_class = 'negative_no';
                        }
                    ?>
                    <td class="<?php echo $put_td_class . ' ' . $puts_net_chng_color_class; ?>"><?php echo $oc_data_value->puts_net_chng; ?></td>
                    <td class="<?php echo $put_td_class; ?>"><?php echo $oc_data_value->puts_ltp; ?></td>
                    <td class="<?php echo $put_td_class; ?>"><?php echo $oc_data_value->puts_iv; ?></td>
                    
                    <td class="option_greek puts_theta_ltp_decay_p <?php echo $put_td_class; ?>"></td>
                    
                    <td class="option_greek puts_rho <?php echo $put_td_class; ?>"></td>
                    <td class="option_greek puts_theta <?php echo $put_td_class; ?>"></td>
                    <td class="option_greek puts_vega <?php echo $put_td_class; ?>"></td>
                    <td class="option_greek puts_gamma <?php echo $put_td_class; ?>"></td>
                    <td class="option_greek puts_delta <?php echo $put_td_class; ?>"></td>
                    
                    
                    
                    <td class="oi-vol <?php echo $put_td_class; ?>">
                    
                        <?php
                            try{
                                
                                if( !empty($oc_data_value->puts_volume)){
                                
                                    echo round( ( $oc_data_value->puts_chng_in_oi / $oc_data_value->puts_volume )  , 2 );
                                    
                                }else{
                                    
                                    echo 'NA';
                                }
                                                                

                            }catch(Exception $e) {
                                
                                echo 'NA';
                            }                        
                        ?>
                        
                    </td>
                    
                    <td class="oi-vol <?php echo $put_td_class; ?>"><?php echo money_format('%!.0n',$oc_data_value->puts_volume); ?></td>
                    <?php 
                    
                        $puts_chng_oi_color_class = '';
                        
                        if($oc_data_value->puts_chng_in_oi > 0){
                            
                            $puts_chng_oi_color_class = 'positive_no_w';
                            
                        }else if($oc_data_value->puts_chng_in_oi < 0){
                            
                            $puts_chng_oi_color_class = 'negative_no_w';
                        }
                    ?>
                    <td class="oi-vol <?php echo $put_td_class; ?> chng_oi_td_class">
                        <span><?php echo money_format('%!.0n',$oc_data_value->puts_chng_in_oi); ?></span>
                        <span class="<?php echo $puts_chng_oi_color_class; ?>"></span>
                    </td>
                    <td class="oi-vol <?php echo $put_td_class; ?>"><?php echo money_format('%!.0n',$oc_data_value->puts_oi); ?></td>
                    <td class="container-histo">
                        <?php $puts_oi_p = number_format( ( ($oc_data_value->puts_oi/$other_info["total_puts_oi"]) * 100), 2); ?>
                        <div class="histo histo-call-put" style="width: <?php echo ($puts_oi_p>=100) ? 0 : $puts_oi_p*10; ?>%">
                        <?php echo $puts_oi_p ; ?>%
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        
    </div>
    
    <?php if( !empty($selling_tips)){ ?>
    
    <div class="option-sell-tips-table mt-60" id='op-sel-tip'>
        
        <h2 class='mb-30'>Option Selling Tips</h2>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="2" class="text-center yellow">Annual Range</th>                    
                    <th colspan="2" class="text-center orange">Monthly Range</th>                    
                    <th colspan="2" class="text-center sky-blue">Weekly Range</th>                    
                    <th colspan="2" class="text-center lime-green">Expiry/Daily</th>
                    
                </tr>
                
                <tr>
                    <th class='yellow'>High</th>
                    <th class='yellow'>Low</th>
                    
                    <th class='orange'>High</th>
                    <th class='orange'>Low</th>
                    
                    <th class='sky-blue'>High</th>
                    <th class='sky-blue'>Low</th>
                    
                    <th class='lime-green'>High</th>
                    <th class='lime-green'>Low</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class='yellow'><?php echo $selling_tips['annual_range_high']; ?></td>
                    <td class='yellow'><?php echo $selling_tips['annual_range_low']; ?></td>
                    
                    <td class='orange'><?php echo $selling_tips['monthly_range_high']; ?></td>
                    <td class='orange'><?php echo $selling_tips['monthly_range_low']; ?></td>
                    
                    <td class='sky-blue'><?php echo $selling_tips['weekly_range_high']; ?></td>
                    <td class='sky-blue'><?php echo $selling_tips['weekly_range_low']; ?></td>
                    
                    <td class='lime-green'><?php echo $selling_tips['daily_range_high']; ?></td>
                    <td class='lime-green'><?php echo $selling_tips['daily_range_low']; ?></td>
                    
                </tr>
            </tbody>
        </table>
        
    </div>
    
    <?php } ?>
    
    <!--</div>-->
</div>

<div id="last_t_bill" data-all='<?php echo $last_t_bill; ?>' ></div>
<div id="chart_data" data-all='<?php echo json_encode($oc_data); ?>' ></div>

