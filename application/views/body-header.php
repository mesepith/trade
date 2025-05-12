<body id="body_page" style="zoom: 100%">
<?php // echo '<pre>'; print_r($_SERVER); exit; ?>
<?php // echo '<pre>'; print_r($this->uri->segment(1)); exit; ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <!--<a class="navbar-brand" href="#">Navbar</a>-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo base_url(); ?>">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo base_url() . 'stock-analysis'; ?>">Stock Analysis <span class="sr-only">(current)</span></a>
                </li>
               
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Option Chain
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/company-list'; ?>">Daily Data</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/company-list/live'; ?>">Daily Live Data</a>
                    </div>
                </li>
               
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Future
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'future/day-wise-analysis'; ?>">Day wise Analysis</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'future/company-list'; ?>">Company Data</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'future/rollover/day-wise-analysis'; ?>">Rollover Day wise Analysis</a>
                    </div>
                </li>
                
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Log
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'log-view/daily/company-list'; ?>">Daily Wise</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'log-view/timely/company-list'; ?>">Time Wise</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo base_url() . 'log-view/sectors-list'; ?>">Sector</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'nifty-heavy-weight-stocks'; ?>">Nifty Heavy Weight</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Up Stocks
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'up-stocks/daily/5-pecent-up-on-last-trade'; ?>">5% UP on Last Trade</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Option Chain Analysis
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/iv-analysis/company-list'; ?>">IV Company Wise</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/iv-analysis/day-wise'; ?>">IV Day Wise</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/iv-analysis/day-wise-live'; ?>">IV Day Wise Live</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/pd-analysis/company-list'; ?>">Premium Decay Company Wise</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/pd-analysis/day-wise'; ?>">Premium Decay Day Wise</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/pd-analysis/day-wise-live'; ?>">Premium Decay Day Wise Live</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/iv-pd-analysis/live/bull'; ?>">Common IV AND Premium Decay Live Bull</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/iv-pd-analysis/live/bear'; ?>">Common IV AND Premium Decay Live Bear</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/op-analysis/day-wise'; ?>">Option Pain Day Wise</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/high-oi-and-high-change-in-oi'; ?>">Highest OI And Highest Change in OI</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'option-chain/option-greek/calculator'; ?>">Option Greek Calculator</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        FII/DII
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'fii-dii/total-investment'; ?>">Total Investment</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'fii-dii/fii-derivative'; ?>">FII Derivative Data</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'fii-dii/fii-sectore-invest'; ?>">FII Sector Investment</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'top-10-exchange-clearing-member'; ?>">Top 10 Exchange Clearing Member Data</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Participant Wise
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'client-activity/oi-participant'; ?>">Oi Participant Wise</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'client-activity/volume-participant'; ?>">Volume Participant Wise</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'category-wise-turnover/derivative'; ?>">Category Wise Turnover Derivative</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'category-wise-turnover/cash'; ?>">Category Wise Turnover Cash</a>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        52 Week High Low
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'year-high-low/high'; ?>">52 Week High</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'year-high-low/low'; ?>">52 Week Low</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'year-high-low-compare/current-price/day-wise'; ?>">Company High Low</a>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Volatility
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'daily-volatility'; ?>">Daily</a>
                    </div>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Shareholding
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo base_url() . 'shareholding/company-list'; ?>">Shareholding Distribution</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'share-corporate/insider-trading'; ?>">Insider Trading</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'company-list/insider-trading'; ?>">Insider Trading Company List</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'share-corporate/sast-regulation-29'; ?>">SAST 29</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'company-list/sast-regulation-29'; ?>">SAST 29 Company List</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'share-corporate/pledged-data'; ?>">Pledged Data</a>
                        <a class="dropdown-item" href="<?php echo base_url() . 'bulk-block-deal'; ?>">Bulk Block Deal</a>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>
