#include <iostream>


int main()
{
    int x = 5;

    auto a = [=]() mutable { ++x; };
    auto b = [&]()         { ++x; };
    std::cout << x <<std::endl;
    a(); //the first will only modify its own copy of x and leave the outside x unchanged.
    std::cout << x <<std::endl;
    b(); //second will modify the outside x
    std::cout << x <<std::endl;
    return 0;
}