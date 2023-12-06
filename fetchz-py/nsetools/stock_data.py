from nsetools import Nse
nse = Nse()
import index
import constant
import stock_data_functionz
import exception_log
import time

# importing the requests library 
import requests 

import json



#company_list = stock_data_model.companiesList();
company_list = stock_data_functionz.companiesList();
#print(company_list);
#exit()

#stock_data_functionz.checkShareMarketRunningByPopularStock()

market_running = stock_data_functionz.checkShareMarketRunningByPopularStock();

#print('market_running or not: ', market_running)
#exit()

for row in company_list:
    
    time.sleep(1)
    
    company_id = row['id'];
    company_symbol = row['symbol'];
    company_name = row['name'];
    
#    exit();
    print("Id = ", company_id )
    print("symbol = ", company_symbol)
    print("name  = ", company_name)
    
    #fetch NSE DATA
    try:
        stk_data = nse.get_quote(company_symbol) # it's ok to use both upper or lower case for codes.
        
        if not stk_data: #if crawl data not available then ignore it
            continue
        
#        from pprint import pprint # just for neatness of display
#        pprint(stk_data)
        
        #stock_data_log_id = stock_data_model.insertStockDataLog(company_id, company_symbol, stk_data, market_running)        
        #print("stock_data_log_id  = ", stock_data_log_id)
        
        API_URL = constant.API_ROOT + "/pyapi/receive-py-fetch-stock-data";
        
        # data to be sent to api 
        data = {'company_id':company_id, 
                'company_symbol':company_symbol, 
                'whole_data':json.dumps(stk_data), 
                'exchange_name':'nse', 
                'market_running':market_running,
                'server':index.SERVER,} 
                
        # sending post request and saving response as response object 
        r = requests.post(url = API_URL, data = data)
        
        # extracting response text  
        pastebin_url = r.text 
        print("The pastebin URL is:%s"%pastebin_url) 
        
        
    except Exception as e:
        print('NO data for ', company_name)
        custom_exception_msg = "fail fetching trading data of company: " + company_name
        command = 'nse.get_quote("'+company_symbol+'")'
        exception_log.insert( company_id, 0, 'stock_data', '', 1, custom_exception_msg, str(e), 'nse-tools', command);
    
#    break;
    print('#####################')
    exit;          
          


