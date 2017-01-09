import json
from subprocess import *

def ReportFlag(filename,FIlefLAG):
    back = filename
    filename = './list/%s' % (fileName)
    f = open(filename,'rb')
    f.seek(6059)
    txt0 = f.read(1)
    txt0 = chr(ord(txt0)^0x4a)
    txt1 = f.read(1)
    txt1 = chr(ord(txt1)^0x28)
    txt2 = f.read(1)
    txt2 = chr(ord(txt2)^0x44)
    txt = f.read(1)
    txt = chr(ord(txt)^0x76)
    if txt != 'p' and txt != 'P' and txt != '0':
        #print FIlefLAG[0:21],txt0,txt1,txt2,txt,"} ",back
        print FIlefLAG," ",back
        
       
        
    f.close()


with open('json_File','r') as jsf:
    data = json.load(jsf)

j = 0

for i in range(0, 2000):
    flagName = data[i]["flag"]
    xx = flagName
    flagName = flagName + "\n"
    fileName = data[i]["file_name"]
    shell = './list/%s' % (fileName)
    p = Popen(shell,stdin=PIPE, stdout=PIPE, shell = True)
    p.stdin.write(flagName)
    output = p.stdout.readline()
    output = p.stdout.readline()
    output = p.stdout.readline()
    #print output
    if(output[0] != 'C'):
        #print "error in line % d" % (i+1)
        j = j+1
        ReportFlag(fileName,xx)
        #print xx," ",fileName
    #break

print "total num: %d" % (j)
