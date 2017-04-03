# 一切的开始 sqlmap.py main()
就是所谓的main

```
cmdLineParser() 
{  
    ...
    (args, _) = parser.parse_args(argv) 
}
```
将输入的参数解析，存储在class的变量中

initOptions(cmdLineOptions)  初始化各种变量与环境

start() 正式开始

# 主流程 controller.py start() 

start函数是实际的主流程

首先

``` if not checkConnection(suppressOutput=conf.forms) or not checkString() or not checkRegexp(): ```
检查地址是否可达，获得目标编码

checkWaf() 检测目标的waf保护

check = heuristicCheckSqlInjection(place, parameter) 进行简单的启发式注入测试

# 启发式注入测试(basic) checks.py heuristicCheckSqlInjection() 请求部分

只是一个最简单的注入测试

```
while randStr.count('\'') != 1 or randStr.count('\"') != 1:
        randStr = randomStr(length=10, alphabet=HEURISTIC_CHECK_ALPHABET)
```
获得一个只由 ' " \ ( ) , 这6个符号组成的payload(长度为10)，并且payload中单引号与双引号有且只有一个。这个payload用于尝试产生数据库错误来识别后端数据库。

``` page, _ = Request.queryPage(payload, place, content=True, raise404=False) ```
使用组合起来的payload

## 请求处理接口 connect.py queryPage(...)

这个接口十分的复杂,但是sqlmap代码却十分的清晰。接口核心功能是处理各种输入的GET/POST请求以及用户指定的请求参数（例如指定请求头，自定义请求处理脚本）

大部分时候真正的请求接口是最后的getPage(除了 nullconnection, timebase injection, two order Request)：

```
 if not pageLength:
            try:
                page, headers, code = Connect.getPage(url=uri, get=get, post=post, method=method, cookie=cookie, ua=ua, referer=referer, host=host, silent=silent, auxHeaders=auxHeaders, response=response, raise404=raise404, ignoreTimeout=timeBasedCompare)
```

## http请求 connnect.py getPage(**kwargs)

这个接口的作用和上面的差不多，用于填充完整的HTTP header，对URL进行编码，使用urlopen()获得页面的返回，并将这个requset、response完整的记录下来。

核心的业务逻辑在 ``` processResponse(page, responseHeaders) ``` 这个函数调用。

## 响应结果处理basic.py processResponse(page, responseHeaders):

第一个参数是响应页面内容，第二个参数是响应属性比如响应头

processResponse() 函数是对返回页面进行处理的核心函数，其任务有:

1. 匹配服务端信息，尝试寻找显错信息判断后台数据库类型
```
parseResponse(page, responseHeaders if kb.processResponseCounter < PARSE_HEADERS_LIMIT else None)
{
    if headers:
    ##使用\xml\banner\...xml
    ##通过http header匹配目标端信息 具体匹配内容与对应文件见下文
        headersParser(headers)  

    if page:
    ##使用\xml\error.xml
    ##通过返回页面的显错来判断后台数据库类型
        htmlParser(page)
}
```

XML 与 http 头匹配对应文件
```

"cookie":                          "cookie.xml"
"microsoftsharepointteamservices": "sharepoint.xml"
"server":                          "server.xml"
"servlet-engine":                  "servlet.xml"
"set-cookie":                      "cookie.xml"
"x-aspnet-version":                "x-aspnet-version.xml"
"x-powered-by":                    "x-powered-by.xml"

```

显错判断部分流程
```
    ## 使用xml文件判断数据库
    parseXmlFile(xmlfile, handler)
    ## 如果成功匹配 则记录到 kb.htmlFp
    if handler.dbms and handler.dbms not in kb.htmlFp:
        kb.lastParserStatus = handler.dbms
        kb.htmlFp.append(handler.dbms)
    else:
        kb.lastParserStatus = None
```

2. 判断返回的页面中有无 Select ... From 之类的语句

```
if not kb.tableFrom and Backend.getIdentifiedDbms() in (DBMS.ACCESS,):
        kb.tableFrom = extractRegexResult(SELECT_FROM_TABLE_REGEX, page) 
        ## SELECT_FROM_TABLE_REGEX = r"\bSELECT .+? FROM (?P<result>([\w.]|`[^`<>]+`)+)"
    else:
        kb.tableFrom = None
```

3. 处理表单中的__EVENTVALIDATION __VIEWSTATE 属性
```
if kb.originalPage is None:
        for regex in (EVENTVALIDATION_REGEX, VIEWSTATE_REGEX):
