"""
https://www.nseindia.com/all-reports/historical-equities-fii-fpi-dii-trading-activity
"""

import pandas as pd
import fii_functionz


def extractTableData(url):
    
    df_list = pd.read_html(url)
    count=0
    
    for each_table in df_list:
        
        tablez = df_list[count]
        count+=1
        
        try:
            fii_dii_activity_data = tablez.to_json(orient = "split") 
            fii_functionz.fii_dii_sum_activity(fii_dii_activity_data)  
            
        except Exception as e:
            print('fail : ' , e)
            return False
            
cmd = '''curl 'https://www.nseindia.com/all-reports/historical-equities-fii-fpi-dii-trading-activity' -H 'authority: www.nseindia.com' -H 'cache-control: max-age=0' -H 'upgrade-insecure-requests: 1' -H 'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/79.0.3945.79 Chrome/79.0.3945.79 Safari/537.36' -H 'sec-fetch-user: ?1' -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9' -H 'sec-fetch-site: same-origin' -H 'sec-fetch-mode: navigate' -H 'referer: https://www.nseindia.com/all-reports/historical-equities-fii-fpi-dii-trading-activity' -H 'accept-encoding: gzip, deflate, br' -H 'accept-language: en-GB,en-US;q=0.9,en;q=0.8' -H 'if-none-match: W/"30e8a-0DhV/3C4yq889dVqTPbQf9ApjhE"' --connect-timeout 10 --max-time 30 --compressed''';

import subprocess

proc = subprocess.Popen([cmd], stdout=subprocess.PIPE, shell=True)
(out, err) = proc.communicate()

extractTableData(out);

exit();

