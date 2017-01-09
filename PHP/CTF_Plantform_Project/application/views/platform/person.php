<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="page-header">
                <h1>
                    你好，<?=$_SESSION['user']['USERNAME'];?> <small>Hello, <?=$_SESSION['user']['USERNAME'];?></small>
                </h1>
            </div>
            <div class="row clearfix">
                <div class="col-md-1 column">
                </div>
                <div class="col-md-8 column">
                    <ul class="nav nav-tabs" id="tab">
                        <li class="active cur"><a href="#" data-toggle="tab" aria-expanded="false">我的总分</a></li>
                        
                        <?php if($_SESSION['user']['LEVEL'] > 0):?>
                        <li class=""><a href="#" data-toggle="tab" aria-expanded="false">我本周的分数</a></li>
                        <?php endif;?>
                        <li class=""><a href="#" data-toggle="tab" aria-expanded="false">我解出的题目</a></li>

                    </ul>

                    <div id="con">

                    <div class="con" style="display:block" align="center">
                    <p>
                        <p style="font-size: 20px;color: darkred">Your Points: <?=$real_point['point'];?></p>
                        <p style="font-size: 20px;color: darkred">Your Web Points: <?=$real_point['webpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your RE Points: <?=$real_point['repoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your PWN Points: <?=$real_point['pwnpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your MISC Points: <?=$real_point['miscpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your PENTEST Points: <?=$real_point['pentestpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your CRYPTO Points: <?=$real_point['cryptopoint'];?></p>

                        <p style="color: darkgreen; font-size: 20px">总排名: <?=$real_point['rank'];?> / <?=$real_point['person_num'];?></p>
                    </p>
                    </div>

                    <br />
                

                    <?php if($_SESSION['user']['LEVEL'] > 0):?>

                    <div class="con" align="center">
                    <p>
                        <p style="font-size: 20px;color: darkred">Your Week Points: <?=$week_point['weekpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your Web Points: <?=$week_point['webpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your RE Points: <?=$week_point['repoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your PWN Points: <?=$week_point['pwnpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your MISC Points: <?=$week_point['miscpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your PENTEST Points: <?=$week_point['pentestpoint'];?></p>
                        <p style="font-size: 20px;color: darkred">Your CRYPTO Points: <?=$week_point['cryptopoint'];?></p>
                        <p style="color: darkgreen; font-size: 20px">Week Rank: <?=$week_point['rank'];?> / <?=$week_point['person_num'];?></p>

                        <p style="color: darkgreen; font-size: 20px">校内排名: <?=$all_point['rank'];?> / <?=$all_point['person_num'];?></p>
                    </p>
                    </div>

                    <?php endif;?>

                    <div class="con" align="center">
                    <p>
                    <table class="table">
                        <tr>
                            <th>题目ID</th>
                            <th>题目标题</th>
                            <th>作答时间</th>
                            <th>得分</th>
                        </tr>
                        <?php foreach ($mypro as $key => $value):?>
                             <?php if (!empty($value['pinfo'])):?>
                            <tr>
                                <td><?=$value['PROBLEMID'];?></td>
                                <td><?=$value['pinfo']['PROBLEM_TITLE'];?></td>
                                <td><?=date('Y-m-d H:i:s',$value['SOLVED_TIME']);?></td>
                                <td><?=$value['FINAL_POINT'];?></td>
                            </tr>
                             <?php endif;?>
                        <?php endforeach;?>
                    </table>
                    </p>
                    </div>

                    </div>
                </div>
                <div class="col-md-1 column">
                </div>
            </div>
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