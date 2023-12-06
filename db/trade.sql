/*
SQLyog Ultimate v11.11 (32 bit)
MySQL - 8.0.35-0ubuntu0.22.04.1 : Database - trade_table
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`trade` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `trade`;

/*Table structure for table `analysis_last_calc` */

DROP TABLE IF EXISTS `analysis_last_calc`;

CREATE TABLE `analysis_last_calc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `topic` varchar(100) DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `created_at_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `analysis_last_calc` */

/*Table structure for table `analysis_task` */

DROP TABLE IF EXISTS `analysis_task`;

CREATE TABLE `analysis_task` (
  `id` int NOT NULL AUTO_INCREMENT,
  `topic` varchar(100) DEFAULT NULL,
  `done_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`topic`,`done_date`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `analysis_task` */

/*Table structure for table `broker_client_fund` */

DROP TABLE IF EXISTS `broker_client_fund`;

CREATE TABLE `broker_client_fund` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `member_code` int DEFAULT NULL,
  `member_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temp_margin_amt_fund` decimal(50,2) DEFAULT NULL COMMENT 'Amount Funded For Temporary Margin (Rupees in Lakhs)',
  `inst_cli_amt_fund` decimal(50,2) DEFAULT NULL COMMENT 'Amount Funded For Institutional Clients (Rupees in Lakhs)',
  `non_inst_cli_amt_fund` decimal(50,2) DEFAULT NULL COMMENT 'Amount Funded For Non-Institutional Clients (Rupees in Lakhs)',
  `under_margin_trad_amt_fund` decimal(50,2) DEFAULT NULL COMMENT 'Amount Funded Under Margin Trading (Rupees in Lakhs)',
  `total_amt_fund` decimal(50,2) DEFAULT NULL COMMENT 'Total Amount Funded (Rupees in Lakhs)',
  `total_cli_funded` int DEFAULT NULL COMMENT 'Total No of Clients Funded',
  `submission_date` date DEFAULT NULL,
  `cron_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`member_code`,`member_name`,`submission_date`)
) ENGINE=InnoDB AUTO_INCREMENT=310 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `broker_client_fund` */

/*Table structure for table `bulk_block_deal` */

DROP TABLE IF EXISTS `bulk_block_deal`;

CREATE TABLE `bulk_block_deal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `market_date` date DEFAULT NULL,
  `exchange` varchar(10) DEFAULT NULL,
  `bulk_or_block` varchar(7) DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `buy_or_sale` varchar(15) DEFAULT NULL,
  `quantity_traded` bigint DEFAULT NULL,
  `trade_price` decimal(15,2) DEFAULT NULL,
  `remarks` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=latin1;

/*Data for the table `bulk_block_deal` */

/*Table structure for table `category_wise_turnover` */

DROP TABLE IF EXISTS `category_wise_turnover`;

CREATE TABLE `category_wise_turnover` (
  `id` int NOT NULL AUTO_INCREMENT,
  `market_date` date DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `buy_value` decimal(50,2) DEFAULT NULL,
  `sell_value` decimal(50,2) DEFAULT NULL,
  `trading_type` varchar(10) DEFAULT NULL,
  `exchange` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`market_date`,`category`,`trading_type`,`exchange`)
) ENGINE=InnoDB AUTO_INCREMENT=4290 DEFAULT CHARSET=latin1;

/*Data for the table `category_wise_turnover` */

/*Table structure for table `companies` */

DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `symbol` varchar(255) DEFAULT NULL,
  `exchange_name` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `symbol_exchange_unique` (`symbol`,`exchange_name`)
) ENGINE=InnoDB AUTO_INCREMENT=1900 DEFAULT CHARSET=latin1;

/*Data for the table `companies` */

/*Table structure for table `db_error_log` */

DROP TABLE IF EXISTS `db_error_log`;

CREATE TABLE `db_error_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(20) DEFAULT NULL,
  `controller_name` varchar(60) DEFAULT NULL,
  `controller_methode_name` varchar(60) DEFAULT NULL,
  `model_name` varchar(60) DEFAULT NULL,
  `model_methode_name` varchar(60) DEFAULT NULL,
  `data` text,
  `query` text,
  `error_code` varchar(60) DEFAULT NULL,
  `error_message` text,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb3;

/*Data for the table `db_error_log` */

/*Table structure for table `exception_log` */

DROP TABLE IF EXISTS `exception_log`;

CREATE TABLE `exception_log` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `stock_data_log_id` bigint DEFAULT NULL,
  `page_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `function_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_type` int DEFAULT NULL COMMENT '1= trading data not found, 2= trading datetime not found, 3= fail to fail fetching put call data',
  `custom_exception_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `error_system_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tool_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `command` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `system_exception_type_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `line_no` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `exception_log` */

/*Table structure for table `exchange_clearing_member` */

DROP TABLE IF EXISTS `exchange_clearing_member`;

CREATE TABLE `exchange_clearing_member` (
  `id` int NOT NULL AUTO_INCREMENT,
  `market_date` date DEFAULT NULL,
  `serial_no` int DEFAULT NULL,
  `index_futures_vol` int DEFAULT NULL,
  `index_futures_trnvr` decimal(50,2) DEFAULT NULL,
  `stock_futures_vol` int DEFAULT NULL,
  `stock_futures_trnvr` decimal(50,2) DEFAULT NULL,
  `index_option_vol` int DEFAULT NULL,
  `index_option_trnvr` decimal(50,2) DEFAULT NULL,
  `index_option_trnvr_prm` decimal(50,2) DEFAULT NULL,
  `stock_option_vol` int DEFAULT NULL,
  `stock_option_trnvr` decimal(50,2) DEFAULT NULL,
  `stock_option_trnvr_prm` decimal(50,2) DEFAULT NULL,
  `exchange` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`market_date`,`serial_no`)
) ENGINE=InnoDB AUTO_INCREMENT=3653 DEFAULT CHARSET=latin1;

/*Data for the table `exchange_clearing_member` */

/*Table structure for table `extra_info` */

DROP TABLE IF EXISTS `extra_info`;

CREATE TABLE `extra_info` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `extra_info` */

/*Table structure for table `fii_derivative` */

DROP TABLE IF EXISTS `fii_derivative`;

CREATE TABLE `fii_derivative` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `reporting_date` date DEFAULT NULL,
  `derivative_products` varchar(30) DEFAULT NULL,
  `buy_no_of_contract` decimal(15,2) DEFAULT NULL,
  `buy_amount` decimal(15,2) DEFAULT NULL,
  `sell_no_of_contract` decimal(15,2) DEFAULT NULL,
  `sell_amount` decimal(15,2) DEFAULT NULL,
  `oi_at_end_no_of_contract` decimal(15,2) DEFAULT NULL,
  `oi_at_end_amount` decimal(15,2) DEFAULT NULL,
  `source` varchar(5) DEFAULT 'nsdl',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`reporting_date`,`derivative_products`,`source`)
) ENGINE=InnoDB AUTO_INCREMENT=2010 DEFAULT CHARSET=latin1;

