<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
                <div class="form-group">
                    <label for="curWeek">当期周数</label>
                    <input type="text" class="form-control" id="curWeek" value="<?=$week['value'];?>"/>
                </div>
                 <div class="form-group">
                    <label>是否开启注册   </label>
                    <select class="form-control" id="open">
                    	<?php if ($open['value']):?>
                    		<option value="1" id="yes">开启</option>
  			<option value="0" id="no">关闭</option>
                    	<?php else: ?>
  			<option value="0" id="no">关闭</option>
  			<option value="1" id="yes">开启</option>
                    	<?php endif;?>
  		
  	     </select>
                </div>


				 <div class="form-group">
                    <label>比赛是否结束   </label>
                    <select class="form-control" id="over">
                    	<?php if ($over['value']):?>
                    		<option value="1" id="yes">已经结束</option>
  			<option value="0" id="no">尚未结束</option>
                    	<?php else: ?>
  			<option value="0" id="no">尚未结束</option>
  			<option value="1" id="yes">已经结束</option>
                    	<?php endif;?>
  		
  	     </select>
                </div>


                <div class="form-group">
                    <label for="publicNotice">公告</label>
                    <textarea class="form-control" id="publicNotice"><?=$notice['value'];?></textarea>
                </div>
                <button type="button" class="btn btn-primary" id="save-setting">保存修改</button>
            </div>
            <div id="save-result">
           	<div class="alert alert-danger" role="alert" id="error" style="display:none;">网络不给力啊!</div>
	<div class="alert alert-danger" role="alert" id="weekwrong" style="display:none;">填错了逗逼!</div>
	<div class="alert alert-danger" role="alert" id="fail" style="display: none;">服务器GG了。</div>
	<div class="alert alert-success" role="alert" id="suc" style="display: none;">修改成功！</div>
	<div class="alert alert-danger" role="alert" style="display: none;" id="hack">Don't hack me!</div> 	
            </div>
    </div>
</div>
<script>
$('.form-group').click(function(){
	$('.alert').hide();
});
$('#save-setting').click(function() {
	var data = {};
	var url = "/adminfuckme_controll/con_set";
	data['curWeek'] = $('#curWeek').val();
	data['publicNotice'] = $('#publicNotice').val();
	data['isopen'] = $('#open').val();
	data['isover'] = $('#over').val();
	if (data['curWeek'] < -1 || data['curWeek'] == "") {
		$('#weekwrong').show();
	}
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		success: function(data){
			if (data.code == 3) {
				$('#weekwrong').show();
			}
			else if (data.code == 1) {
				$('#suc').show();
			}
			else if(data.code == 2){
				$('#hack').show();
			}
			else{
				$('#fail').show();
			}
		},
		error: function(){
			$("#error").show();
		}
	});
});
</script>
