## 55-58

对于大端/小端 本身没有什么特别的，主要是在不同的机子上int 类型的长度不同
```
#include <iostream>
int main()
{
	const char xx[10] = { 0x11,0x22,0x33,0x44 };
	auto test = (void*)xx;
	printf("%x", *(int *)test);
	return 0;
}
```

## 59

easy 简单的位运算： re = (X & 0xFF) | (y & 0xFFFFFF00)

## 60 

easy
```
void replace(unsigned x, int i, unsigned char b)
{
	unsigned a = (0xFF << (i << 2));
	x = x & (~a);
	x = x | (b << (i << 2));
	printf("%x",x);
}
```

## 61 

四种情况： 0x00000000  0xFFFFFFFF 0x??????FF  0x??????00

其中？表示任意

## 62

逻辑右移与算数右移的区别在于最右补位：

逻辑直接补0

算数补最高位，最高位是1补1，最高位是0补0


判断方法(32位机器上)：

因此对于任意负数(signed类型)  x >> 31 == -1 (算数); x >> 31 == -1(逻辑) 

## 63

没什么难度，就是去判断最高位然后进行补位

## 64

简单的使用右移实现，不太懂题目意思

## 65

判断1的奇偶，思路之一是计算1 bit的个数:

//Brian W. Kernighan公开的一个经典算法

```
unsigned bit_count(unsigned v)
{
    unsigned int c; //置位总数累计

    for (c = 0; v; c++)
    {
        v &= v - 1; //干掉最低的置位
    }

    return c;
}
```

不适用循环只能使用二分法了


## 66

获得leftmost 1：如果使用循环这个代码不难

不适用循环只能使用二分法了


## 67 

A . 这里移动了32位 是未定义行为

B,C 考虑进行2次位移代替一次位移

## 68

右边n bits 全设为1

基本思路： 1 >> (n+1)  然后取反 但是在n>=31 的时候有问题，需要进行一次判断

思路2 x = (1 >> n); x = x | (x-1);


## 69


```
// 循环左移

n = n << 2;

// 移动选择器,注意这里n = 0 的情况需要另外判断

xx = 0x80000000 >> n; // 这里是算数右移
xx = xx & (xx-1)

// 前一半

input_A = input & (~xx)
input_A = input_A << n;

// 后一半

input_B = input & xx
input_B = input_B >> (32-xx)

//组合起来

ret = input_A | input_B

```

## 70

先判断最右位是否为1

turn off the trailing 1’s in a word:
(e.g., 10100111 -> 10100000)
```
x & (x+1) //从右向左,第一个0之前所有的1至0
```

判断计算出的值就好

## 71

A 每次右移的个数

B 不是特别困难

## 72

!! 重要 size_t 会导致无符号的转换而永远大于0

## 73

直接思路是进行溢出判断并返回

## 74

## 75









