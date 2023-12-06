"""
https://www.fpi.nsdl.co.in/web/Reports/Monthly.aspx
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
            
            if(count==1):
                cash_data = tablez.to_json(orient = "split")    
                print('########')            
                print (cash_data)
                fii_functionz.fii_cash_nsdl_data(cash_data)
        
            
        except Exception as e:
            print('fail : ' , e)
            return False
            
            
fii_data = extractTableData('https://www.fpi.nsdl.co.in/web/Reports/Monthly.aspx')
