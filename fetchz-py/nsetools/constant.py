import index

import socket; 
hostname = socket.gethostname();

if (index.ENVIRONMENT == 'development'):        
    
    API_PROTOCOL = 'http://'
    API_HOST = API_PROTOCOL + "localhost"
    API_ROOT_FOLDER = '/trade'
    DATABASE_NAME = 'test'
    DATABASE_PASSWORD = ''
    
    if(hostname=='stackdev-H81M-S'):
        API_ROOT_FOLDER='/trd'
        
elif (index.ENVIRONMENT == 'testing'):
    
    if (index.FINAL_DATA_SERVER=='yes'):
        API_PROTOCOL = 'http://'
        API_HOST = API_PROTOCOL + "127.0.0.1"
        API_ROOT_FOLDER = '/pilot-trade'
        
    else:    
        API_PROTOCOL = 'https://'
        API_HOST = API_PROTOCOL + "pilot.ampstart.co"
        API_ROOT_FOLDER = ''
        
    DATABASE_NAME = 'pilot_trade'
    DATABASE_PASSWORD = 'ketoZ19#'
        
elif (index.ENVIRONMENT == 'production'):
    
    if (index.FINAL_DATA_SERVER=='yes'):
        API_PROTOCOL = 'http://'
        API_HOST = API_PROTOCOL + "127.0.0.1"
        API_ROOT_FOLDER = '/trade'
    
    else: 
        API_PROTOCOL = 'https://'
        API_HOST = API_PROTOCOL + "www.ampstart.co"
        API_ROOT_FOLDER = ''
    
    DATABASE_NAME = 'trade'
    DATABASE_PASSWORD = 'ketoZ19#'
    
    
API_ROOT = API_HOST + API_ROOT_FOLDER

FIRST_SERIAL_FAMOUS_STOCK = 'ASIANPAINT'
LAST_SERIAL_FAMOUS_STOCK = 'TCS'
