<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'welcome';

$route['default_controller'] = 'Sectors_Controller/displayNiftyLiveData';
$route['nifty-live'] = 'Sectors_Controller/fetchNiftyLiveData';

$route['stock-analysis'] = 'Home/displayAllNseData';
$route['stock-filter'] = "Home/stockFilter";

$route['log-view/timely/company-list'] = "Home/companyListForTimeWiseLog";
$route['whole-day-data/(:any)/(:any)'] = "FullDay/wholeDayData/$1/$2";
$route['whole-day-data'] = "FullDay/wholeDayDataByFilter";

$route['log-view/daily/company-list'] = "Home/companyListForDailyLog";
$route['daily-log/(:any)/(:any)'] = "Daily_Log_Contr/dailyLog/$1/$2";
$route['daily-log'] = "Daily_Log_Contr/dailyLogFilter";
$route['stock/cluster-return/(:any)/(:any)'] = 'Daily_Log_Contr/stockClusterReturn/$1/$2';
$route['stock/average-by-days/(:any)/(:any)'] = 'Daily_Log_Contr/calcStocksAveragesByDays/$1/$2';

$route['stock-data/check-today-data-inserted'] = "Stock_Data_Contr/checkStockTodayDataInserted";
$route['stock-data/check-today-famous-stock-data-inserted'] = "Stock_Data_Contr/checkStockTodayFamousStockDataInserted";
$route['stock-data/check-today-live-data-inserted'] = "Stock_Data_Contr/checkStocksLiveTodayDataInserted";

/*
* @author: ZAHIR
 * DESC: DATA Fetch Start 
*/
$route['fetch/sectors-data'] = 'Fetch_Controller/extractSectorsData';
/* DATA Fetch End */

/*
* @author: ZAHIR
 * DESC: DATA Fetch Start 
*/
$route['pyapi/receive-py-fetch-stock-data'] = 'Python_Controller/receivePyStockData';
$route['pyapi/receive-py-fetch-put-call-data'] = 'Python_Controller/receivePyPutCallData';
/* DATA Fetch End */

/*
 * @author: ZAHIR
 * Desc: Data Process Start
 */

$route['data-process/put-call-log-data'] = 'Put_Call/processPutCallLogData';
$route['data-process/put-call-log-data/(:num)'] = 'Put_Call/processPutCallLogData/$1/';

$route['data-process/put-call-extract-company-list'] = 'Put_Call/processCompanyList';
$route['data-process/check-put-call-log-data-inserted/(:any)'] = 'Put_Call/checkDataInserted/$1';

$route['data-process/option-chain/insert-oi-change'] = 'Put_call_oi_change_contr/getPutCallOiChange';

/*
 * Desc: Data Process End
 */

/*
 * @author: ZAHIR
 * Desc: Display option chain data start
 */
$route['option-chain/company-list'] = 'Option_Chain/displayOptionChainCompanyList';
$route['option-chain/company-list/live'] = 'Option_Chain/displayOptionChainCompanyList/live'
        . '';
$route['option-chain/stock-info/(:any)/(:any)'] = 'Option_Chain/getOCDataOfStock/$1/$2';
$route['option-chain/stock-info'] = 'Option_Chain/getOCDataOfStockByFilter';

$route['option-chain/stock-info/(:any)/(:any)/live'] = 'Option_Chain/getLiveOCDataOfStock/$1/$2/live';
$route['option-chain/stock-info/live'] = 'Option_Chain/getOCDataOfStockByFilter/live';