/*Data for the table `fii_derivative` */

/*Table structure for table `fii_dii_activity` */

DROP TABLE IF EXISTS `fii_dii_activity`;

CREATE TABLE `fii_dii_activity` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `investment_date` date DEFAULT NULL,
  `investor_type` varchar(255) DEFAULT NULL COMMENT 'ffi or dii or any other',
  `buy_value` decimal(10,2) DEFAULT NULL,
  `sell_value` decimal(10,2) DEFAULT NULL,
  `net_value` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_data` (`investment_date`,`investor_type`,`buy_value`,`sell_value`,`net_value`)
) ENGINE=InnoDB AUTO_INCREMENT=1432 DEFAULT CHARSET=latin1;

/*Data for the table `fii_dii_activity` */

/*Table structure for table `fii_sector_invest` */

DROP TABLE IF EXISTS `fii_sector_invest`;

CREATE TABLE `fii_sector_invest` (
  `id` int NOT NULL AUTO_INCREMENT,
  `report_date` date DEFAULT NULL,
  `sector_name` varchar(50) DEFAULT NULL,
  `equity` int DEFAULT NULL,
  `debt` int DEFAULT NULL,
  `hybrid` int DEFAULT NULL,
  `total` int DEFAULT NULL,
  `source` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Uniquez` (`report_date`,`sector_name`,`source`)
) ENGINE=InnoDB AUTO_INCREMENT=11284 DEFAULT CHARSET=latin1;

/*Data for the table `fii_sector_invest` */

/*Table structure for table `future` */

DROP TABLE IF EXISTS `future`;

CREATE TABLE `future` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `future_log_id` bigint DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `underlying_date_time` datetime DEFAULT NULL,
  `underlying_date` date DEFAULT NULL,
  `underlying_time` time DEFAULT NULL,
  `volume_freeze_quantity` bigint DEFAULT NULL,
  `open_price` float DEFAULT NULL,
  `high_price` float DEFAULT NULL,
  `low_price` float DEFAULT NULL,
  `close_price` float DEFAULT NULL,
  `prev_price` float DEFAULT NULL,
  `last_price` float DEFAULT NULL,
  `change` decimal(7,2) DEFAULT NULL,
  `p_change` decimal(7,2) DEFAULT NULL,
  `no_of_contracts_traded` bigint DEFAULT NULL,
  `total_turnover` float DEFAULT NULL,
  `total_buy_quantity` bigint DEFAULT NULL,
  `total_sell_quantity` bigint DEFAULT NULL,
  `traded_volume` bigint DEFAULT NULL,
  `total_traded_value` float DEFAULT NULL,
  `vmap` float DEFAULT NULL,
  `premium_turnover` float DEFAULT NULL,
  `oi` bigint DEFAULT NULL,
  `change_in_oi` bigint DEFAULT NULL,
  `p_change_in_oi` decimal(7,2) DEFAULT NULL,
  `market_lot` int DEFAULT NULL,
  `settlement_price` float DEFAULT NULL,
  `daily_volatility` float DEFAULT NULL,
  `annual_volatility` float DEFAULT NULL,
  `iv` float DEFAULT NULL,
  `client_wise_position_limits` float DEFAULT NULL,
  `market_wide_position_limits` bigint DEFAULT NULL,
  `market_running` int DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`expiry_date`,`underlying_date`,`underlying_time`,`market_running`)
) ENGINE=InnoDB AUTO_INCREMENT=30161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `future` */

/*Table structure for table `future_companies` */

DROP TABLE IF EXISTS `future_companies`;

CREATE TABLE `future_companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `stock_or_index` int DEFAULT '1' COMMENT '1 mean stock , 2 means index',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=latin1;

/*Data for the table `future_companies` */

/*Table structure for table `future_log` */

DROP TABLE IF EXISTS `future_log`;

CREATE TABLE `future_log` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `info` text,
  `underlying_price` float DEFAULT NULL,
  `volume_freeze_quantity` int DEFAULT NULL,
  `future_data` longtext,
  `server` varchar(50) DEFAULT NULL,
  `data_processed` int DEFAULT '0',
  `market_date_time` datetime DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `market_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `future_log` */

/*Table structure for table `future_rollover` */

DROP TABLE IF EXISTS `future_rollover`;

CREATE TABLE `future_rollover` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `future_log_id` bigint DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `underlying_date_time` datetime DEFAULT NULL,
  `underlying_date` date DEFAULT NULL,
  `underlying_time` time DEFAULT NULL,
  `volume_freeze_quantity` bigint DEFAULT NULL,
  `market_running` int DEFAULT '0',
  `rollover_percentage` decimal(7,2) DEFAULT NULL,
  `roll_cost` decimal(7,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`underlying_date`,`underlying_time`,`market_running`)
) ENGINE=InnoDB AUTO_INCREMENT=8780 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `future_rollover` */

/*Table structure for table `lot_size` */

DROP TABLE IF EXISTS `lot_size`;

CREATE TABLE `lot_size` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `derivative_type` varchar(7) DEFAULT NULL COMMENT 'oc or future',
  `size` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=latin1;

/*Data for the table `lot_size` */

/*Table structure for table `lot_size_monthly` */

DROP TABLE IF EXISTS `lot_size_monthly`;

CREATE TABLE `lot_size_monthly` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `size` int DEFAULT NULL,
  `month` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`size`,`month`,`year`)
) ENGINE=InnoDB AUTO_INCREMENT=1364 DEFAULT CHARSET=latin1;

/*Data for the table `lot_size_monthly` */

/*Table structure for table `most_active` */

DROP TABLE IF EXISTS `most_active`;

CREATE TABLE `most_active` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `snapshot_of` varchar(15) DEFAULT NULL,
  `active_by` varchar(10) DEFAULT NULL,
  `instrument_type` varchar(12) DEFAULT NULL,
  `instrument` varchar(20) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `option_type` varchar(7) DEFAULT NULL,
  `strike_price` decimal(15,2) DEFAULT NULL,
  `last_price` decimal(10,2) DEFAULT NULL,
  `contracts_traded` bigint DEFAULT NULL,
  `total_turnover` decimal(50,2) DEFAULT NULL,
  `premium_turnover` decimal(50,2) DEFAULT NULL,
  `oi` bigint DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `p_change` decimal(7,2) DEFAULT NULL,
  `underlying_date_time` datetime DEFAULT NULL,
  `underlying_date` date DEFAULT NULL,
  `underlying_time` time DEFAULT NULL,
  `market_running` int DEFAULT '0' COMMENT '0 means market is close, 1 means live market',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`active_by`,`instrument_type`,`expiry_date`,`option_type`,`strike_price`,`underlying_date`,`underlying_time`,`market_running`,`snapshot_of`)
) ENGINE=InnoDB AUTO_INCREMENT=335 DEFAULT CHARSET=latin1;

/*Data for the table `most_active` */

/*Table structure for table `nifty_top` */

DROP TABLE IF EXISTS `nifty_top`;