```

4. 判断返回页面中有无 “ 输入验证码” 之类的字样
```
 if not kb.captchaDetected and re.search(r"(?i)captcha", page or ""):
        for match in re.finditer(r"(?si)<form.+?</form>", page):
```

5. 简单判断有没有被防火墙拦截
```
    ## BLOCKED_IP_REGEX = r"(?i)(\A|\b)ip\b.*\b(banned|blocked|block list|firewall)"
if re.search(BLOCKED_IP_REGEX, page):
        warnMsg = "it appears that you have been blocked by the target server"
        singleTimeWarnMessage(warnMsg)
```

自此 启发式注入测试的请求部分结束

# 启发式注入测试(basic) checks.py heuristicCheckSqlInjection() 处理部分

``` page, _ = Request.queryPage(payload, place, content=True, raise404=False) ``` 

使用payload对页面进行请求之后返回，设置了一些请求的属性，这些属性一部分将会在heuristicCheckSqlInjection函数中被处理。

1.检测返回页面中有没有出现路径 common.py parseFilePaths(page)
```
parseFilePaths(page)
{
     if page:
        for regex in FILE_PATH_REGEXES:
            for match in re.finditer(regex, page):
                ...
}
```
其中使用的匹配正则是：
```
'in (?P<result>[^<>]+?) on line \\d+'
' in (file )?<b>(?P<result>.*?)</b> on line \\d+'
'(?:[>(\\[\\s])(?P<result>[A-Za-z]:[\\\\/][\\w. \\\\/-]*)'
'(?:[>(\\[\\s])(?P<result>/\\w[/\\w.-]+)'
'href=[\'\\"]file://(?P<result>/[^\'\\"]+)'
```

2. 检测返回页面中有无出现“数据类型错误”之类的SQL报错
```
 def _(page):
        return any(_ in (page or "") for _ in FORMAT_EXCEPTION_STRINGS)

    casting = _(page) and not _(kb.originalPage)
```

其中FORMAT_EXCEPTION_STRINGS的报错检测是
```
(
 "Type mismatch",
 "Error converting",
 "Conversion failed",
 "String or binary data would be truncated",
 "Failed to convert",
 "unable to interpret text value",
 "Input string was not in a correct format",
 "System.FormatException",
 "java.lang.NumberFormatException",
 "ValueError: invalid literal",
 "DataTypeMismatchException",
 "CF_SQL_INTEGER",
 " for CFSQLTYPE ",
 "cfqueryparam cfsqltype",
 "InvalidParamTypeException",
 "Invalid parameter type",
 "is not of type numeric",
 "<cfif Not IsNumeric(",
 "invalid input syntax for integer",
 "invalid input syntax for type",
 "invalid number",
 "character to number conversion error",
 "unable to interpret text value",
 "String was not recognized as a valid",
 "Convert.ToInt",
 "cannot be converted to a ",
 "InvalidDataException"
 )
```
3. 尝试对数字类型进行测试

条件是没有返回数据库错误与数据类型错误，并且参数是一个数字类型的动态参数。

动态参数在 controller.py start()函数中的 check = checkDynParam(place, parameter, value) 进行测试，使用一个随机的数字对参数进行填充查看返回页面是否产生了变化来判断是不是动态的页面。

然后将参数换成减法表达式查看2次返回的页面是否相同，如果相同则这个参数可能存在注入。

例如，测试 ?id=1 与 ?id=299-298 这2次get请求的返回页面是否相同

```
 if not casting and not result and kb.dynamicParameter and origValue.isdigit():
        randInt = int(randomInt())  
        payload = "%s%s%s" % (prefix, "%d-%d" % (int(origValue) + randInt, randInt), suffix)
        payload = agent.payload(place, parameter, newValue=payload, where=PAYLOAD.WHERE.REPLACE)
        result = Request.queryPage(payload, place, raise404=False)

```
如果返回页面不同，再参数后面加上 . random(1-9) random(a-z) random(a-z) random(a-z) random(a-z)

例如测试 ?id=1 与 ?id=1.4aser 同样查看返回页面是否相同

```
if not casting and not result and kb.dynamicParameter and origValue.isdigit():
    ..........
    if not result:
            randStr = randomStr()
            payload = "%s%s%s" % (prefix, "%s.%d%s" % (origValue, random.randint(1, 9), randStr), suffix)
            payload = agent.payload(place, parameter, newValue=payload, where=PAYLOAD.WHERE.REPLACE)
            casting = Request.queryPage(payload, place, raise404=False)
