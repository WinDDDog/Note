## md5

each loop need MD5Init MD5Update MD5Final

```
    char encrypt[] = "123456";
    unsigned char decrypt[16] = {0};

    //each MD5 need init and update
    MD5Init(&md5);
    MD5Update(&md5, encrypt, strlen((char *)encrypt));
    MD5Final(&md5, decrypt);
```