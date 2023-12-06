
<h1 class=" mb-20"><?php echo 'Shareholding Pattern Detail of <b>' . $company_symbol . '</b>'; ?></h1>    

<div class="row mb-60 mt-60">

    <div class="col-xl-12 col-12">

        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <?php echo $shares_type_arr[$shares_type]; ?>
            </button>
            <div class="dropdown-menu sector-dropdown-menu">

                <?php foreach ($shares_type_arr AS $shares_type_arr_key => $shares_type_arr_val) { ?>

                    <a class="dropdown-item <?php echo ( $shares_type === $shares_type_arr_key) ? 'sel-share-type' : '' ?>" href="<?php echo base_url() . 'shareholding/' . $shares_type_arr_key . '/' . $company_id . '/' . base64_url_encode($company_symbol) . '/' . $market_date . '/' . base64_url_encode($record_id); ?>">
                        <?php echo $shares_type_arr_val; ?>
                    </a>

                <?php } ?>
            </div>
        </div>

    </div>

</div>

<h4 class=" mb-20">Date : <?php echo date('d-M-Y', strtotime($market_date)); ?> </h4>

<div class="row mb-60 mt-60">

    <div class="col-xl-12 col-12">

        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <?php echo empty($market_date) ? 'Select Published Date' : 'Published Date - ' . date('d M Y', strtotime($market_date)); ?>
            </button>
            <div class="dropdown-menu sector-dropdown-menu">

                <?php foreach ($share_distribution_list AS $share_distribution_list_val) { ?>

                    <a class="dropdown-item <?php echo ( $market_date === $share_distribution_list_val->market_date) ? 'sel-pub-date' : '' ?>" href="<?php echo base_url() . 'shareholding/' . $shares_type . '/' . $company_id . '/' . base64_url_encode($company_symbol) . '/' . $share_distribution_list_val->market_date . '/' . base64_url_encode($share_distribution_list_val->record_id); ?>">
                        <?php echo date('d M Y', strtotime($share_distribution_list_val->market_date)); ?>
                    </a>

                <?php } ?>
            </div>
        </div>

    </div>

</div>