```

PS.判断页面2次返回的结果是否相同在connect.py queryPage()函数的最后几行，通过

```return comparison(page, headers, code, getRatioValue, pageLength) ```

对本次请求与上次不同的请求进行判断

4. 注入测试结果输出

如果出现了 SQL “数据类型” 错误 ，或者 上面的?id=1 与 ?id=1.4aser测试的2次返回结果相同

那么SQLMAP 就认为后台存在整形强制转换，并认为这是一个“错误”（无法注入）

```
if casting:
        errMsg = "possible %s casting " % ("integer" if origValue.isdigit() else "type")
        errMsg += "detected (e.g. \"$%s=intval($_REQUEST['%s'])\") " % (parameter, parameter)
        errMsg += "at the back-end web application"
        logger.error(errMsg)
```

并且输出：
[time] [ERROR] possible integer casting detected (e.g. "$id=intval($_REQUEST['id'])") at the back-end web application

之类的描述

如果不存在上述的情况则通过DBMS的显错是否被设置来判断有无注入
```
    elif result: #产生了显错，有注入
        infoMsg += "be injectable"
        if Backend.getErrorParsedDBMSes():
            infoMsg += " (possible DBMS: '%s')" % Format.getErrorParsedDBMSes()
        logger.info(infoMsg)

    else:
        infoMsg += "not be injectable"
        logger.warn(infoMsg)
```

5.XSS测试 文件包含测试

生成一个简单的XSS payload: randomStr(6) <\'"> randomStr(6) 

例如： 'qYhMkL<\'">LaaOpD'

```
 value = "%s%s%s" % (randStr1, DUMMY_NON_SQLI_CHECK_APPENDIX, randStr2)
    payload = "%s%s%s" % (prefix, "'%s" % value, suffix)
```

通过查看返回的页面是否存在输入内容（没有对<\'">进行转义）简单的判断是不是有XSS 漏洞

通过正则测试是否有文件包含漏洞

```
for match in re.finditer(FI_ERROR_REGEX, page or ""): ##FI_ERROR_REGEX = "(?i)[^\n]*(no such file|failed (to )?open)[^\n]*"
    if randStr1.lower() in match.group(0).lower():
        infoMsg = "heuristic (FI) test shows that %s parameter " % paramType
        infoMsg += "'%s' might be vulnerable to file inclusion attacks" % parameter
        logger.info(infoMsg)
        break

```

自此 启发式注入测试(basic) 流程基本完成

# 注入测试 controller.py checkSqlInjection(place, parameter, value)

注入测试的核心是使用给定好的payload一一进行测试

实际上sqlmap就是一个payload发射器

## payload boundary格式

sqlmap读取xml的配置生成指定的payload进行注入测试

xml的格式处理不在这一章节进行介绍

payload 的格式是：``` <prefix> <payload><comment> <suffix> ```

其中的prefix suffix用于对注入数据进行“截断”尝试让payload注入后组成一个有效的sql语句，这部分是boundary模块进行控制的。

payload与comment是真正的测试语句用于生成 bool注入 union注入 时间注入 等注入的payload模块。

payload格式复制自<boolean_blind.xml>:
```
Tag: 
    SQL injection test definition.

    Sub-tag: <title>
        Title of the test.

    Sub-tag: <stype>
        SQL injection family type.

        Valid values:
            1: Boolean-based blind SQL injection
            2: Error-based queries SQL injection
            3: Inline queries SQL injection
            4: Stacked queries SQL injection
            5: Time-based blind SQL injection
            6: UNION query SQL injection

    Sub-tag: <level>
        From which level check for this test.

        Valid values:
            1: Always (<100 requests)
            2: Try a bit harder (100-200 requests)
            3: Good number of requests (200-500 requests)
            4: Extensive test (500-1000 requests)
            5: You have plenty of time (>1000 requests)

    Sub-tag: <risk>
        Likelihood of a payload to damage the data integrity.

        Valid values:
            1: Low risk
            2: Medium risk
            3: High risk

    Sub-tag: <clause>
        In which clause the payload can work.

        NOTE: for instance, there are some payload that do not have to be
        tested as soon as it has been identified whether or not the
        injection is within a WHERE clause condition.

        Valid values:
            0: Always
            1: WHERE / HAVING
            2: GROUP BY
            3: ORDER BY
            4: LIMIT
            5: OFFSET
            6: TOP
            7: Table name
            8: Column name
            9: Pre-WHERE (non-query)

        A comma separated list of these values is also possible.

    Sub-tag: <where>
        Where to add our '<prefix> <payload><comment> <suffix>' string.

        Valid values:
            1: Append the string to the parameter original value
            2: Replace the parameter original value with a negative random
               integer value and append our string
            3: Replace the parameter original value with our string

    Sub-tag: <vector>
        The payload that will be used to exploit the injection point.

    Sub-tag: <request>
        What to inject for this test.

        Sub-tag: <payload>
            The payload to test for.

        Sub-tag: <comment>
            Comment to append to the payload, before the suffix.

        Sub-tag: <char>
            Character to use to bruteforce number of columns in UNION
            query SQL injection tests.

        Sub-tag: <columns>
            Range of columns to test for in UNION query SQL injection
            tests.

    Sub-tag: <response>
        How to identify if the injected payload succeeded.

        Sub-tag: <comparison>
            Perform a request with this string as the payload and compare
            the response with the <payload> response. Apply the comparison
            algorithm.

            NOTE: useful to test for boolean-based blind SQL injections.

        Sub-tag: <grep>
            Regular expression to grep for in the response body.

            NOTE: useful to test for error-based SQL injection.

        Sub-tag: <time>
            Time in seconds to wait before the response is returned.

            NOTE: useful to test for time-based blind and stacked queries
            SQL injections.

        Sub-tag: <union>
            Calls unionTest() function.

            NOTE: useful to test for UNION query (inband) SQL injection.

    Sub-tag: <details>
        Which details can be infered if the payload succeed.

        Sub-tags: <dbms>
            What is the database management system (e.g. MySQL).

        Sub-tags: <dbms_version>
            What is the database management system version (e.g. 5.0.51).

        Sub-tags: <os>
            What is the database management system underlying operating
            system.

