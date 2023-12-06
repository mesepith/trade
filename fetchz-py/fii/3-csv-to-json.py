import csv
import json

csvfile = open('myfile_0.csv', 'r')
jsonfile = open('file.json', 'w')

fieldnames = ("")
reader = csv.DictReader( csvfile, fieldnames)
for row in reader:
    json.dump(row, jsonfile)
    jsonfile.write('\n')
    print(row)
