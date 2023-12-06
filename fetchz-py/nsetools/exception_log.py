import mydbconnect
import constant

def forbiddenAlert(company_id, error_system_msg):
    import requests
    import index
    
    try:
        API_URL = constant.API_ROOT + "/notify/nse-forbidden";
        
        data = {'hostname':constant.hostname, 
                'company_id':company_id, 
                'error_system_msg':error_system_msg.strip(), 
                'server':index.SERVER,} 
        
        r = requests.post(url = API_URL, data = data)
    
        print(r.text)
        
    except Exception as e:
        print('Error while sending forbidden by nse mail')
        

def insert( company_id, stock_data_log_id, page_name, function_name, error_type, custom_exception_msg, error_system_msg, tool_name, command, system_exception_type_desc='', line_no=0):
    #error_type=1= trading data not found, 2= trading datetime not found, 3= fail to fail fetching put call data, 4= fail on searching put call data of company, 
    #5= fail to fetch table data of put call table, 6= Issue with the sending Put Call Table Data to Api, 7= Fail to store put call url, 
    #8= fail to fetch put call url from put_call_urls table with status extracted=0, 9= fail to update status of put_call_urls table
    #10= fail fething sectorwise investment table of fpi/fii from nsdl, 11 = Error getting date from dropdown for fetching fpi net investment data from nsdl
    
    created_at = mydbconnect.now.strftime('%Y-%m-%d %H:%M:%S')
    
    sql = "INSERT INTO exception_log (company_id, stock_data_log_id, page_name, function_name, error_type, custom_exception_msg, error_system_msg, tool_name, command, system_exception_type_desc, line_no, created_at) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
    val = (company_id, stock_data_log_id, page_name, function_name, error_type, custom_exception_msg, error_system_msg, tool_name, command, system_exception_type_desc, line_no, created_at)
    mydbconnect.mycursor.execute(sql, val)
    
    mydbconnect.mydb.commit()
    
    if(error_system_msg.strip()=='HTTP Error 403: Forbidden'):
        forbiddenAlert(company_id, error_system_msg)
        exit()
    