```

其中需要注意的一点是 payload 是以 ```<prefix> <payload><comment> <suffix> ```进行组合的，而payload本身属性不带有``` <prefix>与<suffix> ```选项，这二个参数由boundaries决定

boundaries格式复制自 <boundaries.xml>
```
Tag: <boundary>
    How to prepend and append to the test ' <payload><comment> ' string.

    Sub-tag: <level>
        From which level check for this test.

        Valid values:
            1: Always (<100 requests)
            2: Try a bit harder (100-200 requests)
            3: Good number of requests (200-500 requests)
            4: Extensive test (500-1000 requests)
            5: You have plenty of time (>1000 requests)

    Sub-tag: <clause>
        In which clause the payload can work.

        NOTE: for instance, there are some payload that do not have to be
        tested as soon as it has been identified whether or not the
        injection is within a WHERE clause condition.

        Valid values:
            0: Always
            1: WHERE / HAVING
            2: GROUP BY
            3: ORDER BY
            4: LIMIT
            5: OFFSET
            6: TOP
            7: Table name
            8: Column name
            9: Pre-WHERE (non-query)

        A comma separated list of these values is also possible.

    Sub-tag: <where>
        Where to add our '<prefix> <payload><comment> <suffix>' string.

        Valid values:
            1: When the value of <test>'s <where> is 1.
            2: When the value of <test>'s <where> is 2.
            3: When the value of <test>'s <where> is 3.

        A comma separated list of these values is also possible.

    Sub-tag: <ptype>
        What is the parameter value type.

        Valid values:
            1: Unescaped numeric
            2: Single quoted string
            3: LIKE single quoted string
            4: Double quoted string
            5: LIKE double quoted string

    Sub-tag: <prefix>
        A string to prepend to the payload.

    Sub-tag: <suffix>
        A string to append to the payload.
```

自此我们有了基础的payload格式，

下面是真正的checkSqlInjection()代码分析

## 注入测试代码

1.初始化

获得bounday数组

```
如果参数的输入是一个数字
对初始的intBoundaries进行排序，把suffix与prefix中没有单引号与双引号的放到数组前面
if value.isdigit():
        kb.cache.intBoundaries = kb.cache.intBoundaries or sorted(copy.deepcopy(conf.boundaries), key=lambda boundary: any(_ in (boundary.prefix or "") or _ in (boundary.suffix or "") for _ in ('"', '\'')))
        boundaries = kb.cache.intBoundaries
    else:
        boundaries = conf.boundaries
```

获得 payload 数组

```
tests = getSortedInjectionTests()
seenPayload = set()

kb.data.setdefault("randomInt", str(randomInt(10)))
kb.data.setdefault("randomStr", str(randomStr(10)))

循环读出payload进行测试
while tests:
    test = tests.pop(0)