CREATE TABLE `nifty_top` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weightage` decimal(5,3) DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`market_date`)
) ENGINE=InnoDB AUTO_INCREMENT=6051 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `nifty_top` */

/*Table structure for table `nse_cookies` */

DROP TABLE IF EXISTS `nse_cookies`;

CREATE TABLE `nse_cookies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nse_cookies_url_id` int DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `expiry` varbinary(255) DEFAULT NULL,
  `httpOnly` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `path` varchar(50) DEFAULT NULL,
  `secure` varchar(255) DEFAULT NULL,
  `value` text,
  `is_api` int DEFAULT '0',
  `parent_id` bigint DEFAULT NULL,
  `main_url_id` bigint DEFAULT NULL,
  `url` text,
  `status` int DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;

/*Data for the table `nse_cookies` */

/*Table structure for table `nse_cookies_url` */

DROP TABLE IF EXISTS `nse_cookies_url`;

CREATE TABLE `nse_cookies_url` (
  `id` int NOT NULL AUTO_INCREMENT,
  `main_url` text,
  `api_url` text,
  `referer` text,
  `cookie_working` int DEFAULT '1',
  `curl_url` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `nse_cookies_url` */

/*Table structure for table `oc_high_oi_n_high_add_of_oi` */

DROP TABLE IF EXISTS `oc_high_oi_n_high_add_of_oi`;

CREATE TABLE `oc_high_oi_n_high_add_of_oi` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `underlying_date` date DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `calls_oi` decimal(10,2) DEFAULT NULL,
  `calls_chng_in_oi` decimal(10,2) DEFAULT NULL,
  `strike_price_in_call` int DEFAULT NULL,
  `puts_oi` decimal(10,2) DEFAULT NULL,
  `puts_chng_in_oi` decimal(10,2) DEFAULT NULL,
  `strike_price_in_put` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNQZ` (`company_id`,`company_symbol`,`underlying_date`,`expiry_date`)
) ENGINE=InnoDB AUTO_INCREMENT=28500 DEFAULT CHARSET=latin1;

/*Data for the table `oc_high_oi_n_high_add_of_oi` */

/*Table structure for table `oc_iv_analysis` */

DROP TABLE IF EXISTS `oc_iv_analysis`;

CREATE TABLE `oc_iv_analysis` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `trading_days` int DEFAULT NULL,
  `underlying_date` date DEFAULT NULL,
  `underlying_time` time DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `strike_price` int DEFAULT NULL,
  `calls_iv` decimal(6,2) DEFAULT NULL,
  `puts_iv` decimal(6,2) DEFAULT NULL,
  `strike_price_with_highest_oi_in_call` int DEFAULT NULL,
  `strike_price_with_highest_oi_in_put` int DEFAULT NULL,
  `bearish_probability` decimal(6,2) DEFAULT NULL,
  `close_above_target_bearish` decimal(6,2) DEFAULT NULL,
  `bullish_probability` decimal(6,2) DEFAULT NULL,
  `close_above_target_bullish` decimal(6,2) DEFAULT NULL,
  `market_running` int DEFAULT '0' COMMENT '0 means market is close, 1 means live market',
  `created_at_date` date DEFAULT NULL,
  `script_start_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`underlying_date`,`expiry_date`,`underlying_time`,`market_running`)
) ENGINE=InnoDB AUTO_INCREMENT=131749 DEFAULT CHARSET=latin1;

/*Data for the table `oc_iv_analysis` */

/*Table structure for table `oc_op_analysis` */

DROP TABLE IF EXISTS `oc_op_analysis`;

CREATE TABLE `oc_op_analysis` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `underlying_date` date DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `current_expiry_date` date DEFAULT NULL COMMENT 'current expiry date',
  `sum_of_call_put_oi_1_current_exp` decimal(20,2) DEFAULT NULL COMMENT 'for current expiry, highest put call oi sum',
  `sum_of_call_put_oi_2_current_exp` decimal(20,2) DEFAULT NULL COMMENT 'for current expiry, 2nd highest put call sum',
  `sum_of_call_put_oi_3_current_exp` decimal(20,2) DEFAULT NULL COMMENT 'for current expiry, 3rd highest put call sum',
  `strike_price_1_current_exp` int DEFAULT NULL COMMENT 'strike price with highest put call oi sum for current expiry',
  `strike_price_2_current_exp` int DEFAULT NULL COMMENT 'strike price with 2nd highest put call oi sum for current expiry',
  `strike_price_3_current_exp` int DEFAULT NULL COMMENT 'strike price with 3rd highest put call oi sum for current expiry',
  `next_expiry_date` date DEFAULT NULL COMMENT 'next expiry date',
  `sum_of_call_put_oi_1_next_exp` decimal(20,2) DEFAULT NULL COMMENT 'for next expiry, highest put call oi sum',
  `sum_of_call_put_oi_2_next_exp` decimal(20,2) DEFAULT NULL COMMENT 'for next expiry, 2nd highest put call oi sum',
  `sum_of_call_put_oi_3_next_exp` decimal(20,2) DEFAULT NULL COMMENT 'for next expiry, 3rd highest put call oi sum',
  `strike_price_1_next_exp` int DEFAULT NULL COMMENT 'strike price with highest put call oi sum for next expiry',
  `strike_price_2_next_exp` int DEFAULT NULL COMMENT 'strike price with 2nd highest put call oi sum for next expiry',
  `strike_price_3_next_exp` int DEFAULT NULL COMMENT 'strike price with 3rd highest put call oi sum for next expiry',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`underlying_date`)
) ENGINE=InnoDB AUTO_INCREMENT=23467 DEFAULT CHARSET=latin1;

/*Data for the table `oc_op_analysis` */

/*Table structure for table `oc_pd_avg_decay` */

DROP TABLE IF EXISTS `oc_pd_avg_decay`;

CREATE TABLE `oc_pd_avg_decay` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `oc_pd_input` bigint DEFAULT NULL COMMENT 'id of oc_pd_input table',
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `underlying_date_start` date DEFAULT NULL,
  `underlying_date_end` date DEFAULT NULL,
  `underlying_time_end` time DEFAULT NULL,
  `put_avg_decay` decimal(7,2) DEFAULT NULL,
  `call_avg_decay` decimal(7,2) DEFAULT NULL,
  `market_running` int DEFAULT '0',
  `script_start_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31726 DEFAULT CHARSET=latin1;

/*Data for the table `oc_pd_avg_decay` */

/*Table structure for table `oc_pd_input` */

DROP TABLE IF EXISTS `oc_pd_input`;

CREATE TABLE `oc_pd_input` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `current_price` float DEFAULT NULL,
  `underlying_date_start` date DEFAULT NULL,
  `underlying_date_end` date DEFAULT NULL,
  `lowest_up` float DEFAULT NULL COMMENT 'lowest underlying_price',
  `highest_up` float DEFAULT NULL COMMENT 'highest underlying_price',
  `market_range` text COMMENT 'market range from underlying_date_start to underlying_date_end',
  `sp_with_highest_oi_in_call` int DEFAULT NULL COMMENT 'strike_price_with_highest_oi_in_call in out of the money',
  `sp_with_second_highest_oi_in_call` int DEFAULT NULL COMMENT 'strike_price_with_second_highest_oi_in_call in out of the money',
  `sp_with_highest_oi_in_put` int DEFAULT NULL COMMENT 'strike_price_with_highest_oi_in_put in out of the money',
  `sp_with_second_highest_oi_in_put` int DEFAULT NULL COMMENT 'strike_price_with_second_highest_oi_in_put in out of the money',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`expiry_date`,`underlying_date_end`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `oc_pd_input` */

