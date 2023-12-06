import fii_functionz
from collections import defaultdict

import requests
import pandas as pd

import sys, os

d = fii_functionz.getChromeDriver()

d.get("https://www.fpi.nsdl.co.in/web/Reports/FPI_Fortnightly_Selection.aspx")

try:
    dd_date_arr = defaultdict(dict)
    
    select_box = d.find_element_by_name("ddlfortnighly") 
    options = [x for x in select_box.find_elements_by_tag_name("option")]
    
    dd_date_count=1
            
    for element in options:
        
        data_url = element.get_attribute("value").replace("~", "https://www.fpi.nsdl.co.in/web")
        
        date = element.text;
                
        dd_date_arr[dd_date_count]['url'] = data_url
        dd_date_arr[dd_date_count]['date'] = date
        dd_date_count+=1 
      
        
    for each_dd_date_key in dd_date_arr:
        
        """
        To store only latest date data uncomment below mentioned line
        or if you want to store all dates data then comment it
        """
        if(each_dd_date_key>1): break;
        
        print(' date : ' , dd_date_arr[each_dd_date_key]['date'] );
        
        data_url = dd_date_arr[each_dd_date_key]['url']
        date = dd_date_arr[each_dd_date_key]['date']
        d.get(data_url)
        
        html = requests.get(data_url).content

        df_list = pd.read_html(html)
        
        count=0
        
        tablez = df_list[count]
        count+=1
        
        try:
            json_data = tablez.to_json(orient = "split")
            fii_functionz.postNsdlSectoreInvestDataofFpi(date, json_data)            
        except Exception as e:
            print('fail fething sectorwise investment table of fpi/fii from nsdl : ' , e)
            exc_type, exc_obj, exc_tb = sys.exc_info()
            fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
            
            custom_exception_msg = "fail fething sectorwise investment table of fpi/fii from nsdl "
            log_table_primary_id=0
            tool_name='selenium'
            command=''
            function_name=''
            company_id=0
            error_type=10
            fii_functionz.storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )
            
    
except Exception as e:
    print('Error getting date from dropdown for fetching fpi net investment data from nsdl, ' + str(e))
    exc_type, exc_obj, exc_tb = sys.exc_info()
    fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
    
    custom_exception_msg = "error getting date from dropdown for fetching fpi net investment data from nsdl "
    log_table_primary_id=0
    tool_name='selenium'
    command=''
    function_name=''
    company_id=0
    error_type=11
    fii_functionz.storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )