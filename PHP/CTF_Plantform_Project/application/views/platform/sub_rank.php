<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div>
<h3>本周积分榜<small><a href="#" id="show-rank"> 查看完整积分榜</a></small></h3>

<ul class="nav nav-tabs" id="tab2">
    <li class="active cur"><a href="#" data-toggle="tab" aria-expanded="false">本周积分榜</a></li>
    <li class=""><a href="#" data-toggle="tab" aria-expanded="false">全部积分榜</a></li>

</ul>


<div id="con2">
<div class="con2" style="display:block">

<p>
	<table>
               	<?php foreach ($top_week as $key => $value):?>
                              	<tr align="center">
				<td width="150px" style="font-weight:bold;"><?=$value['USERNAME'];?></td>
                              		<td width="150px" style="font-weight:bold;"><?=$value['WEEKPOINT'];?></td>
                              	</tr>
                            <?php endforeach;?>
   </table>
</p>
</div>

<div class="con2">

<p>
	<table>
               	<?php foreach ($top_all as $key => $value):?>
                              	<tr align="center">
				<td width="150px" style="font-weight:bold;"><?=$value['USERNAME'];?></td>
                              		<td width="150px" style="font-weight:bold;"><?=$value['POINT'];?></td>
                              	</tr>
                            <?php endforeach;?>
               </table>
</p>
</div>

</div>


<script>
	$('#show-rank').click(function(){
		$('#main-body').html('<img src="/static/img/loading.gif"/>');
		$.get('/controll/rank', function(data){
			$('#main-body').html(data);
		});
	});


$(document).ready(function(){ 
var nav=document.getElementById("tab2").getElementsByTagName("li");  
var con=document.getElementById("con2").getElementsByTagName("div");
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
