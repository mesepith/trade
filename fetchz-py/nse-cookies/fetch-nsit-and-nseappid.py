import requests
import index
import sys
sys.path.insert(0, index.NSE_TOOL_LOC)
import constant

# Create a session
session = requests.Session()

# Set a User-Agent
session.headers.update({'User-Agent': 'Mozilla/5.0'})

# Make a GET request to the main URL
url = "https://www.nseindia.com/get-quotes/equity?symbol=GAIL"
response = session.get(url)

# Get the cookies
cookies = response.cookies

# Access the 'nsit' and 'nseappid' cookies
nsit = cookies.get('nsit')
nseappid = cookies.get('nseappid')

#print(f"nsit: {nsit}, nseappid: {nseappid}")

payload = {"nsit" : nsit, "nseappid"  : nseappid}

#print(payload)
headers = {
	'content-type': "application/json",
	'cache-control': "no-cache"
	}

API_URL = constant.API_ROOT + "/Fetch_Nse_Cookies/insertNsitAndnseappid";
response = requests.post(API_URL, data=payload)

print('response from api')
print(response.text)
