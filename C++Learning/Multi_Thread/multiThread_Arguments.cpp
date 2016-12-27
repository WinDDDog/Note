/*
Don't use std::thread
*/

#include <iostream>
#include <string>
#include <thread>
void threadCallback_example(int x, std::string str)
{
    std::cout<<"Passed Number = "<<x<<std::endl;
    std::cout<<"Passed String = "<<str<<std::endl;
}

void threadCallback_ref(int const & x)
{
    int & y = const_cast<int &>(x);
    y++;
    std::cout<<"Inside Thread x = "<<x<<std::endl;
}

int main()  
{
    //An easy example pass argument by value
    int xx = 10;
    std::string str = "Sample String";
    std::thread threadObj1(threadCallback_example, xx, str);
    threadObj1.join();

    //attention
    //pass the arguments by reference must use the std::ref()
    int x = 9;
    std::cout<<"In Main Thread : Before Thread Start x = "<<x<<std::endl;
    std::thread threadObj2(threadCallback_ref,std::ref(x));
    threadObj2.join();
    std::cout<<"In Main Thread : After Thread Joins x = "<<x<<std::endl;

    return 0;
}