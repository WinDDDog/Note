#include <iostream>
#include <vector>
#include <algorithm> //  std::for_each

// define a special-purpose custom printing function
inline void print_it (int i)
{
  std::cout << ":" << i << ":";
}


int main()
{
	std::vector<int> int_vector = {1,2,3,4,5,6,5,4,3,2,1};

	// apply print_it to each integer in the list
	std::for_each(int_vector.begin(), int_vector.end(), print_it);
	std::cout << std::endl;

	// USE the lambda function [](int i){cout << ":" << i << ":";} 
	std::for_each(int_vector.begin(), int_vector.end(), 
	[](int i){std::cout << ":" << i << ":";} 
	);
	std::cout << std::endl;

	// lambda expression in fact creates a thing that can be saved and treated like a function pointer or function object
	// you can save the lambda in a variable like a function pointer
	auto Point_Lambda = [](int i){std::cout << ":" << i << ":";};
	Point_Lambda(100);
	std::cout << std::endl;
	
	//Although it would be unusual to do so, you can call the lambda in the same statement that creates it
	[](int i){std::cout << ":" << i << ":";} (42);

	return 0;
}