```

对于函数getSortedInjectionTests()如果已经获得了数据库的类型，那么会对payload进行排序把相应目标数据库的payload放在前面。

之后的一大堆判断是对bool注入的后台数据库类型进行判断，这个逻辑不在这里进行讨论。

然后是针对union select 注入payload 的处理，跳过，这里我们先讨论用于一般情况的BOOL类型注入。

根据用户的输入选项跳过一些payload 的测试，选项十分的多。

## boolean-base 注入基本逻辑

1. 首先使用agent.cleanupPayload() 对test中的payload进行初始化

```
comment = agent.getComment(test.request) if len(conf.boundaries) > 1 else None
fstPayload = agent.cleanupPayload(test.request.payload, origValue=value if place not in (PLACE.URI, PLACE.CUSTOM_POST, PLACE.CUSTOM_HEADER) else None)
```

agent.py cleanupPayload(self, payload, origValue=None)

初始化步骤首先使用随机数/字符串填充 [RANDNUM] 与 [RANDSTR] 
```
for _ in set(re.findall(r"\[RANDNUM(?:\d+)?\]", payload, re.I)):
    payload = payload.replace(_, str(randomInt()))

for _ in set(re.findall(r"\[RANDSTR(?:\d+)?\]", payload, re.I)):
    payload = payload.replace(_, randomStr())
```

使用原始的输入数据填充 [ORIGVALUE]

```
if origValue is not None and "[ORIGVALUE]" in payload:
            payload = getUnicode(payload).replace("[ORIGVALUE]", origValue if origValue.isdigit() else unescaper.escape("'%s'" % origValue))
```

如果已经确定了后台数据库，则可以使用带有[INFERENCE]模块的payload

[INFERENCE]用于对于特定的数据库使用特定的语句进行数据库内容的推导 例如mysql 的推导payload格式是```ORD(MID((%s),%d,1))>%d```

当然这不是现在需要详细介绍的模块
```
if "[INFERENCE]" in payload:
    ##通过已经判断好的数据库获得相应的payload
            if Backend.getIdentifiedDbms() is not None:
                inference = queries[Backend.getIdentifiedDbms()].inference

                if "dbms_version" in inference:
                    if isDBMSVersionAtLeast(inference.dbms_version):
                        inferenceQuery = inference.query
                    else:
                        inferenceQuery = inference.query2
                else:
                    inferenceQuery = inference.query

                payload = payload.replace("[INFERENCE]", inferenceQuery)
            elif not kb.testMode:
                errMsg = "invalid usage of inference payload without "
                errMsg += "knowledge of underlying DBMS"
                raise SqlmapNoneDataException(errMsg)
```

回到checks.py

2. boundary 遍历测试

填充了payload之后进入一个循环，循环一一获得boundry进行测试

对于boundary有以下要求，因此实际上对于一个payload会跳过许多boundary：

~ boundary的level不高于用户指定level （默认为1）

~ boundary的clause在payload 的可选范围之内

~ boundary的where在payload 的可选范围之内

然后使用perfix payload suffix 组合一次boundPayload, 再使用组合一次boundPayload然后组成reqPayload:

```
if fstPayload:
    boundPayload = agent.prefixQuery(fstPayload, prefix, where, clause)
    boundPayload = agent.suffixQuery(boundPayload, comment, suffix, where)
    reqPayload = agent.payload(place, parameter, newValue=boundPayload, where=where)
    if reqPayload:
        ## 记录这个payload 如果这个payload已经在记录中表示已经使用过了，那么不再使用
        if reqPayload in seenPayload:
            continue
        else:
            seenPayload.add(reqPayload)
else:
    reqPayload = None
```
组合的boundPayload没有对 boundary中的prefix与suffix进行格式化处理

而reqPayload是完成格式化处理之后的

3. boolean 注入测试

首先获得payload中的测试方式与比较方式，然后进入相应的测试分支（这里是boolean-base测试）

然后对test.response.comparison 也就是比较方式进行格式化:

```
 for method, check in test.response.items():
    ##对check进行格式化
    check = agent.cleanupPayload(check, origValue=value if place not in (PLACE.URI, PLACE.CUSTOM_POST, PLACE.CUSTOM_HEADER) else None)
    ##进入boolen盲注分支
    if method == PAYLOAD.METHOD.COMPARISON:
        ##对test.response.comparison进行格式化的功能函数
        def genCmpPayload():
            sndPayload = agent.cleanupPayload(test.response.comparison, origValue=value if place not in (PLACE.URI, PLACE.CUSTOM_POST, PLACE.CUSTOM_HEADER) else None)

            # Forge response payload by prepending with
            # boundary's prefix and appending the boundary's
            # suffix to the test's ' <payload><comment> '
            # string
            boundPayload = agent.prefixQuery(sndPayload, prefix, where, clause)
            boundPayload = agent.suffixQuery(boundPayload, comment, suffix, where)
            cmpPayload = agent.payload(place, parameter, newValue=boundPayload, where=where)

            return cmpPayload
        ##重要 页面判断是通过匹配率进行判断的 见下面的匹配率判断
        kb.matchRatio = None
        kb.negativeLogic = (where == PAYLOAD.WHERE.NEGATIVE)
        ##对cmp进行格式化 然后进行一次"false"注入测试
        Request.queryPage(genCmpPayload(), place, raise404=False)
        ##记录返回结果
        falsePage, falseHeaders, falseCode = threadData.lastComparisonPage or "", threadData.lastComparisonHeaders, threadData.lastComparisonCode
        falseRawResponse = "%s%s" % (falseHeaders, falsePage)
