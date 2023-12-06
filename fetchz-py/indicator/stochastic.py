import requests

import json
import numpy as np

from matplotlib import pyplot as plt

chart_data_url = "http://localhost/trade/Chart_Fetch_Contr/gtochasticRsi";
chart_data_return = requests.post(url = chart_data_url)



chart_data_json = chart_data_return.text



chart_data = json.loads(chart_data_json)

#print(np.array(chart_data['high']))
#exit();

array_time = np.array(chart_data['time']);
print('array_time ###################')
print(array_time)

array_high = np.array(chart_data['high']);
print("High Array size",array_high.size)

array_low = np.array(chart_data['low']);
print("Low Array size",array_low.size)

array_close = np.array(chart_data['close']);
print("array_close")
print(array_close)
print("Close Array size",array_close.size)

#exit();

"""
Finding Highest Values within k Periods start
"""

y=0
z=0
# kperiods are 14 array start from 0 index
kperiods=13
array_highest=[]
for x in range(0,array_high.size-kperiods):
	z=array_high[y]
	for j in range(0,kperiods):
		if(z<array_high[y+1]):
			z=array_high[y+1]
		y=y+1
	# creating list highest of k periods
	array_highest.append(z)
  # skip one from starting after each iteration
	y=y-(kperiods-1)
print("Highest array size",len(array_highest))
print(array_highest)

"""
Finding Highest Values within k Periods End
"""


"""
Finding Lowest Values within k Periods Start
"""

y=0
z=0
array_lowest=[]
for x in range(0,array_low.size-kperiods):
	z=array_low[y]
	for j in range(0,kperiods):
		if(z>array_low[y+1]):
			z=array_low[y+1]
		y=y+1
	# creating list lowest of k periods
	array_lowest.append(z)
  # skip one from starting after each iteration
	y=y-(kperiods-1)
print("Lowest array size",len(array_lowest))
print(array_lowest)

"""
Finding Lowest Values within k Periods Start
"""

"""
Finding %K Line Values Start
"""

Kvalue=[]
for x in range(kperiods,array_close.size):
    
    print('x ', x)
    print('kperiods')
    print(kperiods)
    print('array_close[x]')
    print(array_close[x])
    print('array_lowest[x-kperiods]')
    print(array_lowest[x-kperiods])
    print('array_highest[x-kperiods]')
    print(array_highest[x-kperiods])
    
    part_one = float( array_close[x] ) - float( array_lowest[x-kperiods] );
    print('part_one');
    print(part_one);
    
    part_two = part_one * 100;
    print('part_two');
    print(part_two);
    
    part_three = float( array_highest[x-kperiods] ) - float( array_lowest[x-kperiods] );
    print('part_three');
    print(part_three);
    
    k = part_two/part_three;
    print('k');
    print(k);
    
    #k = ( ( array_close[x]-array_lowest[x-kperiods] ) * 100/ ( array_highest[x-kperiods]-array_lowest[x-kperiods] ) )
    Kvalue.append(k)
    
print(len(Kvalue))

print('Kvalue')
print(Kvalue)

"""
Finding %K Line Values End
"""

"""
Finding %D Line Values Start
"""

y=0
# dperiods for calculate d values
dperiods=3
Dvalue=[None,None]
mean=0
for x in range(0,len(Kvalue)-dperiods+1):
	sum=0
	for j in range(0,dperiods):
		sum=Kvalue[y]+sum
		y=y+1
	mean=sum/dperiods
	# d values for %d line adding in the list Dvalue
	Dvalue.append(mean)
  # skip one from starting after each iteration
	y=y-(dperiods-1)
print(len(Dvalue))

print('Dvalue')
print(Dvalue)

"""
Finding %D Line Values Start
"""

# Visualising the result
plt.figure(figsize=(25,15), dpi=50, facecolor='w', edgecolor='k')
ax = plt.gca() 
plt.plot(Kvalue,color='red',label = '%K line')
plt.plot(Dvalue,color='blue',label = '%D line')
plt.title('Stochastic Oscillator', fontsize=20)

df = []
df['date'] = array_time
x=df['date'].index
labels = array_time[13:]
plt.xticks(x, labels, rotation = 'vertical')
for tick in ax.xaxis.get_major_ticks():
    tick.label1.set_fontsize(10)
for tick in ax.yaxis.get_major_ticks():
    tick.label1.set_fontsize(10)
plt.ylabel('KD_Values', fontsize=20)
plt.xlabel('Dates', fontsize=15)
plt.legend()
plt.show()