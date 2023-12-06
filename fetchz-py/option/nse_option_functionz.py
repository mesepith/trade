#/* for date time converstion https://stackoverflow.com/questions/466345/converting-string-into-datetime
#import nse_option_model
import json
import datetime, pytz; 
import requests

import index
import sys, os
sys.path.insert(0, index.NSE_TOOL_LOC)

import constant
import stock_data_model
import exception_log
import nse_option_model



from collections import defaultdict

from dateutil import parser

now = datetime.datetime.now(pytz.timezone('Asia/Kolkata'))

def storeErrorException( system_exception_type_desc, page_name, line_no, function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, error_system_msg, tool_name, command ):
    exception_log.insert( company_id, log_table_primary_id, page_name, function_name, error_type, custom_exception_msg, error_system_msg, tool_name, command, system_exception_type_desc, line_no )
    print()
    
def companyList():
#    company_list = stock_data_model.companiesList();
#    return company_list

    if(index.FINAL_DATA_SERVER=='yes'):
        company_list = stock_data_model.companiesListWithKey();
        return company_list
    else:
        import json
        COMPANY_DATA_URL = constant.API_ROOT + "/companies/fetch-all-data";
        company_list_return = requests.post(url = COMPANY_DATA_URL)
        company_list_json = company_list_return.text
        company_listz = json.loads(company_list_json)
        return company_listz
    
def companyListToFetchPCUrls():

    import json
    COMPANY_DATA_URL = constant.API_ROOT + "/companies/put-call-urls/fetch-all-data";
    company_list_return = requests.post(url = COMPANY_DATA_URL)
    company_list_json = company_list_return.text
    company_listz = json.loads(company_list_json)
    return company_listz

def json_serial(obj):
    from datetime import date, time, datetime
    """JSON serializer for objects not serializable by default json code"""

    if isinstance(obj, (datetime, date, time)):
        return obj.isoformat()
    raise TypeError ("Type %s not serializable" % type(obj))

def getMarketDateTime(put_option_data, company_symbol):
      
    dictionary = json.loads(put_option_data)
    
    table_1_txt = dictionary['data'][0][1];

    table_1_split = table_1_txt.split('Underlying Stock: ' + str(company_symbol).upper() + " ")
    
    price_n_date_time = table_1_split[1]
    
    price_n_date_time_split = price_n_date_time.split(' As on ')
    
    price = price_n_date_time_split[0]
    date_time_txt = price_n_date_time_split[1]
    
    date_time_txt_split = date_time_txt.split(' IST')

    date_time = date_time_txt_split[0];

    
    date_time_format = parser.parse(date_time)
    
    price_date_time_arr = defaultdict(dict)
    
    from json import dumps
    price_date_time_arr['underlying_price'] = price
    price_date_time_arr['underlying_date_time'] = dumps(date_time_format, default=json_serial)

    price_date_time_arr['underlying_date'] = dumps(date_time_format.date(), default=json_serial)
    price_date_time_arr['underlying_time'] = dumps(date_time_format.time(), default=json_serial)
        
    return price_date_time_arr
    