```
这里sqlmap会先使用 test.response.comparison 与 boundary 先进行一次组合，在一般情况下 comparison 的格式是 'AND [RANDNUM]=[RANDNUM1]'

这种情况下生成的payload 在bool中是“false” (因为二次randnum值不同)  

因此第一次测试被称为：the False response content

接下来是第二次测试，第二次测试发送到是上面通过payload生成好的reqPayload:

```
    ##发送reqPayload进行 test's True request
    trueResult = Request.queryPage(reqPayload, place, raise404=False)
    truePage, trueHeaders, trueCode = threadData.lastComparisonPage or "", threadData.lastComparisonHeaders, threadData.lastComparisonCode
    ##记录返回页面与响应头
    trueRawResponse = "%s%s" % (trueHeaders, truePage)
```

由于 payload 一般的格式类似于AND [RANDNUM]=[RANDNUM1] 生成的random number值是一样的，因此这次测试被称为test's True request

如果2次返回的结果不同，进行第三次False request请求：

```
if trueResult and not(truePage == falsePage and not kb.nullConnection):
    # Perform the test's False request
    falseResult = Request.queryPage(genCmpPayload(), place, raise404=False)

    if not falseResult:
    ...

    injectable = True
```
如果第三次返回结果与第二次还不同 那么就认为这个页面存在注入了。

注意，如果payload的where属性为： 2: Replace the parameter original value with a negative random integer value and append our string

还需要进一步的判断

附：返回页面处理

在最开始的请求部分我们说过，对于直接使用payload的Request.queryPage()的返回结果是与上一次的成功返回页面进行一次比较。

对于bool注入来说这个比较十分重要，因为2次比较直接决定了注入判断。

因此在比较中进行如下处理：对返回页面中是否存在payload内容进行匹配与替换，功能函数： removeReflectiveValues()

这样，如果返回页面中出现payload也不会影响到判断结果。

附 匹配率判断

匹配率的计算通过[Python标准库]difflib.py

bool注入的核心问题之一就是如何判断返回的页面是否相同

sqlmap使用了匹配率判断，如果第一次“false”请求与第二次“true请求的”匹配率大于0.02小于0.98，那么就认为2次返回结果不同并且是可能有注入的

对于大于0.98情况就认为几乎相同 小于0.02 认为完全不同
```
if kb.matchRatio is None:
        if ratio >= LOWER_RATIO_BOUND and ratio <= UPPER_RATIO_BOUND:##0.02=< ratio <=0.98
            kb.matchRatio = ratio
            logger.debug("setting match ratio for current parameter to %.3f" % kb.matchRatio)
```
并且使用kb.matchRatio保存这个匹配率(kb.matchRatio在每个boundary都会测试之前都会重置为none)

在第二次false请求之后会对这次请求与上一次请求进行判断，如果这次比较的匹配率与kb.matchRatio记录的差值小于0.05那么就返回false即认为有注入。

我使用伪代码描述这整一个流程：
```
payload_1 = "FALSE"
returnPage_1 = responsePage(payload_1)

payload_2 = "True"
returnPage_2 = responsePage(payload_2)

matchRatio = compare(returnPage_1,returnPage_2)
if(0.02 =< matchRatio =< 0.98)
    kb.matchRatio = matchRatio//mark
else
    goto fail

payload_3 = "FALSE"
returnPage_3 = responsePage(payload_3)
matchRatio = compare(returnPage_3,returnPage_2)
if((matchRatio - kb.matchRatio) < 0.05)
    goto success
else
    goto fail

```

当然，我认为在某些特殊的情况下也可能出现二次比较成功但是注入测试错误的情况？

4.bool注入通过后处理

对于返回code不同则直接输出：
```
if all((falseCode, trueCode)) and falseCode != trueCode:
    conf.code = trueCode

    infoMsg = "%s parameter '%s' appears to be '%s' injectable (with --code=%d)" % (paramType, parameter, title, conf.code)
    logger.info(infoMsg)
