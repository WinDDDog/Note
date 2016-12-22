#include <iostream>
const struct
{
    int n1;
    mutable int n2;
} x = {0, 0};   

//1. mutable is for marking specific attribute as modifiable from within const methods.
void func1()
{
    int n1 = 0;           // non-const object
    const int n2 = 0;     // const object
    int const n3 = 0;     // const object (same as n2)
    volatile int n4 = 0;  // volatile object

    n1 = 1; // ok, modifiable object
//  n2 = 2; // error: non-modifiable object
    n4 = 3; // ok, treated as a side-effect
//  x.n1 = 4; // error: member of a const object is const
    x.n2 = 4; // ok, mutable member of a const object isn't const

} 
int main()
{
    func1();
/*2. lambda-declarator that removes const qualification from parameters captured by copy (since C++11)
*
*mutable : allows body to modify the parameters captured by copy, and to call their non-const member functions
*
*/
    int x = 0;
    auto f1 = [=]() mutable {x = 42;};  // OK
    std::cout << x <<std::endl;
// auto f2 = [=]()         {x = 42;};  
// Error: a by-value capture cannot be modified in a non-mutable lambda
    return 0;
}