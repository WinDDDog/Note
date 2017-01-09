#include <array>
#include <iostream>
#include <algorithm>


class test
{
public:
	void assign()
	{
		std::cout << "test" << std::endl;
	}
};
//std::array is a container that encapsulates fixed size arrays
//ʵ���Ͼ��Ƕ�C����ֱ������ķ�װ,��װ�м����˶�size��pos�ļ��

/*
template<class _Ty, size_t _Size>
class array
{
	_Ty _Elems[_Size];
}
*/

int main()
{

	std::array<int, 3> arr = { 4, 8, 15 };
	//use it like c style array
	std::cout << arr[2] << std::endl;

	// !! error :  arr = { 1,23 ,2,4 };
	//elemnet num can not change after defination 	

	//use iterator with it
	std::for_each(arr.begin(), arr.end(), [](int &n) {std::cout << n << std::endl; });

	return 0;
}