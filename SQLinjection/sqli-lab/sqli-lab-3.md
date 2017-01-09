## 38-39

所谓的stack injection,但是我觉得这种情况比较少见

主要是必须使用允许堆叠查询功能的API:

```

$query  = "SELECT CURRENT_USER();";
$query .= "SELECT Name FROM City ORDER BY ID LIMIT 20, 5";

/* execute multi query */
if ($mysqli->multi_query($query)) {
    do {
        /* store first result set */
        if ($result = $mysqli->store_result()) {
            while ($row = $result->fetch_row()) {
                printf("%s\n", $row[0]);
            }
            $result->free();
        }
        /* print divider */
        if ($mysqli->more_results()) {
            printf("-----------------\n");
        }
    } while ($mysqli->next_result());
}

```

这样才可以同时执行2段sql语句....在语句之间使用分号分割...

## 40-41

//40 似乎有问题？？？

41没有错误回显了但是使用以前的也可以id=0%20union%20select%201,2,3%20%23

## 42-45

```
function sqllogin($host,$dbuser,$dbpass, $dbname){
   // connectivity
//mysql connections for stacked query examples.
$con1 = mysqli_connect($host,$dbuser,$dbpass, $dbname);
   
   $username = mysqli_real_escape_string($con1, $_POST["login_user"]);
   $password = $_POST["login_password"]; //没有过滤？？
```

## 46-53

学习如何在order by 之后进行注入

```
$id=$_GET['sort'];	
if(isset($id))
	{
    ...
	$sql = "SELECT * FROM users ORDER BY $id";
	$result = mysql_query($sql);
```

看一下 order by 之后能接什么

```
    [ORDER BY {col_name | expr | position}
      [ASC | DESC], ...]
    [LIMIT {[offset,] row_count | row_count OFFSET offset}]
    [PROCEDURE procedure_name(argument_list)]
    [INTO OUTFILE 'file_name'
        [CHARACTER SET charset_name]
        export_options
      | INTO DUMPFILE 'file_name'
      | INTO var_name [, var_name]]
    [FOR UPDATE | LOCK IN SHARE MODE]]
```

最有用的就是可以使用 INTO OUTFILE 导出文件

看一下orderby可以执行什么:

1. 可以执行bool类型的盲注通过rand(true) 与 rand(false)， 这同时也意味着可以进行时间盲注

2. order by 之后可接多个参数，例如 ``` order by 1,2 ``` 这在一些情况下可以进行绕过？ (例如使用if语句)

3. 可以执行报错注入如果有错误回显

例子

```
?sort=1%20%20procedure%20analyse(extractvalue(rand(),concat(0x3a,version())),1)


```

这样就明白了


至于50之后就是加入了mysqli_multi_query函数，这样可以使用 stacked injection 只不过显示不显示错误罢了