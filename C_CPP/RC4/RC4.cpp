#include <iostream>
#include <typeinfo>

int main()
{

	int i;
	unsigned char SBox[256];
	unsigned char rc4_key[5] = "1234";
	int keylength = 4;  //key len
	int EncryptLen = 8;   //input len
	unsigned char Encrypt[9] = "xxxxxxxx";
	int i, j, k, temp, n;
    
	//rc4
	for (i = 0; i < 256; i++)
	{
		SBox[i] = i;
	}

	for (j = 0, i = 0; i < 256; i++)
	{
		j = (j + SBox[i] + *(rc4_key + i % keylength)) % 256;

		temp = SBox[i];
		SBox[i] = SBox[j];
		SBox[j] = temp;
	}
	i = 0; j = 0;
	for (k = 0; k < EncryptLen; k++)
	{
		i = (i + 1) % 256;
		j = (j + SBox[i]) % 256;

		temp = SBox[i];
		SBox[i] = SBox[j];
		SBox[j] = temp;

		n = SBox[(SBox[i] + SBox[j]) % 256];

		*(Encrypt + k) = *(Encrypt + k) ^ n;
	}
	//print Encrypt
}