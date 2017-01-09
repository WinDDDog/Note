<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
        <div class="row clearfix">
            <div class="col-md-12 column">
                <div class="page-header">
                    <h1>
                        修改题目 <small>Edit Problem</small>
                    </h1>
                </div>
                <div role="form" id="add-form">
                <input type="hidden" id="id" value="<?=$problem['ID'];?>">
                    <div class="form-group">
                        <label for="title">题目名称</label><input type="text" class="form-control" id="title" value="<?=$problem['PROBLEM_TITLE'];?>"/>
                    </div>
                    <div class="form-group">
                        <label for="describe">题目描述</label><input type="text" class="form-control" id="describe" value="<?=$problem['PROBLEM_DESCRIBLE'];?>"/>
                    </div>
                    <div class="form-group">
                        <label for="hint">HINT（可不填）：</label><input type="text" class="form-control" id="hint" value="<?=$problem['PROBLEM_HINT'];?>" />
                    </div>
                    <div class="form-group">
                        <label for="WEEK">WEEK
                           <?php if($week['code'] == 0): ?>
                            <span style='color: red'>(你还没有设置当前周数！)</span>
                         <?php else: ?>
                                <span style='color: red'>当前第<?=$week['value'];?>周</span>
                        <?php endif;?>
                        </label><input type="text" class="form-control" id="week" value="<?=$problem['WEEK'];?>" />
                    </div>
                    <label for="class" >题目分类</label>
                    <select class="form-control" id="problem-class" name="problemclass">
                    <?php if(empty($class)): ?>
                       	<option>No Class</option>
                     <?php else: ?>
                     	<?php foreach ($class as $value): ?>
<?php if($value['CLASS'] == $problem['CLASS']):?>
                                        <option selected><?=$value['CLASS'];?></option>
                                    <?php else:?>
                                	<option><?=$value['CLASS'];?></option>
                                <?php endif;?>
                                <?php endforeach;?>
                     <?php endif;?>
                    </select><br />
                    <div class="form-group">
                        <label for="point">POINT</label><input type="text" class="form-control" id="point" value="<?=$problem['BASEPOINT'];?>" />
                    </div>
                    <div class="form-group">
                        <label for="level">LEVEL</label><input type="text" class="form-control" id="level" value="<?=$problem['LEVEL'];?>" />
                    </div>
                    <div class="form-group">
                        <label for="flag">FLAG</label><input type="text" class="form-control" id="flag" value="<?=$problem['FLAG']?>" />
                    </div>
                    <button type="button" class="btn btn-success" id="edit-problem-button">确认修改</button>
                </div>
            </div>
            <div id="return-info">
	<div class="alert alert-danger" role="alert" style="display: none;" id="gg">服务器GG了。</div>
	<div class="alert alert-success" role="alert" style="display: none;" id="suc">题目修改成功，<a onclick="ret()" href="#">点我返回</a></div>
	<div class="alert alert-danger" role="alert" style="display: none;" id="hack">Don't hack me!</div>
            </div>
        </div>
    </div>
<script>
function ret () {
	$('#manage-problem').click();
}
$(document).ready(function () {
	$('#edit-problem-button').click(function(){
		var data = {};
		var url = "/adminfuckme_controll/con_edit";
		data['title'] = $('#title').val();
		data['describe'] = $('#describe').val();
		data['hint'] = $('#hint').val();
		data['week'] = $('#week').val();
		data['problemclass'] = $('#problem-class').val();
		data['point'] = $('#point').val();
        data['level'] = $('#level').val();
		data['flag'] = $('#flag').val();
		data['id'] = $('#id').val();
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			dataType: 'json',
			success: function(data){
				if (data.code == 1) {
					$('#suc').show();
					setTimeout("$('#manage-problem').click()", 2000);
				}
				else if (data.code == 0){
					$('#gg').show();
				}
				else{
					$('#hack').show();
				}
			},
			error: function(){
				$('#gg').show();
			}
		});
	});
});
</script>
