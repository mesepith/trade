import mysql.connector
import datetime, pytz
import constant

now = datetime.datetime.now(pytz.timezone('Asia/Kolkata'))
today_date = datetime.datetime.now().date()

try:

    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        passwd=constant.DATABASE_PASSWORD,
        database=constant.DATABASE_NAME)
    
    mycursor = mydb.cursor()
    
except Exception as e:
    print('Error while connecting to mysql')
    print(e)
    
    import requests
    import index
    API_URL = constant.API_ROOT + "/notify/mysql-connect-fail";
        
    data = {'hostname':constant.hostname, 
            'error_system_msg':e, 
            'server':index.SERVER,} 
    
    r = requests.post(url = API_URL, data = data)
    
    exit()