## //todolost

### 数据库

在表CTF_USER上加一个字段，用于标示 内部人员,校内人员，其它人员
在表CTF_USER上加三个字段 ： 姓名,学号,学院
在表CTF_PROBLEM 加一个字段表示是不是内部题目

### 页面

修改注册页面，支持校内人员：姓名,学号,学院

侧边栏 积分榜显示：总积分，本周积分

积分榜加一列用于显示校内人员的积分

### 后台

校内注册需要被后台审核，否则为其它人员

内部人员不出现在任何积分榜内

侧边栏直接显示校内积分，在积分榜上显示总积分

部分题目只有内部人员可见










## Overview

The CTF training platform.


## General Naming Rules

The format of the file name should be :
``` <PROJECT_NAME>_<FILE_NAME>. ```

The PROJECT_NAME is "CTF"


## deploy

modify the ``` $config['sess_save_path'] ``` and make sure the path is a writeable directory.

**modify the password of admin in CTF_admin_index.php**
```
if ($username == 'admin' && md5($password) == 'e10adc3949ba59abbe56e057f20f883e') {
			$this->session->set_userdata('admin', '1');
```