def getPutOptionTableData(url, company_id, company_symbol, expiry_date):   
    
    now = datetime.datetime.now(pytz.timezone('Asia/Kolkata'))
    
    import pandas as pd
    #html = requests.get(url).content
    
    headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.76 Safari/537.36', "Upgrade-Insecure-Requests": "1","DNT": "1","Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8","Accept-Language": "en-US,en;q=0.5","Accept-Encoding": "gzip, deflate"}
    html = requests.get(url, headers=headers, timeout=30).content

    df_list = pd.read_html(html)
    
    count=0
    each_page_data = defaultdict(dict)
    
    for each_table in df_list:
        tablez = df_list[count]
        count+=1
        
        if(count==3): continue
        
        try:
            tablez.to_json()
            put_option_data = tablez.to_json(orient = "split")
            if(count==1):

                price_date_time_arr = getMarketDateTime(put_option_data, company_symbol)
                
            elif(count==2):
                each_page_data['put_call_data'] = put_option_data
                                            
        except Exception as e:
            print('fail to fetch table data: ')
            print(e)
            exc_type, exc_obj, exc_tb = sys.exc_info()
            fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
            
            custom_exception_msg = "fail to fetch table data of : " + company_symbol + " on expiry date : " + expiry_date 
            log_table_primary_id=0
            tool_name='selenium'
            command=''
            function_name='getPutOptionTableData'
            error_type=5
            storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )

    
    try:
        
        created_at = now.strftime('%Y-%m-%d %H:%M:%S')
        
        created_at_date = now.strftime('%Y-%m-%d')
        
        created_at_time = now.strftime('%H:%M:%S')
        
        API_URL = constant.API_ROOT + "/pyapi/receive-py-fetch-put-call-data";
        
         # data to be sent to api 
        data = {'company_id':company_id, 
                'company_symbol':company_symbol, 
                'expiry_date':parser.parse(expiry_date), 
                'put_call_data':put_option_data, 
                'price_date_time':json.dumps(price_date_time_arr), 
                'created_at':created_at,
                'created_at_date':created_at_date,
                'created_at_time':created_at_time,
                'market_running':0,
                'server':index.SERVER,} 
                
        # sending post request and saving response as response object 
        r = requests.post(url = API_URL, data = data)
        
        # extracting response text  
        api_return = r.text 
        print("php api_return of  ", company_symbol , " for expiry date: " , expiry_date , " is:%s"%api_return) 
        
    except Exception as e:
        print('Issue with the sending Put Call Table Data to Api: ')
        print(e)
        exc_type, exc_obj, exc_tb = sys.exc_info()
        fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
        
        custom_exception_msg = "Issue with the sending Put Call Table Data to Api : " + company_symbol + " on expiry date : " + expiry_date 
        log_table_primary_id=0
        tool_name='selenium'
        command=''
        function_name='getPutOptionTableData'
        error_type=6
        storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )
#    exit()            
    
def getChromeDriver():
    
    from selenium import webdriver
    
    if (index.ENVIRONMENT == 'development'):
        
        d = webdriver.Chrome()
        
#        from selenium.webdriver.chrome.options import Options 
#        chrome_options = Options()  
#        chrome_options.add_argument('--no-sandbox')
#        chrome_options.add_argument("--headless")          
#        d = webdriver.Chrome('//usr/local/bin/chromedriver',   chrome_options=chrome_options)  
        
    elif (index.ENVIRONMENT == 'testing'):
        
        from selenium.webdriver.chrome.options import Options 
        chrome_options = Options()  
        chrome_options.add_argument('--no-sandbox')
        chrome_options.add_argument("--headless")  
        
        d = webdriver.Chrome('/usr/bin/chromedriver',   chrome_options=chrome_options)  

    elif (index.ENVIRONMENT == 'production'):
        
        from selenium.webdriver.chrome.options import Options 
        chrome_options = Options()  
        chrome_options.add_argument('--no-sandbox')
        chrome_options.add_argument("--headless")  
        
        d = webdriver.Chrome('/usr/bin/chromedriver',   chrome_options=chrome_options)  
        
    return d

"""
@author: ZAHIR
DESC: proces put call data after data fetching is done    
"""
def processPutCallData(redirect_url):
    
    now = datetime.datetime.now(pytz.timezone('Asia/Kolkata'))
    
    if(redirect_url=='start'):
    
        API_URL = constant.API_ROOT + "/data-process/put-call-log-data";
    elif(redirect_url=='none'):
        
        exit();
    else:
        API_URL = redirect_url
        
    print('')
    print(API_URL)
    
    r = requests.post(url = API_URL)
    
    if( r.status_code==200 ):
        print(now.strftime('%Y-%m-%d %H:%M:%S') , ' : ################### Return From Put Call Log Data Process Api #############################')            
        processPutCallData(r.text)
    else:
        print(now.strftime('%Y-%m-%d %H:%M:%S') , ' : Fail to process put call data of url : ', redirect_url)
        print(r)
    
    exit()

"""
@author: ZAHIR
DESC: Check if stock_data table has todays any stock    
"""
def checkStockTodayDataPresent():
    
    API_URL = constant.API_ROOT + "/stock-data/check-today-data-inserted";
    r = requests.post(url = API_URL)
    
    if(r.text != 'present'):
        print('since todays stock data is not present at : ' , now.strftime('%Y-%m-%d %H:%M:%S') , ' , so we are not fetching put call data')
        exit()
        
"""
@author: ZAHIR
DESC: Check if todays put option log data is present    
"""

