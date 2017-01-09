
#include <stdio.h>

char last[] = "1000";
char xor[] = "\x4a\x28\x44\x76";
//step1_xor_125 偏移是 619 ，亦或值为"\x4a\x28\x44\x76";
//原始值为 7A 18 74 46
int main()  
{
    int i = 0;
 
    for(i= 0 ; i < 4; i ++)
    {
        printf("%.2x\n", last[i] ^xor[i]);
     
    }
    
    return 0;
}