```

否则先分别提取出false与true页面中的text有关的html字段的信息，例如```<title>Title</title>```

然后将这2个中不同的部分找出来，如果这部分是系类的可显内容则输出信息
```
 else:
    ###提取字段
    trueSet = set(extractTextTagContent(trueRawResponse))##get text like html
    trueSet = trueSet.union(__ for _ in trueSet for __ in _.split())

    falseSet = set(extractTextTagContent(falseRawResponse))
    falseSet = falseSet.union(__ for _ in falseSet for __ in _.split())
    ###去重
    candidates = filter(None, (_.strip() if _.strip() in trueRawResponse and _.strip() not in falseRawResponse else None for _ in (trueSet - falseSet)))
    ###log
    if candidates:
        candidates = sorted(candidates, key=lambda _: len(_))
        for candidate in candidates:
            if re.match(r"\A\w+\Z", candidate):
                break

        conf.string = candidate

        infoMsg = "%s parameter '%s' appears to be '%s' injectable (with --string=\"%s\")" % (paramType, parameter, title, repr(conf.string).lstrip('u').strip("'"))
        logger.info(infoMsg)
```

以上是bool注入测试部分

之后的代码记录了一些信息，并对后面的处理进行初始化，然后退出循环

进入数字指纹识别模块

## boolean-base 后台数据库识别

后台数据库识别的入口在checks.py checkSqlInjection(place, parameter, value)开始一点

当上面的注入测试通过并且后台数据库没有被指定时，进入bool注入数据库识别模块：

```
 if conf.dbms is None:
    # If the DBMS has not yet been fingerprinted (via simple heuristic check
    # or via DBMS-specific payload) and boolean-based blind has been identified
    # then attempt to identify with a simple DBMS specific boolean-based
    # test what the DBMS may be
    if not injection.dbms and PAYLOAD.TECHNIQUE.BOOLEAN in injection.data:
        if not Backend.getIdentifiedDbms() and kb.heuristicDbms is None:
            kb.heuristicDbms = heuristicCheckDbms(injection)
```

数据库识别本质上就是遍历使用每个数据库特有的bool类型注入语句，进行一次true一次false测试，如果2次测试都通过了就认为识别出了后台数据库:

check.py  heuristicCheckDbms(injection)
```
    ##遍历数据库
    for dbms in getPublicTypeMembers(DBMS, True):
        randStr1, randStr2 = randomStr(), randomStr()
        Backend.forceDbms(dbms)

        if conf.noEscape and dbms not in FROM_DUMMY_TABLE:
            continue
        ##注入测试2次
        if checkBooleanExpression("(SELECT '%s'%s)='%s'" % (randStr1, FROM_DUMMY_TABLE.get(dbms, ""), randStr1)):
            if not checkBooleanExpression("(SELECT '%s'%s)='%s'" % (randStr1, FROM_DUMMY_TABLE.get(dbms, ""), randStr2)):
                retVal = dbms##成功
                break

```

识别注入测试的核心是生成每个数据库对应的payload, payload在FROM_DUMMY_TABLE.get(dbms, "")已经存放好了,可以通过对应的数据库取出，当然有些数据库不需要就可以直接判断。
```
FROM_DUMMY_TABLE = {
    DBMS.ORACLE: " FROM DUAL",
    DBMS.ACCESS: " FROM MSysAccessObjects",
    DBMS.FIREBIRD: " FROM RDB$DATABASE",
    DBMS.MAXDB: " FROM VERSIONS",
    DBMS.DB2: " FROM SYSIBM.SYSDUMMY1",
    DBMS.HSQLDB: " FROM INFORMATION_SCHEMA.SYSTEM_USERS",
    DBMS.INFORMIX: " FROM SYSMASTER:SYSDUAL"
}
```
生成了一个payload之后的第二步是对他进行编码,不同的数据库编码方式不同，编码部分的代码架构比较奇怪，入口是

agent.py 中的 prefixQuery(self, expression, prefix=None, where=None, clause=None)

```
 def prefixQuery(self, expression, prefix=None, where=None, clause=None):

        if conf.direct:
            return self.payloadDirect(expression)

        if expression is None:
            return None

        expression = self.cleanupPayload(expression)
        ##进行编码
        expression = unescaper.escape(expression)
        query = None

