import index
import sys
sys.path.insert(0, index.NSE_TOOL_LOC)
import mydbconnect

def inserPutOptionData(put_option_data):
    print (put_option_data)
    print()
    print('############################################')
    print()  
#    exit()
    
def storePutCallUrl( url, company_id, company_symbol, expiry_date , created_at, created_at_date, created_at_time):
    
    sql = "INSERT INTO put_call_urls (company_id, company_symbol, url, expiry_date, created_at, created_at_date, created_at_time) VALUES (%s, %s, %s, %s, %s, %s, %s)"
    val = (company_id, company_symbol, url, expiry_date, created_at, created_at_date, created_at_time)
    mydbconnect.mycursor.execute(sql, val)
    
    mydbconnect.mydb.commit()
    
    
def fetchPutCallUrl(today_date):
    
    dict_cursor = mydbconnect.mydb.cursor(dictionary=True)
    
#    dict_cursor.execute("SELECT *  FROM put_call_urls where created_at_date = '"+str(today_date)+"' AND extracted=0 AND status=1 ")
    dict_cursor.execute("SELECT *  FROM put_call_urls where created_at_date = '2020-01-07' AND extracted=1 AND status=1 ") #uncomment above code and comment this line code as of now option chain is unable to crawl
    
    myresult = dict_cursor.fetchall()
    
    return myresult

def updatePutCallDataExtractionStatus( company_id, company_symbol, expiry_date, created_at_date ):
    
    sql = "UPDATE put_call_urls SET extracted = '1' WHERE company_id = '"+str(company_id)+"' AND company_symbol = '"+company_symbol+"' AND expiry_date = '"+str(expiry_date)+"' AND created_at_date = '"+str(created_at_date)+"' AND status=1 "

    mydbconnect.mycursor.execute(sql)
    
    mydbconnect.mydb.commit()
    
    
def checkTodayPutCallLogDataExtracted( today_date ):
    
#    dict_cursor.execute("SELECT *  FROM put_call_urls where created_at_date = '"+str(today_date)+"' AND extracted=0 AND status=1 ")
    mydbconnect.mycursor.execute("SELECT count(*) AS non_extracted_url_count FROM put_call_urls where extracted=0 AND created_at_date = '"+str(today_date)+"' AND status=1 order by id desc limit 1")
    
    query_result = mydbconnect.mycursor.fetchall()
    
    non_extracted_url_count = query_result[0][0]
    
    return non_extracted_url_count
    