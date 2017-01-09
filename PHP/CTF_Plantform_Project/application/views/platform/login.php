<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="page-header">
                <h2>
                    登陆到 HCTF GAME <small>Sign In To HCTF GAME CTF Platform</small>
                </h2>
            </div>
            <div class="form-horizontal back" role="form">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-7">
                        <input id="username" type="text" class="form-control" name="username" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-7">
                        <input id="password" type="password" class="form-control" name="password" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-7">
                        <button id="sub" type="button" class="btn btn-success btn-lg" name="pp">登录</button>
                        <a class="btn btn-link" href="registry">没账号？注册去！</a>
                    </div>
                </div>
            </div>
<div id="return-info">
                <div class="alert alert-danger" role="alert" style="display: none;" id="ser-error">FUCK！插不进去，服务器君GG了！</div>
                <div id="fail" style="display: none;" class="alert alert-danger" role="alert">用户名或密码错误！</div>
                <div id="suc" style="display: none;" class="alert alert-success" role="alert">登录成功, <a href="/controll">Jumping...</a></div>
                <div id="blank-error" style="display: none;" class="alert alert-info" role="alert">填满了再来嘛~</div>
                <div class="alert alert-danger" role="alert" style="display: none;" id="net-error">网络不给力啊！</div>
                <div class="alert alert-danger" role="alert" style="display: none;" id="reject">管理员不让你登陆啊！</div>
</div>
        </div>
    </div>
</div>
<script>

$('input').click(function(){
    $('.alert').hide();
});

$('#sub').click(function(){
    var data = {};
    var url = "/con_login";
    data['username'] = $('#username').val();
    data['password'] = $('#password').val();
    if (data['username'] == '' || data['password'] == '') {
        $('#blank-error').show();
    }
    $.ajax({
        url: url,
        data: data,
        type: 'post',
        dataType: 'json',
        success: function(data){
            if (data.code == 1) {
                $('#suc').show();
                setTimeout("location.href = '/controll'", 2000);
            }
            else if (data.code == 2) {
                $('#blank-error').show();
            }
            else if (data.code == 3) {
                $('#fail').show();
            }
            else if (data.code == 4) {
                $('#reject').show();
            }
            else{
                $('#ser-error').show();
            }
        },
        error: function(){
            $('#net-error').show();
        }
    });
});
</script>