def checkTodayPutCallLogDataPresent():
    
    print('function: checkTodayPutCallLogDataPresent : ' , now.strftime('%Y-%m-%d'))
    
    API_URL = constant.API_ROOT + "/data-process/check-put-call-log-data-inserted/" + now.strftime('%Y-%m-%d');
    print(API_URL)
    r = requests.post(url = API_URL)
    
    print(r.text)
    
    if(r.text == 'present'):
        print('since todays put call log data is already present at : ' , now.strftime('%Y-%m-%d %H:%M:%S') , ' , so we dont need to fetch the data')
        exit()
        
"""
@author: ZAHIR
DESC: Check if all rows of put_call_urls table data is extracted of today 
"""

def checkTodayPutCallUrlsExtracted():
    
    print('function: checkTodayPutCallUrlsExtracted : ' , now.strftime('%Y-%m-%d %H:%M:%S') )
    
    today_date = now.strftime('%Y-%m-%d')
    
    non_extracted_url_count = nse_option_model.checkTodayPutCallLogDataExtracted( today_date )
    
    if(non_extracted_url_count ==0):
        print(' non extracted url count =', non_extracted_url_count , ', means all put_call_urls table data is extracted, so need to to continue this script')
        exit();
    
    

"""
@author: ZAHIR
DESC: Store url of option chain page with expiry date
"""        
def storePutCallUrl( url, company_id, company_symbol, expiry_date ):
    
    expiry_date = parser.parse(expiry_date)
    
    now = datetime.datetime.now(pytz.timezone('Asia/Kolkata'))
    
    created_at_date = now.strftime('%Y-%m-%d')
    
    created_at_time = now.strftime('%H:%M:%S')
    
    try: 
        
        nse_option_model.storePutCallUrl(url, company_id, company_symbol, expiry_date, now, created_at_date, created_at_time)
        
    except Exception as e:
        print('fail to store put call url: ')
        print(e)
        exc_type, exc_obj, exc_tb = sys.exc_info()
        fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
        
        custom_exception_msg = "Fail to store put call url : " + company_symbol + " on expiry date : " + expiry_date 
        log_table_primary_id=0
        tool_name='selenium'
        command=''
        function_name='storePutCallUrl'
        error_type=7
        storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )
    
"""
@author: ZAHIR
DESC: Fetch put call url
"""        
def fetchPutCallUrl():
    
    now = datetime.datetime.now(pytz.timezone('Asia/Kolkata'))
    
    today_date = now.strftime('%Y-%m-%d')
    
    try: 
        url_list = nse_option_model.fetchPutCallUrl(today_date)
        
    except Exception as e:
        print('fail to fetch put call url from put_call_urls table with status extracted=0 : ')
        print(e)
        exc_type, exc_obj, exc_tb = sys.exc_info()
        fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
        
        custom_exception_msg = "fail to fetch put call url from put_call_urls table with status extracted=0: "
        log_table_primary_id=0
        tool_name='selenium'
        command=''
        function_name='fetchPutCallUrl'
        error_type=8
        storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, 0, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )
    
    return url_list

"""
@author: ZAHIR
DESC: Update put_call_urls extracted status after data extraction finished of that url
"""   
def updatePutCallDataExtractionStatus( company_id, company_symbol, expiry_date, created_at_date ):
    
    try: 
        nse_option_model.updatePutCallDataExtractionStatus( company_id, company_symbol, expiry_date, created_at_date )
        
    except Exception as e:
        print("fail to update status of put_call_urls table of " + company_symbol + " with expiry date : " + expiry_date)
        print(e)
        exc_type, exc_obj, exc_tb = sys.exc_info()
        fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
        print(str(exc_tb.tb_lineno))
        print(fname)
        
        custom_exception_msg = "fail to update status of put_call_urls table of " + company_symbol + " with expiry date : " + expiry_date
        log_table_primary_id=0
        tool_name='selenium'
        command=''
        function_name='updatePutCallDataExtractionStatus'
        error_type=9
        storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, 0, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )
        
"""
@author: ZAHIR
DESC: Check if stock_data_live table has todays any stock    
"""
def checkStocksLiveTodayDataPresent():
    
    API_URL = constant.API_ROOT + "/stock-data/check-today-live-data-inserted";
    r = requests.post(url = API_URL)
    
    if(r.text != 'present'):
        print('since todays live stock data is not present at : ' , now.strftime('%Y-%m-%d %H:%M:%S') , ' , so we are not fetching put call urls list')
        exit()
    
    