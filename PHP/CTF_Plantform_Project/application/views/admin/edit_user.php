<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
        <div class="row clearfix">
            <div class="col-md-12 column">
                <div class="page-header">
                    <h1>
                        修改用户信息 <small>Edit User</small>
                    </h1>
                </div>
                <div role="form" id="add-form">
                <input type="hidden" id="id" value="<?=$user['ID'];?>">
                    <div class="form-group">
                        <label>USERNAME：</label><?=$user['USERNAME'];?>
                    </div>
                    <div class="form-group">
                        <label for="password">密码：</label><input type="text" class="form-control" id="password" />
                    </div>
                    <div class="form-group">
                    <label for="class" >状态：</label>
                    <select class="form-control" id="open">
                        <?php if($value['LEVEL'] == 0):?>
                            <option value="0">校外</option>
                            <option value="1">校内</option>
                            <option value="2">内部</option>
                        <?php elseif($value['LEVEL'] == 1):?>
                            <option value="1">校内</option>
                            <option value="0">校外</option>
                            <option value="2">内部</option>
                        <?php else:?>
                            <option value="2">内部</option>
                            <option value="1">校内</option>
                            <option value="0">校外</option>
                        <?php endif;?>
                    </select>
                    </div>
                    <button type="button" class="btn btn-success" id="edit-user-button">确认修改</button>
                </div>
            </div>
            <div id="return-info">
	<div class="alert alert-danger" role="alert" style="display: none;" id="gg">服务器GG了。</div>
	<div class="alert alert-success" role="alert" style="display: none;" id="suc">修改成功，<a onclick="ret()" href="#">点我返回</a></div>
            </div>
        </div>
    </div>
<script>
$(document).ready(function () {
	$('#edit-user-button').click(function(){
		var data = {};
		var url = "/adminfuckme_controll/con_user";
        data['password'] = $('#password').val();
        data['LEVEL'] = $('#open').val();
		data['id'] = $('#id').val();
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			dataType: 'json',
			success: function(data){
				if (data.code == 1) {
					$('#suc').show();
					setTimeout("$('#user-problem').click()", 2000);
				}
				else {
					$('#gg').show();
				}
			},
			error: function(){
				$('#gg').show();
			}
		});
	});
});
</script>