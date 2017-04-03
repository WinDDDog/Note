## WP

nc pwn2.jarvisoj.com 9877

```
.text:0804847B buf             = byte ptr -88h
.text:0804847B
.text:0804847B                 push    ebp
.text:0804847C                 mov     ebp, esp
.text:0804847E                 sub     esp, 88h
.text:08048484                 sub     esp, 8
.text:08048487                 lea     eax, [ebp+buf]
.text:0804848D                 push    eax
.text:0804848E                 push    offset format   ; "What's this:%p?\n"
.text:08048493                 call    _printf
.text:08048498                 add     esp, 10h
.text:0804849B                 sub     esp, 4
.text:0804849E                 push    100h            ; nbytes
.text:080484A3                 lea     eax, [ebp+buf]
.text:080484A9                 push    eax             ; buf
.text:080484AA                 push    0               ; fd
.text:080484AC                 call    _read
.text:080484B1                 add     esp, 10h
.text:080484B4                 nop
.text:080484B5                 leave
.text:080484B6                 retn
```

不能直接运行，但是呢输出了esp的地址，明显就是要我们写shellcode进去了

shellcode + (0x88-len(shellcode)) * padding + 4 padding + shellcode Addr

