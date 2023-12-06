from selenium import webdriver
import pandas as pd
import time
import pickle
import requests
import json

import index
import sys
sys.path.insert(0, index.NSE_TOOL_LOC)
import constant


"""
@author: ZAHIR
DESC: Fetch list of urls 
"""
API_URL = constant.API_ROOT + "/Fetch_Nse_Cookies/fetch_nse_cookie_urls";

url_list_data = requests.post(url = API_URL)

url_list_json = url_list_data.text
url_list = json.loads(url_list_json)

#print(url_list); exit();

for data in url_list:
    
#    print(data['cookie_working']); 
    
    if( data['cookie_working'] == '1'):
        
        CURL_CHECK_API = constant.API_ROOT + data['curl_url'] + "/curl_yes";
        
        curl_return_data = requests.post(url = CURL_CHECK_API)
        
        print(curl_return_data.text);
        
        if( curl_return_data.text != 'no data' ):
            
            continue;
            
    
    #driver = webdriver.Chrome(chromedriver)
    driver = webdriver.Chrome('/var/www/html/software/chromedriver_linux64/chromedriver')
    driver.get('https://www.google.co.in/')
    
    driver.delete_all_cookies()
    time.sleep(2);
    #driver.implicitly_wait(30)
    
    #driver.get('https://www.nseindia.com/')
    #exit();
#    main_url = "https://www.nseindia.com/companies-listing/corporate-filings-insider-trading";
    main_url = data['main_url'] ;
    
    driver.get(main_url)
    driver.delete_all_cookies()
    time.sleep(5);
    driver.delete_all_cookies()
    
    driver.get(main_url)
    time.sleep(2);
    
#    df=pd.read_html(driver.find_element_by_id("table-CFinsidertrading").get_attribute('outerHTML'))
    
    pickle.dump( driver.get_cookies() , open("/var/www/html/trade/fetchz-py/nse-cookies/cookies.txt","wb"))
    
    cookies = pickle.load(open("/var/www/html/trade/fetchz-py/nse-cookies/cookies.txt", "rb"))
    
    cookies_list = (json.dumps(cookies))
    
    print(cookies_list);
    
    API_URL = constant.API_ROOT + "/Fetch_Nse_Cookies/cookies_hook";
    
    nse_cookies_url_id = data['id'] 
    
    payload = {"data" : cookies_list, "is_api"  : 0, "nse_cookies_url_id"  : nse_cookies_url_id, "url" : main_url}
    print(cookies_list)
    headers = {
        'content-type': "application/json",
        'cache-control': "no-cache"
        }
    
    response = requests.post(API_URL, data=payload)
    
    #print(response)
    
    y = json.loads(response.text)
    main_url_id = y["main_url_id"];
    print(y["main_url_id"])
    
    #/* Store Api Cookies start */ #
    time.sleep(2);
    driver.delete_all_cookies()
    time.sleep(5);
    driver.get(main_url)
#    api_nse_url = "https://www.nseindia.com/api/corporates-pit";
    api_nse_url = data['api_url'] ;
    driver.get(api_nse_url)
    
    pickle.dump( driver.get_cookies() , open("/var/www/html/stock-update/scrape-pyz/dividend/cookies.txt","wb"))
    
    api_cookies = pickle.load(open("/var/www/html/stock-update/scrape-pyz/dividend/cookies.txt", "rb"))
    
    api_cookies_list = (json.dumps(api_cookies))
    
    payload = {"data" : api_cookies_list, "is_api"  : 1, "nse_cookies_url_id"  : nse_cookies_url_id, "url" : api_nse_url, "main_url_id" : main_url_id}
    print(cookies_list)
    headers = {
        'content-type': "application/json",
        'cache-control': "no-cache"
        }
    
    response = requests.post(API_URL, data=payload)
    
    print('response from api')
    print(response.text)
    
    #/* Store Api Cookies end */ #