/*Table structure for table `oc_pd_premium` */

DROP TABLE IF EXISTS `oc_pd_premium`;

CREATE TABLE `oc_pd_premium` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `oc_pd_input` bigint DEFAULT NULL COMMENT 'id of oc_pd_input table',
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `underlying_date_start` date DEFAULT NULL,
  `underlying_date_end` date DEFAULT NULL,
  `put_or_call` varchar(5) DEFAULT NULL,
  `sp_with_highest_oi` int DEFAULT NULL COMMENT 'strike_price_with_highest_oi in put or call and strike_price_with_second_highest_oi in put or call',
  `min_market_price` float DEFAULT NULL COMMENT 'min_market_price for strike_price_with_highest_oi in put or call',
  `max_market_price` float DEFAULT NULL COMMENT 'max_market_price for strike_price_with_highest_oi in put or call',
  `ltp` decimal(7,2) DEFAULT NULL COMMENT 'puts_ltp for strike_price_with_highest_oi_in_put',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `oc_pd_premium` */

/*Table structure for table `oc_pd_values` */

DROP TABLE IF EXISTS `oc_pd_values`;

CREATE TABLE `oc_pd_values` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `oc_pd_input` bigint DEFAULT NULL COMMENT 'id of oc_pd_input table',
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `underlying_date_start` date DEFAULT NULL,
  `underlying_date_end` date DEFAULT NULL,
  `put_or_call` varchar(5) DEFAULT NULL,
  `decay` decimal(7,2) DEFAULT NULL COMMENT 'decay between market prices',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `oc_pd_values` */

/*Table structure for table `oi_participant` */

DROP TABLE IF EXISTS `oi_participant`;

CREATE TABLE `oi_participant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `market_date` date DEFAULT NULL,
  `client_type` varchar(10) DEFAULT NULL,
  `future_index_long` decimal(15,2) DEFAULT NULL,
  `future_index_short` decimal(15,2) DEFAULT NULL,
  `future_stock_long` decimal(15,2) DEFAULT NULL,
  `future_stock_short` decimal(15,2) DEFAULT NULL,
  `option_index_call_long` decimal(15,2) DEFAULT NULL,
  `option_index_put_long` decimal(15,2) DEFAULT NULL,
  `option_index_call_short` decimal(15,2) DEFAULT NULL,
  `option_index_put_short` decimal(15,2) DEFAULT NULL,
  `option_stock_call_long` decimal(15,2) DEFAULT NULL,
  `option_stock_put_long` decimal(15,2) DEFAULT NULL,
  `option_stock_call_short` decimal(15,2) DEFAULT NULL,
  `option_stock_put_short` decimal(15,2) DEFAULT NULL,
  `total_long_contracts` decimal(25,2) DEFAULT NULL,
  `total_short_contracts` decimal(25,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1621 DEFAULT CHARSET=latin1;

/*Data for the table `oi_participant` */

/*Table structure for table `put_call` */

DROP TABLE IF EXISTS `put_call`;

CREATE TABLE `put_call` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `put_call_log_id` bigint DEFAULT NULL COMMENT 'id from put_call_log table',
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `underlying_date_time` datetime DEFAULT NULL,
  `underlying_date` date DEFAULT NULL,
  `underlying_time` time DEFAULT NULL,
  `calls_total_buy_quantity` int DEFAULT NULL,
  `calls_total_sell_quantity` int DEFAULT NULL,
  `calls_oi_no_lot` decimal(10,2) DEFAULT NULL,
  `calls_chng_in_oi_no_lot` decimal(10,2) DEFAULT NULL,
  `calls_oi` decimal(10,2) DEFAULT NULL,
  `calls_chng_in_oi` decimal(10,2) DEFAULT NULL,
  `calls_chng_in_oi_p` decimal(7,2) DEFAULT NULL,
  `calls_volume` int DEFAULT NULL,
  `calls_iv` decimal(6,2) DEFAULT NULL,
  `calls_ltp` decimal(7,2) DEFAULT NULL,
  `calls_net_chng` decimal(5,2) DEFAULT NULL,
  `calls_net_chng_p` decimal(5,2) DEFAULT NULL,
  `calls_bid_qty` int DEFAULT NULL,
  `calls_bid_price` decimal(7,2) DEFAULT NULL,
  `calls_ask_price` decimal(7,2) DEFAULT NULL,
  `calls_ask_qty` int DEFAULT NULL,
  `strike_price` decimal(10,2) DEFAULT NULL,
  `puts_bid_qty` int DEFAULT NULL,
  `puts_bid_price` decimal(7,2) DEFAULT NULL,
  `puts_ask_price` decimal(7,2) DEFAULT NULL,
  `puts_ask_qty` int DEFAULT NULL,
  `puts_net_chng_p` decimal(5,2) DEFAULT NULL,
  `puts_net_chng` decimal(5,2) DEFAULT NULL,
  `puts_ltp` decimal(7,2) DEFAULT NULL,
  `puts_iv` decimal(6,2) DEFAULT NULL,
  `puts_volume` int DEFAULT NULL,
  `puts_chng_in_oi_p` decimal(7,2) DEFAULT NULL,
  `puts_chng_in_oi` decimal(10,2) DEFAULT NULL,
  `puts_oi` decimal(10,2) DEFAULT NULL,
  `puts_chng_in_oi_no_lot` decimal(7,2) DEFAULT NULL,
  `puts_oi_no_lot` decimal(10,2) DEFAULT NULL,
  `puts_total_sell_quantity` int DEFAULT NULL,
  `puts_total_buy_quantity` int DEFAULT NULL,
  `market_running` int DEFAULT '0' COMMENT '0 means market is close, 1 means live market',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`expiry_date`,`underlying_date`,`strike_price`,`underlying_time`,`market_running`)
) ENGINE=InnoDB AUTO_INCREMENT=24074 DEFAULT CHARSET=latin1;

/*Data for the table `put_call` */

/*Table structure for table `put_call_companies` */

DROP TABLE IF EXISTS `put_call_companies`;

CREATE TABLE `put_call_companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `stock_or_index` int DEFAULT '1' COMMENT '1 mean stock , 2 means index',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=latin1;

/*Data for the table `put_call_companies` */

/*Table structure for table `put_call_expiry` */

DROP TABLE IF EXISTS `put_call_expiry`;

