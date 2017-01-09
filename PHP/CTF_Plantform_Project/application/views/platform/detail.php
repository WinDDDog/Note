<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="table-responsive">
<?php if($code == 1):?>
<table class="table-striped table">
	<tr align="center">
		<th>名次</th>
		<th>用户名</th>
		<th>解题时间</th>
		<th>得分</th>
	</tr>
<?php foreach ($log as $key => $value):?>
	<?php if($key == 0):?>
		<tr class="danger">
	<?php elseif($key == 1):?>
		<tr class="info">
	<?php elseif($key == 2):?>
		<tr class="success">
	<?php else:?>
		<tr class="active">
	<?php endif;?>
		<td style="font-weight:bold;"><?=$key+1;?></td>
		<td style="font-weight:bold;"><?=$value['USERNAME'];?></td>
		<td style="font-weight:bold;"><?=date('Y-m-d H:i:s', $value['SOLVED_TIME']);?></td>
		<td style="font-weight:bold;"><?=$value['FINAL_POINT'];?></td>
	</tr>
<?php endforeach;?>
</table>
<?php else:?>
	<p>Don't Hack me!</p>
<?php endif;?>
</div>