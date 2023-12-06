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

    <h1 class='mb-60'>Insider Trading <?php
        if (!empty($company_id) && !empty($company_symbol)) {
            echo 'of ' . $company_symbol;
        }
        ?></h1>

    <?php
    if (!empty($acq_disp_name)) {
        echo '<h3 class="mb-60">Name Of The Acquirer/ Disposer : <b>' . $acq_disp_name . ' </b> </h3>';
    }
    ?>

    <h3 class="mb-30">Broadcast date: 
        <b>
            <?php
            if (!empty($acq_disp_name) && $broadcaste_date_all === 'all') {

                echo 'All';
            } else {
                echo date('d M Y', strtotime($broadcaste_date));
            }
            ?>
        </b>

        <?php if ((!empty($company_id) && !empty($company_symbol) || !empty($acq_disp_name) ) && !empty($broadcaste_date_to)) { ?>

            to <b><?php echo date('d M Y', strtotime($broadcaste_date_to)); ?></b>

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

            <?php if ((!empty($company_id) && !empty($company_symbol) ) || !empty($acq_disp_name)) { ?>

                <div class="col-xl-2 mb-10">Select Date To: </div>
                <div class="col-xl-2 col-11 mb-30 htm-date-container"> 
                    <input class="htm-date date_flat_pickz date_flat_pickz_to" readonly="readonly" value="<?php echo empty($broadcaste_date_to) ? date('Y-m-d') : $broadcaste_date_to; ?>"  onchange="changeStockDate(event, 'to');">
                    <span class="open-date-button">
                        <button type="button">📅</button>
                    </span>
                </div>

                <input type="hidden" class="company_id" value="<?php echo $company_id; ?>">

                <input type="hidden" class="broadcaste_date" name="broadcaste_date" value="<?php echo empty($broadcaste_date_all) ? date('Y-m-d') : $broadcaste_date_all; ?>">

                <input type="hidden" class="broadcaste_date_to" name="broadcaste_date_to" value="<?php echo empty($broadcaste_date_to) ? date('Y-m-d') : $broadcaste_date_to; ?>">

                <div class="acq_disp_name" data-valz="<?php echo $acq_disp_name; ?>"></div>

            <?php } else { ?>

                <input type="hidden" class="broadcaste_date" name="broadcaste_date" value="<?php echo empty($broadcaste_date) ? date('Y-m-d') : $broadcaste_date; ?>">
                <input type="hidden" class="broadcaste_date_to" name="broadcaste_date_to" value="<?php echo empty($broadcaste_date_to) ? date('Y-m-d') : $broadcaste_date_to; ?>">

            <?php } ?>

        </div>

        <div class="row mb-20">

            <div class="col-xl-2 col-4 mb-30 form-check">
                Select Acquisition/ Disposal
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_disp" value="all" name="acq_disp" <?php echo ( $acq_disp === "all" ) ? 'checked' : ''; ?>>All
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_disp" value="buy" name="acq_disp" <?php echo ( $acq_disp === "buy" ) ? 'checked' : ''; ?>>Buy
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_disp" value="sell" name="acq_disp" <?php echo ( $acq_disp === "sell" ) ? 'checked' : ''; ?>>Sell
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_disp" value="Pledge" name="acq_disp" <?php echo ( $acq_disp === "Pledge" ) ? 'checked' : ''; ?>>Pledge
                </label>
            </div>
            <div class="col-xl-1 col-3 mb-30 form-check" data-toggle="tooltip" title="Lenders sells the pledged shares in the market!">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_disp" value="Pledge Inv" name="acq_disp" <?php echo ( $acq_disp === "Pledge Inv" ) ? 'checked' : ''; ?>>Pledge Invoke
                </label>
            </div>
            <div class="col-xl-1 col-3 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_disp" value="Pledge Rev" name="acq_disp" <?php echo ( $acq_disp === "Pledge Rev" ) ? 'checked' : ''; ?>>Pledge Revoke
                </label>
            </div>

        </div>

        <div class="row mb-20">

            <div class="col-xl-2 col-4 mb-30 form-check">
                Select Mode of Acquisition
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_mode" value="all" name="acq_mode" <?php echo ( $acq_mode === "all" ) ? 'checked' : ''; ?>>All
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_mode" value="Market Purchase" name="acq_mode" <?php echo ( $acq_mode === "Market Purchase" ) ? 'checked' : ''; ?>>Market Purchase
                </label>
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input select_acq_mode" value="Market Sale" name="acq_mode" <?php echo ( $acq_mode === "Market Sale" ) ? 'checked' : ''; ?>>Market Sale
                </label>
            </div>            

        </div>

        <div class="row mb-20">

            <div class="col-xl-2 col-4 mb-30 form-check">
                Select Category of Person
            </div>
            <div class="col-xl-1 col-2 mb-30 form-check">

                <select name="person_category[]" multiple="multiple">
                    <option value="all">All</option>
                    <option value="Director" <?php
                    if ((is_array($person_category)) && (in_array("Director", $person_category))) {
                        echo 'selected';
                    }
                    ?>>Director</option>
                    <option value="Promoter Group" <?php
                    if ((is_array($person_category)) && (in_array("Promoter Group", $person_category))) {
                        echo 'selected';
                    }
                    ?>>Promoter Group</option>
                    <option value="Promoters" <?php
                    if ((is_array($person_category)) && (in_array("Promoters", $person_category))) {
                        echo 'selected';
                    }
                    ?>>Promoters</option>
                </select>

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
                        Sort Value Of Security (Acquired/ Disposed) - <?php echo ucfirst($security_sortby); ?>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item sort_by_security_val <?php echo ($security_sortby === 'high') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-security-sortby="high">High</a>
                        <a class="dropdown-item sort_by_security_val <?php echo ($security_sortby === 'low') ? 'sel-sec' : ''; ?>" href="javascript:void(0)" data-security-sortby="low">Low</a>
                    </div>
                </div>
            </div>

        </div>


        <div class="row mb-20">

            <div class="col-xl-3 col-4 mb-30 form-check">
                <input type="checkbox" class="sum_sec_val_by_comp" id="sum_sec_val_by_comp" name="sum_sec_val_by_comp" value="yes" <?php
                if ($sum_sec_val_by_comp == 'yes') {
                    echo 'checked';
                }
                ?>>
                <label for="sum_sec_val_by_comp"> Consolidated Value Of Security By Company</label><br>
            </div>

        </div>


        <div class="row mb-20">

            <div class="col-xl-1 col-6 mb-30 form-check">
                <a href="<?php echo base_url($url); ?>">Reset</a>
            </div>

<?php if (!empty($company_id) && !empty($company_symbol)) { ?>
                <div class="col-xl-1 col-6 mb-30 form-check">
                    <a href="<?php echo base_url('share-corporate/insider-trading/' . $company_id . '/' . base64_url_encode($company_symbol) . '/all'); ?>">All Date</a>
                </div>
        <?php } ?>
        </div>

<?php // echo $market_date; exit;    ?>

        <input type="hidden" class="security_sortby" name="security_sortby">

        <input type="submit" class="d-none apply-btn-actionz" value="Apply">

    </form>

<?php if (!empty($insider_trading) && count($insider_trading) > 0) { ?>

        <h4 class="mb-20">Total Trading : <?php echo count($insider_trading); ?></h4>

    <?php if ($sum_sec_val_by_comp == 'yes') { ?>        

            <table class="table table-striped">
                <thead>
                    <tr> 
                        <th>Company</th> 
                        <th>Value Of Security (Acquired/ Disposed)</th> 
                    </tr>                
                </thead>

                <tbody> 

        <?php foreach ($insider_trading AS $insider_trading_key => $insider_trading_value) { ?>   

                        <tr>
                            <td><?php echo $insider_trading_value->company_symbol; ?></td>
                            <td><?php echo money_format('%!.0n', $insider_trading_value->sec_val); ?></td>
                        </tr>
        <?php } ?>

                </tbody>

    <?php } else { ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <?php if ((!empty($company_id) && !empty($company_symbol) || !empty($acq_disp_name) ) && (!empty($broadcaste_date_to) || $broadcaste_date_all === 'all' )) { ?>
                                <th>Broadcast Date Time</th>
                            <?php } else { ?>
                                <th>Broadcast Time</th>
        <?php } ?>
                            <th>Company</th>
                            <th>Name Of The Acquirer/ Disposer </th>

                            <th>Type of Security</th>
                            <th>Security Acquired/ Disposed</th>
                            <th>Value Of Security (Acquired/ Disposed)</th>

                            <th>Acquisition/ Disposal</th>                
                            <th>Category Of Person </th>
                            <th>Mode Of Acquisition</th>

                            <th>No. Of Security (Prior)</th>
                            <th>% Shareholding (Prior)</th>

                            <th>No. Of Security (Post)</th>
                            <th>% Shareholding (Post)</th>

                            <th>Date Of Allotment/acquisition From</th>
                            <th>Date Of Allotment/acquisition To</th>
                            <th>Date Of Initmation To Company</th>


                            <th>Exchange</th>
                        </tr>
                    </thead>
                    <tbody>

                            <?php 
                            
                                $sum_total_security = 0;
                                $sum_total_security_value = 0;
                                
                                foreach ($insider_trading AS $insider_trading_key => $insider_trading_value) {
                                    
                                    $sum_total_security = $sum_total_security + $insider_trading_value->sec_acq;
                                    $sum_total_security_value = $sum_total_security_value + $insider_trading_value->sec_val;
                            ?>

                            <tr>
            <?php if ((!empty($company_id) && !empty($company_symbol) || !empty($acq_disp_name) ) && (!empty($broadcaste_date_to) || $broadcaste_date_all === 'all' )) { ?>

                                    <td><?php echo date('d M Y h:i a ', strtotime($insider_trading_value->broadcaste_datetime)); ?></td>

                                    <td>
                                        <a href="<?php echo base_url() . 'daily-log/' . $insider_trading_value->company_id . '/' . base64_url_encode($insider_trading_value->company_symbol); ?>">
                <?php echo $insider_trading_value->company_symbol; ?>
                                        </a>
                                    </td>

            <?php } else { ?>

                                                                    <!--<td><?php echo date('h:i a ', strtotime($insider_trading_value->broadcaste_time)); ?></td>-->
                                    <td><?php echo date('d M Y h:i a ', strtotime($insider_trading_value->broadcaste_datetime)); ?></td>

                                    <td>
                                        <a href="<?php echo base_url() . 'share-corporate/insider-trading/' . $insider_trading_value->company_id . '/' . base64_url_encode($insider_trading_value->company_symbol) . '/' . $insider_trading_value->broadcaste_date; ?>">
                <?php echo $insider_trading_value->company_symbol; ?>
                                        </a>
                                    </td>

            <?php } ?>


                                <td>
                                    <a href="<?php echo base_url() . 'share-corporate/insider-trading/acquirer-disposer/' . base64_url_encode($insider_trading_value->acq_name) . '/all'; ?>">
            <?php echo $insider_trading_value->acq_name; ?>
                                    </a>
                                </td>

                                <td><?php echo $insider_trading_value->sec_type; ?></td>
                                <td><?php echo money_format('%!.0n', $insider_trading_value->sec_acq); ?></td>
                                <td><?php echo money_format('%!.0n', $insider_trading_value->sec_val); ?></td>

                                <td><?php echo $insider_trading_value->tdp_transaction_type; ?></td>
                                <td><?php echo $insider_trading_value->person_category; ?></td>
                                <td><?php echo $insider_trading_value->acq_mode; ?></td>

                                <td><?php echo money_format('%!.0n', $insider_trading_value->bef_acq_shares_no); ?></td>
                                <td><?php echo $insider_trading_value->bef_acq_shares_per; ?></td>

                                <td><?php echo money_format('%!.0n', $insider_trading_value->after_acq_shares_no); ?></td>
                                <td><?php echo $insider_trading_value->after_acq_shares_per; ?></td>

                                <td><?php echo date('d M Y ', strtotime($insider_trading_value->acq_from_dt)); ?></td>
                                <td><?php echo date('d M Y ', strtotime($insider_trading_value->acq_to_dt)); ?></td>
                                <td><?php echo date('d M Y ', strtotime($insider_trading_value->intim_dt)); ?></td>


                                <td><?php echo $insider_trading_value->exchange; ?></td>
                            </tr>
        <?php } ?>
                            <tr>
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><?php echo money_format('%!.0n', $sum_total_security); ?></td>
                                <td><?php echo money_format('%!.0n', $sum_total_security_value); ?></td>
                            </tr>
                            <tr>
                                <td>Average Share Price</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><?php echo money_format('%!.0n', ($sum_total_security_value/$sum_total_security) ); ?></td>
                            </tr>
                    </tbody>
                </table>

            <?php } ?>

<?php } else { ?>

            <div class=" mt-60">
                <div class="alert alert-danger">
                    <strong>No Data Available</strong> 
                </div>
            </div>

<?php } ?>

</div>