CREATE TABLE `put_call_expiry` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `put_call_log_id` varchar(255) DEFAULT NULL COMMENT 'id from put_call_log table',
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `underlying_date_time` datetime DEFAULT NULL,
  `underlying_date` date DEFAULT NULL,
  `underlying_time` time DEFAULT NULL,
  `pcl_created_at_date` date DEFAULT NULL COMMENT 'created_at_date from put_call_log table',
  `pcl_created_at_time` time DEFAULT NULL COMMENT 'created_at time from put_call_log table',
  `pcl_created_at` datetime DEFAULT NULL COMMENT 'created_at from put_call_log table',
  `market_running` int DEFAULT '0' COMMENT '0 means market is close, 1 means live market',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`expiry_date`,`underlying_date`,`underlying_time`,`market_running`)
) ENGINE=InnoDB AUTO_INCREMENT=611 DEFAULT CHARSET=latin1;

/*Data for the table `put_call_expiry` */

/*Table structure for table `put_call_live_log` */

DROP TABLE IF EXISTS `put_call_live_log`;

CREATE TABLE `put_call_live_log` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `expiry_dates` text,
  `put_call_data` longtext,
  `market_running` int DEFAULT '0',
  `server` varchar(50) DEFAULT NULL,
  `data_processed` int DEFAULT '0',
  `market_date_time` datetime DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `market_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`market_date`,`market_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `put_call_live_log` */

/*Table structure for table `put_call_log` */

DROP TABLE IF EXISTS `put_call_log`;

CREATE TABLE `put_call_log` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `price_date_time` text,
  `put_call_data` text,
  `market_running` int DEFAULT NULL,
  `server` varchar(50) DEFAULT NULL,
  `data_processed` int DEFAULT '0',
  `created_at_date` date DEFAULT NULL,
  `created_at_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `put_call_log` */

/*Table structure for table `put_call_log2` */

DROP TABLE IF EXISTS `put_call_log2`;

CREATE TABLE `put_call_log2` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `underlying_price` float DEFAULT NULL,
  `expiry_dates` text,
  `put_call_data` longtext,
  `market_running` int DEFAULT '0',
  `server` varchar(50) DEFAULT NULL,
  `data_processed` int DEFAULT '0',
  `market_date_time` datetime DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `market_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`market_date`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

/*Data for the table `put_call_log2` */

/*Table structure for table `put_call_urls` */

DROP TABLE IF EXISTS `put_call_urls`;

CREATE TABLE `put_call_urls` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `url` text,
  `expiry_date` date DEFAULT NULL,
  `extracted` int DEFAULT '0' COMMENT 'if data extraction finish, then make it 1',
  `created_at_date` date DEFAULT NULL,
  `created_at_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10077 DEFAULT CHARSET=latin1;

/*Data for the table `put_call_urls` */

/*Table structure for table `sectors` */

DROP TABLE IF EXISTS `sectors`;

CREATE TABLE `sectors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `index_name` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `live_fetch` int DEFAULT '0' COMMENT 'if 1 means live fetch allowed',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Data for the table `sectors` */

/*Table structure for table `sectors_data` */

DROP TABLE IF EXISTS `sectors_data`;

CREATE TABLE `sectors_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sectors_id` int DEFAULT NULL COMMENT 'id from sectors table',
  `sectors_data_log_id` int DEFAULT NULL COMMENT 'id from sectors_data_log table',
  `index_name` varchar(50) DEFAULT NULL,
  `open_price` float DEFAULT NULL,
  `high_price` float DEFAULT NULL,
  `low_price` float DEFAULT NULL,
  `ltp` float DEFAULT NULL COMMENT ' last traded price',
  `change` float DEFAULT NULL,
  `change_in_percent` float DEFAULT NULL,
  `year_change_in_percent` float DEFAULT NULL,
  `month_change_in_percent` float DEFAULT NULL,
  `year_high_price` float DEFAULT NULL,
  `year_low_price` float DEFAULT NULL,
  `advances` int DEFAULT NULL,
  `declines` int DEFAULT NULL,
  `unchanged` int DEFAULT NULL,
  `trade_value_sum` float DEFAULT NULL COMMENT 'turnoverin in Crs',
  `trade_volume_sum` float DEFAULT NULL COMMENT 'volume in Lacs',
  `stock_date` date DEFAULT NULL,
  `stock_time` time DEFAULT NULL,
  `stock_date_time` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`sectors_id`,`stock_date`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Data for the table `sectors_data` */

/*Table structure for table `sectors_data_live` */

DROP TABLE IF EXISTS `sectors_data_live`;

CREATE TABLE `sectors_data_live` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sectors_id` int DEFAULT NULL COMMENT 'id from sectors table',
  `sectors_data_log_id` int DEFAULT NULL COMMENT 'id from sectors_data_log table',
  `index_name` varchar(50) DEFAULT NULL,
  `open_price` float DEFAULT NULL,
  `high_price` float DEFAULT NULL,
  `low_price` float DEFAULT NULL,
  `ltp` float DEFAULT NULL COMMENT ' last traded price',
  `change` float DEFAULT NULL,
  `change_in_percent` float DEFAULT NULL,
  `year_change_in_percent` float DEFAULT NULL,
  `month_change_in_percent` float DEFAULT NULL,
  `year_high_price` float DEFAULT NULL,
  `year_low_price` float DEFAULT NULL,
  `advances` int DEFAULT NULL,
  `declines` int DEFAULT NULL,
  `unchanged` int DEFAULT NULL,
  `trade_value_sum` float DEFAULT NULL COMMENT 'turnoverin in Crs',
  `trade_volume_sum` float DEFAULT NULL COMMENT 'volume in Lacs',
  `stock_date` date DEFAULT NULL,
  `stock_time` time DEFAULT NULL,
  `stock_date_time` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`sectors_id`,`stock_date`,`stock_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sectors_data_live` */

/*Table structure for table `sectors_data_log` */

DROP TABLE IF EXISTS `sectors_data_log`;

CREATE TABLE `sectors_data_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sectors_id` int DEFAULT NULL COMMENT 'id from sectors table',
  `index_name` varchar(50) DEFAULT NULL,
  `data` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

/*Data for the table `sectors_data_log` */

/*Table structure for table `share_beneficial` */

DROP TABLE IF EXISTS `share_beneficial`;

CREATE TABLE `share_beneficial` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `share_distribution_id` bigint DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `record_id` bigint DEFAULT NULL,
  `sbo_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DETAILS OF THE SIGNIFICANT BENEFICIAL OWNER (SBO)',
  `sbo_nationality` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sbo_pan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sbo_passport` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regis_owner_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DETAILS OF THE REGISTERED OWNER',
  `regis_owner_nationality` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regis_owner_pan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regis_owner_passport` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regis_owner_share` decimal(5,2) DEFAULT NULL COMMENT 'DETAILS OF HOLDING/ EXERCISE OF RIGHT OF THE SBO IN THE REPORTING COMPANY, WHETHER DIRECT OR INDIRECT',
  `regis_owner_vote_right` decimal(5,2) DEFAULT NULL COMMENT 'VOTING RIGHTS (%)',
  `regis_owner_rights` decimal(5,2) DEFAULT NULL COMMENT 'RIGHTS ON DISTRIBUTABLE (%) DIVIDEND OR ANY OTHER DISTRIBUTION',
  `exec_sign_influ` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'EXERCISE OF CONTROL',
  `exec_control` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'EXERCISE OF SIGNIFICANT INFLUENCE',
  `creation_acq_date` datetime DEFAULT NULL COMMENT 'DATE OF CREATION / ACQUISITION OF SIGNIFICANT BENEFICIAL INTEREST#',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8871 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `share_beneficial` */

/*Table structure for table `share_consert` */

DROP TABLE IF EXISTS `share_consert`;

CREATE TABLE `share_consert` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `share_distribution_id` bigint DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `record_id` bigint DEFAULT NULL,
  `shareholder_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pac_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Persons Acting in Concert',
  `no_of_shareholders` bigint DEFAULT NULL,
  `no_of_shares` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15739 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `share_consert` */

