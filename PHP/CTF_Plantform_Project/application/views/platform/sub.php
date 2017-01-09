<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h3>最新提交</h3>
<p>
               <?php foreach ($prolog as $key => $value): ?>
               	<span style="font-weight:bold;">
                              	<span style="color: blue"><?=date('Y-m-d H:i:s', $value['SOLVED_TIME']); ?></span><br />
                              	<span style="color: darkcyan"><?=$value['USERNAME'];?></span>
                              	<span style="color: red;">PWNED</span>
                              	<?=$value['problem']['PROBLEM_TITLE'];?>
                              	<br /><br />
		</span>
               <?php endforeach;?>
</p>
<script type="text/javascript">
	$('#show-rank').click(function(){
		$.get('/controll/rank', function(data){
			$('#main-body').html(data);
		});
	});
</script>