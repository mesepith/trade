#/* Guide: https://stackoverflow.com/questions/10556048/how-to-extract-tables-from-websites-in-python/44506462 */

from selenium.webdriver.support.select import Select
from collections import defaultdict

import sys, os

import time

import nse_option_functionz

nse_option_functionz.checkStockTodayDataPresent()
nse_option_functionz.checkTodayPutCallLogDataPresent()


d = nse_option_functionz.getChromeDriver()


#d = webdriver.PhantomJS()

d.get("https://nseindia.com/live_market/dynaContent/live_watch/option_chain/optionKeys.jsp")

def searchingStocksPutCallData(company_id, company_symbol ):
    
#    company_symbol = 'axisbank'
    dd_date_arr = defaultdict(dict)
    try:
    
        inputElement = d.find_element_by_id("underlyStock")
        inputElement.send_keys(company_symbol)
        
        
        #/* Another method to click on Go buttond.find_element_by_css_selector("input[src='/live_market/resources/images/gobtn.gif']").click();
        d.execute_script("goBtnClick('stock');")
        
        
        select_box = d.find_element_by_name("date") 
        options = [x for x in select_box.find_elements_by_tag_name("option")]
        
        dd_date_count=1
        
        for element in options:
            if(element.get_attribute("value")=='select'):
                continue
            dd_date_arr[dd_date_count] = element.get_attribute("value")
            dd_date_count+=1  
        
    except Exception as e:
        exc_type, exc_obj, exc_tb = sys.exc_info()
        fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
        custom_exception_msg = "fail on searching put call data of company: " + company_symbol
        log_table_primary_id=0
        tool_name='selenium'
        command=''
        function_name='searchingStocksPutCallData'
        error_type=4
        nse_option_functionz.storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )
        d.get("https://nseindia.com/live_market/dynaContent/live_watch/option_chain/optionKeys.jsp")
           
        
    if( len(dd_date_arr)> 0 ):
        
        for each_dd_date_key in dd_date_arr:  
            try:
#                print(dd_date_arr[each_dd_date_key])
                select_fr = Select(d.find_element_by_id("date"))
                select_fr.select_by_value(dd_date_arr[each_dd_date_key])
                
                nse_option_functionz.getPutOptionTableData(d.current_url, company_id, company_symbol, dd_date_arr[each_dd_date_key] )
                
                time.sleep(5)
            except Exception as e:
                
                exc_type, exc_obj, exc_tb = sys.exc_info()
                fname = os.path.split(exc_tb.tb_frame.f_code.co_filename)[1]
                
                custom_exception_msg = "fail fetching put call data of company: " + company_symbol
                log_table_primary_id=0
                tool_name='selenium'
                command=''
                function_name='searchingStocksPutCallData'
                error_type=3
                nse_option_functionz.storeErrorException( str(exc_type), str(fname), str(exc_tb.tb_lineno), function_name, error_type, company_id, log_table_primary_id, custom_exception_msg, str(e), tool_name, command )
    
    elif( len(dd_date_arr) == 0):
        print('00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000')
        print('No Put data for : ', company_symbol )
        print('00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000')
        print(' ')
        time.sleep(1)
                
    
        
        
#    exit()
    
#searchingStocksPutCallData(20, 'ACC')
#searchingStocksPutCallData(23, 'ADANIENT')
#searchingStocksPutCallData(151, 'AXISBANK')
#searchingStocksPutCallData(692, 'ITC')
#searchingStocksPutCallData(1443, 'TCS')

company_list = nse_option_functionz.companyList()
for row in company_list:
    company_id = row['id'];
    company_symbol = row['symbol'];
    company_name = row['name'];
    
    print("Id = ", company_id )
    print("symbol = ", company_symbol)
    print("name  = ", company_name)
    
    searchingStocksPutCallData(company_id, company_symbol)

d.close()

nse_option_functionz.processPutCallData('start')
    
    

    


