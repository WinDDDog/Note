<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div>

<div class="col-md-offset-1 col-md-10">

    <ul class="nav nav-tabs" id="tab">
        <li class="active cur"><a href="#" data-toggle="tab" aria-expanded="false">本周积分榜</a></li>
        <li class=""><a href="#" data-toggle="tab" aria-expanded="false">校内积分榜</a></li>
        <li class=""><a href="#" data-toggle="tab" aria-expanded="false">总积分榜</a></li>

    </ul>


<div id="con">
<div class="con" style="display:block" align="center">
    <table class="table table-hover" style="font-size: 16px">
        <thead>
            <tr>
                <th>用户名</th>
                <th>方向</th>
                <th>得分</th>
                <th>最后一次得分时间</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($info as $key => $value):?>
            <tr>
                <th><?=$value['USERNAME'];?></th>
                <th><?=$value['Problem_type'];?></th>
                <th><?=$value['WEEKPOINT'];?></th>
                 <th>
                <?php if ($value['time']['code']): ?>
                    <?=date("Y-m-d H:i:s", $value['time']['value']);?>
                <?php else: ?>
                    <?=$value['time']['value'];?>
                <?php endif;?>
               </th>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


<div class="con" align="center">
    <table class="table table-hover" style="font-size: 16px">
        <thead>
            <tr>
                <th>用户名</th>
                <th>方向</th>
                <th>得分</th>
                <th>最后一次得分时间</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($info_school as $key => $value):?>
            <tr>
                <th><?=$value['USERNAME'];?></th>
                <th><?=$value['Problem_type'];?></th>
                <th><?=$value['POINT'];?></th>
                 <th>
                <?php if ($value['time']['code']): ?>
                    <?=date("Y-m-d H:i:s", $value['time']['value']);?>
                <?php else: ?>
                    <?=$value['time']['value'];?>
                <?php endif;?>
               </th>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

</div>


<div class="con" align="center">
    <table class="table table-hover" style="font-size: 16px">
        <thead>
            <tr>
                <th>用户名</th>
                <th>方向</th>
                <th>得分</th>
                <th>最后一次得分时间</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($info_all as $key => $value):?>
            <tr>
                <th><?=$value['USERNAME'];?></th>
                <th><?=$value['Problem_type'];?></th>
                <th><?=$value['POINT'];?></th>
                 <th>
                <?php if ($value['time']['code']): ?>
                    <?=date("Y-m-d H:i:s", $value['time']['value']);?>
                <?php else: ?>
                    <?=$value['time']['value'];?>
                <?php endif;?>
               </th>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

</div>

</div>
</div>

<script language="javascript">

$(document).ready(function(){ 
var nav=document.getElementById("tab").getElementsByTagName("li");  
var con=document.getElementById("con").getElementsByTagName("div");
for(i=0;i<nav.length;i++){
    nav[i].index = i;
    nav[i].onclick=function(){
        for(var n = 0; n < con.length; n++) {
            con[n].style.display = "none";
            nav[n].className = "";
        }
        con[this.index].style.display = "block";
        nav[this.index].className = "cur";
    }    
}});

</script>