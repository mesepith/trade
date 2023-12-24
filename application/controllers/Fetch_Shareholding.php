<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/System_Notification_Controller.php");
include_once (dirname(__FILE__) . "/Nse_Contr.php");
include_once (dirname(__FILE__) . "/Python_Controller.php");
include_once (dirname(__FILE__) . "/Send_Api_Contr.php");

class Fetch_Shareholding extends MX_Controller {

    public function __construct() {
        parent::__construct();
        // $this->load->model('fetch_nse_cookies_model');
    }

    function fetchShareHoldingDataByCompany() {

        $Python_contr = new Python_Controller();
        $Python_contr->executeCookieScript();

        ini_set('max_execution_time', 0);

        ini_set('xdebug.max_nesting_level', 200000000000000);
        ini_set('memory_limit', '-1');

        $Send_Api_Contr = new Send_Api_Contr();

        $System_Notification_contr = new System_Notification_Controller();
        $Nse_Contr = new Nse_Contr();

        $last_calculated_company_id = 0;

        $this->load->model('Analysis_task_model');
        $last_calculated_company = $this->Analysis_task_model->lastCalculatedCompany('share_distribution');
        echo '<pre>'; print_r($last_calculated_company); exit;

        if( !empty($last_calculated_company) ){
            
            $last_crawled_company_id = $last_calculated_company->company_id;
            $last_calculated_company_id = ($last_crawled_company_id);
            $last_calculated_time = $last_calculated_company->updated_at;

        }

        $company_list = $Send_Api_Contr->listAllCompanies($last_calculated_company_id);

        foreach ($company_list AS $company_list_value) {

            $company_symbol = $company_list_value['symbol'];
//            $company_symbol = 'RELIANCE';

            $company_id = $company_list_value['id'];

            echo '<br/><br/>$company_id ' . $company_id;
            echo '<br/><br/>$company_symbol ' . $company_symbol;

            $share_holding_master_url = 'https://www.nseindia.com/api/corporate-share-holdings-master?index=equities&symbol=' . urlencode($company_symbol);
            $share_holding_master_referer = 'https://www.nseindia.com/companies-listing/corporate-filings-shareholding-pattern?symbol=' . urlencode($company_symbol) . '&tabIndex=equity';

            $share_holding_master = $Nse_Contr->curlNse($share_holding_master_url, $share_holding_master_referer);

        //    echo '<pre>';
        //    print_r($share_holding_master); exit;

            if (empty($share_holding_master)) {
                continue;
            }

            $count = 0;

            $this->load->model('ShareHolding_model');

            foreach ($share_holding_master AS $share_holding_master_val) {

                $count++;
                // echo '<br/>';
                // echo 'count : ' . $count;

                // echo '<br/>';
                $market_date = trim($share_holding_master_val['date']);
                // echo 'Date ' . $market_date; 
                // echo '<br/>';
                $ndsId = trim($share_holding_master_val['recordId']);
                // echo '$ndsId : ' . $ndsId;

                $share_distribution_arr = array();

                $share_distribution_arr['company_id'] = $company_id;
                $share_distribution_arr['company_symbol'] = $company_symbol;
                $share_distribution_arr['market_date'] = date('Y-m-d', strtotime(trim($market_date)));
                $share_distribution_arr['record_id'] = $ndsId;
                $share_distribution_arr['promoter'] = trim($share_holding_master_val['pr_and_prgrp']);
                $share_distribution_arr['public'] = trim($share_holding_master_val['public_val']);
                $share_distribution_arr['underlying_drs'] = trim($share_holding_master_val['underlyingDrs']);
                $share_distribution_arr['employee_trusts'] = trim($share_holding_master_val['employeeTrusts']);
                $share_distribution_arr["created_at"] = date("Y-m-d H:i:s");

                $share_distribution_id = $this->ShareHolding_model->insertShareDistribution($share_distribution_arr);

                /*if ($share_distribution_id > 0) {

                    $this->shareHoldingsEquities($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                    $this->declaration($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                    $this->unclaimedShares($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                    $this->concertShare($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                    $this->beneficialOwners($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                }*/
            }

            $this->Analysis_task_model->ocCalculationDone($company_id, $company_symbol, 'share_distribution');#this means we have stored last share distribution for this company 
            echo '<br/><br/> ********************************************** <br/><br/>';
        //    exit();
        }

        flush();
    }

