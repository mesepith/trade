import requests
import pandas as pd

import rbi_functionz

url = 'https://www.rbi.org.in/'

print(url)
#exit()

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.76 Safari/537.36', "Upgrade-Insecure-Requests": "1","DNT": "1","Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8","Accept-Language": "en-US,en;q=0.5","Accept-Encoding": "gzip, deflate"}
html = requests.get(url, headers=headers, timeout=30).content

df_list = pd.read_html(html)

print(df_list)

count=4
        
tablez = df_list[count]

json_data = tablez.to_json(orient = "split")

print(json_data)

rbi_functionz.postRbiMarketTrends(json_data)  
