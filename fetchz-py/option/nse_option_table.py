import nse_option_functionz

"""
Uncomment below line after test
"""
#nse_option_functionz.checkStockTodayDataPresent()
#nse_option_functionz.checkTodayPutCallUrlsExtracted()

url_list = nse_option_functionz.fetchPutCallUrl()

print('url list to be extracted: ' , len(url_list))

if(len(url_list) > 0 ):

    for row in url_list:
        
        print ( str(row["company_symbol"]) )
        
        nse_option_functionz.getPutOptionTableData(row["url"], row["company_id"], row["company_symbol"], str(row["expiry_date"]) )
        
        nse_option_functionz.updatePutCallDataExtractionStatus( row["company_id"], row["company_symbol"], row["expiry_date"], row["created_at_date"] )
        
        
    #nse_option_functionz.processPutCallData('start')

else:
    
    print('No unextracted url available')