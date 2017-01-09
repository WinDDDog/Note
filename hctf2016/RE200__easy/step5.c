#include <stdio.h>
int Check_Last_Mutiple_Flag1(char *input);
//其中Check_Last_Mutiple_Flag1是原型，step5为他的binary
const char step5[] = 
"\x55\x48\x89\xE5\x48\x89\x7D\xE8\xC7\x45\xF0\x30\x30\x30\x30\xC6"
"\x45\xF4\x00\x48\x8B\x45\xE8\x0F\xB6\x10\x0F\xB6\x45\xF0\x38\xC2"
"\x75\x40\x48\x8B\x45\xE8\x48\x83\xC0\x01\x0F\xB6\x10\x0F\xB6\x45"
"\xF1\x38\xC2\x75\x2D\x48\x8B\x45\xE8\x48\x83\xC0\x02\x0F\xB6\x10"
"\x0F\xB6\x45\xF2\x38\xC2\x75\x1A\x48\x8B\x45\xE8\x48\x83\xC0\x03"
"\x0F\xB6\x10\x0F\xB6\x45\xF3\x38\xC2\x75\x07\xB8\x01\x00\x00\x00"
"\xEB\x05\xB8\x00\x00\x00\x00\x5D\xC3";
int main()
{
    char input[] = "0000"; 
    int (*Check_Last_Mutiple_Flag)(char* );
    Check_Last_Mutiple_Flag = (int(*)(char *))step5;
    int result = 
    Check_Last_Mutiple_Flag(input);

    if(result == 0)
    {
        printf("NO\n");
    }
    else if(result == 1)
    {
        printf("0K\n");
    }
    else
    {
        printf("error!!!\n");
    }
    printf("==end===");
}

int Check_Last_Mutiple_Flag1(char *input)
{
    const char Mutiple_Flag[] = "0000"; //此处会被修改作为mutiFlag检测
   if(input[0] == Mutiple_Flag[0])
   {
       if(input[1] == Mutiple_Flag[1])
       {
           if(input[2] == Mutiple_Flag[2])
           {
               if(input[3] == Mutiple_Flag[3])
               {
                   return 1;
               }
           }
       }
   }
    return 0;
}