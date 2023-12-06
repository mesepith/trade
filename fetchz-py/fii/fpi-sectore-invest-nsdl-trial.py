from datetime import date

today = date.today()

crawl_date = today.strftime("%B%d%Y")
print("crawl_date =", crawl_date)

import requests
import pandas as pd

import fii_functionz

url = 'https://www.fpi.nsdl.co.in/web/StaticReports/Fortnightly_Sector_wise_FII_Investment_Data/FIIInvestSector_'+crawl_date+'.html'

print(url)
#exit()

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.76 Safari/537.36', "Upgrade-Insecure-Requests": "1","DNT": "1","Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8","Accept-Language": "en-US,en;q=0.5","Accept-Encoding": "gzip, deflate"}
html = requests.get(url, headers=headers, timeout=30).content

df_list = pd.read_html(html)

count=0
        
tablez = df_list[count]

json_data = tablez.to_json(orient = "split")

print(json_data)

date = today.strftime("%b-%d-%Y")

fii_functionz.postNsdlSectoreInvestDataofFpi(date, json_data)  
