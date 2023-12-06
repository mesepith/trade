import index
import sys
sys.path.insert(0, index.NSE_TOOL_LOC)
import constant
import requests

    
def postRbiMarketTrends( json_data ):
    
    API_URL = constant.API_ROOT + "/Rbi_Fetch/marketTrend";
    
    data = {'json_data':json_data, 
            'server':index.SERVER,} 
    
    # sending post request and saving response as response object 
    r = requests.post(url = API_URL, data = data)
    
    # extracting response text  
    pastebin_url = r.text 
    print("The pastebin URL is:%s"%pastebin_url)


    
    
    