/*Table structure for table `share_declaration` */

DROP TABLE IF EXISTS `share_declaration`;

CREATE TABLE `share_declaration` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `share_distribution_id` bigint DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `record_id` bigint DEFAULT NULL,
  `question` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promoter_group` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `non_public` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165441 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `share_declaration` */

/*Table structure for table `share_distribution` */

DROP TABLE IF EXISTS `share_distribution`;

CREATE TABLE `share_distribution` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `record_id` bigint DEFAULT NULL,
  `promoter` decimal(7,2) DEFAULT NULL COMMENT 'PROMOTER & PROMOTER GROUP (A)',
  `public` decimal(7,2) DEFAULT NULL,
  `underlying_drs` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_trusts` decimal(7,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`market_date`,`record_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1852 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `share_distribution` */

/*Table structure for table `share_holding` */

DROP TABLE IF EXISTS `share_holding`;

CREATE TABLE `share_holding` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `share_distribution_id` bigint DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `record_id` bigint DEFAULT NULL,
  `shares_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'category',
  `shareholder_category` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'COL_I',
  `shareholders_no` bigint DEFAULT NULL COMMENT 'COL_III',
  `fully_paid_up_equity_shares_no` bigint DEFAULT NULL COMMENT 'COL_IV',
  `total_shares` bigint DEFAULT NULL COMMENT 'COL_IX_Total',
  `share_in_p_a` decimal(7,2) DEFAULT NULL COMMENT 'SHAREHOLDING AS A % OF TOTAL NO. OF SHARES (CALCULATED AS PER SCRR, 1957) AS A % OF (A+B+C2) => COL_IX_TotalABC',
  `no_of_voting_right` bigint DEFAULT NULL COMMENT 'COL_IX_X',
  `total_no_of_voting_right` bigint DEFAULT NULL COMMENT 'COL_VII',
  `voting_share_p` decimal(5,2) DEFAULT NULL COMMENT 'TOTAL AS A % OF (A+B+C) => COL_VIII',
  `share_in_p_b` decimal(7,2) DEFAULT NULL COMMENT ' ( SHAREHOLDING , AS A % ASSUMING FULL CONVERSION OF CONVERTIBLE SECURITIES ( AS A PERCENTAGE OF DILUTED SHARE CAPITAL) AS A % OF (A+B+C2) )	=>COL_XI',
  `no_of_shares_demat_form` bigint DEFAULT NULL COMMENT 'COL_XIV',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `share_holding` */

/*Table structure for table `share_insider_trading` */

DROP TABLE IF EXISTS `share_insider_trading`;

CREATE TABLE `share_insider_trading` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) DEFAULT NULL,
  `regulation` varchar(10) DEFAULT NULL COMMENT 'REGULATION',
  `acq_name` varchar(255) DEFAULT NULL COMMENT 'NAME OF THE ACQUIRER/DISPOSER',
  `broadcaste_datetime` datetime DEFAULT NULL COMMENT 'BROADCASTE DATE AND TIME',
  `broadcaste_date` date DEFAULT NULL,
  `broadcaste_time` time DEFAULT NULL,
  `pid` bigint DEFAULT NULL,
  `sec_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'TYPE OF SECURITY (PRIOR)',
  `sec_acq` bigint DEFAULT NULL COMMENT 'NO. OF SECURITIES (ACQUIRED/DISPLOSED)',
  `tdp_transaction_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ACQUISITION/DISPOSAL TRANSACTION TYPE',
  `did` bigint DEFAULT NULL,
  `person_category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CATEGORY OF PERSON',
  `bef_acq_shares_no` bigint DEFAULT NULL COMMENT 'NO. OF SECURITY (PRIOR)',
  `bef_acq_shares_per` decimal(5,2) DEFAULT NULL COMMENT '% SHAREHOLDING (PRIOR)',
  `sec_val` bigint DEFAULT NULL COMMENT 'VALUE OF SECURITY (ACQUIRED/DISPLOSED)',
  `securities_type_post` varchar(30) DEFAULT NULL COMMENT 'TYPE OF SECURITY (POST)',
  `after_acq_shares_no` bigint DEFAULT NULL COMMENT 'NO. OF SECURITY (POST)',
  `after_acq_shares_per` decimal(5,2) DEFAULT NULL COMMENT '% POST',
  `acq_from_dt` date DEFAULT NULL COMMENT 'DATE OF ALLOTMENT/ACQUISITION FROM',
  `acq_to_dt` date DEFAULT NULL COMMENT 'DATE OF ALLOTMENT/ACQUISITION TO',
  `intim_dt` date DEFAULT NULL COMMENT 'DATE OF INITMATION TO COMPANY',
  `acq_mode` varchar(70) DEFAULT NULL COMMENT 'MODE OF ACQUISITION',
  `derivative_type` varchar(50) DEFAULT NULL COMMENT 'DERIVATIVE TYPE SECURITY',
  `exchange` varchar(10) DEFAULT NULL COMMENT 'EXCHANGE',
  `tdp_derivative_contract_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'DERIVATIVE CONTRACT SPECIFICATION',
  `tkd_acqm` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buy_value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buy_quantity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sell_value` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sell_quantity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'REMARK',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_symbol`,`pid`,`did`)
) ENGINE=InnoDB AUTO_INCREMENT=35959 DEFAULT CHARSET=latin1;

/*Data for the table `share_insider_trading` */

/*Table structure for table `share_pledged` */

DROP TABLE IF EXISTS `share_pledged`;

