```
gcc -Og -S -masm=att test.c
```

各种意义上讲，逆向工程需要不断的训练啊。

在各种意义上都是对于逆向工程十分重要的一节！！！

程序的机器级表示

## 标识位

### ZF

零标志位(Zero Flag)

判断结果是否为0。运算结果0，ZF置1，否则置0。

### PF

奇偶标志位(Parity Flag)

反映运算结果低8位中'1'的个数。'1'的个数为偶数，PF置1，否则置0。

### SF

符号标志位(Sign Flag)

反映运算结果的符号。运算结果为负，SF置1，否则置0。

### CF

进位标志位(Carry Flag)

反映无符号运算是否产生进位或借位。运算结果的最高有效位向更高位进位或者借位，CF置1，否则置0。

### OF

溢出标志位(Overflow Flag)

反映有符号运算是否溢出。运算结果超过了8位或者16位有符号数的表示范围，OF置1，否则置0。

### AF

辅助进位标志位(Auxiliary Flag)

在字节操作时低半字节向高半字节进位或借位。字操作时低字节向高字节进位或借位，AF置1，否则置0。

### DF

方向标志位(Direction Flag)

决定串处理指令控制每次操作后si、di的增减。df=0，则每次操作后si、di递增，否则递减。

### IF

中断标志位(Interrupt Flag)

决定CPU是否响应外部可屏蔽中断请求。IF为1时，CPU允许响应外部的可屏蔽中断请求。

### TF

陷阱标志位(Trap Flag)

TF被设置位1时，CPU进入单步模式，所谓单步模式就是CPU在每执行一步指令后都产生一个单步中断。主要用于程序的调试。8086/8088中没有专门用来置位和清零TF的命令。


## 比较指令

## CMP

CMP 指令实际上是做一个不影响寄存器的减法运算

影响的标志位:
ZF 结果是否为0
CF 有无借位

对于有符号数还会影响 
SF 表示结果的符号 
OF 减法是否产生了溢出

## TEST

TEST 指令实际上是一个不影响寄存器的AND运算

影响的标志位:
ZF 结果是否为0
SF 结果的符号
PF 结果的奇偶

CF OF 被直接置0

test 经常被用来判断一个寄存器的值是否为零 如 test eax,eax

## 跳转指令

注意:注意溢出情况下仍会设置Sf 所以在许多有符号大小判断时需要加入OF的判断

指令	          同义词	跳转条件	        描述                     
jmp Label		        always true	        直接跳转
jmp *operand		    always true	        间接跳转
je Label	    jz	    ZF	                相等/零
jne Label	    jnz	    ~ZF	                不相等/非零
js Label		        SF	                负数
jns Label		        ~SF	                非负数
jg Label	    jnle	~(SF ^ OF) & ~ZF	有符号数大于           
jge Label	    jnl	    ~(SF ^ OF)	        有符号数大于等于
jl Label	    jnge	SF ^ OF	            有符号数小于
jle Label	    jng	    (SF ^ OF) or ZF	    有符号数小于等于
ja Label	    jnbe	~CF & ~ZF	        无符号数大于
jae Label	    jnb	    ~CF	                无符号数大于等于
jb Label	    jnae	CF	                无符号数小于
jbe Label	    jna	    CF	ZF              无符号小于等于

## 算数指令

## 经验tips