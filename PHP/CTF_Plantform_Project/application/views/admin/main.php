<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container"><br /><br />
    <div class="row clearfix">
        <div class="col-md-12 column">
            <ul class="nav nav-pills">
                <li class="active">
                    <a href="/adminfuckme_controll">首页</a>
                </li>
                <li>
                    <a href="#" id="add-new-problem">添加新题目</a>
                </li>
                <li>
                    <a href="#" id="manage-problem">管理题目</a>
                </li>
                <li>
                    <a href="#" id="user-problem">管理用户</a>
                </li>
                <li>
                    <a href="#" id="class-problem">管理分类</a>
                </li>
                <li>
                    <a href="#" id="system-setting">系统设置</a>
                </li>
            </ul><hr />
            <div id="admin-main-content">
                <div class="jumbotron well">
                    <h1>
                        管理员专用！
                    </h1>
                    <div>
                        <p>
                            你可以增加或修改原有题目，或是更改系统设置。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/static/js/system-admin.js"></script>