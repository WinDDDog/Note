<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="page-header">
                <h1>
                    添加新题目 <small>Add new problem</small>
                </h1>
            </div>
            <div role="form" id="add-form">
                <div class="form-group">
                    <label for="title">题目名称</label><input type="text" class="form-control" id="title" />
                </div>
                <div class="form-group">
                    <label for="describe">题目描述</label><input type="text" class="form-control" id="describe" />
                </div>
                <div class="form-group">
                    <label for="hint">HINT（可不填）：</label><input type="text" class="form-control" id="hint" />
                </div>
                <div class="form-group">
                    <label for="WEEK">WEEK
                        <?php if($week['code'] == 0): ?>
                            <span style='color: red'>(你还没有设置当前周数！)</span>
                         <?php else: ?>
                                <span style='color: red'>当前第<?=$week['value'];?>周</span>
                        <?php endif;?>
                    </label><input type="text" class="form-control" id="week" />
                </div>
                <label for="class" >题目分类</label>
                <select class="form-control" id="problem-class" name="problemclass">
                    <?php if(empty($class)): ?>
                       	<option>No Class</option>
                     <?php else: ?>
                     	<?php foreach ($class as $value): ?>
                                	<option><?=$value['CLASS'];?></option>
                                <?php endforeach;?>
                     <?php endif;?>
                </select><br />
                <div class="form-group">
                    <label for="level">LEVEL</label><input type="text" class="form-control" id="level" value="0" />
                </div>
                <div class="form-group">
                    <label for="point">POINT</label><input type="text" class="form-control" id="point" />
                </div>
                <div class="form-group">
                    <label for="flag">FLAG</label><input type="text" class="form-control" id="flag" />
                </div>
                 <button type="button" class="btn btn-success" id="add-new-problem-button">添加新题目</button>
                <button type="button" class="btn btn-info" id="reset-add">重置</button>
            </div>
        </div><div id="return-info">
        	<div class="alert alert-danger" role="alert" style="display: none;" id="class-error">请选择一个分类</div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="blank-error">请填写完整除HINT外的信息</div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="point-error">负数？</div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="ser-error">服务器GG了，插不进去！</div>
        	<div class="alert alert-success" role="alert" style="display: none;" id="suc">添加成功! ...自动跳转中</div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="net-error">网络不给力啊！</div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="hack">dont't hack me!</div>
        </div>
    </div>
</div>
<script>
	$('#add-new-problem-button').click(function(){
		var data = {};
		url = "/adminfuckme_controll/con_add";
		data['title'] = $('#title').val();
		data['describe'] = $('#describe').val();
		data['hint'] = $('#hint').val();
		data['week'] = $('#week').val();
		data['problemclass'] = $('#problem-class').val();
        data['level'] = $('#level').val();
		data['point'] = $('#point').val();
		data['flag'] = $('#flag').val();
		for(var i in data) {
			if (i != "hint") {
				if (data[i] == '') {
					$('#blank-error').show();
					return;
				}
			}
		}
		$.ajax({
			url: url,
			data: data,
			type: 'post',
			dataType: 'json',
			success: function(data){
				if (data.code == 1) {
					$('#add-new-problem-button').attr("disabled","true");
					$('#suc').show();
					setTimeout("$('#manage-problem').click()", 2000);
				}
				else if (data.code == 2) {
					$('#class-error').show();
				}
				else if (data.code == 3) {
					$('#hack').show();
				}
				else if (data.code == 4) {
					$('#point-error').show();
				}
				else{
					$('#ser-error').show();
				}
			},
			error: function(){
				$("#net-error").show();
			}
		});
	});

	$('#reset-add').click(function() {
		$('input').val('');
	});
	$('input').click(function(){
		$('.alert').hide();
	});
</script>
