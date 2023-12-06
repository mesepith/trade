ENVIRONMENT = 'development';

SERVER = 'scaleway-1'

FINAL_DATA_SERVER = 'yes'

if(ENVIRONMENT=='development'):
    
    import socket; 
    hostname = socket.gethostname();
    
    ROOT_FOLDER='/var/www/html/trade'
    
    if(hostname=='stackdev-H81M-S'):
        ROOT_FOLDER='/var/www/html/trd'
    
elif(ENVIRONMENT=='testing'):
    ROOT_FOLDER='/var/www/html/pilot-trade'
elif(ENVIRONMENT=='production'):
    ROOT_FOLDER='/var/www/html/trade'
    
NSE_TOOL_LOC = ROOT_FOLDER + '/' + 'fetchz-py/nsetools'