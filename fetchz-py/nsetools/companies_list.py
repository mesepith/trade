from nsetools import Nse
nse = Nse()
all_stock_codes = nse.get_stock_codes()
import exception_log

import companies_model
for key in all_stock_codes:
    print('')
    print (key, all_stock_codes[key])
    
    if(key!="SYMBOL"):
        try:
            exchange_name='nse'
            is_presentz = companies_model.checkDuplicateCompany(key, all_stock_codes[key], exchange_name)
            
            if(is_presentz>0):
                print('already present in database : ', key )
                continue;
            
            companies_model.insertCompaiesList(key, all_stock_codes[key], exchange_name)
        except Exception as e:
            print('fail fetching companies name and symbol of ', key)
            command = 'nse.get_stock_codes()'
            custom_exception_msg = "fail fetching companies name and symbol of : " + key
            exception_log.insert( 0, 0, 'companies_list', '', 1, custom_exception_msg, str(e), 'nse-tools', command);
#            exit()