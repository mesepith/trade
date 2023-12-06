import mydbconnect
import exception_log
import json
import constant

def companiesList():
    mydbconnect.mycursor.execute("SELECT id, symbol, name FROM companies where status=1")
    
    myresult = mydbconnect.mycursor.fetchall()
    
    return myresult

def companiesListWithKey():
    
    dict_cursor = mydbconnect.mydb.cursor(dictionary=True)
    dict_cursor.execute("SELECT id, symbol, name FROM companies where status=1")
    
    myresult = dict_cursor.fetchall()
    
    return myresult


def insertStockDataLog(company_id, company_symbol, whole_data, market_running):
    dataInJsonObj = json.dumps(whole_data)
    created_at = mydbconnect.now.strftime('%Y-%m-%d %H:%M:%S')
    exchange_name='nse'
    
    sql = "INSERT INTO stock_data_log (company_id, company_symbol, data, exchange_name, market_running, created_at) VALUES (%s, %s, %s, %s, %s, %s)"
    val = (company_id, company_symbol, dataInJsonObj, exchange_name, market_running, created_at)
    mydbconnect.mycursor.execute(sql, val)
    
    mydbconnect.mydb.commit()
    
    
    stock_data_log_id = mydbconnect.mycursor.lastrowid
    insertStockData(whole_data, stock_data_log_id, company_id, company_symbol, market_running)
    
    return stock_data_log_id


def insertStockData(whole_data, stock_data_log_id, company_id, company_symbol, market_running):
    
    if(market_running):
        print('market running')
        stock_data_table = 'stock_data_live'
    else:
        stock_data_table = 'stock_data'
        print('market not running')
    
    import datetime; 
    
    created_at = mydbconnect.now.strftime('%Y-%m-%d %H:%M:%S')
    created_at_date = mydbconnect.today_date.strftime('%Y-%m-%d')
    exchange_name='nse'
    
    company_name = whole_data['companyName']
    series = whole_data['series']
    
    open_price = whole_data['open']
    close_price = whole_data['closePrice']
    day_high_price = whole_data['dayHigh']
    day_low_price = whole_data['dayLow']
    
    total_traded_volume = whole_data['totalTradedVolume']
    delivery_quantity = whole_data['deliveryQuantity']
    delivery_to_traded_quantity = whole_data['deliveryToTradedQuantity']
    
    total_buy_quantity = whole_data['totalBuyQuantity']
    total_sell_quantity = whole_data['totalSellQuantity']
    total_traded_value = whole_data['totalTradedValue']
    
    try:
        stock_date_time = datetime.datetime.strptime(whole_data['secDate'], '%d-%b-%Y %H:%M:%S');
        stock_date = stock_date_time.date();
        stock_time = stock_date_time.time();
         
    except Exception as e:
        stock_date_time = created_at
        stock_date = created_at_date
        stock_time = '00:00:01'
        print('######################## Trading Date time not available: '+ str(e))
        custom_exception_msg = "trading datetime not found of company: " + company_name 
        exception_log.insert( company_id, stock_data_log_id, 'stock_data_model', 'insertStockData', 2, custom_exception_msg, str(e), 'nse-tools', '');      

    print('stock_date_time', stock_date_time)
    print('stock_date', stock_date)
    print('stock_time', stock_time)
    
    sql = "INSERT INTO "+stock_data_table+" (stock_data_log_id, company_id, company_symbol, company_name, exchange_name, series, open_price, close_price, day_high_price, day_low_price, total_traded_volume, delivery_quantity, delivery_to_traded_quantity, total_buy_quantity, total_sell_quantity, total_traded_value, stock_date_time, stock_date, stock_time, created_at_date, created_at) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
    val = (stock_data_log_id, company_id, company_symbol, company_name, exchange_name, series, open_price, close_price, day_high_price, day_low_price, total_traded_volume, delivery_quantity, delivery_to_traded_quantity, total_buy_quantity, total_sell_quantity, total_traded_value, stock_date_time, stock_date, stock_time, created_at_date, created_at)
    mydbconnect.mycursor.execute(sql, val)
    
    mydbconnect.mydb.commit()
    
def checkTodayDataInserted(now):
    
    #/* If time is greater than 8 pm we will query by alphabetically last stock */
    if(now.hour>20):
        QUERY_BY_STOCK = constant.LAST_SERIAL_FAMOUS_STOCK
    else:
        QUERY_BY_STOCK = constant.FIRST_SERIAL_FAMOUS_STOCK
    
    #/* Check if famous stock data is inserted or not of todays date*/
    mydbconnect.mycursor.execute("SELECT count(*) AS ispresent FROM stock_data where company_symbol='"+QUERY_BY_STOCK+"' AND stock_date = '"+str(now.date())+"' AND stock_time = '00:00:00' AND status=1 order by id desc limit 1")
#    mydbconnect.mycursor.execute("SELECT count(*) AS ispresent FROM stock_data where company_symbol='tcs' AND stock_date = '2019-06-06' AND stock_time = '00:00:00' AND status=1 order by id desc limit 1")
    query_result = mydbconnect.mycursor.fetchall()
    
    is_present = query_result[0][0]
    print('is_presentz : ' ,QUERY_BY_STOCK , ' : ' , is_present)
#    exit();
    return is_present
#    return myresult