import mydbconnect
    
def insertCompaiesList(symbol, company_name, exchange_name):
    
    created_at = mydbconnect.now.strftime('%Y-%m-%d %H:%M:%S')
    sql = "INSERT INTO companies (symbol, name, exchange_name, created_at) VALUES (%s, %s, %s, %s)"
    val = (symbol, company_name, exchange_name, created_at)
    mydbconnect.mycursor.execute(sql, val)
    
    mydbconnect.mydb.commit()
    
"""
@author: ZAHIR
DESC: Check if company is already inserted
"""

def checkDuplicateCompany(symbol, company_name, exchange_name):

    sql = "SELECT count(*) AS ispresent FROM companies where symbol = %s AND name= %s AND exchange_name= %s AND status= %s"
    val = (symbol, company_name, exchange_name, '1')
    
    mydbconnect.mycursor.execute(sql, val)
    
    query_result = mydbconnect.mycursor.fetchall()
    
    is_present = query_result[0][0]
    
    return is_present
    
    
