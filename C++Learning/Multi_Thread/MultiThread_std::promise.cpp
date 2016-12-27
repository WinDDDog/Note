#include <iostream>
#include <thread>
#include <algorithm>
#include <future>
#include <unistd.h>
/*std::promise 与 std::future 相结合
* promise 对象可以保存某一类型 T 的值，该值可被 future 对象读取（可能在另外一个线程中）
* 因此 promise 可以说是提供了一种线程同步的手段
* 注意 这与一般的通过锁共享的变量不同,他只能被设定一次,但是std::future可以查询这个对象的状态
*
* deferred：异步操作还没开始
* ready：异步操作已经完成
* timeout：异步操作超时
*/
int main()
{
	auto promise = std::promise<std::string>();

	auto producer = std::thread([&]
	{
		sleep(5);
		promise.set_value("Hello World");
	});

 // 和 future 关联
	auto future = promise.get_future();

	auto consumer = std::thread([&]
	{
		//future.get()等待promise被设置后才会输出
		std::cout << future.get();
	});

	producer.join();
	consumer.join();

	return 0;
}