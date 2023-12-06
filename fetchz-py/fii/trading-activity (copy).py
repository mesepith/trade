"""
https://www1.nseindia.com/products/content/equities/equities/fii_dii_market_today.htm
"""

import requests
import pandas as pd
import fii_functionz


def extractTableData(url):
    headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.76 Safari/537.36', "Upgrade-Insecure-Requests": "1","DNT": "1","Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8","Accept-Language": "en-US,en;q=0.5","Accept-Encoding": "gzip, deflate"}
    html = requests.get(url, headers=headers, timeout=30).content
    
    df_list = pd.read_html(html)
    
    count=0
    
    for each_table in df_list:
        
        tablez = df_list[count]
        count+=1
        
        try:
            activity_data = tablez.to_json(orient = "split")                
#            print (activity_data)
            return activity_data
            
        except Exception as e:
            print('fail : ' , e)
            return False
            
            
fii_data = extractTableData('https://www1.nseindia.com/products/dynaContent/equities/equities/htms/fiiEQ.htm')
dii_data = extractTableData('https://www1.nseindia.com/products/dynaContent/equities/equities/htms/DiiEQ.htm')


fii_functionz.fii_dii_sum_activity(fii_data, dii_data)


