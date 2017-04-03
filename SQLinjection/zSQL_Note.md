SQL injection Notes


同时判断的一种绕过

```
select * from TableName where Akey = ' ' = ' ' and Bkey = ' ' = ' '
```

Akey = ''  获得 empty 就是 '' 然后 empty = empty 所有条件成立