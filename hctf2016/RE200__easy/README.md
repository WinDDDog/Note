
flag ： hctf{The_Basic_0f_RE_????}

len(flag) = 26

## step0:

全代码由 Code——A 与 Code——B 轮流进行互解密与自解密

### step1：

main

获得输入
检测 len(flag) = 26
检测 "hctf{" 和 “}”
运行 Code——B 进入step2

### step2:

Code——B 对 "The_" 进行加密扩充 然后作为明文使用RC4加密

使用上面的解密出来部分 解密Code——A 为可执行部分。

Code——B进行部分自解密 重新作为可执行部分，注意，自解密只解密前面部分因此必须保证解密之后的代码比之前的短的多

运行解密之后的Code——A

### step3:

Code——A再次解密Code——B，变种的Base64编码后检测 Basic_

解密执行Code——B

### step4:

Code——B 对输入高低换位后亦或检测 0f_RE_ 

解密并执行step5作为结束

### step5:

该部分描述被隐藏