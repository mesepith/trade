<?php $this->load->helper('function_helper'); ?>

<div class="container">
    <h2>Stock Analysis</h2>
    <p>Use Stock Analysis tool, to make an trade on share market.</p>                                                                                      

    <div class="row">


        <div class="col-xl-4">
            <div class="mb-30">
                <button type="button" class="btn btn-success display_good_stock">Display Pure Good Stock</button>
                <button type="button" class="btn btn-primary display_all_stock d-none">Display All Stock</button>
            </div>
        </div>

    </div>
    
    <form method="get" action="<?php echo base_url('stock-filter'); ?>">
        <div class="row">
        
            <div class="col-xl-1 col-12 mb-30">
                Date Range
            </div>
            <div class="col-xl-2 col-12 mb-30 htm-date-container"> 
                <input class="htm-date" type="date" name="from-date" value="<?php echo $from_date; ?>">
                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>
            <div class="col-xl-2 col-12 mb-30 htm-date-container">
                <input class="htm-date" type="date" name="to-date" value="<?php echo $to_date; ?>">

                <span class="open-date-button">
                    <button type="button">📅</button>
                </span>
            </div>

        </div>
        <div class="row mb-60">
            <div class="col-xl-1 col-12 mb-30">
                Select Range of
            </div>
            <div class="col-xl-3 col-12 mb-30"> 
                
                <?php
                $delivery_to_traded_quantity_date = !empty($filter['delivery_to_traded_quantity_date']) ? $filter['delivery_to_traded_quantity_date'] : '';
                $delivery_to_traded_quantity_min = !empty($filter['delivery_to_traded_quantity_min']) ? $filter['delivery_to_traded_quantity_min'] : 1;
                $delivery_to_traded_quantity_max = !empty($filter['delivery_to_traded_quantity_max']) ? $filter['delivery_to_traded_quantity_max'] : 100;
                ?>
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Delivery to Traded Quantity On:</span><span class='dtq__selected_date'><?php echo ' ' . $delivery_to_traded_quantity_date; ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){?>
                      <a class="dropdown-item delivery_to_traded_quantity_date_selct" href="javascript:void(0)" data-date='<?php echo $stock_date_list_arr_value; ?>'>
                          <?php echo $stock_date_list_arr_value; ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            <div class="col-xl-4 col-12 mb-30">                 
                
                <p>
                    <label for = "delivery_to_traded_quantity_slider">
                        <span>Delivery to Traded Quantity Range:</span><span class='dtq__selected_date'><?php echo ' ' . $delivery_to_traded_quantity_date . ' : '; ?></span>
                    </label>
                    <input type = "text" id = "delivery_to_traded_quantity_slider" 
                           style = "border:0; color:#d062cc; font-weight:bold; font-size: 26px;">
                </p>
                <div id = "slider-3"></div>                                
                
                <input type="hidden" name='delivery_to_traded_quantity_date' class="delivery_to_traded_quantity_date" value='<?php echo $delivery_to_traded_quantity_date; ?>'>
                <input type="hidden" name='delivery_to_traded_quantity_min' class="delivery_to_traded_quantity_min" value='<?php echo $delivery_to_traded_quantity_min; ?>'>
                <input type="hidden" name='delivery_to_traded_quantity_max' class="delivery_to_traded_quantity_max" value='<?php echo $delivery_to_traded_quantity_max; ?>'>
            </div>
        </div>
        
        <div class="row mb-60">
            <div class="col-xl-1 col-12 mb-30">
                Select Range of
            </div>
            <div class="col-xl-3 col-12 mb-30"> 
                
                <?php
                $total_traded_volume_date = !empty($filter['total_traded_volume_date']) ? $filter['total_traded_volume_date'] : '';
                $total_traded_volume_min = !empty($filter['total_traded_volume_min']) ? $filter['total_traded_volume_min'] : 10;
                $total_traded_volume_max = !empty($filter['total_traded_volume_max']) ? $filter['total_traded_volume_max'] : 100000000;
                ?>
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Total Traded Volume  On:</span><span class='ttv__selected_date'><?php echo ' ' . $total_traded_volume_date; ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){?>
                      <a class="dropdown-item total_traded_volume_date_selct" href="javascript:void(0)" data-date='<?php echo $stock_date_list_arr_value; ?>'>
                          <?php echo $stock_date_list_arr_value; ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            <div class="col-xl-4 col-12 mb-30"> 
                <div class="form-group">
                    <label for="mttv1"><span>Minimum Total Traded Volume:</span><span class='ttv__selected_date'><?php echo ' ' . $total_traded_volume_date . ' : ';?></span></label>
                    <input type="number" class="form-control" name='total_traded_volume_min' class="total_traded_volume_min" value='<?php echo $total_traded_volume_min; ?>'>
                </div>
            </div>
            <div class="col-xl-4 col-12 mb-30"> 
                <div class="form-group">
                    <label for="mttv2"><span>Maximum Total Traded Volume:</span><span class='ttv__selected_date'><?php echo ' ' . $total_traded_volume_date . ' : ';?></span></label>
                    <input type="number" class="form-control" name='total_traded_volume_max' class="total_traded_volume_max" value='<?php echo $total_traded_volume_max; ?>'>
                </div>
                <input type="hidden" name='total_traded_volume_date' class="total_traded_volume_date" value='<?php echo $total_traded_volume_date; ?>'>
            </div>
        </div>
        
        
        <div class="row mb-60">
            <div class="col-xl-1 col-12 mb-30">
                Select Range of
            </div>
            <div class="col-xl-3 col-12 mb-30"> 
                
                <?php
                $delivery_quantity_date = !empty($filter['delivery_quantity_date']) ? $filter['delivery_quantity_date'] : '';
                $delivery_quantity_min = !empty($filter['delivery_quantity_min']) ? $filter['delivery_quantity_min'] : 6;
                $delivery_quantity_max = !empty($filter['delivery_quantity_max']) ? $filter['delivery_quantity_max'] : 100000000;
                ?>
                 <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        <span>Delivery Quantity On:</span><span class='dq__selected_date'><?php echo ' ' . $delivery_quantity_date; ?></span>
                    </button>
                    <div class="dropdown-menu">
                    <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){?>
                      <a class="dropdown-item delivery_quantity_date_selct" href="javascript:void(0)" data-date='<?php echo $stock_date_list_arr_value; ?>'>
                          <?php echo $stock_date_list_arr_value; ?>
                      </a>
                    <?php }?>
                    </div>
                  </div>
                
            </div>
            <div class="col-xl-4 col-12 mb-30"> 
                <div class="form-group">
                    <label for="dq1"><span>Minimum Delivery Quantity:</span><span class='dq__selected_date'><?php echo ' ' . $delivery_quantity_date . ' : ';?></span></label>
                    <input type="number" class="form-control" name='delivery_quantity_min' class="delivery_quantity_min" value='<?php echo $delivery_quantity_min; ?>'>
                </div>
            </div>
            <div class="col-xl-4 col-12 mb-30"> 
                <div class="form-group">
                    <label for="dq2"><span>Maximum Delivery Quantity:</span><span class='dq__selected_date'><?php echo ' ' . $delivery_quantity_date . ' : ';?></span></label>
                    <input type="number" class="form-control" name='delivery_quantity_max' class="delivery_quantity_max" value='<?php echo $delivery_quantity_max; ?>'>
                </div>
                <input type="hidden" name='delivery_quantity_date' class="delivery_quantity_date" value='<?php echo $delivery_quantity_date; ?>'>
            </div>
        </div>
        
        <div class="row  mb-60">
            
            <div class="col-xl-1 col-12">
                <input type="submit" class="btn btn-outline-primary dt-range-sub apply-btn-actionz" value="Apply">
            </div>
            <div class="col-xl-1 col-12">
                <a class='btn btn-outline-secondary dt-range-sub apply-btn-actionz' href='<?php echo base_url(); ?>'>Reset</a>
            </div>
            
        </div>
        
        <div class="row">
            
            <div class="col-xl-3 col-12 mb-30"> 
                <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        Sort By Total Traded Volume <?php if( !empty($filter['total_traded_volume'])  && !empty($filter['sort_date']) ) echo '- ' . ucfirst($filter['total_traded_volume']) . ' - On: ' . $filter['sort_date']; ?>
                    </button>
                    <div class="sort-dd-menu dropdown-menu">
                        <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){?>
                        <h5 class="dropdown-header"> On <?php echo $stock_date_list_arr_value; ?></h5>
                        <?php
                        
                        $make_filter_sort_high_color='';
                        $make_filter_sort_low_color='';
                        
                        if( !empty($filter['total_traded_volume'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $stock_date_list_arr_value){
                            
                            if($filter['total_traded_volume'] =='high'){
                             
                                $make_filter_sort_high_color = 'yes';
                                
                            }else if($filter['total_traded_volume'] =='low'){
                                
                                $make_filter_sort_low_color = 'yes';
                            }
                        }
                        
                        ?>
                        <a class="is_on_filter_<?php echo $make_filter_sort_high_color; ?> dropdown-item sort_by" data-sortby="total_traded_volume" data-select="high" data-sortdate="<?php echo $stock_date_list_arr_value; ?>" href="javascript:void(0)"> High to Low</a>
                        <a class="is_on_filter_<?php echo $make_filter_sort_low_color; ?> dropdown-item sort_by" data-sortby="total_traded_volume" data-select="low"  data-sortdate="<?php echo $stock_date_list_arr_value; ?>" href="javascript:void(0)"> Low to High</a>
                        <?php }?>
                    </div>
                </div>
            </div>
            
            <!-- Date: 9 April 2020 Start -->
            <div class="col-xl-3 col-12 mb-30"> 
                <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        Sort By Total Traded Value <?php if( !empty($filter['total_traded_value'])  && !empty($filter['sort_date']) ) echo '- ' . ucfirst($filter['total_traded_value']) . ' - On: ' . $filter['sort_date']; ?>
                    </button>
                    <div class="sort-dd-menu dropdown-menu">
                        <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){?>
                        <h5 class="dropdown-header"> On <?php echo $stock_date_list_arr_value; ?></h5>
                        <?php
                        
                        $make_filter_sort_high_color='';
                        $make_filter_sort_low_color='';
                        
                        if( !empty($filter['total_traded_value'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $stock_date_list_arr_value){
                            
                            if($filter['total_traded_value'] =='high'){
                             
                                $make_filter_sort_high_color = 'yes';
                                
                            }else if($filter['total_traded_value'] =='low'){
                                
                                $make_filter_sort_low_color = 'yes';
                            }
                        }
                        
                        ?>
                        <a class="is_on_filter_<?php echo $make_filter_sort_high_color; ?> dropdown-item sort_by" data-sortby="total_traded_value" data-select="high" data-sortdate="<?php echo $stock_date_list_arr_value; ?>" href="javascript:void(0)"> High to Low</a>
                        <a class="is_on_filter_<?php echo $make_filter_sort_low_color; ?> dropdown-item sort_by" data-sortby="total_traded_value" data-select="low"  data-sortdate="<?php echo $stock_date_list_arr_value; ?>" href="javascript:void(0)"> Low to High</a>
                        <?php }?>
                    </div>
                </div>
            </div>
            <!-- Date: 9 April 2020 End -->
            
            <div class="col-xl-3 col-12 mb-30"> 
                <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        Sort By Delivery Quantity <?php if( !empty($filter['delivery_quantity'])  && !empty($filter['sort_date']) ) echo '- ' . ucfirst($filter['delivery_quantity']) . ' - On: ' . $filter['sort_date']; ?>
                    </button>
                    <div class="sort-dd-menu dropdown-menu">
                        <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){?>
                        <h5 class="dropdown-header"> On <?php echo $stock_date_list_arr_value; ?></h5>
                        
                        <?php
                        
                        $make_filter_sort_high_color='';
                        $make_filter_sort_low_color='';
                        
                        if( !empty($filter['delivery_quantity'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $stock_date_list_arr_value){
                            
                            if($filter['delivery_quantity'] =='high'){
                             
                                $make_filter_sort_high_color = 'yes';
                                
                            }else if($filter['delivery_quantity'] =='low'){
                                
                                $make_filter_sort_low_color = 'yes';
                            }
                        }
                        
                        ?>
                        
                        <a class="is_on_filter_<?php echo $make_filter_sort_high_color; ?> dropdown-item sort_by" data-sortby="delivery_quantity" data-select="high" data-sortdate="<?php echo $stock_date_list_arr_value; ?>" href="javascript:void(0)"> High to Low</a>
                        <a class="is_on_filter_<?php echo $make_filter_sort_low_color; ?> dropdown-item sort_by" data-sortby="delivery_quantity" data-select="low"  data-sortdate="<?php echo $stock_date_list_arr_value; ?>" href="javascript:void(0)"> Low to High</a>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-12 mb-30"> 
                <div class="dropdown">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                        Delivery to Traded Quantity <?php if( !empty($filter['delivery_to_traded_quantity'])  && !empty($filter['sort_date']) ) echo '- ' . ucfirst($filter['delivery_to_traded_quantity']) . ' - On: ' . $filter['sort_date']; ?>
                    </button>
                    <div class="sort-dd-menu dropdown-menu">
                        <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){?>
                        <h5 class="dropdown-header"> On <?php echo $stock_date_list_arr_value; ?></h5>
                        
                        <?php
                        
                        $make_filter_sort_high_color='';
                        $make_filter_sort_low_color='';
                        
                        if( !empty($filter['delivery_to_traded_quantity'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $stock_date_list_arr_value){
                            
                            if($filter['delivery_to_traded_quantity'] =='high'){
                             
                                $make_filter_sort_high_color = 'yes';
                                
                            }else if($filter['delivery_to_traded_quantity'] =='low'){
                                
                                $make_filter_sort_low_color = 'yes';
                            }
                        }
                        
                        ?>
                        
                        <a class="is_on_filter_<?php echo $make_filter_sort_high_color; ?> dropdown-item sort_by" data-sortby="delivery_to_traded_quantity" data-select="high" data-sortdate="<?php echo $stock_date_list_arr_value; ?>" href="javascript:void(0)"> High to Low</a>
                        <a class="is_on_filter_<?php echo $make_filter_sort_low_color; ?> dropdown-item sort_by" data-sortby="delivery_to_traded_quantity" data-select="low"  data-sortdate="<?php echo $stock_date_list_arr_value; ?>" href="javascript:void(0)"> Low to High</a>
                        <?php }?>
                    </div>
                </div>
            </div>
            
            <input type="hidden" class="sort_by_selection">
            <input type="hidden" class="sort_date">
            
        </div>
        
    </form>

    <div onscroll='scroller("scroller", "scrollme")' style="overflow:scroll; height: 10;" id="scroller">
        <img src="#" height=1 style="width:2066px;" class="fake-img">
    </div>
    <div class="table-responsive" onscroll='scroller("scrollme", "scroller")'  id="scrollme">
        
        <table class="stock-list-table table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Companies</th>
                    
                    <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){?>
                    <th>Price on <?php echo $stock_date_list_arr_value; ?></th>
                    <?php }?>
                    
                    <?php for($i=1; $i<$most_occured_no; $i++){?>
                    <th><?php echo 'Is Price on ' . $stock_date_list_arr[$i] . ' > '. $stock_date_list_arr[$i+1] . ' ? '; ?></th>
                    <?php } ?>
                    
                    <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){
                        
                        $make_filter_sort_color='';
                        if( (!empty($filter['total_traded_value'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $stock_date_list_arr_value)  ){
                            $make_filter_sort_color = 'yes';
                        }
                    ?>
                    
                    <th class="is_on_filter_<?php echo $make_filter_sort_color; ?>">Traded Value on <?php echo $stock_date_list_arr_value; ?></th>
                    
                    <?php }?>
                    
                    <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){
                        
                        $make_filter_sort_color='';
                        if( (!empty($filter['total_traded_volume'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $stock_date_list_arr_value)  || ( $total_traded_volume_date == $stock_date_list_arr_value ) ){
                            $make_filter_sort_color = 'yes';
                        }
                    ?>
                    
                    <th class="is_on_filter_<?php echo $make_filter_sort_color; ?>">Total Traded Volume on <?php echo $stock_date_list_arr_value; ?></th>
                    <?php }?>
                    
                    <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){                     
                        
                        $make_filter_sort_color='';
                        if( !empty($filter['delivery_quantity'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $stock_date_list_arr_value  || ( $delivery_quantity_date == $stock_date_list_arr_value )  ){
                            $make_filter_sort_color = 'yes';
                        }
                        
                    ?>
                    <th class="is_on_filter_<?php echo $make_filter_sort_color; ?>">Delivery Quantity on <?php echo $stock_date_list_arr_value; ?></th>
                    <?php }?>
                    
                    <?php foreach($stock_date_list_arr AS $stock_date_list_arr_value){
                    
                        
                        $make_filter_sort_color='';
                        if( ( !empty($filter['delivery_to_traded_quantity'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $stock_date_list_arr_value ) || ( $delivery_to_traded_quantity_date == $stock_date_list_arr_value ) ){
                            $make_filter_sort_color = 'yes';
                        }
                        
                    ?>
                    <th class="is_on_filter_<?php echo $make_filter_sort_color; ?>">Delivery to Traded Quantity on <?php echo $stock_date_list_arr_value; ?></th>
                    <?php }?>
                    
                    <?php for($i=1; $i<$most_occured_no; $i++){?>
                    <th><?php echo 'Is Delivery to Traded Quantity on ' . $stock_date_list_arr[$i] . ' > '. $stock_date_list_arr[$i+1] . ' ? '; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php $serial_count = 0;
                foreach ($company_arr AS $company_arr_key => $company_arr_value) {
                    if (count($company_arr[$company_arr_key]) > $most_occured_no) {
                        $serial_count++; ?>
                        <tr class="each_stock_analysis are_all_good_stock_<?php echo $company_arr_value['are_all_good_stock'] ?>" >
                            <td><?php echo $serial_count; ?></td>
                            <td>
                                <a href="<?php echo base_url() . 'daily-log/' . $company_arr_value['company_id'] . '/' . base64_url_encode($company_arr_key); ?>">
                                    <?php echo $company_arr_key; ?>
                                </a>
                            </td>

                        <?php for ($i = 1; $i < ($most_occured_no+1); $i++) { ?>

                            <td><span href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo $company_arr_value[$i]['stock_date']; ?>">
                                <?php echo $company_arr_value[$i]['close_price']; ?>
                                <br/>
                                <span class="<?php echo ($company_arr_value[$i]['price_change_in_p']>0) ? 'col-green' : 'col-red' ?>">(<?php echo $company_arr_value[$i]['price_change_in_p'];?>%)</span>
                                </span>
                            </td>

                        <?php } ?>

                        <?php for ($i = 1; $i < $most_occured_no; $i++) { ?>

                            <td><span class="<?php echo $company_arr_value[$i]['is_price_increase']; ?>" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo 'On ' . $company_arr_value[$i]['stock_date'] . ' of ' . $company_arr_key; ?>"><?php echo $company_arr_value[$i]['is_price_increase']; ?></span></td>

                        <?php } ?>
                                
                        <?php for ($i = 1; $i < ($most_occured_no+1); $i++) { 
                            
                            $make_filter_sort_color='';
                            if( (!empty($filter['total_traded_value'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $company_arr_value[$i]['stock_date']) ){
                                $make_filter_sort_color = 'yes';
                            }
                        ?>

                            <td class="is_on_filter_<?php echo $make_filter_sort_color; ?>"><span href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo $company_arr_value[$i]['stock_date']; ?>"><?php echo number_format($company_arr_value[$i]['total_traded_value']); ?></span></td>

                        <?php } ?>

        <?php for ($i = 1; $i < ($most_occured_no+1); $i++) { 
            
            $make_filter_sort_color='';
            if( (!empty($filter['total_traded_volume'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $company_arr_value[$i]['stock_date'])  || ( $total_traded_volume_date == $company_arr_value[$i]['stock_date'] ) ){
                $make_filter_sort_color = 'yes';
            }
        ?>

                                <td class="is_on_filter_<?php echo $make_filter_sort_color; ?>"><span href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo $company_arr_value[$i]['stock_date']; ?>"><?php echo money_format('%!.0n',$company_arr_value[$i]['total_traded_volume']); ?></span></td>

        <?php } ?> 

        <?php for ($i = 1; $i < ($most_occured_no+1); $i++) {
            
            $make_filter_sort_color='';
            if( (!empty($filter['delivery_quantity'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $company_arr_value[$i]['stock_date']) || ( $delivery_quantity_date == $company_arr_value[$i]['stock_date'] )  ){
                $make_filter_sort_color = 'yes';
            }
            
        ?>

                                <td class="is_on_filter_<?php echo $make_filter_sort_color; ?>"><span href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo $company_arr_value[$i]['stock_date']; ?>"><?php echo money_format('%!.0n',$company_arr_value[$i]['delivery_quantity']); ?></span></td>

        <?php } ?> 

        <?php for ($i = 1; $i < ($most_occured_no+1); $i++) {
        
            $make_filter_sort_color='';
            if( (!empty($filter['delivery_to_traded_quantity'])  && !empty($filter['sort_date']) && $filter['sort_date'] == $company_arr_value[$i]['stock_date'] ) || ( $delivery_to_traded_quantity_date == $company_arr_value[$i]['stock_date'] ) ){
                $make_filter_sort_color = 'yes';
            }
            
        ?>

                                <td class="is_on_filter_<?php echo $make_filter_sort_color; ?>"><span href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo $company_arr_value[$i]['stock_date']; ?>"><?php echo $company_arr_value[$i]['delivery_to_traded_quantity']; ?> %</span></td>

        <?php } ?> 

        <?php for ($i = 1; $i < $most_occured_no; $i++) { ?>

                                <td><span class="<?php echo $company_arr_value[$i]['is_delivery_to_traded_quantity_increase']; ?>" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="<?php echo $company_arr_value[$i]['stock_date']; ?>"><?php echo $company_arr_value[$i]['is_delivery_to_traded_quantity_increase']; ?></span></td>

        <?php } ?> 

                        </tr>
    <?php }
} ?>
            </tbody>
        </table>
    </div>
</div>