CREATE TABLE `share_pledged` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shp` date DEFAULT NULL,
  `tot_issued_shares` bigint DEFAULT NULL COMMENT 'TOTAL NO. OF ISSUED SHARES A+B+C',
  `tot_promoter_holding` bigint DEFAULT NULL COMMENT ' NO. OF SHARES (A)',
  `perc_promoter_holding` decimal(5,2) DEFAULT NULL COMMENT 'TOTAL PROMOTER HOLDING % A /(A+B+C)',
  `tot_public_holding` bigint DEFAULT NULL COMMENT 'TOTAL PUBLIC HOLDING (%)B',
  `tot_promoter_shares_enc` bigint DEFAULT NULL COMMENT 'PROMOTER SHARES ENCUMBERED AS OF LAST QUARTER NO. OF SHARES (X)',
  `perc_promoter_shares_enc` decimal(5,2) DEFAULT NULL COMMENT 'PROMOTER SHARES ENCUMBERED AS OF LAST QUARTER % OF PROMOTER SHARES (X/A)',
  `perc_tot_shares_enc` decimal(5,2) DEFAULT NULL COMMENT 'PROMOTER SHARES ENCUMBERED AS OF LAST QUARTER % OF TOTAL SHARES [X/(A+B+C)]',
  `disclosure_from_date` date DEFAULT NULL,
  `num_shares_pledged_demat` bigint DEFAULT NULL COMMENT 'NO. OF SHARES PLEDGED IN THE DEPOSITORY SYSTEM NO. OF SHARES PLEDGED',
  `tot_demat_shares` bigint DEFAULT NULL COMMENT 'NO. OF SHARES PLEDGED IN THE DEPOSITORY SYSTEM TOTAL NO. OF DEMAT SHARES',
  `perc_shares_pledged_demat` decimal(5,2) DEFAULT NULL COMMENT '(%) PLEDGE / DEMAT',
  `broadcaste_datetime` datetime DEFAULT NULL COMMENT 'BROADCASTE DATE AND TIME',
  `broadcaste_date` date DEFAULT NULL COMMENT 'BROADCASTE DATE',
  `broadcaste_time` time DEFAULT NULL COMMENT 'BROADCASTE TIME',
  `disclosure_to_date` date DEFAULT NULL,
  `comp_broadcast_date` date DEFAULT NULL,
  `shares_collateral` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nbfc_promo_share` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nbfc_non_promo_share` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2881 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `share_pledged` */

/*Table structure for table `share_sast_buy_sale` */

DROP TABLE IF EXISTS `share_sast_buy_sale`;

CREATE TABLE `share_sast_buy_sale` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `acq_or_sale_date_from` date DEFAULT NULL,
  `acq_or_sale_date_to` date DEFAULT NULL,
  `total_share_acq` bigint DEFAULT NULL,
  `total_share_sale` bigint DEFAULT NULL,
  `total_share_after` bigint DEFAULT NULL,
  `regulation` varchar(10) DEFAULT NULL COMMENT 'REGULATION',
  `application_no` bigint DEFAULT NULL,
  `promoter_type` varchar(3) DEFAULT NULL,
  `acq_or_sale_type` varchar(15) DEFAULT NULL,
  `mode` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `total_share_acq_p` decimal(10,2) DEFAULT NULL,
  `total_acq_diluted_p` decimal(10,2) DEFAULT NULL,
  `total_share_sale_p` decimal(10,2) DEFAULT NULL,
  `total_sale_diluted_p` decimal(10,2) DEFAULT NULL,
  `total_share_after_p` decimal(10,2) DEFAULT NULL,
  `total_after_diluted_p` decimal(10,2) DEFAULT NULL,
  `broadcaste_datetime` datetime DEFAULT NULL COMMENT 'BROADCASTE DATE AND TIME',
  `broadcaste_date` date DEFAULT NULL,
  `broadcaste_time` time DEFAULT NULL,
  `exchange` varchar(10) DEFAULT NULL COMMENT 'EXCHANGE',
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'REMARK',
  `attachement` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`application_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2104 DEFAULT CHARSET=latin1;

/*Data for the table `share_sast_buy_sale` */

/*Table structure for table `share_unclaimed` */

DROP TABLE IF EXISTS `share_unclaimed`;

CREATE TABLE `share_unclaimed` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `share_distribution_id` bigint DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `record_id` bigint DEFAULT NULL,
  `shares_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_of_shareholders` bigint DEFAULT NULL,
  `no_of_shares` bigint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7524 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `share_unclaimed` */

/*Table structure for table `stock_data` */

DROP TABLE IF EXISTS `stock_data`;