    /*
    Crawl sll share distribution data, which has all_data_fetched = 0 in share_distribution table
    */
    function crawlAllShareDistributionRecords(){

        $Python_contr = new Python_Controller();
        $Python_contr->executeCookieScript();

        $Send_Api_Contr = new Send_Api_Contr();
        $Nse_Contr = new Nse_Contr();

        $company_list = $Send_Api_Contr->listAllCompanies(0);
        // echo '<pre>'; print_r($company_list);

        foreach ($company_list AS $company_list_value) {

            $company_symbol = $company_list_value['symbol'];
            $company_id = $company_list_value['id'];

            echo '<br/> $company_symbol : ' . $company_symbol . ', company_id : ' . $company_id . '<br/>';

            $this->load->model('ShareHolding_model');
            $records = $this->ShareHolding_model->listPendingFetchedShareDistribution($company_id, $company_symbol);
           
            if (empty($records)) {
                echo '<br/> No records for  : ' . $company_symbol . '<br/>';
                continue;
            }

            // echo '<pre>'; print_r($records); exit;

            foreach($records as $each_records ){

                $ndsId = $each_records->record_id;
                $market_date = $each_records->market_date;
                $share_distribution_id = $each_records->id;

                echo '<br/> ndsId : ' . $ndsId . ', market_date : ' . $market_date . ', share_distribution_id : ' . $share_distribution_id . '<br/>';

                //We delete Old Fetching status to avoid duplicacy
                $this->ShareHolding_model->deleteOldFetching($company_id, $company_symbol, $ndsId, $share_distribution_id);

                $this->shareHoldingsEquities($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                // exit;
                $this->declaration($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                $this->unclaimedShares($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                $this->concertShare($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);
                $this->beneficialOwners($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id);

                $records = $this->ShareHolding_model->updateShareDistributionFetchStatus($company_id, $company_symbol, $ndsId, $share_distribution_id);

                $this->shareDistributionNewApiTest($company_id, $company_symbol, $ndsId, $market_date, $Nse_Contr);
                // exit;
                
            }

            // exit;

            
        }

    }

    /**
     * New  API for Share distribution test
     */

     function shareDistributionNewApiTest($company_id, $company_symbol, $ndsId, $market_date, $Nse_Contr){

        $new_format_date = date("d-M-Y", strtotime($market_date));
        echo $new_format_date; 

        $url_arr = array(
            'disclosure' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?index=disclsrTM&symbol='.$company_symbol.'&period_ended='. $new_format_date,
            'clarifications' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId='.$ndsId.'&index=clarifications',
            'foreign-ownership-limits' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId='.$ndsId.'&index=foreign-ownership-limits',
        );

        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-shareholding-pattern?symbol=' . urlencode($company_symbol) . '&tabIndex=equity';

        foreach ($url_arr AS $url_arr_key => $url_arr_val) {

            $url = $url_arr_val;

            $share_arr = $Nse_Contr->curlNse($url, $referer);

            if (empty($share_arr)) {
                return;
            }

            $this->ShareHolding_model->insertShareDistributionNewApiTest($company_id, $company_symbol, $ndsId, $market_date, $url_arr_key, $url, $share_arr);
        }

     }

    /*
     * Get share holders details
     */

    function shareHoldingsEquities($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id) {

        $url_arr = array(
            'summary' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=summary',
            'promoter' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=promoter',
            'public-shareholder' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=public-shareholder',
            'non-public-shareholder' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=non-public-shareholder',
        );

        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-shareholding-pattern?symbol=' . urlencode($company_symbol) . '&tabIndex=equity';

        foreach ($url_arr AS $url_arr_key => $url_arr_val) {

            $url = $url_arr_val;

            $share_arr = $Nse_Contr->curlNse($url, $referer);

            // echo 'share_arr ';
            // print_r($share_arr);  exit; 

            if (empty($share_arr)) {
                return;
            }
            
            // echo '<br/> $url_arr_val : ' .$url_arr_val . '<br/>';
            // echo '<pre>';
                          

            foreach ($share_arr AS $share_arr_val) {

                $share_insert_arr = array();

                $share_insert_arr['company_id'] = $company_id;
                $share_insert_arr['company_symbol'] = $company_symbol;
                $share_insert_arr['market_date'] = date('Y-m-d', strtotime(trim($market_date)));
                $share_insert_arr['record_id'] = $ndsId;
                $share_insert_arr['share_distribution_id'] = $share_distribution_id;
                $share_insert_arr['shares_type'] = $url_arr_key;

                if ($url_arr_key === "public-shareholder" || $url_arr_key === "non-public-shareholder" || $url_arr_key === "promoter") {

//                    continue;
                    $share_insert_arr['category'] = trim($share_arr_val['category']);
                    $share_insert_arr['shareholder_category'] = trim($share_arr_val['COL_I']);
                } else {

                    $share_insert_arr['category'] = trim($share_arr_val['COL_I']);
                    $share_insert_arr['shareholder_category'] = trim($share_arr_val['COL_II']);
                }


                $share_insert_arr['shareholders_no'] = trim($share_arr_val['COL_III']);
                $share_insert_arr['fully_paid_up_equity_shares_no'] = trim($share_arr_val['COL_IV']);
                $share_insert_arr['total_shares'] = trim($share_arr_val['COL_IX_Total']);


                $share_insert_arr['no_of_voting_right'] = trim($share_arr_val['COL_IX_X']);
                $share_insert_arr['total_no_of_voting_right'] = trim($share_arr_val['COL_VII']);

                /* if( $url_arr_key === "non-public-shareholder" ){

                  $share_insert_arr['voting_share_p'] = trim($share_arr_val['COL_IX_TotalABC']);

                  }else{

                  $share_insert_arr['voting_share_p'] = trim($share_arr_val['COL_VIII']);
                  $share_insert_arr['share_in_p_a'] = trim($share_arr_val['COL_IX_TotalABC']);
                  } */

                $share_insert_arr['share_in_p_a'] = trim($share_arr_val['COL_VIII']);
                $share_insert_arr['voting_share_p'] = trim($share_arr_val['COL_IX_TotalABC']);



                $share_insert_arr['share_in_p_b'] = trim($share_arr_val['COL_XI']);
                $share_insert_arr['no_of_shares_demat_form'] = trim($share_arr_val['COL_XIV']);

                $share_insert_arr["created_at"] = date("Y-m-d H:i:s");

                // echo '<pre>';
                // print_r($share_insert_arr);

                $this->ShareHolding_model->insertShareHolding($share_insert_arr);
            }

            // echo '<br/>';
            // echo ' ################################################# ';
            // echo '<br/>';
        }

//        exit;
    }

    /*
     * Declaration submitted by the Listed companies
     */

    function declaration($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id) {

        $url = 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=declaration';

        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-shareholding-pattern?symbol=' . urlencode($company_symbol) . '&tabIndex=equity';

        $declaration_return_arr = $Nse_Contr->curlNse($url, $referer);

        if (empty($declaration_return_arr)) {
            return;
        }

//        echo '<pre>';
//        print_r($declaration_arr);

        foreach ($declaration_return_arr AS $declaration_return_arr_val) {

            $declaration_arr = array();

            $declaration_arr['company_id'] = $company_id;
            $declaration_arr['company_symbol'] = $company_symbol;
            $declaration_arr['market_date'] = date('Y-m-d', strtotime(trim($market_date)));
            $declaration_arr['record_id'] = $ndsId;
            $declaration_arr['share_distribution_id'] = $share_distribution_id;

            $declaration_arr['question'] = $declaration_return_arr_val['particulars'];
            $declaration_arr['promoter_group'] = $declaration_return_arr_val['promoter_group'];
            $declaration_arr['public'] = $declaration_return_arr_val['public'];
            $declaration_arr['non_public'] = $declaration_return_arr_val['non_public'];

            $declaration_arr["created_at"] = date("Y-m-d H:i:s");

            $this->ShareHolding_model->insertDeclaration($declaration_arr);
        }
    }

    /*
     * Details of Unclaimed shares
     */

    function unclaimedShares($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id) {

        $url_arr = array(
            'promoter' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=promoter&subIndex=sharesdata',
            'public-shareholder' => 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=public-shareholder&subIndex=sharesdata',
        );

        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-shareholding-pattern?symbol=' . urlencode($company_symbol) . '&tabIndex=equity';

        foreach ($url_arr AS $url_arr_key => $url_arr_val) {

            $unclaimed_shares_return_arr = $Nse_Contr->curlNse($url_arr_val, $referer);

            if (empty($unclaimed_shares_return_arr)) {
                continue;
            }

            $unclaimed_shares_arr['company_id'] = $company_id;
            $unclaimed_shares_arr['company_symbol'] = $company_symbol;
            $unclaimed_shares_arr['market_date'] = date('Y-m-d', strtotime(trim($market_date)));
            $unclaimed_shares_arr['record_id'] = $ndsId;
            $unclaimed_shares_arr['share_distribution_id'] = $share_distribution_id;
            $unclaimed_shares_arr['shares_type'] = $url_arr_key;

            $unclaimed_shares_arr["created_at"] = date("Y-m-d H:i:s");

            $unclaimed_shares_arr['no_of_shareholders'] = $unclaimed_shares_return_arr[0]['noOfShareHolders'];
            $unclaimed_shares_arr['no_of_shares'] = $unclaimed_shares_return_arr[0]['noOfSharesHeld'];

            $this->ShareHolding_model->insertUnclaimedShares($unclaimed_shares_arr);
        }
    }

    /*
     * Details of  Concert Share holder 
     */

    function concertShare($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id) {

        $url = 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=public-shareholder&subIndex=shareholders';
        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-shareholding-pattern?symbol=' . urlencode($company_symbol) . '&tabIndex=equity';

        $concert_shareholder_return_arr = $Nse_Contr->curlNse($url, $referer);

        if (empty($concert_shareholder_return_arr)) {
            return;
        }

        foreach ($concert_shareholder_return_arr AS $concert_shareholder_return_arr_val) {

            $concert_shareholder_arr = array();

            $concert_shareholder_arr['company_id'] = $company_id;
            $concert_shareholder_arr['company_symbol'] = $company_symbol;
            $concert_shareholder_arr['market_date'] = date('Y-m-d', strtotime(trim($market_date)));
            $concert_shareholder_arr['record_id'] = $ndsId;
            $concert_shareholder_arr['share_distribution_id'] = $share_distribution_id;

            $concert_shareholder_arr['shareholder_name'] = trim($concert_shareholder_return_arr_val['shareholderName']);
            $concert_shareholder_arr['pac_name'] = trim($concert_shareholder_return_arr_val['nameOfPAC']);
            $concert_shareholder_arr['no_of_shareholders'] = trim($concert_shareholder_return_arr_val['noOfShareHolders']);
            $concert_shareholder_arr['no_of_shares'] = trim($concert_shareholder_return_arr_val['noOfSharesHeld']);

            $concert_shareholder_arr["created_at"] = date("Y-m-d H:i:s");

            $this->ShareHolding_model->insertConcertShare($concert_shareholder_arr);
        }

//        exit;
    }

    function beneficialOwners($company_id, $company_symbol, $Nse_Contr, $ndsId, $market_date, $share_distribution_id) {

        $url = 'https://www.nseindia.com/api/corporate-share-holdings-equities?ndsId=' . $ndsId . '&index=beneficial-owners';
        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-shareholding-pattern?symbol=' . urlencode($company_symbol) . '&tabIndex=equity';

        $beneficial_owners_return_arr = $Nse_Contr->curlNse($url, $referer);

        if (empty($beneficial_owners_return_arr)) {
            return;
        }

        foreach ($beneficial_owners_return_arr AS $beneficial_owners_return_arr_val) {

            $beneficial_owners_arr = array();

            $beneficial_owners_arr['company_id'] = $company_id;
            $beneficial_owners_arr['company_symbol'] = $company_symbol;
            $beneficial_owners_arr['market_date'] = date('Y-m-d', strtotime(trim($market_date)));
            $beneficial_owners_arr['record_id'] = $ndsId;
            $beneficial_owners_arr['share_distribution_id'] = $share_distribution_id;

            $beneficial_owners_arr['sbo_name'] = trim($beneficial_owners_return_arr_val['ssName']);
            $beneficial_owners_arr['sbo_nationality'] = trim($beneficial_owners_return_arr_val['ssNationality']);
            $beneficial_owners_arr['sbo_pan'] = trim($beneficial_owners_return_arr_val['ssPan']);
            $beneficial_owners_arr['sbo_passport'] = trim($beneficial_owners_return_arr_val['ssPassport']);

            $beneficial_owners_arr['regis_owner_name'] = trim($beneficial_owners_return_arr_val['ssrName']);
            $beneficial_owners_arr['regis_owner_nationality'] = trim($beneficial_owners_return_arr_val['ssrNationality']);
            $beneficial_owners_arr['regis_owner_pan'] = trim($beneficial_owners_return_arr_val['ssrPan']);
            $beneficial_owners_arr['regis_owner_passport'] = trim($beneficial_owners_return_arr_val['ssrPassport']);
            $beneficial_owners_arr['regis_owner_share'] = trim($beneficial_owners_return_arr_val['ssrShare']);
            $beneficial_owners_arr['regis_owner_vote_right'] = trim($beneficial_owners_return_arr_val['ssrVotingRes']);

            $beneficial_owners_arr['regis_owner_rights'] = trim($beneficial_owners_return_arr_val['ssrRights']);
            $beneficial_owners_arr['exec_sign_influ'] = trim($beneficial_owners_return_arr_val['ssrExecSignInflu']);
            $beneficial_owners_arr['exec_control'] = trim($beneficial_owners_return_arr_val['ssrExecControl']);
            $beneficial_owners_arr['creation_acq_date'] = date('Y-m-d', strtotime(trim($beneficial_owners_return_arr_val['ssrCreationAcqDate'])));

            $beneficial_owners_arr["created_at"] = date("Y-m-d H:i:s");

            $this->ShareHolding_model->insertBeneficialOwner($beneficial_owners_arr);
        }
    }

    /*
     * Insider Trading
     * https://www.nseindia.com/companies-listing/corporate-filings-insider-trading
     */

    function insiderTrading($curl_check = false) {
        
//        echo $curl_check; exit;

        $url = 'https://www.nseindia.com/api/corporates-pit?';
//        $url = 'https://www.nseindia.com/api/corporates-pit?index=equities&from_date=21-05-2020&to_date=21-10-2020';
//        $url = 'https://www.nseindia.com/api/corporates-pit?index=equities&from_date=22-10-2020&to_date=21-04-2021';
//        $url = 'https://www.nseindia.com/api/corporates-pit?index=equities&from_date=22-04-2021&to_date=21-05-2021';
        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-insider-trading';
        
        // $data_return_arr = $this->curlCookieNSeExecute( $url, $referer, $curl_check );

//        exit;
        $Python_contr = new Python_Controller();
        $Python_contr->executeCookieScript();

        $Nse_Contr = new Nse_Contr();
        $data_return_arr = $Nse_Contr->curlNse($url, $referer);

        // echo '<pre>';
        // print_r($data_return_arr); exit;


        if (empty($data_return_arr['data'])) {
            return;
        }

        $Send_Api_Contr = new Send_Api_Contr();

        $this->load->model('ShareHolding_model');

        foreach ($data_return_arr['data'] AS $data_return_arr_val) {
//        foreach (array_reverse($data_return_arr['data']) AS $data_return_arr_val) {

            $insider_trading = array();

            $insider_trading['company_symbol'] = trim($data_return_arr_val['symbol']);

            if (!empty($data_return_arr_val['symbol'])) {

                $insider_trading['company_id'] = $Send_Api_Contr->getCompanyIdAndIndexIdBySymbol(trim($data_return_arr_val['symbol']));
            } else {

                $insider_trading['company_id'] = 0;
            }

            $insider_trading['regulation'] = trim($data_return_arr_val['anex']); // REGULATION 
            $insider_trading['acq_name'] = trim($data_return_arr_val['acqName']); // NAME OF THE ACQUIRER/DISPOSER  

            $insider_trading['broadcaste_datetime'] = date('Y-m-d H:i:s', strtotime(trim($data_return_arr_val['date']))); // BROADCASTE DATE AND TIME 
            $insider_trading['broadcaste_date'] = date('Y-m-d', strtotime(trim($data_return_arr_val['date']))); // BROADCASTE DATE 
            $insider_trading['broadcaste_time'] = date('H:i:s', strtotime(trim($data_return_arr_val['date']))); // BROADCASTE TIME 

            $insider_trading['pid'] = trim($data_return_arr_val['pid']);

            $insider_trading['tkd_acqm'] = trim($data_return_arr_val['tkdAcqm']);
            $insider_trading['buy_value'] = trim($data_return_arr_val['buyValue']);
            $insider_trading['sell_value'] = trim($data_return_arr_val['sellValue']);
            $insider_trading['buy_quantity'] = trim($data_return_arr_val['buyQuantity']);
            $insider_trading['sell_quantity'] = trim($data_return_arr_val['sellquantity']);

            $insider_trading['sec_type'] = trim($data_return_arr_val['secType']); //TYPE OF SECURITY (PRIOR) 
            $insider_trading['sec_acq'] = trim($data_return_arr_val['secAcq']); // NO. OF SECURITIES (ACQUIRED/DISPLOSED) 
            $insider_trading['tdp_transaction_type'] = trim($data_return_arr_val['tdpTransactionType']); // ACQUISITION/DISPOSAL TRANSACTION TYPE 

            $insider_trading['did'] = trim($data_return_arr_val['did']);

            $insider_trading['tdp_derivative_contract_type'] = trim($data_return_arr_val['tdpDerivativeContractType']); // DERIVATIVE CONTRACT SPECIFICATION 

            $insider_trading['person_category'] = trim($data_return_arr_val['personCategory']); // CATEGORY OF PERSON 

            $insider_trading['bef_acq_shares_no'] = trim($data_return_arr_val['befAcqSharesNo']); // NO. OF SECURITY (PRIOR) 
            $insider_trading['bef_acq_shares_per'] = trim($data_return_arr_val['befAcqSharesPer']); // % SHAREHOLDING (PRIOR) 
            $insider_trading['sec_val'] = trim($data_return_arr_val['secVal']); // VALUE OF SECURITY (ACQUIRED/DISPLOSED) 
            $insider_trading['securities_type_post'] = trim($data_return_arr_val['securitiesTypePost']); // TYPE OF SECURITY (POST) 
            $insider_trading['after_acq_shares_no'] = trim($data_return_arr_val['afterAcqSharesNo']); // NO. OF SECURITY (POST) 
            $insider_trading['after_acq_shares_per'] = trim($data_return_arr_val['afterAcqSharesPer']); // % POST 

            $insider_trading['acq_from_dt'] = date('Y-m-d', strtotime(trim($data_return_arr_val['acqfromDt']))); //DATE OF ALLOTMENT/ACQUISITION FROM 
            $insider_trading['acq_to_dt'] = date('Y-m-d', strtotime(trim($data_return_arr_val['acqtoDt']))); //DATE OF ALLOTMENT/ACQUISITION TO 
            $insider_trading['intim_dt'] = date('Y-m-d', strtotime(trim($data_return_arr_val['intimDt']))); //DATE OF INITMATION TO COMPANY 

            $insider_trading['acq_mode'] = trim($data_return_arr_val['acqMode']); // MODE OF ACQUISITION 
            $insider_trading['derivative_type'] = trim($data_return_arr_val['derivativeType']); // DERIVATIVE TYPE SECURITY 
            $insider_trading['exchange'] = trim($data_return_arr_val['exchange']); // EXCHANGE 
            $insider_trading['remarks'] = trim($data_return_arr_val['remarks']); // REMARK  

            $insider_trading["created_at"] = date("Y-m-d H:i:s");

            $share_distribution_id = $this->ShareHolding_model->insertInsiderTrading($insider_trading);
        }
    }

    /*
     * Pledge Data
     * https://www.nseindia.com/companies-listing/corporate-filings-pledged-data
     */

    function pledgeData() {

        $url = 'https://www.nseindia.com/api/corporate-pledgedata?index=equities';
        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-insider-trading';

        $Nse_Contr = new Nse_Contr();

        $data_return_arr = $Nse_Contr->curlNse($url, $referer);
//        echo 'rows ' ;  
//        echo '<pre>'; print_r($data_return_arr);
//        if( empty($data_return_arr['data'])){ return ; }

        $this->load->model('ShareHolding_model');

        $Send_Api_Contr = new Send_Api_Contr();

//        $comp_name = $Send_Api_Contr->getCompanyIdAndSymbolByName('Ashiana Housing Limited'); 
//        echo '$comp_name ' ;  
//        echo '<pre>'; print_r($comp_name); exit;

        foreach ($data_return_arr['data'] AS $data_return_arr_val) {

            $pledge_data = array();

            $pledge_data['company_id'] = 0;
            $pledge_data['company_symbol'] = '';

            if (!empty($data_return_arr_val['comName'])) {

                $company_arr = $Send_Api_Contr->getCompanyIdAndSymbolByName(trim($data_return_arr_val['comName']));

                $pledge_data['company_id'] = !empty($company_arr->id) ? $company_arr->id : 0;
                $pledge_data['company_symbol'] = !empty($company_arr->symbol) ? $company_arr->symbol : '';
            }

            $pledge_data['company_name'] = trim($data_return_arr_val['comName']);


            $pledge_data['shp'] = date('Y-m-d', strtotime(trim($data_return_arr_val['shp'])));
            $pledge_data['tot_issued_shares'] = trim($data_return_arr_val['totIssuedShares']); // TOTAL NO. OF ISSUED SHARES A+B+C
            $pledge_data['tot_promoter_holding'] = trim($data_return_arr_val['totPromoterHolding']); // NO. OF SHARES (A)
            $pledge_data['perc_promoter_holding'] = trim($data_return_arr_val['percPromoterHolding']); //TOTAL PROMOTER HOLDING % A /(A+B+C)
            $pledge_data['tot_public_holding'] = trim($data_return_arr_val['totPublicHolding']); //TOTAL PUBLIC HOLDING (%)B
            $pledge_data['tot_promoter_shares_enc'] = trim($data_return_arr_val['totPromoterShares']); //PROMOTER SHARES ENCUMBERED AS OF LAST QUARTER NO. OF SHARES (X)
            $pledge_data['perc_promoter_shares_enc'] = trim($data_return_arr_val['percPromoterShares']); //PROMOTER SHARES ENCUMBERED AS OF LAST QUARTER % OF PROMOTER SHARES (X/A)
            $pledge_data['perc_tot_shares_enc'] = trim($data_return_arr_val['percTotShares']); //PROMOTER SHARES ENCUMBERED AS OF LAST QUARTER % OF TOTAL SHARES [X/(A+B+C)]

            $pledge_data['disclosure_from_date'] = date('Y-m-d', strtotime(trim($data_return_arr_val['disclosureFromDate']))); //

            $pledge_data['num_shares_pledged_demat'] = trim($data_return_arr_val['numSharesPledged']); //NO. OF SHARES PLEDGED IN THE DEPOSITORY SYSTEM NO. OF SHARES PLEDGED
            $pledge_data['tot_demat_shares'] = trim($data_return_arr_val['totDematShares']); //NO. OF SHARES PLEDGED IN THE DEPOSITORY SYSTEM TOTAL NO. OF DEMAT SHARES

            $pledge_data['shares_collateral'] = trim($data_return_arr_val['sharesCollateral']); //
            $pledge_data['nbfc_promo_share'] = trim($data_return_arr_val['nbfcPromoShare']); //
            $pledge_data['nbfc_non_promo_share'] = trim($data_return_arr_val['nbfcNonPromoShare']); //

            $pledge_data['perc_shares_pledged_demat'] = trim($data_return_arr_val['percSharesPledged']); //  (%) PLEDGE / DEMAT                      

            $pledge_data['broadcaste_datetime'] = date('Y-m-d H:i:s', strtotime(trim($data_return_arr_val['broadcastDt']))); // BROADCASTE DATE AND TIME 
            $pledge_data['broadcaste_date'] = date('Y-m-d', strtotime(trim($data_return_arr_val['broadcastDt']))); // BROADCASTE DATE             
            $pledge_data['broadcaste_time'] = date('H:i:s', strtotime(trim($data_return_arr_val['broadcastDt']))); // BROADCASTE TIME             

            $pledge_data['disclosure_to_date'] = date('Y-m-d', strtotime(trim($data_return_arr_val['disclosureToDate']))); // DISCLOSURE MADE BY PROMOTERS
            $pledge_data['comp_broadcast_date'] = date('Y-m-d', strtotime(trim($data_return_arr_val['compBroadcastDate']))); // 

            echo 'rows ';
            echo '<pre>';
            print_r($pledge_data);

//            $pledge_data["created_at"] = date("Y-m-d H:i:s");

            $return = $this->ShareHolding_model->insertPledgeData($pledge_data);

            echo 'return <br/>';
            echo '<pre>';
            print_r($return);
        }
    }

    /*
     * Mutual or institutional investor buying or selling by SAST regulation 29(1) and 29(2)
     * https://www.nseindia.com/companies-listing/corporate-filings-regulation-29
     */

    function sast29( $curl_check = false ) {

        $today_date = date("d-m-Y");

        $last_90_day_date = date('d-m-Y', strtotime('-5 day', strtotime(date("Y-m-d"))));

        $url = 'https://www.nseindia.com/api/corporate-sast-reg29?index=equities&from_date=' . $last_90_day_date . '&to_date=' . $today_date;
        $referer = 'https://www.nseindia.com/companies-listing/corporate-filings-regulation-29';
        
        /*
        $Nse_Contr = new Nse_Contr();

        $data_return_arr = $Nse_Contr->curlNse($url, $referer);         
         */
        
        $data_return_arr = $this->curlCookieNSeExecute( $url, $referer, $curl_check );

        $reverse_arr = array_reverse($data_return_arr['data']);

        $Send_Api_Contr = new Send_Api_Contr();

        $this->load->model('ShareHolding_model');

        foreach ($reverse_arr AS $data_return_arr_val) {

            $sast_data = array();

            $sast_data['company_id'] = 0;
            $sast_data['company_symbol'] = '';

            if (!empty($data_return_arr_val['symbol'])) {

                $company_id = $Send_Api_Contr->getCompanyIdBySymbol(trim($data_return_arr_val['symbol']));

                $sast_data['company_id'] = !empty($company_id) ? $company_id : 0;
                $sast_data['company_symbol'] = trim($data_return_arr_val['symbol']);
            }

            $sast_data['company_name'] = trim($data_return_arr_val['company']);

            $sast_data['name'] = trim($data_return_arr_val['acquirerName']);



            $acq_sale_dt_expld = explode('to', $data_return_arr_val['acquirerDate']);
//            echo '<pre>'; print_r($acq_sale_dt_expld);            
//            $sast_data['acq_or_sale_date'] = trim($data_return_arr_val['acquirerDate']);

            $sast_data['acq_or_sale_date_from'] = !empty($acq_sale_dt_expld[0]) ? date('Y-m-d', strtotime($acq_sale_dt_expld[0])) : '';
            $sast_data['acq_or_sale_date_to'] = !empty($acq_sale_dt_expld[1]) ? date('Y-m-d', strtotime($acq_sale_dt_expld[1])) : '';

            $sast_data['total_share_acq'] = trim($data_return_arr_val['noOfShareAcq']);
            $sast_data['total_share_sale'] = trim($data_return_arr_val['noOfShareSale']);
            $sast_data['total_share_after'] = trim($data_return_arr_val['noOfShareAft']);
            $sast_data['regulation'] = trim($data_return_arr_val['regType']);
            $sast_data['application_no'] = trim($data_return_arr_val['application_no']);

            $sast_data['promoter_type'] = trim($data_return_arr_val['promoterType']);
            $sast_data['acq_or_sale_type'] = trim($data_return_arr_val['acqSaleType']);

            $sast_data['mode'] = trim($data_return_arr_val['acquisitionMode']);
            $sast_data['type'] = trim($data_return_arr_val['acqType']);

            $sast_data['total_share_acq_p'] = trim($data_return_arr_val['totAcqShare']);
            $sast_data['total_acq_diluted_p'] = trim($data_return_arr_val['totAcqDiluted']);

            $sast_data['total_share_sale_p'] = trim($data_return_arr_val['totSaleShare']);
            $sast_data['total_sale_diluted_p'] = trim($data_return_arr_val['totSaleDiluted']);

            $sast_data['total_share_after_p'] = trim($data_return_arr_val['totAftShare']);
            $sast_data['total_after_diluted_p'] = trim($data_return_arr_val['totAftDiluted']);

            $sast_data['remarks'] = trim($data_return_arr_val['remarks']);
            $sast_data['attachement'] = trim($data_return_arr_val['attachement']);

            $sast_data['broadcaste_datetime'] = date('Y-m-d H:i:s', strtotime(trim($data_return_arr_val['timestamp']))); // BROADCASTE DATE AND TIME 
            $sast_data['broadcaste_date'] = date('Y-m-d', strtotime(trim($data_return_arr_val['timestamp']))); // BROADCASTE DATE             
            $sast_data['broadcaste_time'] = date('H:i:s', strtotime(trim($data_return_arr_val['timestamp']))); // BROADCASTE TIME 

            $sast_data['exchange'] = 'nse';


//            $sast_data['time'] = attachementtrim($data_return_arr_val['time']);

            echo '<pre>';
            print_r($sast_data);

            $return = $this->ShareHolding_model->insertSastBuySale($sast_data);

            echo 'return <br/>';
            echo '<pre>';
            print_r($return);
        }
    }
    
    function curlCookieNSeExecute( $url, $referer, $curl_check ){
        
        $this->load->helper('nse_cookies_helper');
        
        $cookie_data = getWorkingApiCookiesRow($referer);
        
//        echo '<pre>'; print_r($cookie_data); exit;
        
        if (empty($cookie_data) ) {
            
            if ($curl_check != 'curl_yes') {
            
                echo 'No Active Working Cookie Available';
                exit;
            
            }
        }

        $cookies = $cookie_data['cookies_string'];
        
        $Nse_Contr = new Nse_Contr();

        $data_return_arr = $Nse_Contr->curlNseWithCookie($url, $referer, $cookies);

        if (empty($data_return_arr)) {

            $cnw = $this->fetch_nse_cookies_model->cookieNotWorking($referer, $url, $cookie_data['nse_cookies_url_id']);

            if ($curl_check == 'curl_yes') {

                echo 'no data'; exit;
                
            }else{

                return;
            }
        }

        if ($curl_check == 'curl_yes') {

            return;
        }
        
        return $data_return_arr;
        
    }
    
}
