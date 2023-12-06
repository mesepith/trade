import screener_fn
d = screener_fn.getChromeDriver()

import time
    
d.get("https://www.screener.in/login/")

username = 'zahiralam19cse@gmail.com'
password = 'tradeZ19#'


inputElement = d.find_element_by_id("id_username")
inputElement.send_keys(username)

inputElement = d.find_element_by_id("id_password")
inputElement.send_keys(password)

elem = d.find_element_by_class_name('button-primary').click()


#Search Button 
#inputElement = d.find_element_by_xpath('//*[@id="top-nav-search"]/div/input'); inputElement.send_keys('TCS')


d.get("https://www.screener.in/company/TCS/consolidated/")


time.sleep(2)

import pandas as pd
url = r'https://www.screener.in/company/TCS/consolidated/'
tables = pd.read_html(url) # Returns list of all tables on page
#sp500_table = tables[0] # Select table of interest

for each_table in tables:
    
    print('############################')
    print('')
    print(each_table)
    table_data = each_table.to_json(orient = "split")
    
    print()
    print()
    print (table_data)
    
    
peers_url = 'https://www.screener.in/api/company/6599230/peers/'
peers_tables = pd.read_html(peers_url)

for each_table in peers_tables:
    
    print('############################ Peers')
    print('')
    print(each_table)
    table_data = each_table.to_json(orient = "split")
    
    print()
    print()
    print (table_data)
    
    
# Get text from all elements
text_contents = [el.text for el in d.find_elements_by_xpath('//*[@id="content-area"]/section[1]')]
# Print text
print('********************************')
for text in text_contents:
    print('-----------------------------------')
    print (text)
    
text_contents = [el.text for el in d.find_elements_by_xpath('//*[@id="analysis"]')]
# Print text
print('**************** Pros and Cons ****************')
for text in text_contents:
    print('-----------------------------------')
    print (text)
