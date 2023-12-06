import index
import sys
sys.path.insert(0, index.NSE_TOOL_LOC)
import constant
import requests

def fii_dii_sum_activity( fii_dii_activity_data ):
    
    API_URL = constant.API_ROOT + "/fii-dii/total-invest-of-trading-activity";
        
        # data to be sent to api 
#    data = {'fii_data':fii_data, 
#            'dii_data':dii_data, 
#            'server':index.SERVER,} 
    data = {'fii_dii_activity_data':fii_dii_activity_data, 
            'server':index.SERVER,} 
            
    # sending post request and saving response as response object 
    r = requests.post(url = API_URL, data = data)
    
    # extracting response text  
    pastebin_url = r.text 
    print("The pastebin URL is:%s"%pastebin_url) 
    
def getChromeDriver():
    
    from selenium import webdriver
    
    if (index.ENVIRONMENT == 'development'):
        
        d = webdriver.Chrome('/var/www/html/software/chromedriver_linux64/chromedriver')
        
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

def postNsdlSectoreInvestDataofFpi( date, json_data ):
    
    API_URL = constant.API_ROOT + "/fii-dii/get-nsdl-sectore-invest-data-of-fpi-fii";
    
    data = {'json_data':json_data, 
            'date':date,
            'server':index.SERVER,} 
    
    # sending post request and saving response as response object 
    r = requests.post(url = API_URL, data = data)
    
    # extracting response text  
    pastebin_url = r.text 
    print("The pastebin URL is:%s"%pastebin_url)
    

def storeErrorException( system_exception_type_desc, page_name, line_no, function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, error_system_msg, tool_name, command ):
    import exception_log
    exception_log.insert( company_id, log_table_primary_id, page_name, function_name, error_type, custom_exception_msg, error_system_msg, tool_name, command, system_exception_type_desc, line_no )
    print()
    
def fii_derivative_data( derivative_data ):
    
    API_URL = constant.API_ROOT + "/Fii_Dii/insertFiiDerivativeData";
    
    print(API_URL)
    
    data = {'fii_derivative_data':derivative_data, 
            'server':index.SERVER,} 
            
    # sending post request and saving response as response object 
    r = requests.post(url = API_URL, data = data)
    
    # extracting response text  
    pastebin_url = r.text 
    print("The pastebin URL is:%s"%pastebin_url) 
    

def fii_cash_nsdl_data( cash_data ):
    
    API_URL = constant.API_ROOT + "/Fii_Dii/insertFiiCashData";
    
    print(API_URL)
    
    data = {'fii_cash_data':cash_data, 
            'server':index.SERVER,} 
            
    # sending post request and saving response as response object 
    r = requests.post(url = API_URL, data = data)
    
    # extracting response text  
    pastebin_url = r.text 
    print("The pastebin URL is:%s"%pastebin_url) 
    
    
    
