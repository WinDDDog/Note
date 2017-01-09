// std_tr1__unordered_map__unordered_map_const_iterator.cpp   
// compile with: /EHsc   
#include <unordered_map>   
#include <iostream>   
//unordered_map is kind of hash map

typedef std::unordered_map<char, int> Mymap;   
int main()   
{   
	Mymap c1;   

	c1.insert(Mymap::value_type('a', 1));   
	c1.insert(Mymap::value_type('b', 2));   
	c1.insert(Mymap::value_type('c', 3));   

	// display contents " [c 3] [b 2] [a 1]"   
	for (Mymap::const_iterator it = c1.begin();   
		it != c1.end(); ++it)   
		std::cout << " [" << it->first << ", " << it->second << "]";   
	std::cout << std::endl;   

	return 0;   
}  
  