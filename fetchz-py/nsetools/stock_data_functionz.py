#*
#@author: ZAHIR    
#Check market is running or not    
#
import datetime; 
now = datetime.datetime.now()
import index
import requests
import constant
    
def checkRunningMarket():
    
    start_hour=9 # market start hour
    end_hour=17 # market end hour, we have set end hour to 5:30 PM as this scripts exectution time might gets increase 
    end_minute=30 #market end minute
    market_running = 0; # if market is running then 1 otherwise 0
    
    dayofweek = datetime.datetime.today().strftime("%A")
    
    if now.hour >= start_hour and now.hour <= end_hour and dayofweek !='Saturday' and dayofweek !='Sunday':
	
        if (now.hour == end_hour and now.minute >= 0 and now.minute <=end_minute) or (now.hour != end_hour):
	
            market_running = 1;
		
        else:
		
            market_running = 0;
    else :
        market_running = 0;
        
    return market_running


def checkShareMarketRunningByPopularStock():
    
    stock1 = checkStockData('axisbank')
    stock2 = checkStockData('tcs')
    
    
    stock_date_time1 = datetime.datetime.strptime(stock1['secDate'], '%d-%b-%Y %H:%M:%S');
    stock_time1 = stock_date_time1.time();
    
    stock_date_time2 = datetime.datetime.strptime(stock2['secDate'], '%d-%b-%Y %H:%M:%S');
    stock_time2 = stock_date_time2.time(); 
    
    market_running = 0
    
    if( stock1['closePrice'] != stock2['closePrice'] and stock_time1 == stock_time2 and now.date() == stock_date_time1.date() and now.date() == stock_date_time2.date() ):#during running market, close price is zero of all share and during close market , time is 00:00:00 
        """We also checked if date of stocks is same with today's date"""
        
        market_running = 0
        
    elif(now.date() == stock_date_time1.date() and now.date() == stock_date_time2.date() ):
        
        market_running = 1
        
    else:
        print('')
        print('#########################################################################################################################################################################')
        print(now, ' : checkShareMarketRunningByPopularStock condition is not satisfied. stock : ' , stock1['symbol'] , ' ; stock date time : ' , stock_date_time1, ' : data : ', stock1)
        print('')
        print(now, ' : checkShareMarketRunningByPopularStock condition is not satisfied. stock : ' , stock2['symbol'] , ' ; stock date time : ' , stock_date_time2, ' : data : ', stock2)
        print('#########################################################################################################################################################################')
        print('')
        exit() 
    #/* When market is not running and the time is in between 9 am to 9:30 am then stop the script. This will prevent to insert duplicate data of previous day*/    
    if( market_running == 0 ):
        start_hour=9 # market start hour
        if (now.hour == start_hour and now.minute <= 30 ):
            print('####### Market is not running and time is ' , now.hour , ' : ' , now.minute)
            exit()
#        elif( now.hour == 17 or now.hour == 18 or now.hour == 19 or now.hour == 20 or now.hour == 21 or now.hour == 22 or now.hour == 23):            
        elif( now.hour >= 17):
            """ When time is greater than equal to 5pm , check todays final data of stock is inserted or not """            
            
            # /* When time is 5pm, 6pm, 7pm, 8pm, 9pm, 10pm, 11pm Check todays final data of stock is inserted or not into the database */
            if(index.FINAL_DATA_SERVER=='yes'):
                import stock_data_model
                is_today_data_inserted = stock_data_model.checkTodayDataInserted(now)
                
            else:
                TODAY_FAMOUS_STOCK_URL = constant.API_ROOT + "/stock-data/check-today-famous-stock-data-inserted";
                r = requests.post(url = TODAY_FAMOUS_STOCK_URL)
    
                is_today_data_inserted = r.text
                print('inside else of is_today_data_inserted')
                
            print('market_running : ', market_running)
            if( int(is_today_data_inserted) > 0 ): # is_today_data_inserted is > 0 means we found todays data into the database
                print( ' today data inserted ')
                exit()
    elif( market_running == 1 and now.hour >=17 and now.hour < 20 ):
        #/* do not insert any data for stock_data_live table after 5pm */
        print('Tims is greater than equal to 17 , but data is showing that market is running')
        exit()
        
    elif(market_running == 1 and now.hour >=20 and now.date() == stock_date_time1.date() and now.date() == stock_date_time2.date() ):
        #/*if data is showing that market is running, but in reality market is not running
        if(index.FINAL_DATA_SERVER=='yes'):
            import stock_data_model
            is_today_data_inserted = stock_data_model.checkTodayDataInserted(now)
            
        else:
            TODAY_FAMOUS_STOCK_URL = constant.API_ROOT + "/stock-data/check-today-famous-stock-data-inserted";
            r = requests.post(url = TODAY_FAMOUS_STOCK_URL)

            is_today_data_inserted = r.text
            print('inside else of is_today_data_inserted')
            
        print('market_running : ', market_running)
        if( int(is_today_data_inserted) > 0 ): # /* is_today_data_inserted is > 0 means we found todays data into the database
            print( ' today data inserted ')
            exit()
        else:#/*in else means todays data is not present in database table and since in reality market is not running so make market_running = 0
            market_running = 0;
        
    return market_running
 
def checkStockData(stock_name):
    try:
        from nsetools import Nse
        nse = Nse()
        
        data = nse.get_quote(stock_name)
#        print(data); exit()
        return data       
        
    except Exception as e:
        import exception_log
        print('NO data for ', stock_name)
        custom_exception_msg = "fail fetching trading data of company: " + stock_name
        command = 'nse.get_quote("'+stock_name+'")'
        exception_log.insert( 0, 0, 'stock_data_functionz', 'checkStockData', 1, custom_exception_msg, str(e), 'nse-tools', command);

"""
@author: ZAHIR
DESC: Get company list
"""        
def companiesList():
    
    if(index.FINAL_DATA_SERVER=='yes'):
        import stock_data_model
        company_list = stock_data_model.companiesListWithKey();
        return company_list
    else:
        import json
        COMPANY_DATA_URL = constant.API_ROOT + "/companies/fetch-all-data";
        company_list_return = requests.post(url = COMPANY_DATA_URL)
        company_list_json = company_list_return.text
        company_listz = json.loads(company_list_json)
        return company_listz
        
        
        
    
    

    

