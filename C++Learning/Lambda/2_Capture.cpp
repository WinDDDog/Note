#include <iostream>
#include <vector>
#include <algorithm> //  std::for_each


/* Syntax:
* (1) [ capture-list ] ( params ) mutable(optional) constexpr(optional)(c++17) exception attribute -> ret { body }
* (2) [ capture-list ] ( params ) -> ret { body }
* (3) [ capture-list ] ( params ) { body }	
* (4) [ capture-list ] { body }
*/

/*capture-list
* a comma-separated list of zero or more captures, optionally beginning with a capture-default.
* Capture list can be passed as follows (see below for the detailed description):
*
	* [a,&b] where a is captured by value and b is captured by reference.
	* [this] captures the this pointer by value
	* [&] captures all automatic variables odr-used in the body of the lambda by reference
	* [=] captures all automatic variables odr-used in the body of the lambda by value
	* [] captures nothing
*/
int main()
{
	auto HelloWorld = [](){std::cout << "Hello, world!" << std::endl;};
	HelloWorld();

	// define the return type in lambda
	// the return type is int
	auto fun_ret_int = [] (int x, int y) -> int 
	{
		if(x > 5)
			return x + y;
		else 
			return x - y;
	};

	std::cout << fun_ret_int(6.4,1)<<std::endl;

	//copy by reference and value
	int a = 100;
	int b = 200;
	auto CopyByReference = [&] ()
	{
		a = 10;
	};
	CopyByReference();
	std::cout << a <<std::endl;

	auto CopyByValue = [=] ()
	{
		// b = 10;  <--- error the b pass by value is a readonly var
		std::cout << b <<std::endl;
	};
	CopyByValue();

	return 0;
}