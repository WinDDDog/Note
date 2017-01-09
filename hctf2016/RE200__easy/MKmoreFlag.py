import os  
import hashlib
import random

ctf_Name = "Re200"
json_File = "json_File"


Wtemplate = "{\"flag\":\"%s\",\"file_name\":\"%s\"},\n"

def CompilerFlagFile(hex1,hex2,hex3,hex4,OutPut_FileName,Input_Name = 'test.c'):
    f = open(Input_Name,'r+')
    f.seek(7897)
    # a = "\\x%x\\x%x\\x%x\\x%x" % (0x7B,0x18,0x74,0x46)
    a = "\\x%x\\x%x\\x%x\\x%x" % (hex1,hex2,hex3,hex4)
    f.write(a)
    f.close()
    Output =  "gcc test.c -o list/%s -fno-stack-protector -z execstack" % (OutPut_FileName)
    os.system(Output)




os.system("mkdir list")
# char xor[] = "\x4a\x28\x44\x76";
xor_1 = 0x4A
xor_2 = 0x28
xor_3 = 0x44
xor_4 = 0x76
UseFor_md5 = hashlib.md5()
jsf = open(json_File,'w')
jsf.write("[")

for i in range(48,58):
    for j in range(65,85):
        for k in range(48,58):
            os.system("cp main.c test.c")
            RandNum = random.randint(97,122)
            for1 = xor_1 ^ i
            for2 = xor_2 ^ j
            for3 = xor_3 ^ k
            for4 = xor_4 ^ RandNum
            This_Flag = "%c%c%c%c" % (i,j,k,RandNum)

            This_Flag = "hctf{The_Basic_0f_RE_%s}" % This_Flag

            tmp = "%d_This_IS_tHE_rE200_%d" % (random.randint(1,100),random.randint(1,100))
            UseFor_md5.update(tmp.encode("utf-8"))
            This_File_Name = UseFor_md5.hexdigest()
            This_File_Name = "%d%d%d%d%s%d%d" % (random.randint(1,9),i ^ 0x30,random.randint(1,9), j ^ 0x20, This_File_Name, k ^ 0x10, random.randint(1,9))
            CompilerFlagFile(for1,for2,for3,for4,This_File_Name)
            jsf.write("{\"flag\":\"%s\",\"file_name\":\"%s\"},\n" % (This_Flag,This_File_Name) )
jsf.write("]")
jsf.close()


