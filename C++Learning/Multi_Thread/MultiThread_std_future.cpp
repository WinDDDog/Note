//use std::future instead of std::thread;

#include <iostream>
#include <future>

int Example(){ return 10;}

int main()
{
    //example
    //return type -> std::future<int>
    auto GetAnAnswer = std::async(Example);  // GetMyAnswer starts background execution
    int returnValue = GetAnAnswer.get(); // answer = 10; 
    std::cout<< returnValue <<std::endl;
    return 0;
}