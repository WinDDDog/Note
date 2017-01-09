## 1.Introduction

This book distinguishes between mathematical expressions of ordinary arithmetic and
those that describe the operation of a computer.

The main difference between computer arithmetic and ordinary arithmetic is that in
computer arithmetic, the results of calculation are reduced modulo 2^n , 
where n is the word size of the machine.

```
    int x = 0x80000000;
    int y = 0x80000000;
    printf("%x",x+y);   // print: 0
    return 0;
```

## 2.Basic

### 2.1 Manipulating Rightmost Bits

最右位的magic

Use the following formula to turn off the rightmost 1-bit in a word:

```
x&(x-1) //最右位1置0
```

turn on the rightmost 0-bit in a word:

```
x|(x+1) //最右位0置1
```

turn off the trailing 1’s in a word:
(e.g., 10100111 -> 10100000)
```
x & (x+1) //从右向左,第一个0之前所有的1至0
```

turn on the trailing 0’s in a word:
(e.g., 10101000 -> 10101111)
```
x | (x-1) //从右向左,第一个1之前所有的0至1
```

Ceate a word with a single 1-bit at the position of the rightmost 0-bit in x:
(e.g., 10100111 -> 00001000)
```
~x & ( x + 1) //最右位0置1,其它置0
```

create a word with a single 0-bit at the position of the rightmost 1-bit:
(e.g., 10101000 11110111)
```
~x | (x-1)  //最右位1置0,其它置1
```

create a word with 1’s at the positions of the trailing 0’s in x , and 0’s elsewhere:
(e.g., 01011000 00000111)
```
//三个都可以, 从右向左, 第一个1之前所有的0至1, 其它位置0
~x & (x-1) ,
~ (x | -x) ,
(x & -x)-1 ,
```

> 后面的就不用中文解释了,看示例应该能理解大意

create a word with 0’s at the positions of the trailing 1’s in x , and 0’s elsewhere:
(e.g., 10100111 11111000)

```
~x|(x+1)
```

Use the following formula to isolate the rightmost 1-bit:
(e.g.,01011000 00001000)
```
x & (−x ) //这个比较奇怪,从右向左保留第一个1,其它置0
```

create a word with 1’s at the positions of the rightmost 1-bit and the trailing 0’s in x:
(e.g., 01011000 00001111)
```
x ^ ( x − 1)
```

create a word with 1’s at the positions of the rightmost 0-bit and the trailing 1’s in x , producing all 1’s if no 0-bit
(e.g., 01010111 00001111)
```
x ^ ( x + 1)
```

turn off the rightmost contiguous string of 1’s:
(e.g., 01011100 01000000)
```
((( x | ( x − 1)) + 1) & x ),
(( x & − x ) + x )& x
```


> 一些等式

~(x & y) == ~x | ~y

~(x | y) == ~x & ~y

~(x + 1) == ~x - 1

~(x - 1) == ~x + 1

### Right-to-Left Computability Test

THEOREM : 
A function mapping words to words can be implemented with word-parallel 
add, subtract, and, or, and not instructions 
if and only if
each bit of the result depends only on bits at and to the right of each input operand .

//!!todo 理解这个理论??比较困难

### A Novel Application

//比特位数相同的下一个大的数

the problem of finding the next higher number after a given number that has the same number of 1-bits

```
#include <cstdio>
unsigned snoob(unsigned x)
{
    unsigned smallest, ripple, ones;

    smallest = x & -x;      //保留 第一个 1 bit
    ripple = x + smallest;  //加上 "最小位" 这个容易理解
    
    ones = x ^ ripple;
    ones = (ones >> 2)/smallest; //这2步计算出需要padding的 1 bit的个数
    return ripple | ones;
}
```
注.算法推导

使用x & -x 找到rightmost的最低1 bit 位 ,然后与原数据相加 ,得到的值再去xor原数据.

首先,加上最低1 bit保证了获得的数比原来的大 ,这个容易理解.

也很容易理解xor之后得到的是一段开头是连续1 bit位的数字,那么这个数字有什么特点?

举例:

```
例1

1001 1100 -> 100
1001 1100 + 100 -> 1010 0000
1010 0000 ^ 1001 1100 -> 11 1100

例2
1111 0000 -> 1 0000
1111 0000 + 1 0000 -> 1 0000 0000
1 0000 0000 ^ 1111 0000 -> 1 1111 0000
```
经过思考,发现1 bit 的个数减2 就是我们需要padding 的1 bit的个数.

当然,这个算法也十分精致:  ones = (ones >> 2)/smallest; 

右移2位让１的个数减2,除法计算padding个数




### 2.2 Addition Combined with Logical Operations

-x  = ~x+1
    = ~(x-1)

### 2.3 Inequalities among Logical and Arithmetic Expressions

### 2.4 Absolute Value Function

首先 这里 y = x>>31

//注意:unisgned逻辑右移补0 负数的算数右移补1 有疑惑的查一下

abs: (x ^ y)-y   (x+y)^y   x-(2x&y)

nabs: y-(x^y)   (y-x)^y   (2x&y)-x

## 2.5 Average of Two Integers

compute the average of two unsigned integers, ( x + y )/2 without causing overflow:

//注意这里是无符号的,逻辑右移

(x & y) + ((x ^ y) >> 1)

(x | y) - ((x ^ y) >> 1)

use the same formulas, but with the unsigned shift replaced with a signed shift.

//也就是说对于有符号的计算,只要把右移换成算数右移就可以啦

//todo 理解这些算法如何能计算平均值

## 2.6 Sign Extension

By “sign extension,” we mean to consider a certain bit position in a word to be the sign bit.

## 2.7 Shift Right Signed from Unsigned

## 2.8 Sign Function

(x 算术右移 31) | (ｘ逻辑右移31)

## 2.9 Three-Valued Compare Function

## 2.10 Transfer of Sign Function

## 2.11 Decoding a “Zero Means 2** n ” Field

## 2.12 Comparison Predicates

## 2.13 Overflow Detection

## 2.14 Condition Code Result of Add Subtract and 

## 2.15 Rotate Shifts

## 2.16 Double-Length Add/Subtract

## 2.17 Double Length Shifts

## 2.18 Multibyte Add, Subtract, Absolute Value

## 2.19 Doz, Max, Min

## 2.20 Exchanging Registers

## 2.21 Alternating among Two or More Values

## 2.22  A Boolean Decomposition Formula


# 3. Power-of-2 Boundaries

# 4. Arithmetic Bounds

# 5. Counting Bits

