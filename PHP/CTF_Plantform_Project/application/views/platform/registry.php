<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="page-header">
                <h2>
                    注册到 HCTF GAME <small>Sign Up To HCTF GAME CTF Platform</small>
                </h2>
            </div>
            <div class="form-horizontal back" role="form">
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="username" id="username" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="password1" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control" name="password1" id="password1" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="password2" class="col-sm-2 control-label">Confirm Password</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control" name="password2" id="password2" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-7">
                        <input type="email" class="form-control" name="email" id="email" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="qq" class="col-sm-2 control-label">QQ</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="qq" id="qq" />
                        <p class="help-block">请留下可以联系到你的联系方式</p>
                    </div>
                </div>

                <div class="form-group">
                    <h4>
                        如果是参加正式比赛的新生，你需要如实填写以下内容：
                    </h4>
                </div>

                <div class="form-group">
                    <label for="schoolid" class="col-sm-2 control-label">学号</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="schoolid" id="schoolid" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="realname" class="col-sm-2 control-label">真实姓名</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="realname" id="realname" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="college" class="col-sm-2 control-label">学院</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="college" id="college" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label><input type="checkbox" id="sf" name="sf" />我一定遵守当地法律法规，一定遵纪守法！</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button id="sub" type="button" class="btn btn-success btn-lg button" name="pp">注册</button>
                        <a class="btn btn-link" href="login">已经有账号了？登录去！</a>
                    </div>
                </div>
            </div>
            <div id="return-info">
        	<div class="alert alert-danger" role="alert" style="display: none;" id="daizou">你敢不遵守相关法律？</div>
        	<div class="alert alert-info" role="alert" style="display: none;" id="F">还没有填满呦</div>
        	<div class="alert alert-info" role="alert" style="display:none;" id="bad-pass">为什么要用不一样的密码嘞~</div>
        	<div class="alert alert-info" role="alert" style="display:none;'" id="bad-user">已经有人用这个名字啦！换一个吧！</div>
        	<div class="alert alert-info" role="alert" style="display:none;'" id="bad-email">能好好输入邮箱吗？</div>
        	<div class="alert alert-info" role="alert" style="display:none;'" id="bad-qq">能好好输入QQ吗？</div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="ser-error">遇到了一些问题，服务器君GG了！</div>
        	<div class="alert alert-success" role="alert" style="display: none;" id="suc">注册成功>_< <a href="/login">...自动跳转中</a></div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="net-error">网络不给力啊！</div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="hack">dont't hack me!</div>
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
		var url = "/con_registry";
		data['username'] = $('#username').val();
		data['password'] = $('#password1').val();
		data['email'] = $('#email').val();
		data['qq'] = $('#qq').val();

        if ($('#schoolid').val() != ""){
        data['schoolid'] = $('#schoolid').val();
        }

        if ($('#college').val() != ""){
        data['college'] = $('#college').val();
        }

        if ($('#realname').val() != ""){
        data['realname'] = $('#realname').val();
		}

        repass = $('#password2').val();
		for(var i in data){
			if (data[i] == "") {
				$('#blank-error').show();
				return;
			}
		}
		if (!$('#sf').is(':checked')) {
			$('#daizou').show();
			return;
		}
		if (repass != data['password']) {
			$('#bad-pass').show();
			return;
		}
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			dataType: 'json',
			success: function(data){
				if (data.code == 1) {
					$('#suc').show();
					setTimeout("location.href = '/login'", 2000);
				}
				else if (data.code == 2) {
					$('#blank-error').show();
				}
				else if (data.code == 3) {
					$('#bad-user').show();
				}
				else if (data.code == 4) {
					$('#bad-email').show();
				}
				else if (data.code == 5) {
					$('#bad-qq').show();
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