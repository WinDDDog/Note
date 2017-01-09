#include <vector>
#include <algorithm>
#include <iostream>

/*
 // possible implementation
template<class InputIt, class UnaryFunction>
UnaryFunction for_each(InputIt first, InputIt last, UnaryFunction f)
{
	for (; first != last; ++first)  f(*first);
	return f;
}

 // implementation in VS 2015
template<class _InIt, class _Fn1> inline
void _For_each_unchecked(_InIt _First, _InIt _Last, _Fn1& _Func)
{
	for (; _First != _Last; ++_First) _Func(*_First);
}

template<class _InIt, class _Fn1> inline
_Fn1 for_each(_InIt _First, _InIt _Last, _Fn1 _Func)
{	
	_DEBUG_RANGE_PTR(_First, _Last, _Func);
	_For_each_unchecked(_Unchecked(_First), _Unchecked(_Last), _Func);
	return (_Func);
}
*/


struct Sum {
	Sum() { sum = 0; }
	void operator()(int n) { sum += n; }

	int sum;
};

int main()
{
	std::vector<int> nums{ 3, 4, 2, 9, 15, 267 };

	std::cout << "before: ";
	for (auto n : nums) {
		std::cout << n << " ";
	}
	std::cout << '\n';

	std::for_each(nums.begin(), nums.end(), [](int &n) { n++; });
	Sum s = std::for_each(nums.begin(), nums.end(), Sum());

	std::cout << "after:  ";
	for (auto n : nums) {
		std::cout << n << " ";
	}
	std::cout << '\n';
	std::cout << "sum: " << s.sum << '\n';
}