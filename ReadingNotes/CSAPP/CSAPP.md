## 2.72

重要 size_t 会导致无符号的转换而永远大于0
```
int x = ???;
if(x - sizeof(int ) > 0)
{
    //该条件永真,sizeof(int) 返回unsigned int 进行了隐式转换而永远大于0 
}
```