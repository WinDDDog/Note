<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>
                        用户ID
                    </th>
                    <th>
                        USERNAME
                    </th>
                    <th>
                        EMAIL
                    </th>
                    <th>
                        QQ
                    </th>
                    <th>
                        POINT
                    </th>
		    <th>
                        LAST_IP
                    </th>
		    <th>
                        LAST_LOGIN_TIME
                    </th>
                    <th>
                    	学号
                    </th>
                    <th>
                        真实姓名
                    </th>
                    <th>
                        学院
                    </th>
                    <th>
                        等级
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($user as $key => $value): ?>
		<tr>
			<th><?=$key;?></th>
			<th><?=$value['USERNAME'];?></th>
			<th><?=$value['EMAIL'];?></th>
			<th><?=$value['QQ'];?></th>
			<th><?=$value['POINT'];?></th>
			<th><?=$value['IP'];?></th>
			<th><?=date('Y-m-d H:i:s', $value['TIME']);?></th>
            <th><?=$value['SCHOOLID'];?></th>
            <th><?=$value['REALNAME'];?></th>
            <th><?=$value['COLLEGE'];?></th>
			<th>
            <?php if($value['LEVEL'] == 0):?>
                    校外
                <?php elseif($value['LEVEL'] == 1):?>
                    校内
                <?php else:?>
                    内部
                <?php endif;?>
                    
            </th>
            <th>
				<a onclick="edit(<?=$value['ID'];?>)" class="btn btn-success">设置</a>
			</th>
                            <tr />
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function edit (id) {
	$.get('/adminfuckme_controll/view_user?id='+id, function (data) {
		$('#admin-main-content').html(data);
	});
}
</script>
