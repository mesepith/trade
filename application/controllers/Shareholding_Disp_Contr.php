<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shareholding_Disp_Contr extends MX_Controller {

    function companyList() {

        $this->load->model('Companies_model');

        $company_list = $this->Companies_model->listAllCompanies();

        $data['nestedData']['company_list'] = $company_list;

        $data['content'] = "shareholding/company-list";

        $this->load->view('index', $data);
    }

//    function companyListInsiderTrading(){
    function companyListByType($type) {

//        echo $type;
//        exit;

        switch ($type) {
            case "insider-trading":
                $h2txt = 'Insider Trading Company List';
                $redirect_url = 'share-corporate/insider-trading';
                break;
            case "sast-regulation-29":
                $h2txt = 'Sast Regulation 29 Company List';
                $redirect_url = 'share-corporate/sast-regulation-29';
                break;
            case "green":
                echo "Your favorite color is green!";
                break;
            default:
                echo "Your favorite color is neither red, blue, nor green!";
        }

        $this->load->model('Companies_model');

        $company_list = $this->Companies_model->listAllCompanies();

//        echo '<pre>';
//        print_r($company_list);

        $data['nestedData']['company_list'] = $company_list;
        $data['nestedData']['h2txt'] = $h2txt;
        $data['nestedData']['redirect_url'] = $redirect_url;

        $data['content'] = "shareholding/company-list-insdr-trding";

        $this->load->view('index', $data);
    }

    function displayShareDistrubution($company_id, $company_symbol_encode) {

        $this->load->helper('function_helper');
        $company_symbol = base64_url_decode($company_symbol_encode);

        $this->load->model('ShareHolding_disp_model');

        $all_date_chkbox = $this->input->get('all_date_chkbox');

        $market_date = $this->input->get('market_date');
        $market_date_to = $this->input->get('market_date_to');

        if (empty($market_date)) {

            $market_date = date('Y-m-d');
        }

        $share_distrubution = $this->ShareHolding_disp_model->fetchShareDistrubution($company_id, $company_symbol, $market_date, $market_date_to, $all_date_chkbox);

//        echo '<pre>'; print_r($share_distrubution);  exit;

        $data['nestedData']['market_date'] = empty($share_distrubution[0]->market_date) ? ( empty($market_date) ? date('Y-m-d') : $market_date ) : $share_distrubution[0]->market_date;

        $data['nestedData']['market_date_to'] = empty($market_date_to) ? date('Y-m-d') : $market_date_to;

        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;

        $data['nestedData']['all_date_chkbox'] = $all_date_chkbox;

        $data['nestedData']['share_distrubution'] = $share_distrubution;

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/share-distrubution.js");

        $data['content'] = "shareholding/share-distrubution";
        $this->load->view('index', $data);
    }

    /*
     * Display Summary Of Share
     */

    function displayShareSummary($shares_type, $company_id, $company_symbol_encode, $market_date, $record_id_encode) {

        $this->load->helper('function_helper');

        $this->load->model('ShareHolding_disp_model');

        $company_symbol = base64_url_decode($company_symbol_encode);

        $record_id = base64_url_decode($record_id_encode);

        $share_distribution_list = $this->ShareHolding_disp_model->listAllShareDistribution($company_id, $company_symbol);

        $shares_type_arr = array('declaration' => 'Declaration', 'summary' => 'Summary', 'promoter' => 'Promoter & Promoter Group', 'public-shareholder' => 'Public Shareholder'
            , 'non-public-shareholder' => 'Non Promoter Non Public Shareholder', 'unclaimed-shares' => 'Unclaimed shares'
            , 'significant-beneficial-owners' => 'Significant Beneficial Owners'
            , 'shareholders-concert' => 'Shareholders Concert');

        $data['nestedData']['shares_type_arr'] = $shares_type_arr;

        $data['nestedData']['shares_type'] = $shares_type;

        $data['nestedData']['share_distribution_list'] = $share_distribution_list;

        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;
        $data['nestedData']['market_date'] = $market_date;
        $data['nestedData']['record_id'] = $record_id;

        if ($shares_type === 'significant-beneficial-owners') {

            $this->significantBeneficialOwner($data);

            return;
        } else if ($shares_type === 'shareholders-concert') {

            $this->shareholdersConcert($data);

            return;
        } else if ($shares_type === 'declaration') {

            $this->shareDeclaration($data);

            return;
        } else if ($shares_type === 'unclaimed-shares') {

            $this->shareUnclaimed($data);

            return;
        }

        $share_data = $this->ShareHolding_disp_model->fetchShareSummary($shares_type, $company_id, $company_symbol, $market_date, $record_id);

        $data['nestedData']['share_data'] = $share_data;

        $data['content'] = "shareholding/holding-data";
        $this->load->view('index', $data);
    }

    function significantBeneficialOwner($data) {

        $share_data = $this->ShareHolding_disp_model->fetchShareBeneficialOwner($data['nestedData']['company_id'], $data['nestedData']['company_symbol'], $data['nestedData']['market_date'], $data['nestedData']['record_id']);

        $data['nestedData']['share_data'] = $share_data;

        $data['content'] = "shareholding/beneficial";
        $this->load->view('index', $data);
    }

    function shareholdersConcert($data) {

        $share_data = $this->ShareHolding_disp_model->fetchShareConsert($data['nestedData']['company_id'], $data['nestedData']['company_symbol'], $data['nestedData']['market_date'], $data['nestedData']['record_id']);

        $data['nestedData']['share_data'] = $share_data;

        $data['content'] = "shareholding/concert";
        $this->load->view('index', $data);
    }

    function shareDeclaration($data) {

        $share_data = $this->ShareHolding_disp_model->fetchShareDeclaration($data['nestedData']['company_id'], $data['nestedData']['company_symbol'], $data['nestedData']['market_date'], $data['nestedData']['record_id']);

        $data['nestedData']['share_data'] = $share_data;

        $data['content'] = "shareholding/declaration";
        $this->load->view('index', $data);
    }

    function shareUnclaimed($data) {

        $share_data = $this->ShareHolding_disp_model->fetchShareUnclaimed($data['nestedData']['company_id'], $data['nestedData']['company_symbol'], $data['nestedData']['market_date'], $data['nestedData']['record_id']);

        $data['nestedData']['share_data'] = $share_data;

        $data['content'] = "shareholding/unclaimed";
        $this->load->view('index', $data);
    }

    /*
     * Display Insider Trading Info
     */

    function insiderTrading() {

        $this->load->model('ShareHolding_disp_model');

        $broadcaste_date = $this->input->get('broadcaste_date');
        $broadcaste_date_to = $this->input->get('broadcaste_date_to');


        echo 'broadcaste_date : ' . $broadcaste_date . '<br/>';
        echo 'broadcaste_date_to : ' . $broadcaste_date_to . '<br/>';

        $acq_disp = empty($this->input->get('acq_disp')) ? 'all' : $this->input->get('acq_disp');
        $acq_mode = empty($this->input->get('acq_mode')) ? 'all' : $this->input->get('acq_mode');
        $person_category = ( empty($this->input->get('person_category')) || ($this->input->get('person_category')) == 'all' ) ? '' : $this->input->get('person_category');

        $security_sortby = $this->input->get('security_sortby');
        $sum_sec_val_by_comp = $this->input->get('sum_sec_val_by_comp');

        if (empty($broadcaste_date)) {

            $broadcaste_date = date('Y-m-d');
        }

        $insider_trading = $this->ShareHolding_disp_model->fetchInsiderTrading($broadcaste_date, $acq_disp, $security_sortby, false, false, $broadcaste_date_to, false, $acq_mode, $person_category, $sum_sec_val_by_comp);

//        echo '<pre>'; print_r($insider_trading);  exit;
//        $data['nestedData']['broadcaste_date'] = $broadcaste_date; 
//        $data['nestedData']['broadcaste_date'] = empty($insider_trading[0]->broadcaste_date) ? ( empty($broadcaste_date) ? date('Y-m-d') : $broadcaste_date ) : $insider_trading[0]->broadcaste_date; 
        $data['nestedData']['broadcaste_date'] = empty($broadcaste_date) ? date('Y-m-d') : $broadcaste_date;
        $data['nestedData']['broadcaste_date_to'] = $broadcaste_date_to;

        $data['nestedData']['acq_disp'] = $acq_disp;
        $data['nestedData']['acq_mode'] = $acq_mode;
        $data['nestedData']['person_category'] = $person_category;
        $data['nestedData']['security_sortby'] = $security_sortby;
        $data['nestedData']['sum_sec_val_by_comp'] = $sum_sec_val_by_comp;

        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;

        $data['nestedData']['url'] = 'share-corporate/insider-trading';

        $data['nestedData']['insider_trading'] = $insider_trading;

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/insider_trading.js");

        $data['content'] = "shareholding/insider_trading";
        $this->load->view('index', $data);
    }

    /*
     * Insider Trading of Company
     */

    function insiderTradingOfCompany($company_id, $company_symbol_encode, $broadcaste_date, $acq_disp_name_encode = false) {

        $this->load->helper('function_helper');

        $this->load->model('ShareHolding_disp_model');

        $acq_disp_name = false;

        if (!empty($acq_disp_name_encode)) {

            $url = 'share-corporate/insider-trading/acquirer-disposer/' . $acq_disp_name_encode . '/' . $broadcaste_date;
            $acq_disp_name = base64_url_decode($acq_disp_name_encode);
        } else if ($broadcaste_date === 'all') {

            $url = 'share-corporate/insider-trading/' . $company_id . '/' . $company_symbol_encode . '/all';
        } else {

            $url = 'share-corporate/insider-trading/' . $company_id . '/' . $company_symbol_encode . '/' . $broadcaste_date;
        }

        $company_symbol = base64_url_decode($company_symbol_encode);

        $acq_disp = empty($this->input->get('acq_disp')) ? 'all' : $this->input->get('acq_disp');
        $acq_mode = empty($this->input->get('acq_mode')) ? 'all' : $this->input->get('acq_mode');
        $person_category = ( empty($this->input->get('person_category')) || ($this->input->get('person_category')) == 'all' ) ? '' : $this->input->get('person_category');

        $security_sortby = $this->input->get('security_sortby');
        $sum_sec_val_by_comp = $this->input->get('sum_sec_val_by_comp');

        $broadcaste_date_to = $this->input->get('broadcaste_date_to');

        $broadcaste_date = empty($this->input->get('broadcaste_date')) ? $broadcaste_date : $this->input->get('broadcaste_date');

        $insider_trading = $this->ShareHolding_disp_model->fetchInsiderTrading($broadcaste_date, $acq_disp, $security_sortby, $company_id, $company_symbol, $broadcaste_date_to, $acq_disp_name, $acq_mode, $person_category, false);

        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;

//        $data['nestedData']['broadcaste_date'] = empty($insider_trading[0]->broadcaste_date) ? ( empty($broadcaste_date) ? date('Y-m-d') : $broadcaste_date ) : $insider_trading[0]->broadcaste_date;          
//        echo '<pre>'; print_r($insider_trading);  exit;

        $total = empty($insider_trading) ? 0 : count($insider_trading);

        $data['nestedData']['broadcaste_date'] = ( $broadcaste_date === 'all' ) ? ( empty($insider_trading[$total - 1]->broadcaste_date) ? date('Y-m-d') : $insider_trading[$total - 1]->broadcaste_date ) : $broadcaste_date;

        $data['nestedData']['broadcaste_date_all'] = ( $broadcaste_date === 'all') ? 'all' : $data['nestedData']['broadcaste_date'];
//        $data['nestedData']['broadcaste_date'] = $broadcaste_date;

        $data['nestedData']['broadcaste_date_to'] = $broadcaste_date_to;

        $data['nestedData']['acq_disp'] = $acq_disp;
        $data['nestedData']['acq_mode'] = $acq_mode;
        $data['nestedData']['person_category'] = $person_category;
        $data['nestedData']['security_sortby'] = $security_sortby;
        $data['nestedData']['sum_sec_val_by_comp'] = $sum_sec_val_by_comp;
        $data['nestedData']['acq_disp_name'] = $acq_disp_name;

        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;

        $data['nestedData']['url'] = $url;

        $data['nestedData']['insider_trading'] = $insider_trading;

//        echo '<pre>'; print_r($insider_trading);  exit;

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/insider_trading.js");

        $data['content'] = "shareholding/insider_trading";
        $this->load->view('index', $data);
    }

    /*
     * Fetch insider Trading Data By acquirer-disposer
     */

    function insiderTradingByAcqDisp($acq_disp_encode, $broadcaste_date) {

        $this->insiderTradingOfCompany($company_id = false, $company_symbol = false, $broadcaste_date, $acq_disp_encode);

        return;
    }

    function pledgedData() {

        $this->load->model('ShareHolding_disp_model');

        $prmtr_hldng_p_sortby = $this->input->get('prmtr_hldng_p_sortby');
        $encumb_p_sortby = $this->input->get('encumb_p_sortby');
        $dmat_pldg_p_sortby = $this->input->get('dmat_pldg_p_sortby');

        $broadcaste_date = $this->input->get('broadcaste_date');

        if (empty($broadcaste_date)) {

            $broadcaste_date = date('Y-m-d');
        }

        $pledged_data = $this->ShareHolding_disp_model->fetchPledgedData($broadcaste_date, $encumb_p_sortby, $dmat_pldg_p_sortby, $prmtr_hldng_p_sortby);

//        echo '<pre>'; print_r($pledged_data);  exit;

        $data['nestedData']['broadcaste_date'] = empty($pledged_data[0]->broadcaste_date) ? ( empty($broadcaste_date) ? date('Y-m-d') : $broadcaste_date ) : $pledged_data[0]->broadcaste_date;

        $data['nestedData']['prmtr_hldng_p_sortby'] = $prmtr_hldng_p_sortby;
        $data['nestedData']['encumb_p_sortby'] = $encumb_p_sortby;
        $data['nestedData']['dmat_pldg_p_sortby'] = $dmat_pldg_p_sortby;

        $data['nestedData']['pledged_data'] = $pledged_data;

        $data['nestedData']['url'] = 'share-corporate/pledged-data';

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/pledged-data.js");

        $data['content'] = "shareholding/pledged_data";
        $this->load->view('index', $data);
    }

    /*
     * Pledged Data of Company
     */

    function pledgedDataOfCompany($company_id, $company_symbol_encode, $broadcaste_date) {

        $this->load->helper('function_helper');

        $this->load->model('ShareHolding_disp_model');

        $company_symbol = base64_url_decode($company_symbol_encode);

        $prmtr_hldng_p_sortby = $this->input->get('prmtr_hldng_p_sortby');
        $encumb_p_sortby = $this->input->get('encumb_p_sortby');
        $dmat_pldg_p_sortby = $this->input->get('dmat_pldg_p_sortby');

        $broadcaste_date_to = $this->input->get('broadcaste_date_to');

        $broadcaste_date = empty($this->input->get('broadcaste_date')) ? $broadcaste_date : $this->input->get('broadcaste_date');

        $pledged_data = $this->ShareHolding_disp_model->fetchPledgedData($broadcaste_date, $encumb_p_sortby, $dmat_pldg_p_sortby, $prmtr_hldng_p_sortby, $company_id, $company_symbol, $broadcaste_date_to);

//        echo '<pre>'; print_r($pledged_data);  exit;

        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;

        $data['nestedData']['broadcaste_date'] = ( $broadcaste_date === 'all' ) ? ( empty($insider_trading[0]->broadcaste_date) ? date('Y-m-d') : $insider_trading[0]->broadcaste_date ) : $broadcaste_date;

        $data['nestedData']['broadcaste_date_all'] = ( $broadcaste_date === 'all') ? 'all' : $data['nestedData']['broadcaste_date'];

        $data['nestedData']['broadcaste_date_to'] = $broadcaste_date_to;

        if ($broadcaste_date === 'all') {

            $url = 'share-corporate/pledged-data/' . $company_id . '/' . $company_symbol_encode . '/all';
        } else {

            $url = 'share-corporate/pledged-data/' . $company_id . '/' . $company_symbol_encode . '/' . $broadcaste_date;
        }

        $data['nestedData']['prmtr_hldng_p_sortby'] = $prmtr_hldng_p_sortby;
        $data['nestedData']['encumb_p_sortby'] = $encumb_p_sortby;
        $data['nestedData']['dmat_pldg_p_sortby'] = $dmat_pldg_p_sortby;

        $data['nestedData']['pledged_data'] = $pledged_data;

        $data['nestedData']['url'] = $url;

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/pledged-data.js");

        $data['content'] = "shareholding/pledged_data";
        $this->load->view('index', $data);
    }

    function bulkBlockDeal() {

        $this->load->model('ShareHolding_disp_model');

        $market_date = $this->input->get('market_date');

        if (empty($market_date)) {

            $market_date = date('Y-m-d');
        }

        $exchange = $this->input->get('exchange');
        $deal_type = $this->input->get('deal_type');
        $buy_or_sale = $this->input->get('buy_or_sale');
        $quantity_traded_sortby = $this->input->get('quantity_traded_sortby');

        $bulk_block_data = $this->ShareHolding_disp_model->fetchBulkBlockDeal($market_date, $exchange, $deal_type, $buy_or_sale, $quantity_traded_sortby);

//        echo '<pre>'; print_r($bulk_block_data);  exit;

        $data['nestedData']['market_date'] = empty($bulk_block_data[0]->market_date) ? ( empty($market_date) ? date('Y-m-d') : $market_date ) : $bulk_block_data[0]->market_date;

        $data['nestedData']['bulk_block_data'] = $bulk_block_data;

        $data['nestedData']['exchange'] = $exchange;
        $data['nestedData']['deal_type'] = $deal_type;
        $data['nestedData']['buy_or_sale'] = $buy_or_sale;
        $data['nestedData']['quantity_traded_sortby'] = $quantity_traded_sortby;

        $data['nestedData']['url'] = 'bulk-block-deal';

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/bulk-block-data.js");

        $data['content'] = "shareholding/bulk_block_data";
        $this->load->view('index', $data);
    }

    function bulkBlockDealOfCompany($company_id, $company_symbol_encode, $market_date, $client_encode = false) {

        $this->load->helper('function_helper');

        $this->load->model('ShareHolding_disp_model');

        $company_symbol = base64_url_decode($company_symbol_encode);

        $market_date_to = $this->input->get('market_date_to');

        if (!empty($client_encode)) {

            $url = 'bulk-block-deal/client/' . $client_encode . '/' . $market_date;
            $client = base64_url_decode($client_encode);
        } else if ($market_date === 'all') {

            $url = 'bulk-block-deal/' . $company_id . '/' . $company_symbol_encode . '/all';
            $client = false;
        } else {

            $url = 'bulk-block-deal/' . $company_id . '/' . $company_symbol_encode . '/' . $market_date;
            $client = false;
        }


        $exchange = $this->input->get('exchange');
        $deal_type = $this->input->get('deal_type');
        $buy_or_sale = $this->input->get('buy_or_sale');
        $quantity_traded_sortby = $this->input->get('quantity_traded_sortby');

        $market_date = empty($this->input->get('market_date')) ? $market_date : $this->input->get('market_date');

        $bulk_block_data = $this->ShareHolding_disp_model->fetchBulkBlockDeal($market_date, $exchange, $deal_type, $buy_or_sale, $quantity_traded_sortby, $company_id, $company_symbol, $market_date_to, $client);

        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;

        $data['nestedData']['market_date'] = ( $market_date === 'all' ) ? ( empty($bulk_block_data[0]->market_date) ? date('Y-m-d') : $bulk_block_data[0]->market_date ) : $market_date;

        $data['nestedData']['market_date_all'] = ( $market_date === 'all') ? 'all' : $data['nestedData']['market_date'];

        $data['nestedData']['market_date_to'] = $market_date_to;

        $data['nestedData']['bulk_block_data'] = $bulk_block_data;

        $data['nestedData']['exchange'] = $exchange;
        $data['nestedData']['deal_type'] = $deal_type;
        $data['nestedData']['buy_or_sale'] = $buy_or_sale;
        $data['nestedData']['quantity_traded_sortby'] = $quantity_traded_sortby;
        $data['nestedData']['client'] = $client;

        $data['nestedData']['url'] = $url;

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/bulk-block-data.js");

        $data['content'] = "shareholding/bulk_block_data";
        $this->load->view('index', $data);
    }

    /*
     * Fetch Bulk Block Deal By Client Name
     */

    function bulkBlockDealofClient($client_encode, $market_date) {

        $this->bulkBlockDealOfCompany($company_id = false, $company_symbol = false, $market_date, $client_encode);

        return;
    }

    function sastRegulation29() {

        $this->load->model('ShareHolding_disp_model');

        $broadcaste_date = $this->input->get('broadcaste_date');

        if (empty($broadcaste_date)) {

            $broadcaste_date = date('Y-m-d');
        }

        $acq_or_sale_disp = empty($this->input->get('acq_or_sale_disp')) ? 'all' : $this->input->get('acq_or_sale_disp');
        $promoter_type = empty($this->input->get('promoter_type')) ? 'all' : $this->input->get('promoter_type');

        $total_share_acq_sortby = $this->input->get('total_share_acq_sortby');
        $total_share_sale_sortby = $this->input->get('total_share_sale_sortby');

        $sast_data = $this->ShareHolding_disp_model->fetchSastRegulation29($broadcaste_date, $acq_or_sale_disp, $promoter_type, $total_share_acq_sortby, $total_share_sale_sortby);

//        echo '<pre>'; print_r($sast_data);

        $data['nestedData']['broadcaste_date'] = empty($sast_data[0]->broadcaste_date) ? ( empty($broadcaste_date) ? date('Y-m-d') : $broadcaste_date ) : $sast_data[0]->broadcaste_date;

        $data['nestedData']['url'] = 'share-corporate/sast-regulation-29';

        $data['nestedData']['acq_or_sale_disp'] = $acq_or_sale_disp;
        $data['nestedData']['promoter_type'] = $promoter_type;
        $data['nestedData']['total_share_acq_sortby'] = $total_share_acq_sortby;
        $data['nestedData']['total_share_sale_sortby'] = $total_share_sale_sortby;
        $data['nestedData']['sast_data'] = $sast_data;

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/sast_29.js");

        $data['content'] = "shareholding/sast_29";
        $this->load->view('index', $data);
    }

    /*
     * SAST Regulation 29 of Company
     */

    function sastRegulation29OfCompany($company_id, $company_symbol_encode, $broadcaste_date, $acq_saler_name_encode = false) {

        $this->load->helper('function_helper');

        $this->load->model('ShareHolding_disp_model');

        $acq_saler_name = false;

        if (!empty($acq_saler_name_encode)) {

            $url = 'share-corporate/sast-regulation-29/acquirer-saler/' . $acq_saler_name_encode . '/' . $broadcaste_date;
            $acq_saler_name = base64_url_decode($acq_saler_name_encode);
        } else if ($broadcaste_date === 'all') {

            $url = 'share-corporate/sast-regulation-29/' . $company_id . '/' . $company_symbol_encode . '/all';
        } else {

            $url = 'share-corporate/sast-regulation-29/' . $company_id . '/' . $company_symbol_encode . '/' . $broadcaste_date;
        }

        $company_symbol = base64_url_decode($company_symbol_encode);

        $acq_or_sale_disp = empty($this->input->get('acq_or_sale_disp')) ? 'all' : $this->input->get('acq_or_sale_disp');
        $promoter_type = empty($this->input->get('promoter_type')) ? 'all' : $this->input->get('promoter_type');

        $total_share_acq_sortby = $this->input->get('total_share_acq_sortby');
        $total_share_sale_sortby = $this->input->get('total_share_sale_sortby');

        $broadcaste_date_to = $this->input->get('broadcaste_date_to');

        $broadcaste_date = empty($this->input->get('broadcaste_date')) ? $broadcaste_date : $this->input->get('broadcaste_date');

        $sast_data = $this->ShareHolding_disp_model->fetchSastRegulation29($broadcaste_date, $acq_or_sale_disp, $promoter_type, $total_share_acq_sortby, $total_share_sale_sortby, $company_id, $company_symbol, $broadcaste_date_to, $acq_saler_name);

        $data['nestedData']['company_id'] = $company_id;
        $data['nestedData']['company_symbol'] = $company_symbol;

        $total = empty($sast_data) ? 0 : count($sast_data);

        $data['nestedData']['broadcaste_date'] = ( $broadcaste_date === 'all' ) ? ( empty($sast_data[$total - 1]->broadcaste_date) ? date('Y-m-d') : $sast_data[$total - 1]->broadcaste_date ) : $broadcaste_date;

        $data['nestedData']['broadcaste_date_all'] = ( $broadcaste_date === 'all') ? 'all' : $data['nestedData']['broadcaste_date'];

        $data['nestedData']['broadcaste_date_to'] = $broadcaste_date_to;

        $data['nestedData']['acq_saler_name'] = $acq_saler_name;

        $data['nestedData']['url'] = $url;

        $data['nestedData']['acq_or_sale_disp'] = $acq_or_sale_disp;
        $data['nestedData']['promoter_type'] = $promoter_type;
        $data['nestedData']['total_share_acq_sortby'] = $total_share_acq_sortby;
        $data['nestedData']['total_share_sale_sortby'] = $total_share_sale_sortby;
        $data['nestedData']['sast_data'] = $sast_data;
        
        $date_period = $this->input->get('date_period');
        $data['nestedData']['date_period'] = $date_period;

//        echo '<pre>'; print_r($sast_data);  exit;

        $data['nestedStyle']['css'] = array("assets/plugin/flatpickr/flatpickr.min.css", "assets/plugin/font-awesome-4.7.0/css/font-awesome.min.css");
        $data['nestedScript']['js'] = array("assets/plugin/flatpickr/flatpickr.js", "assets/js/pages/shareholding/sast_29.js");

        $data['content'] = "shareholding/sast_29";
        $this->load->view('index', $data);
    }

    /*
     * Fetch insider Trading Data By acquirer-disposer
     */

    function insiderTradingByAcqSaler($acq_saler_encode, $broadcaste_date) {

        $this->sastRegulation29OfCompany($company_id = false, $company_symbol = false, $broadcaste_date, $acq_saler_encode);

        return;
    }

}
