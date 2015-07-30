#!C:\Python34\python.exe -u
#!/usr/bin/env python

import cgi
import cgitb; cgitb.enable()  # for troubleshooting
import sys
import json

def parse(filename):
    data = open(filename, "r")

    obj = {}

    #TODO: 1.Read first line for headers
    # an array of headers
    headers = data.readline().split("\t")
    # Pull out index of two data we want
    points = headers.index("RminCorr (ohm)")
    label = headers.index("SplitLabel")

    #TODO: 2.Read two columns

    for line in data:
        line = line.split("\t") #line is now an array of values
        #TODO: 3.Save x and y data

        # check that line[points] is a real data point
        

        # if label has not been seen before, create label:[] relationship
        # else, append data points to label
        if label not in obj:
            obj[line[label]] = [line[points]]
        else:
            obj[line[label]].append(line[points])

    return obj


def get():
    toreturn = ""


    file = sys.argv[1]
    tojson = parse("../htdocs/uploads/"+file) #call parse function

    return json.dumps(tojson)

    #print(file)
    #toreturn += "target file: "+ file
    #f = open("../htdocs/uploads/outputs/output.txt", 'a')
    #nums = f.write("testing testing 1-2-3")
    #f.write("hi")
    #f.close()

    #toreturn +="<br>characters added:"+ str(nums)

print(get())


