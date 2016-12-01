/*
注意 这个是多flag 生成工具本体
想要正常运行还需要一个配置好的用于生成的VS工程项目
具体要求看func_CompilerFile()函数
*/

#define  _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <time.h>
#include <windows.h>
#include "md5.h"
//hctf{S0_T3rr1b1e_0000}

void func_Calculate_Equal(int Num1, int Num2, int Num3, int Num4, int * ret);
BOOL func_Modify_Source(int * ret);
BOOL func_CompilerFile(char * file_Name);
int main()
{
	int i = 0, j = 0, k = 0;
	int For_RAND = 0;
	int ret[22] = { 0 };
	char encrypt[40] = {0};
	unsigned char decrypt[16] = {0};
	char File_Name[100] = { 0 };
	char jos_Line[512] = { 0 };
	FILE * j_fP = fopen("josn_List", "w");
	MD5_CTX md5;
	srand(time(0));


	fwrite("[", 1, 1, j_fP);
	
	for (i = 99; i < 119; i++)
	{
		for (j = 48; j < 58; j++)
		{
			for (k = 48; k < 58; k++)
			{
			
	//i = 99; j = 48; k = 48;
				sprintf(encrypt, "%d22Equ_HCTFRE1%d%d", rand() % 10, rand() % 10, rand() % 10);
				printf("%s", encrypt);
				MD5Init(&md5);
				MD5Update(&md5, encrypt, strlen((char *)encrypt));
				MD5Final(&md5, decrypt);
				sprintf(File_Name, "%d%d%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%02x%d%d%d", rand() % 10, i ^ 0x33, decrypt[0], decrypt[1], decrypt[2], decrypt[3], decrypt[4], decrypt[5], decrypt[6], decrypt[7], decrypt[8], decrypt[9], decrypt[10], decrypt[11], decrypt[12], decrypt[13], decrypt[14], decrypt[15], rand() % 10, j ^ 0x44, k ^ 0x55);
				For_RAND = 'A' + (rand() % 26);
				sprintf(jos_Line, "{\"flag\":\"hctf{S0_T3rr1b1e_%c%c%c%c}\",\"file_name\":\"%s\"},\n", For_RAND, i, j, k, File_Name);
				fwrite(jos_Line, 1, strlen(jos_Line), j_fP);
				func_Calculate_Equal(For_RAND, i, j, k, ret);
				func_Modify_Source(ret);
				func_CompilerFile(File_Name);
				printf("\n%d %c %c %c %c\n", i-98, For_RAND, i,j,k);
			}
		}
	}
	
	fwrite("]", 1, 1, j_fP);
	fclose(j_fP);

	
	return 0;
}
BOOL func_Modify_Source(int * ret)
{
	FILE * fP = fopen("test.c", "r+");
	int i = 0;
	char s[80] = {0};
	if (fP == NULL)
	{
		printf("<error> Can not open source file\n");
		getchar();
		return FALSE;
	}

	fseek(fP, 16, SEEK_SET);

	for (i = 0; i < 22; i++)
	{
		sprintf(s, "%.8X", ret[i]);
		//printf("%s\n", s);
		fwrite(s, 1, 8, fP);
		fseek(fP, 18, SEEK_CUR);
	}
	
	
	fclose(fP);

	return TRUE;
}
BOOL func_CompilerFile(char * file_Name)
{
	char Path[512] = { 0 };
	system("copy G:\\HCTF_RE1\\HCTF_RE1\\test.c G:\\HCTF_RE100_MutiFlag\\HCTF_RE100_MutiFlag\\main.c"); //拷贝生成的源码
	system("G:\\vs2015\\Common7\\IDE\\devenv.exe G:\\HCTF_RE100_MutiFlag\\HCTF_RE100_MutiFlag.sln /rebuild RELEASE");//编译
	sprintf(Path, "copy G:\\HCTF_RE100_MutiFlag\\Release\\HCTF_RE100_MutiFlag.exe G:\\HCTF_RE100_MutiFlag\\list\\%s", file_Name);
	system(Path);//拷贝回生成的exe
	return TRUE;
}
void func_Calculate_Equal(int Num1, int Num2, int Num3, int Num4,int * ret)
{
	int i = 0;
	const int Num01[23] = { 8923,659,1303,1949,4447,3527,757,367,5507,7907,691,9629,5303,8117,9103,9391,89,3361,751,9067,5417,6829 };
	const int Num02[23] = { 9067,1259,107,8597,4229,1213,8831,3259,269,5323,769,1237,5501,6763,8053,67,3163,3863,4447,5569,4357,5503 };
	const int Num03[23] = { 9533,23,1973,8269,6961,8929,6301,2791,4861,8053,1609,8219,911,7583,6143,2953,7247,6131,7853,4451,7187,8629 };
	const int Num04[23] = { 1039,389,1487,5987,937,239,3583,2897,8893,3307,7459,8521,9769,9689,6959,7949,9137,3461,4229,9059,7177,7643 };
	const int Num05[23] = { 7853,6271,9371,1613,73,8243,9013,919,5387,2207,6211,139,5077,7211,2053,8443,4421,5717,8779,8971,6337,7159 };
	const int Num06[23] = { 3019,8377,1613,1973,3923,8821,797,4969,7643,7297,2381,4679,5869,647,7411,3329,6199,7349,4969,8731,877,1039 };
	const int Num07[23] = { 3089,9859,7159,227,271,8161,1051,5701,1259,1361,3673,8311,4679,7877,2621,991,9949,683,743,6079,2473,4519 };
	const int Num08[23] = { 1259,4651,5479,4951,4657,4591,509,3821,6661,4127,2011,4547,7621,5261,5261,2003,4871,457,2083,4561,6947,1187 };
	const int Num09[23] = { 4703,9629,3769,2003,1297,4283,2381,8429,7057,9371,4483,4099,1873,499,7583,5897,937,727,241,4799,6361,5531 };
	const int Num10[23] = { 283,5591,151,2113,7229,307,3851,8963,2777,7757,8831,17,8563,1543,8243,3529,3833,2411,2897,19,3559,853, };
	const int Num11[23] = { 9467,2207,2269,2083,7741,5801,2633,349,9257,479,331,7649,5393,887,6329,4243,3329,7121,4001,6043,8263,3253 };
	const int Num12[23] = { 4993,7577,6833,661,4129,67,2791,3121,4597,8053,8147,1619,5801,6173,127,8179,8093,9319,1063,9157,7817,2341 };
	const int Num13[23] = { 1493,9137,9787,617,5557,8387,4219,3301,251,3203,8443,2521,2887,2437,7883,5653,3907,4457,9091,523,887,8101 };
	const int Num14[23] = { 9467,2251,9067,4153,557,4999,5669,9343,7949,7019,113,1801,1867,1187,3541,5527,2347,4813,3019,683,6869,5051 };
	const int Num15[23] = { 7333,8677,3557,4099,5279,449,2099,8929,5393,1933,9157,6827,467,3299,443,3739,823,7499,691,2467,281,4049 };
	const int Num16[23] = { 7489,739,9769,7963,5651,7691,947,8537,4943,1187,4651,9011,6359,1063,7541,9187,2551,7649,4001,3187,6199,7433 };
	const int Num17[23] = { 5653,9349,9419,2459,2423,1823,1291,2423,3671,4673,1033,8389,2777,8629,6203,6673,1877,7583,5077,9227,6037,2339 };
	const int Num18[23] = { 1663,3529,9631,6833,17,3697,4327,6053,7639,6679,797,3209,3191,3259,5563,5717,3181,1571,751,1163,211,4421 };
	const int Num19[23] = { 2273,9341,8081,9311,41,4241,1279,4483,6581,6863,7583,4129,1543,5651,4357,9521,5557,11,7723,2441,6733,6521 };
	const int Num20[23] = { 1171,241,9851,3583,1609,43,9281,5867,2819,5659,4493,223,2767,3221,6173,6947,5897,6113,6737,3989,9733,3467 };
	const int Num21[23] = { 173,2099,2953,7243,4987,1723,2657,1213,2731,7507,9721,4637,9203,5407,3169,5003,8681,2,3329,5843,8017,83 };
	const int Num22[23] = { 5119,3109,8369,7993,2927,127,5233,4783,5171,3907,1613,4567,3343,2617,5387,8713,7829,3559,419,9931,6067,4481 };

	int flag[22] = { 104,99,116,102,123,83,48,95,84,51,114,114,49,98,49,101,95,30,30,30,30,125 };
	flag[17] = Num1;
	flag[18] = Num2;
	flag[19] = Num3;
	flag[20] = Num4;

	memset(ret, 0, 22*sizeof(int));


	for ( i = 0;i <= 21;i++)
	{
		ret[0]	= ret[0] + flag[i] * Num01[i];
		ret[1]	= ret[1] + flag[i] * Num02[i];
		ret[2]	= ret[2] + flag[i] * Num03[i];
		ret[3]	= ret[3] + flag[i] * Num04[i];
		ret[4]	= ret[4] + flag[i] * Num05[i];
		ret[5]	= ret[5] + flag[i] * Num06[i];
		ret[6]	= ret[6] + flag[i] * Num07[i];
		ret[7]	= ret[7] + flag[i] * Num08[i];
		ret[8]	= ret[8] + flag[i] * Num09[i];
		ret[9]	= ret[9] + flag[i] * Num10[i];
		ret[10] = ret[10] + flag[i] * Num11[i];
		ret[11] = ret[11] + flag[i] * Num12[i];
		ret[12] = ret[12] + flag[i] * Num13[i];
		ret[13] = ret[13] + flag[i] * Num14[i];
		ret[14] = ret[14] + flag[i] * Num15[i];
		ret[15] = ret[15] + flag[i] * Num16[i];
		ret[16] = ret[16] + flag[i] * Num17[i];
		ret[17] = ret[17] + flag[i] * Num18[i];
		ret[18] = ret[18] + flag[i] * Num19[i];
		ret[19] = ret[19] + flag[i] * Num20[i];
		ret[20] = ret[20] + flag[i] * Num21[i];
		ret[21] = ret[21] + flag[i] * Num22[i];
	}
	return;
}