```
unescaper.escape(expression)编码调用的实现是通过"插件函数"方式实现

所谓“插件函数”是指在 plufins\dbms\ 这个目录下有各种数据库对应的文件夹，其中的各个syntax.py就是实际的实现函数

然后在各个数据库的_init_.py通过 unescaper[DBMS.ACCESS] = Syntax.escape 保存对应的函数指针，这样就像插件一样直接调用了

```
class Unescaper(AttribDict):
    def escape(self, expression, quote=True, dbms=None):
        if conf.noEscape:
            return expression

        if expression is None:
            return expression

        for exclude in EXCLUDE_UNESCAPE:
            if exclude in expression:
                return expression
        ##获得需要编码的数据库
        identifiedDbms = Backend.getIdentifiedDbms()
        ##调用相应的数据库编码处理函数
        if dbms is not None:
            return self[dbms](expression, quote=quote)
        elif identifiedDbms is not None:
            return self[identifiedDbms](expression, quote=quote)
        else:
            return expression
```

在上一节的 4.bool注入通过后处理  最后一部分说过，我们提取了“true page”与“false page ”返回页面中不同部分的信息

这些信息就被用来判断二次测试是否通过：
comparison.py _comparison(page, headers, code, getRatioValue, pageLength)
```
    ##String to match in page when the query is True and/or valid
    if conf.string:
        return conf.string in rawResponse

    ##String to match in page when the query is False and/or invalid
    if conf.notString:
        return conf.notString not in rawResponse


```

自此bool注入-数据库判断部分结束

# time-base 注入浅析

由于是简单分析就不列举一些细节了，只讲一下关键的逻辑。

这里使用boundary与payload的构造方式与之前的bool注入基本相同。

## 注入判断基本流程

time base 注入的判断也是2~3次：
```
elif method == PAYLOAD.METHOD.TIME:
    ##第一次测试时延
    trueResult = Request.queryPage(reqPayload, place, timeBasedCompare=True, raise404=False)
    trueCode = threadData.lastCode

    if trueResult:
        ##第二次测试没有时延
        if SLEEP_TIME_MARKER in reqPayload:
            falseResult = Request.queryPage(reqPayload.replace(SLEEP_TIME_MARKER, "0"), place, timeBasedCompare=True, raise404=False)
            if falseResult:
                continue

        ##再一次测试时延，确认存在注入
        trueResult = Request.queryPage(reqPayload, place, timeBasedCompare=True, raise404=False)

        if trueResult:
            infoMsg = "%s parameter '%s' appears to be '%s' injectable " % (paramType, parameter, title)
            logger.info(infoMsg)

            injectable = True
```
第一次使用生成的payload (/xml/paeyloads/time_blind.xml)进行一次注入测试，如果返回true进行下一步

如果SLEEP_TIME_MARKER 即 [SLEEPTIME] 标签在使用的payload 之中，则再进行一次测试，这次测试的时延设置为0，并确认返回的时候没有延迟

经过上面2次测试之后，最后在进行一次确认测试，确认测试的进行方式与第一次相同。

## 时间延迟判断

注入的一个重要流程是判断本次注入是否存在sleep之类函数导致的延迟

判断的逻辑:

```
    ##记录开始的时间
connect.py  -> def queryPage():

    start = time.time() 
    ##判断时延
common.py -> def wasLastResponseDelayed(): 
    ##计算之前请求所用时间的方差
    deviation = stdev(kb.responseTimes.get(kb.responseTimeMode, []))

    ##计算产生延迟的最长响应
    ##公式是: 之前请求响应的平均值 + 7 * 之前请求所用时间的方差
    lowerStdLimit = average(kb.responseTimes[kb.responseTimeMode]) + TIME_STDEV_COEFF * deviation

    ##进行判断 其中 MIN_VALID_DELAYED_RESPONSE = 0.5
    retVal = (threadData.lastQueryDuration >= max(MIN_VALID_DELAYED_RESPONSE, lowerStdLimit))

    return retVal
```

代码只写了关键部分

核心就是：计算方差 ===> 计算 平均值 + 7 * 方差  ===> 看之前那个值与0.5哪个大 ===> 判断返回

至于后台数据库的判断，由于time-base注入的特殊性质，即不同数据库的sleep方式不同，所以所有的payload自带相应的数据库

例如: 
```
[20:35:13] [INFO] testing 'MySQL >= 5.0.12 AND time-based blind'

[20:35:13] [PAYLOAD] 1) AND SLEEP(5) AND (6998=6998

[20:35:13] [PAYLOAD] 1 AND SLEEP(5)
```


# 结束语

虽然只分析了bool-base与time-base，不过已经足够了，sqlmap的注入测试部分就结束了，如果有机会再写关于他的注入数据获取流程与sqlmap架构部分

