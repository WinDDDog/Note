flag ： hctf{The_Basic_0f_RE_XXXX}
flag ： hctf{The_Basic_0f_RE_0000}

//gcc main.c -fno-stack-protector -z execstack

len(flag) = 26
## step0:

预计增加难度，将RC4中的Sbox生成与换位亦或分开写

### step1：

main

获得输入
检测 len(flag) = 26
检测 "hctf{" 和 “}”
运行 SeriesB 进入step2

### step2:

SeriesB 对 "The_" 进行加密扩充 然后作为明文使用RC4加密，秘钥可见？？进行比对 // <---可能这部分会被修改

使用上面的解密出来部分 解密SeriesA 为可执行部分。

进行部分自解密 重新作为可执行部分，注意，自解密只解密前面部分因此必须保证解密之后的代码比之前的短的多

运行解密之后的SeriesA

### step3:

SeriesA再次解密SeriesB，简单的检测 Basic_后执行SeriesB

### step4:

SeriesB 亦或后检测 0f_RE_ 
解密并执行step5作为结束

### step5:

step5使用多flag进行作弊检测，根据平台作弊检测机制来决定是否开启这个功能