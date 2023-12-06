import pandas as pd
url = 'https://www.fpi.nsdl.co.in/web/StaticReports/Fortnightly_Sector_wise_FII_Investment_Data/FIIInvestSector_April302019.html'

for i, df in enumerate(pd.read_html(url)):
    df.to_csv('myfile_%s.csv' % i)
    print(i)
    if i==0: break;
