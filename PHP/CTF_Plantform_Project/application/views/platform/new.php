<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="page-header">
<h1>
	<?php if ($type == 1):?>
		Week<?=$week;?>下的题目
	<?php endif;?>
	<?php if($type == 2):?>
		分类<?=$t;?>下的题目
	<?php endif;?>
	<?php if($type == 0):?>
		本周添加的题目
	<?php endif;?>
</h1>
</div>
<?php foreach ($new as $key => $value): ?>
<?php if ($value['done']): ?>
	<div class="alert alert-success" style="padding: 10px;">
	<div class="change" style="cursor: pointer;">
		<span style="font-size: 20px;">
			<?=$value['PROBLEM_TITLE'];?>
			<small style="color: red">&nbsp;&nbsp;&nbsp;&nbsp;POINT: <?=$value['BASEPOINT'];?></small>
			<span style="float: right;">
				DONE
				<span style="font-size: 16px;" class="glyphicon glyphicon glyphicon-th-list
"></span>
			</span>
		</span>
	</div>
		<div style="display: none;" class="detail">
			<button class="btn btn-danger" data-toggle="modal" data-target="#problem-modal" id="<?=$value['ID'];?>">
				本题题解详情
			</button><br />
			<span style="font-weight: bold">题目ID：</span>
			<span><?=$value['ID'];?></span><br />
			<span style="font-weight: bold">题目描述：</span>
			<span><?=$value['PROBLEM_DESCRIBLE'];?></span><br />
			<span style="font-weight: bold">Hint:</span>
			<?=$value['PROBLEM_HINT'];?><br />
			<span style="font-weight: bold">前三名解出该题目的同学：</span>
			<?php foreach ($value['top'] as $k => $v):?>
				<?=$k+1;?>.<?=$v['USERNAME'];?>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php endforeach;?><br />
			回答正确 / 错误次数：

			<?php if ($value['SUBMITTED'] == 0): ?>
				<div class="progress active">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="<?=$value['SOLVED'];?>" aria-valuemin="0" aria-valuemax="<?=$value['SUBMITTED'];?>" style="width: <?=0*100;?>%">
						<span><?=$value['SOLVED'];?></span>
					</div>
					<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: <?=(1)*100;?>%">
						<span><?=$value['SUBMITTED'] - $value['SOLVED'];?></span>
					</div>
				</div>
			<?php else: ?>
				<div class="progress active">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="<?=$value['SOLVED'];?>" aria-valuemin="0" aria-valuemax="<?=$value['SUBMITTED'];?>" style="width: <?=$value['SOLVED']/$value['SUBMITTED']*100;?>%">
						<span><?=$value['SOLVED'];?></span>
					</div>
					<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: <?=(1 - $value['SOLVED']/$value['SUBMITTED'])*100;?>%">
						<span><?=$value['SUBMITTED'] - $value['SOLVED'];?></span>
					</div>
				</div>
			<?php endif; ?>


		</div>
	</div>
<?php else: ?>
	<h2 class="myhead"><?=$value['PROBLEM_TITLE'];?>
		<small style="color: red">&nbsp;&nbsp;&nbsp;&nbsp;POINT: <?=$value['BASEPOINT'];?></small>
	</h2>
	<button class="btn btn-danger" data-toggle="modal" data-target="#problem-modal" id="<?=$value['ID'];?>">本题题解详情</button>
	<br /><span style="font-weight: bold">题目ID：</span>
	<span><?=$value['ID'];?></span><br />
	<span style="font-weight: bold">题目描述：</span>
	<span><?=$value['PROBLEM_DESCRIBLE'];?></span><br />
	<span style="font-weight: bold">Hint:</span>
	<?=$value['PROBLEM_HINT'];?><br />
	<span style="font-weight: bold">前三名解出该题目的同学：</span>
	<?php foreach ($value['top'] as $k => $v):?>
		<?=$k+1;?>.<?=$v['USERNAME'];?>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php endforeach;?>
	<br />
	回答正确 / 错误次数：
			<?php if ($value['SUBMITTED'] == 0): ?>
				<div class="progress active">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="<?=$value['SOLVED'];?>" aria-valuemin="0" aria-valuemax="<?=$value['SUBMITTED'];?>" style="width: <?=0*100;?>%">
						<span><?=$value['SOLVED'];?></span>
					</div>
					<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: <?=(1)*100;?>%">
						<span><?=$value['SUBMITTED'] - $value['SOLVED'];?></span>
					</div>
				</div>
			<?php else: ?>
				<div class="progress active">
					<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="<?=$value['SOLVED'];?>" aria-valuemin="0" aria-valuemax="<?=$value['SUBMITTED'];?>" style="width: <?=$value['SOLVED']/$value['SUBMITTED']*100;?>%">
						<span><?=$value['SOLVED'];?></span>
					</div>
					<div class="progress-bar progress-bar-warning progress-bar-striped" style="width: <?=(1 - $value['SOLVED']/$value['SUBMITTED'])*100;?>%">
						<span><?=$value['SUBMITTED'] - $value['SOLVED'];?></span>
					</div>
				</div>
			<?php endif; ?>
<?php endif;?>
	<hr />
<?php endforeach; ?>
<?php if(count($new) == 0):?>
	<?php if ($type == 2):?>
		<span style="font-size: 20px">该分类下暂无题目</span>
	<?php else:?>
		<span style="font-size: 20px">尚未布置题目</span>
	<?php endif;?>
<?php endif;?>
<div class="modal fade" id="problem-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">本题 PWNED 详情</h4>
			</div>
			<div class="modal-body" id="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$('#problem-modal').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).attr('id');
		$.get('/controll/detail?id='+id, function(data){
			$('#modal-body').html(data);
		});
	});
	$('.change').click(function(){
		$(this).next('.detail').toggle();
	});
</script>
