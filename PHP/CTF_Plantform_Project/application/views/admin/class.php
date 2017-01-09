<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
                <div class="form-group">
                    <label for="curWeek">增加分类</label>
                    <input type="text" class="form-control" id="class"/>
                </div>
                <button type="button" class="btn btn-primary" id="save-setting">保存修改</button>
         </div>
     <div id="save-result">
           	<div class="alert alert-danger" role="alert" id="error" style="display:none;">网络不给力啊!</div>
	<div class="alert alert-danger" role="alert" id="fail" style="display: none;">服务器GG了。</div>
	<div class="alert alert-success" role="alert" id="suc" style="display: none;">修改成功！</div>
      </div>    
     </div>
</div>
<script type="text/javascript">
	$('#save-setting').click(function(){
		alert('懒得开发这功能了～');
	});
</script>