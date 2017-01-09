import json

with open('json_File','r') as jsf:
    data = json.load(jsf) 
#json retrn type is list
print("return type : ",type(data))
#each line in josn ret is dictionary
print("josn line type : ",type(data[0]))
#read a specified line
print("the first line :",data[0]["flag"] ,data[0]["file_name"])
#read each line
for line in data:
    print(line["flag"],line["file_name"])