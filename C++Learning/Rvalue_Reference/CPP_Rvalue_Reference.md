> http://thbecker.net/articles/rvalue_references/section_01.html

## C++ Rvalue References Explained

Rvalue references solve at least two problems:

1. Implementing move semantics
2. Perfect forwarding




An lvalue is an expression that refers to a memory location and allows us to take the address of that memory location via the & operator. 

An rvalue is an expression that is not an lvalue. Examples are:

```
  // lvalues:
  int i = 42;
  i = 43; // ok, i is an lvalue
  int* p = &i; // ok, i is an lvalue
  int& foo();
  foo() = 42; // ok, foo() is an lvalue
  int* p1 = &foo(); // ok, foo() is an lvalue

```
Note : 

[What does “int& foo()” mean in C++](http://stackoverflow.com/questions/36477542/what-does-int-foo-mean-in-c)
```
  
  // rvalues:
  int foobar();
  int j = 0;
  j = foobar(); // ok, foobar() is an rvalue
  int* p2 = &foobar(); // error, cannot take the address of an rvalue
  j = 42; // ok, 42 is an rvalue
```

## Move Semantics

Suppose X is a class that holds a pointer or handle to some resource, say, m_pResource.
By a resource, anything in that class takes a great effort to construct, clone, and destruct.
A good example is std::vector, which holds a collection of objects that live in an array of allocated memory.

in order to much more efficient, to swap resource pointers (handles) between x and the temporary, and then let the temporary's destructor destruct x's original resource. In other words, in the special case where the right hand side of the assignment is an rvalue, we want the copy assignment operator to act like this:
```
// [...]
// swap m_pResource and rhs.m_pResource
// [...]  
```

This is called move semantics. With C++11, this conditional behavior can be achieved via an overload:
```
X& X::operator=(<mystery type> rhs)
{
  // [...]
  // swap this->m_pResource and rhs.m_pResource
  // [...]  
}
```
Note:

[What are move semantics?](http://stackoverflow.com/questions/3106110/what-are-move-semantics)

## Rvalue References

If X is any type, then X&& is called an rvalue reference to X.
For better distinction, the ordinary reference X& is now also called an lvalue reference.

The most important one is that when it comes to function overload resolution, lvalues prefer old-style lvalue references, whereas rvalues prefer the new rvalue references:

```
void foo(X& x); // lvalue reference overload
void foo(X&& x); // rvalue reference overload

X x;
X foobar();

foo(x); // argument is lvalue: calls foo(X&)
foo(foobar()); // argument is rvalue: calls foo(X&&)

```

this kind of overload should occur only for copy constructors and assignment operators, for the purpose of achieving move semantics:
```
X& X::operator=(X const & rhs); // classical implementation
X& X::operator=(X&& rhs)
{
  // Move semantics: exchange content between this and rhs
  return *this;
}
```

## Forcing Move Semantics

In C++11, there is an std library function called std::move. 
It is a function that turns its argument into an rvalue without doing anything else.
Therefore, in C++11, the std library function swap looks like this:

```
template<class T> 
void swap(T& a, T& b) 
{ 
  T tmp(std::move(a));
  a = std::move(b); 
  b = std::move(tmp);
} 

X a, b;
swap(a, b);

```

Now consider the line
a = std::move(b); 

If move semantics are implemented as a simple swap, then the effect of this is that the objects held by a and b are being exchanged between a and b. 

## Is an Rvalue Reference an Rvalue?

Things that are declared as rvalue reference can be lvalues or rvalues.
The distinguishing criterion is: if it has a name, then it is an lvalue. Otherwise, it is an rvalue.

The “X&& x” is declared as an rvalue reference has a name, and therefore, it is an lvalue:
```
void foo(X&& x)
{
  X anotherX = x; // calls X(X const & rhs)
}
```

Here is an example of something that is declared as an rvalue reference and does not have a name, and is therefore an rvalue:
```
X&& goo();
X x = goo(); // calls X(X&& rhs) because the thing on
             // the right hand side has no name
```

"If it does not have a name, then it's an rvalue" that allows us to achieve that in a controlled manner. 
That's how the function std::move works. 

 Although it is still too early to show you the exact implementation, we just got a step closer to understanding std::move.

 It passes its argument right through by reference, doing nothing with it at all, and its result type is rvalue reference. 
 So the expression
***std::move(x)***
is declared as an rvalue reference and does not have a name. 

Suppose you have written a class Base, and you have implemented move semantics by overloading Base's copy constructor and assignment operator:
```
Base(Base const & rhs); // non-move semantics
Base(Base&& rhs); // move semantics
```
Now you write a class Derived that is derived from Base. 
```
Derived(Derived const & rhs) 
  : Base(rhs)
{
  // Derived-specific stuff
}
```

The version for rvalues has a big fat subtlety. Here's what someone who is not aware of the if-it-has-a-name rule might have done:

```
Derived(Derived&& rhs) 
  : Base(rhs) // wrong: rhs is an lvalue
{
  // Derived-specific stuff
}
```
f we were to code it like that, the non-moving version of Base's copy constructor would be called, because rhs, having a name, is an lvalue. 
What we want to be called is Base's moving copy constructor, and the way to get that is to write
```
Derived(Derived&& rhs) 
  : Base(std::move(rhs)) // good, calls Base(Base&& rhs)
{
  // Derived-specific stuff
}
```

## Move Semantics and Compiler Optimizations


Consider the following function definition:
```
X foo()
{
  X x;
  // perhaps do something to x
  return x;
}
```
Let me using move semantics instead:

```
X foo()
{
  X x;
  // perhaps do something to x
  return std::move(x); // making it worse!
}

```
Unfortunately, that would make things worse rather than better. 
Any modern compiler will apply return value optimization to the original function definition. 
In other words, rather than constructing an X locally and then copying it out, the compiler would construct the X object directly at the location of foo's return value. 
Rather obviously, that's even better than move semantics

## Perfect Forwarding: The Problem

Consider the following simple factory:

```
template<typename T, typename Arg> 
shared_ptr<T> factory(Arg arg)
{ 
  return shared_ptr<T>(new T(arg));
} 
```
Obviously, the intent here is to forward the argument arg from the factory function to T's constructor.

The code above fails miserably at that: it introduces an extra call by value, which is particularly bad if the constructor takes its argument by reference.

The most common solution, chosen e.g. by boost::bind, is to let the outer function take the argument by reference:
```
template<typename T, typename Arg> 
shared_ptr<T> factory(Arg& arg)
{ 
  return shared_ptr<T>(new T(arg));
} 

```
That's better, but not perfect. The problem is that now, the factory function cannot be called on ***rvalues***:

```
factory<X>(hoo()); // error if hoo returns by value
factory<X>(41); // error
```

## Perfect Forwarding: The Solution

in pre-11 C++, it was not allowed to take a reference to a reference: something like A& & would cause a compile error. 
C++11, by contrast, introduces the following reference collapsing rules1:
```
 A& & becomes A&

 A& && becomes A&

 A&& & becomes A&

 A&& && becomes A&&
```
Note:
For more things to the modern CPP template deduction, ***effective modern C++ Item1*** 

Secondly, there is a special template argument deduction rule for function templates that take an argument by rvalue reference to a template argument:
```
template<typename T>
void foo(T&&);

```

Here, the following apply:

1. When foo is called on an lvalue of type A, then T resolves to A& and hence, by the reference collapsing rules above, the argument type effectively becomes A&.
2. When foo is called on an rvalue of type A, then T resolves to A, and hence the argument type becomes A&&.

Here's what the solution looks like this:

```
template<typename T, typename Arg> 
shared_ptr<T> factory(Arg&& arg)
{ 
  return shared_ptr<T>(new T(std::forward<Arg>(arg)));
} 
```
where std::forward is defined as follows:
```
template<class S>
S&& forward(typename remove_reference<S>::type& a) noexcept
{
  return static_cast<S&&>(a);
} 
```

To see how the code above achieves perfect forwarding, we will discuss separately what happens when our factory function gets called on lvalues and rvalues. 
Let A and X be types. Suppose first that factory\<A\> is called on an lvalue of type X:
```
X x;
factory<A>(x);
```

Then, by the special template deduction rule stated above, factory's template argument Arg resolves to X&.
```
shared_ptr<A> factory(X& && arg)
{ 
  return shared_ptr<A>(new A(std::forward<X&>(arg)));
} 
```

Therefore, the compiler will create the following instantiations of factory and std::forward:
```
X& && forward(remove_reference<X&>::type& a) noexcept
{
  return static_cast<X& &&>(a);
} 
```

After evaluating the remove_reference and applying the reference collapsing rules, this becomes:


```
shared_ptr<A> factory(X& arg)
{ 
  return shared_ptr<A>(new A(std::forward<X&>(arg)));
} 

X& std::forward(X& a) 
{
  return static_cast<X&>(a);
} 

```


This is certainly perfect forwarding for lvalues: the argument arg of the factory function gets passed on to A's constructor through two levels of indirection, both by old-fashioned lvalue reference.

Next, suppose that factory\<A\> is called on an rvalue of type X:
```
X foo();
factory<A>(foo());
```

Then, again by the special template deduction rule stated above, factory's template argument Arg resolves to X. 
```
shared_ptr<A> factory(X&& arg)
{ 
  return shared_ptr<A>(new A(std::forward<X>(arg)));
} 
```

Therefore, the compiler will now create the following function template instantiations:
```
X&& forward(X& a) noexcept
{
  return static_cast<X&&>(a);
} 

```

If you want to dig a little deeper for extra credit, ask yourself this question: 
why is the remove_reference in the definition of std::forward needed?

The answer is, it is not really needed at all. 
If you use just S& instead of remove_reference\<S\>::type& in the defintion of std::forward, you can repeat the case distinction above to convince yourself that perfect forwarding still works just fine. 



Rejoice. We're almost done. It only remains to look at the implementation of std::move.

Remember, the purpose of std::move is to pass its argument right through by reference and make it bind like an rvalue. Here's the implementation:

```
template<class T> 
typename remove_reference<T>::type&&
std::move(T&& a) noexcept
{
  typedef typename remove_reference<T>::type&& RvalRef;
  return static_cast<RvalRef>(a);
} 
```

Suppose that we call std::move on an lvalue of type X:
```
X x;
std::move(x);
```

Suppose that we call std::move on an lvalue of type X:
X x;
std::move(x);
```
typename remove_reference<X&>::type&&
std::move(X& && a) noexcept
{
  typedef typename remove_reference<X&>::type&& RvalRef;
  return static_cast<RvalRef>(a);
} 
```

After evaluating the remove_reference and applying the new reference collapsing rules, this becomes:
```
X&& std::move(X& a) noexcept
{
  return static_cast<X&&>(a);
} 
```

That does the job: our lvalue x will bind to the lvalue reference that is the argument type, and the function passes it right through, turning it into an unnamed rvalue reference.


## Rvalue References And Exceptions

When you overload the copy constructor and the copy assignment operator of a class for the sake of move semantics, it is very much recommended that you do the following:

1. Strive to write your overloads in such a way that they cannot throw exceptions. That is often trivial, because move semantics typically do no more than exchange pointers and resource handles between two objects.
2. If you succeeded in not throwing exceptions from your overloads, then make sure to advertise that fact using the new noexcept keyword.

Note:
[perfect-forwarding](http://eli.thegreenplace.net/2014/perfect-forwarding-and-universal-references-in-c)