CREATE TABLE `stock_data` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `stock_data_log_id` bigint DEFAULT NULL COMMENT 'id from stock_data_log table',
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `exchange_name` varchar(10) DEFAULT NULL,
  `series` varchar(50) DEFAULT NULL,
  `last_price` float DEFAULT NULL,
  `open_price` float DEFAULT NULL,
  `close_price` float DEFAULT NULL,
  `day_high_price` float DEFAULT NULL,
  `day_low_price` float DEFAULT NULL,
  `total_traded_volume` float DEFAULT NULL,
  `delivery_quantity` float DEFAULT NULL,
  `delivery_to_traded_quantity` float DEFAULT NULL,
  `total_buy_quantity` float DEFAULT NULL,
  `total_sell_quantity` float DEFAULT NULL,
  `total_traded_value` float DEFAULT NULL,
  `pd_sector_pe` float DEFAULT NULL,
  `pd_symbol_pe` float DEFAULT NULL,
  `pd_sector_ind` varchar(12) DEFAULT NULL,
  `price_change` decimal(7,2) DEFAULT NULL,
  `price_change_in_p` decimal(7,2) DEFAULT NULL,
  `vwap` decimal(7,2) DEFAULT NULL,
  `lower_cp` decimal(7,2) DEFAULT NULL,
  `upper_cp` decimal(7,2) DEFAULT NULL,
  `p_price_band` varchar(15) DEFAULT NULL,
  `base_price` decimal(7,2) DEFAULT NULL,
  `year_week_low` decimal(7,2) DEFAULT NULL,
  `year_week_low_date` date DEFAULT NULL,
  `year_week_high` decimal(7,2) DEFAULT NULL,
  `year_week_high_date` date DEFAULT NULL,
  `no_block_deals` varchar(100) DEFAULT NULL,
  `total_market_cap` decimal(30,2) DEFAULT NULL,
  `quantity_traded` decimal(30,2) DEFAULT NULL,
  `total_no_of_trades` decimal(20,2) DEFAULT NULL,
  `total_traded_value_eod` decimal(40,2) DEFAULT NULL,
  `total_traded_volume_eod` decimal(50,2) DEFAULT NULL,
  `volume_by_total_no_of_trade` decimal(20,2) DEFAULT NULL,
  `stock_date_time` datetime DEFAULT NULL,
  `stock_date` date DEFAULT NULL,
  `stock_time` time DEFAULT NULL,
  `created_at_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_EACH_DAY_DATA` (`company_symbol`,`exchange_name`,`series`,`stock_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `stock_data` */

/*Table structure for table `stock_data_live` */

DROP TABLE IF EXISTS `stock_data_live`;

CREATE TABLE `stock_data_live` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `stock_data_log_id` bigint DEFAULT NULL COMMENT 'id from stock_data_log table',
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `exchange_name` varchar(10) DEFAULT NULL,
  `series` varchar(50) DEFAULT NULL,
  `last_price` float DEFAULT NULL,
  `open_price` float DEFAULT NULL,
  `close_price` float DEFAULT NULL,
  `day_high_price` float DEFAULT NULL,
  `day_low_price` float DEFAULT NULL,
  `total_traded_volume` float DEFAULT NULL,
  `delivery_quantity` float DEFAULT NULL,
  `delivery_to_traded_quantity` float DEFAULT NULL,
  `total_buy_quantity` float DEFAULT NULL,
  `total_sell_quantity` float DEFAULT NULL,
  `total_traded_value` float DEFAULT NULL,
  `pd_sector_pe` float DEFAULT NULL,
  `pd_symbol_pe` float DEFAULT NULL,
  `pd_sector_ind` varchar(12) DEFAULT NULL,
  `price_change` decimal(7,2) DEFAULT NULL,
  `price_change_in_p` decimal(7,2) DEFAULT NULL,
  `vwap` decimal(7,2) DEFAULT NULL,
  `lower_cp` decimal(7,2) DEFAULT NULL,
  `upper_cp` decimal(7,2) DEFAULT NULL,
  `p_price_band` varchar(15) DEFAULT NULL,
  `base_price` decimal(7,2) DEFAULT NULL,
  `year_week_low` decimal(7,2) DEFAULT NULL,
  `year_week_low_date` date DEFAULT NULL,
  `year_week_high` decimal(7,2) DEFAULT NULL,
  `year_week_high_date` date DEFAULT NULL,
  `no_block_deals` varchar(100) DEFAULT '1',
  `total_market_cap` decimal(30,2) DEFAULT NULL,
  `quantity_traded` decimal(30,2) DEFAULT NULL,
  `stock_date_time` datetime DEFAULT NULL,
  `stock_date` date DEFAULT NULL,
  `stock_time` time DEFAULT NULL,
  `created_at_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_MAIN_COL` (`company_id`,`company_symbol`,`stock_date`,`stock_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `stock_data_live` */

/*Table structure for table `stock_data_log` */

DROP TABLE IF EXISTS `stock_data_log`;

CREATE TABLE `stock_data_log` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(255) DEFAULT NULL,
  `data` text,
  `exchange_name` varchar(10) DEFAULT NULL,
  `market_running` int DEFAULT '0',
  `server` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `stock_data_log` */

/*Table structure for table `vix` */

DROP TABLE IF EXISTS `vix`;

CREATE TABLE `vix` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `market_date` date DEFAULT NULL,
  `last_price` decimal(10,5) DEFAULT NULL,
  `change` decimal(5,5) DEFAULT NULL,
  `p_change` decimal(5,4) DEFAULT NULL,
  `market_running` int DEFAULT '0',
  `created_at_time` time DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`market_date`,`market_running`,`created_at_time`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `vix` */

/*Table structure for table `volatility` */

DROP TABLE IF EXISTS `volatility`;

CREATE TABLE `volatility` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `company_symbol` varchar(10) DEFAULT NULL,
  `daily_volatility` float DEFAULT NULL COMMENT 'oc or future',
  `daily_volatility_p` decimal(5,2) DEFAULT NULL,
  `annual_volatility` float DEFAULT NULL,
  `annual_volatility_p` decimal(7,2) DEFAULT NULL,
  `market_date` date DEFAULT NULL,
  `derivative` int DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_id`,`company_symbol`,`market_date`)
) ENGINE=InnoDB AUTO_INCREMENT=22536 DEFAULT CHARSET=latin1;

/*Data for the table `volatility` */

/*Table structure for table `volume_participant` */

DROP TABLE IF EXISTS `volume_participant`;

CREATE TABLE `volume_participant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `market_date` date DEFAULT NULL,
  `client_type` varchar(10) DEFAULT NULL,
  `future_index_long` decimal(15,2) DEFAULT NULL,
  `future_index_short` decimal(15,2) DEFAULT NULL,
  `future_stock_long` decimal(15,2) DEFAULT NULL,
  `future_stock_short` decimal(15,2) DEFAULT NULL,
  `option_index_call_long` decimal(15,2) DEFAULT NULL,
  `option_index_put_long` decimal(15,2) DEFAULT NULL,
  `option_index_call_short` decimal(15,2) DEFAULT NULL,
  `option_index_put_short` decimal(15,2) DEFAULT NULL,
  `option_stock_call_long` decimal(15,2) DEFAULT NULL,
  `option_stock_put_long` decimal(15,2) DEFAULT NULL,
  `option_stock_call_short` decimal(15,2) DEFAULT NULL,
  `option_stock_put_short` decimal(15,2) DEFAULT NULL,
  `total_long_contracts` decimal(25,2) DEFAULT NULL,
  `total_short_contracts` decimal(25,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1611 DEFAULT CHARSET=latin1;

/*Data for the table `volume_participant` */

/*Table structure for table `year_high_data` */

DROP TABLE IF EXISTS `year_high_data`;

CREATE TABLE `year_high_data` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `year_high_low_log_id` bigint DEFAULT NULL COMMENT 'id from year_high_low_log table',
  `company_id` int DEFAULT '0',
  `company_symbol` varchar(10) DEFAULT NULL,
  `pc_exists` int DEFAULT '0' COMMENT '1 if exists in put_call_comapies table',
  `new_high` decimal(7,2) NOT NULL,
  `year_high` decimal(7,2) DEFAULT NULL,
  `ltp` decimal(7,2) DEFAULT NULL,
  `prev_high` decimal(7,2) DEFAULT NULL,
  `prev_high_date` date DEFAULT NULL,
  `prev_close` decimal(7,2) DEFAULT NULL,
  `change` decimal(7,2) DEFAULT NULL,
  `pChange` decimal(7,2) DEFAULT NULL COMMENT 'change in percentage',
  `market_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_symbol`,`market_date`)
) ENGINE=InnoDB AUTO_INCREMENT=2121 DEFAULT CHARSET=latin1;

/*Data for the table `year_high_data` */

/*Table structure for table `year_high_low_log` */

DROP TABLE IF EXISTS `year_high_low_log`;

CREATE TABLE `year_high_low_log` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `market_date` date DEFAULT NULL,
  `high_or_low` varchar(4) DEFAULT NULL,
  `data` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`market_date`,`high_or_low`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `year_high_low_log` */

/*Table structure for table `year_low_data` */

DROP TABLE IF EXISTS `year_low_data`;

CREATE TABLE `year_low_data` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `year_high_low_log_id` bigint DEFAULT NULL COMMENT 'id from year_high_low_log table',
  `company_id` int DEFAULT '0',
  `company_symbol` varchar(10) DEFAULT NULL,
  `pc_exists` int DEFAULT '0' COMMENT '1 if exists in put_call_comapies table',
  `new_low` decimal(7,2) NOT NULL,
  `year_low` decimal(7,2) DEFAULT NULL,
  `ltp` decimal(7,2) DEFAULT NULL,
  `prev_low` decimal(7,2) DEFAULT NULL,
  `prev_low_date` date DEFAULT NULL,
  `prev_close` decimal(7,2) DEFAULT NULL,
  `change` decimal(7,2) DEFAULT NULL,
  `pChange` decimal(7,2) DEFAULT NULL COMMENT 'change in percentage',
  `market_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQZ` (`company_symbol`,`market_date`)
) ENGINE=InnoDB AUTO_INCREMENT=2642 DEFAULT CHARSET=latin1;

/*Data for the table `year_low_data` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
