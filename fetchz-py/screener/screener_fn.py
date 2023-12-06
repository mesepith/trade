import index
def getChromeDriver():
    
    from selenium import webdriver
    
    if (index.ENVIRONMENT == 'development'):
        
        d = webdriver.Chrome('/var/www/html/software/chromedriver_linux64/chromedriver')
        
    elif (index.ENVIRONMENT == 'testing'):
        
        from selenium.webdriver.chrome.options import Options 
        chrome_options = Options()  
        chrome_options.add_argument('--no-sandbox')
        chrome_options.add_argument("--headless")  
        
        d = webdriver.Chrome('/usr/bin/chromedriver',   chrome_options=chrome_options)  

    elif (index.ENVIRONMENT == 'production'):
        
        from selenium.webdriver.chrome.options import Options 
        chrome_options = Options()  
        chrome_options.add_argument('--no-sandbox')
        chrome_options.add_argument("--headless")  
        
        d = webdriver.Chrome('/usr/bin/chromedriver',   chrome_options=chrome_options)  
        
    return d