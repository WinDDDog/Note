<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="page-header">
                <h1>
                    HCTF GAME管理平台 <small> HCTF GAME Manager Platform</small>
                </h1>
            </div>
 	<div class="form-horizontal">
                 <div class="form-group">
                    <label for="adminusername" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="adminusername" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="adminpassword" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="adminpassword" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-info" id="signin">Sign in</button>
                    </div>
                </div>
                </div>
       </div>
    </div>
</div>
<div id="error" class="alert alert-danger" role="alert" style="display:none">网络错误</div>
<div id="wrong" class="alert alert-danger" role="alert" style="display:none">用户名或密码错误！</div>
<script>
	$('input').click(function(){
		$('.alert').hide();
	});
	$('.btn').click(function(){
		var data = {};
		data['adminusername'] = $('#adminusername').val();
		data['adminpassword'] = $('#adminpassword').val();
		if (data['adminusername'] == "") {
			alert("请输入用户名！");return;
		}
		if (data['adminpassword'] == "") {
			alert("请输入密码！");return;
		}
		data['signin'] = "1";
		url = "/adminfuckme/login";
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			dataType: 'json',
			success: function(data){
				if (data.code) {
					location.href = '/adminfuckme_controll'
				}
				else{
					$('#wrong').show();
				}
			},
			error: function(){
				$('#error').show();
			}
		});
	});
</script>