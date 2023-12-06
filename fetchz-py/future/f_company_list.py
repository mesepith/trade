"""
https://www.nseindia.com/products-services/equity-derivatives-list-underlyings-information
"""

import index
import sys
sys.path.insert(0, index.NSE_TOOL_LOC)
import constant
import requests
import pandas as pd
#import fii_functionz


def extractTableData(url):
    
    df_list = pd.read_html(url)
    count=0
    
    for each_table in df_list:
        
        tablez = df_list[count]
        count+=1
        
        try:                        
            
            company_list = tablez.to_json(orient = "split") 
            
            print('company_list')
            print('')
            
            print(company_list)
            
            print('')
            print('')
            
            API_URL = constant.API_ROOT + "/future/companies/insert";
            
            data = {'company_list':company_list} 
            
            r = requests.post(url = API_URL, data = data)
            
            # extracting response text  
            pastebin_url = r.text 
            print("The pastebin URL is:%s"%pastebin_url) 
            
        except Exception as e:
            print('fail : ' , e)
            return False
            
cmd = '''curl 'https://www.nseindia.com/products-services/equity-derivatives-list-underlyings-information' -H 'authority: www.nseindia.com' -H 'cache-control: max-age=0' -H 'upgrade-insecure-requests: 1' -H 'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/79.0.3945.79 Chrome/79.0.3945.79 Safari/537.36' -H 'sec-fetch-user: ?1' -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9' -H 'sec-fetch-site: same-origin' -H 'sec-fetch-mode: navigate' -H 'referer: https://www.nseindia.com/products-services/equity-derivatives-list-underlyings-information' -H 'accept-encoding: gzip, deflate, br' -H 'accept-language: en-GB,en-US;q=0.9,en;q=0.8' -H 'if-none-match: W/"30e8a-0DhV/3C4yq889dVqTPbQf9ApjhE"' --connect-timeout 10 --max-time 30 --compressed''';

import subprocess

proc = subprocess.Popen([cmd], stdout=subprocess.PIPE, shell=True)
(out, err) = proc.communicate()

extractTableData(out);

exit();

