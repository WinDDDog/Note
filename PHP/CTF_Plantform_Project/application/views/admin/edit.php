<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="return-info">
	<div class="alert alert-success" role="alert" style="display: none;" id="suc">删除成功</div>
        	<div class="alert alert-danger" role="alert" style="display: none;" id="hack">Don't hack me!</div>
</div>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>
                        题目ID
                    </th>
                    <th>
                        题目名称
                    </th>
                    <th>
                        POINT
                    </th>
                    <th>
                        WEEK
                    </th>
                    <th>
                        类别
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($problem as $key => $value): ?>
		<tr>
			<th><?=$key;?></th>
			<th><?=$value['PROBLEM_TITLE'];?></th>
			<th><?=$value['BASEPOINT'];?></th>
			<th><?=$value['WEEK'];?></th>
			<th><?=$value['CLASS'];?></th>
			<th>
				<a onclick="edit(<?=$value['ID'];?>)" class="btn btn-success">修改</a>
				<a onclick="del(<?=$value['ID'];?>)" class="btn btn-danger">删除</a>
			</th>
                            <tr />
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
	function del(id) {
		if (id <= 0) {
			$('#hack').show();
			return;
		}
		$.get('/adminfuckme_controll/del?id='+id, function (data) {
			if (data.code == 1) {
				$('#suc').show();
				setTimeout("$('#manage-problem').click()", 2000);
			}
			else{
				$('#hack').show();
			}
		}, 'json');
	}
	function edit(id){
		if (id <= 0) {
			$('#hack').show();
			return;
		}
		$.get('/adminfuckme_controll/con_problem?id='+id, function (data) {
			$('#admin-main-content').html(data);
		});
	}
</script>