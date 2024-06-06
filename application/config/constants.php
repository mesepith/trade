<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


define('SERVER_NAME', 'scaleway-1');
define('FINAL_DATA_SERVER', 'yes');
define('FINAL_OC_SERVER', 'yes');
define('FINAL_FUTURE_DATA_SERVER', 'yes');


if(!empty($_SERVER['HTTP_HOST']) && ( $_SERVER['HTTP_HOST'] =='127.0.0.1' || $_SERVER['HTTP_HOST'] =='localhost' ) ){
    
    if (ENVIRONMENT === "testing") {
        
        if( FINAL_OC_SERVER === 'yes' ){
            
            define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"] . "/pilot-option-trade");
            
        }else{
        
            define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"] . "/pilot-trade");
        }
        
    } else if (ENVIRONMENT === "production") {
        
        if( FINAL_OC_SERVER === 'yes' ){
            
            define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"] . "/option-trade");
            
        }else{
        
            define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"] . "/trade");
        }
                
        
    }else if(ENVIRONMENT === "development"){
        
        define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"]);
    }
    
}else{
    
    define('DOCUMENT_ROOT', $_SERVER["DOCUMENT_ROOT"]);
}


if (ENVIRONMENT === "development") {

    if (!empty($_SERVER["REQUEST_SCHEME"])) {

        $protocol = $_SERVER["REQUEST_SCHEME"];
    } else {

        $protocol = "http";
    }
    
    if( get_current_user() === "stackdev") {
        
        define('DEV_MAIN_FOLDER', 'trd');
        define('DEV_DB', 'test');
        
    }else{
    
	define('DEV_MAIN_FOLDER', 'trade');
        define('DEV_DB', 'trade');
    }
    
    define('PROTOCOL', $protocol);
    define('PROJECT_DOCUMENT_ROOT', DOCUMENT_ROOT . "/".DEV_MAIN_FOLDER."/");
    
    define('AHZ_SERVER','http://localhost/startz');
    define('TRADE_SELENIUM_SERVER','http://localhost/'. DEV_MAIN_FOLDER);
    
    define('PARENT_WEB_SERVER','http://localhost/'.DEV_MAIN_FOLDER.'/');
//    define('PARENT_OC_WEB_SERVER','http://localhost/'.DEV_MAIN_FOLDER.'/');
    define('PARENT_OC_WEB_SERVER','https://option.ampstart.co/');
    define('PARENT_FUTURE_WEB_SERVER','http://localhost/'.DEV_MAIN_FOLDER.'/');

    define('PYTHON_COOKIE_SCRIPT_NAME', "dev-fetch-nsit-and-nseappid.py");
    
} else if (ENVIRONMENT === "testing") {

    define('PROTOCOL', "https");
    define('PROJECT_DOCUMENT_ROOT', DOCUMENT_ROOT);
    
    define('AHZ_SERVER','https://pilot.ahealz.com');
    define('TRADE_SELENIUM_SERVER','http://35.225.106.210/pilot-trade');
    
    define('PARENT_WEB_SERVER','https://pilot.ampstart.co/');
    define('PARENT_OC_WEB_SERVER','https://pilot-option.ampstart.co/');
    define('PARENT_FUTURE_WEB_SERVER','https://pilot.ampstart.co/');

    define('PYTHON_COOKIE_SCRIPT_NAME', "staging-fetch-nsit-and-nseappid.py");
    
} else if (ENVIRONMENT === "production") {

    define('PROTOCOL', "https");
    define('PROJECT_DOCUMENT_ROOT', DOCUMENT_ROOT);
    
    define('AHZ_SERVER','https://www.ahealz.com');
    define('TRADE_SELENIUM_SERVER','http://35.225.106.210/trade');
    
    define('PARENT_WEB_SERVER','https://trade.zahiralam.com/');
    define('PARENT_OC_WEB_SERVER','https://option.ampstart.co/');
    define('PARENT_FUTURE_WEB_SERVER','https://www.ampstart.co/');

    define('PYTHON_COOKIE_SCRIPT_NAME', "fetch-nsit-and-nseappid.py");
}
define('PROTOCOL_HOST', PROTOCOL . "://" . $_SERVER['HTTP_HOST']);
define('YTB_PAGE_LIST_LIMIT', 21);
define('HALF_SUGGESTION_LIST_LIMIT_PLAY_VID', 6);
define('HALF_SUGGESTION_TOP_VIWED_LIST_LIMIT_PLAY_VID', 6);


if (date('D') == 'Sat') {

    define('EACH_STOCK_QUERY_DATE_LIMIT', 5);
} else if (date('D') == 'Sun') {

    define('EACH_STOCK_QUERY_DATE_LIMIT', 6);
} else {

    define('EACH_STOCK_QUERY_DATE_LIMIT', 4);
}

define('FIRST_SERIAL_FAMOUS_STOCK', 'ASIANPAINT');
define('LAST_SERIAL_FAMOUS_STOCK', 'TCS');

define('MIN_STOCK_CHECK_COUNT', 4);

define('HOUR_FOR_FINAL_DATA', 18);