$route['option-chain/strike-price-log/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Oc_Sp/getStrikePriceLog/$1/$2/$3/$4/$5';
$route['option-chain/strike-price-log/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Oc_Sp/getStrikePriceLog/$1/$2/$3/$4/$5/$6';
/*
 * Display option chain data end
 */

/*
 * @author: ZAHIR
 * Desc: Alert 
 */
$route['notify/nse-forbidden'] = 'System_Notification_Controller/nseForbidden';
$route['notify/mysql-connect-fail'] = 'System_Notification_Controller/mysqlConnectFail';
$route['option-chain/check-today-data-inserted'] = 'Put_Call/checkTodayDataInserted';

/*
 * @author: ZAHIR
 * DESC: Analysis of stocks bearish or bullish probability by option chain
 */

$route['stock-analysis/option-chain/implied-volatility/bearish-or-bullish'] = 'Stock_Analysis/stockBearishOrBullishByIVOfOC';

/*
 * @author: ZAHIR
 * DESC: Analysis of stocks by premium decay of option chain
 */

$route['stock-analysis/option-chain/premium-decay/bearish-or-bullish'] = 'Stock_Analysis_PD/stockAnlysisByPDOfOC';

/*
 * @author: ZAHIR
 * DESC: Analysis of Option Pain by Option chain Data
 */

$route['stock-analysis/option-chain/option-pain'] = 'Stock_Analysis_OP/stockAnlaysisByOPOfOC';

/*
 * @author: ZAHIR
 * DESC: Analysis of option chain by highest oi and addition of highest oi for both call and put
 */

$route['stock-analysis/option-chain/highest-oi-n-addition-of-oi'] = 'Stock_Analysis_High_OI_N_Add_OI/stockAnlaysisByHighestOiNAddOi';

/*
 * @author: ZAHIR
 * DESC: Option chain Analysis view by option chain
 */

$route['option-chain/iv-analysis/company-list'] = 'OC_Analysis/ivAnalysisCompanyList';
$route['option-chain/iv-analysis/(:any)/(:any)'] = 'OC_Analysis/getOCIVData/$1/$2';
$route['option-chain/iv-analysis'] = 'OC_Analysis/getOCIVDataFilter';
$route['option-chain/iv-analysis/(:any)/(:any)/live'] = 'OC_Analysis/getLiveOCIVDataOfStock/$1/$2/live';

$route['option-chain/iv-analysis/day-wise'] = 'OC_Analysis/dayWiseIvAnalysis';
$route['option-chain/iv-analysis/day-wise-live'] = 'OC_Analysis/dayWiseIvAnalysis/live';

$route['option-chain/pd-analysis/company-list'] = 'OC_PD_Analysis/pdAnalysisCompanyList';
$route['option-chain/pd-analysis/(:any)/(:any)'] = 'OC_PD_Analysis/getOCPDData/$1/$2';
$route['option-chain/pd-analysis'] = 'OC_PD_Analysis/getOCPDDataFilter';
$route['option-chain/pd-analysis/(:any)/(:any)/live'] = 'OC_PD_Analysis/getLiveOCPDDataOfStock/$1/$2/live';

$route['option-chain/pd-analysis/day-wise'] = 'OC_PD_Analysis/dayWisePdAnalysis';
$route['option-chain/pd-analysis/day-wise-live'] = 'OC_PD_Analysis/dayWisePdAnalysis/live';

$route['option-chain/iv-pd-analysis/live/(:any)'] = 'OC_Comn_Contr/IvPdCommonAnalysisTimeWise/$1';

$route['option-chain/op-analysis/day-wise'] = 'OC_OP_Analysis/dayWiseOpAnalysis';

$route['option-chain/high-oi-and-high-change-in-oi'] = 'OC_High_OI_N_Add_OI_Analysis/dayWiseAnalysis';

$route['option-chain/option-greek/calculator'] = 'Option_Greek/viewOGCalculator';

/*
 * @author: ZAHIR
 * Desc: Display Sector Data 
 */
$route['log-view/sectors-list'] = 'Sectors_Controller/sectorsList';
$route['sectors/log/(:any)/(:any)'] = 'Sectors_Controller/sectorsLog/$1/$2';
$route['sectors/cluster-return/(:any)/(:any)'] = 'Sectors_Controller/sectorsClusterReturn/$1/$2';
$route['sectors/log'] = 'Sectors_Controller/sectorsLogFilter';

$route['sector/average-by-days/(:any)/(:any)'] = 'Sectors_Controller/calcsectorAveragesByDays/$1/$2';

/*
 * @author: ZAHIR
 * Desc: Copy companies name from companies table and insert it in other database 
 */
$route['companies/fetch-all-data'] = 'Companies/fetchAllData/$1';
$route['companies/put-call-urls/fetch-all-data'] = 'Put_Call/fetchDataToExtractPCUrls';
$route['companies/fetch-data-by-limit/(:num)'] = 'Companies/fetchDataByLimit/$1';
$route['companies/insert-data/(:num)'] = 'Companies/insertData/$1';
$route['companies/match-company-with-prime-server'] = 'Companies/matchCompanyWithPrimeServer';

/*
 * @author: ZAHIR
 * Desc: fii and dii data data fetch
 */
$route['fii-dii/total-invest-of-trading-activity'] = 'Fii_Dii/totalInvestOftradingActivity';
$route['fii-dii/get-nsdl-sectore-invest-data-of-fpi-fii'] = 'Fii_Dii/getNsdlSectoreInvestDataofFii';

/*
 * @author: ZAHIR
 * Desc: fii and dii data data display
 */
$route['fii-dii/total-investment'] = 'Fii_Dii_Disp_Contr/displayTotalInvestment';
$route['fii-dii/fii-derivative'] = 'Fii_Dii_Disp_Contr/displayFiiDerivative';
$route['fii-dii/fii-sectore-invest'] = 'Fii_Dii_Disp_Contr/displayFiiSectorInvest';

$route['top-10-exchange-clearing-member'] = 'Fii_Dii_Disp_Contr/dispExchangeClearMembr';
/*
 * @author: ZAHIR
 * Desc: delete duplicate data
 */

$route['delete-duplicate-data/stock-data-live'] = 'Delete_Duplicate/delLiveStockDupData';

/*
 * Futures
 */

$route['future/companies/insert'] = 'Companies/getDerivativeCompanyFromNse';



$route['year-high-low/([a-zA-Z]+)'] = 'Year_High_Low_Contr/viewYearHighLow/$1';
$route['year-high-low-compare/current-price/day-wise'] = 'Year_High_Low_Contr/compareCurrentPriceDayWise';

$route['daily-volatility'] = 'Volatility_Contr/viewDailyVolatility';
$route['daily-volatility-of/(:num)/(:any)'] = 'Volatility_Contr/viewDailyVolatilityCompany/$1/$2';

$route['client-activity/oi-participant'] = 'Client_Activity_Disp/displayOiParticipant';
$route['oi-participant/cluster-return/(:any)'] = 'Client_Activity_Disp/oiParticipantClusterReturn/$1';

$route['client-activity/volume-participant'] = 'Client_Activity_Disp/displayVolumeParticipant';
$route['volume-participant/cluster-return/(:any)'] = 'Client_Activity_Disp/volumeParticipantClusterReturn/$1';

$route['category-wise-turnover/(:any)'] = 'Client_Activity_Disp/dispCatWiseTrnvr/$1';

/*
 * Display Shareholding Data
 */
$route['shareholding/company-list'] = "Shareholding_Disp_Contr/companyList";
$route['shareholding/distrubution/(:any)/(:any)'] = 'Shareholding_Disp_Contr/displayShareDistrubution/$1/$2';
$route['shareholding/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'Shareholding_Disp_Contr/displayShareSummary/$1/$2/$3/$4/$5';

$route['company-list/(:any)'] = "Shareholding_Disp_Contr/companyListByType/$1";

//$route['insider-trading/company-list'] = "Shareholding_Disp_Contr/companyListInsiderTrading";
$route['share-corporate/insider-trading'] = 'Shareholding_Disp_Contr/insiderTrading';
$route['share-corporate/insider-trading/acquirer-disposer/(:any)/(:any)'] = 'Shareholding_Disp_Contr/insiderTradingByAcqDisp/$1/$2';
$route['share-corporate/insider-trading/(:any)/(:any)/(:any)'] = 'Shareholding_Disp_Contr/insiderTradingOfCompany/$1/$2/$3';

$route['share-corporate/sast-regulation-29'] = 'Shareholding_Disp_Contr/sastRegulation29';
$route['share-corporate/sast-regulation-29/acquirer-saler/(:any)/(:any)'] = 'Shareholding_Disp_Contr/insiderTradingByAcqSaler/$1/$2';
$route['share-corporate/sast-regulation-29/(:any)/(:any)/(:any)'] = 'Shareholding_Disp_Contr/sastRegulation29OfCompany/$1/$2/$3';

$route['share-corporate/pledged-data'] = 'Shareholding_Disp_Contr/pledgedData';
$route['share-corporate/pledged-data/(:any)/(:any)/(:any)'] = 'Shareholding_Disp_Contr/pledgedDataOfCompany/$1/$2/$3';

$route['bulk-block-deal'] = 'Shareholding_Disp_Contr/bulkBlockDeal';
$route['bulk-block-deal/client/(:any)/(:any)'] = 'Shareholding_Disp_Contr/bulkBlockDealofClient/$1/$2';
$route['bulk-block-deal/(:any)/(:any)/(:any)'] = 'Shareholding_Disp_Contr/bulkBlockDealOfCompany/$1/$2/$3';

$route['nifty-heavy-weight-stocks'] = 'Nifty_Disp_Contr/niftyHeavyStocks';

/*
 * @author: ZAHIR
 * Desc: Display Future data start
 */
$route['future/company-list'] = 'Future/displayFutureCompanyList';
$route['future/stock-info/(:any)/(:any)'] = 'Future/getFrDataOfStock/$1/$2';
$route['future/stock-info'] = 'Future/getFrDataOfStockByFilter';
$route['future/day-wise-analysis'] = 'Future/dayWiseAnalysis';

$route['future/rollover-log/(:any)/(:any)'] = 'Future/getFrRolloverofSingleStock/$1/$2';
$route['future/rollover-log'] = 'Future/getFrRolloverofSingleStockByFilter';

$route['future/rollover/day-wise-analysis'] = 'Future/dayWiseRolloverAnalysis';

/**
 * Bullies Stocks
 */
$route['up-stocks/five-pecent-up-on-last-trade'] = 'UpStock_Contr/fivePercentUpOnLastTradeStocks';