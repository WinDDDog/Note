<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="page-header">
                <h2>
                    HCTF GAME <small>HCTF GAME</small>
                </h2>
            </div>
            <nav class="navbar navbar-default navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button> <a class="navbar-brand" href="/controll">Home</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <!-- <li class="active"> -->
                        <li>
                            <a href="#" id="new">NEWS</a>
                        </li>
                        <?php foreach ($class as $key => $value): ?>
                            <li>
                             <a href="#" id="<?=$value['CLASS'];?>" onclick="typed(<?=$value['ID'];?>)"><?=$value['CLASS'];?></a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Week X<strong class="caret"></strong></a>
                            <ul class="dropdown-menu">
                            <?php for ($i=0; $i <= $week['value']; $i++): ?>
                                    <li>
                                        <a href="#" class="week" onclick="week(<?=$i;?>)">Week<?=$i;?></a>
                                    </li>
                            <?php endfor;?>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$user['USERNAME']?><strong class="caret"></strong></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" id="person">个人中心</a>
                                </li>
                                <li class="divider">
                                </li>
                                <li>
                                    <a href="/controll/logout">注销</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav> <!--nav end -->

            <div class="row clearfix">
                <div class="col-md-3 column">
                    <!-- 提交flag -->
                    <div class="page-header">
                        <h3>提交FLAG</h3>
                    </div>
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="flag" placeholder="请在这里输入flag"/>
                    </div><br /><br />
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="problemid" placeholder="请输入题目ID"/>
                    </div>
                    <button class="btn btn-danger col-md-3" id="submit-flag">提交</button><br />
                    <br />
                    <div id="submit-result">
                        <div class="alert alert-danger" role="alert" style="display: none;" id="ser-error">服务器GG了</div>
                        <div class="alert alert-danger" role="alert" style="display: none;" id="done">你已经做过这个题了，去做别的吧！</div>
                        <div class="alert alert-success" role="alert" style="display: none;" id="suc">回答正确!</div>
                        <div class="alert alert-danger" role="alert" style="display: none;" id="error">答案错误，继续努力！</div>
                        <div class="alert alert-danger" role="alert" style="display: none;" id="bad">输入的题目ID错误</div>
                    </div>
                    <!-- 积分榜 -->
                    <div class="page-header" id="rank">
                    </div>
                    <div class="page-header" id="sub">
                    </div>
                </div>
                <div class="col-md-9 column">
                    <div class="alert alert-info" role="alert"><b>公告</b><br />
                    <?=$notice['value'];?>
                        </div> 
                    <div id="main-body">
                        <div class="tab-pane">
                        <p class="title">比赛规则 </p>
                        <ul>
                            <li>0.比赛为校内新生赛，面向杭州电子科技大学全日制本科生中大一、二年级（15、16级）学生，其他人员不参与奖励排名，参赛者注册时必须提交真实信息，否则参赛成绩无效。</li>
                            <li>1.比赛为线上赛，比赛期间的所有题目都在线上完成。</li>
                            <li>2.比赛为周赛制，以周赛为基础单位，每周的的题目只有在当前比赛周提交才有有效分数，周榜单独存在，每周的周冠军会得到额外的奖励。（周冠军不重复发放，依次推后）</li>
                            <li>3.比赛中每道题目的flag均为hctf{xxxxxx}的形式，若flag为非标准形式，则会在题目中说明。</li>
                            <li>4.对于每道题目，每支队伍仅有30次提交flag的机会。</li>
                            <li>5.比赛中不允许对题目以及平台进行DDOS攻击。各参赛队伍之间不允许交换flag。一旦发现有交换flag的行为或是对平台有任意攻击的行为，则取消队伍的比赛资格。</li>
                            <li>6.比赛期间，参赛队伍以个人为单位，在分数有效期间，不得以任何方式交换思路，否则取消参赛资格。</li>
                            <li>7.发现平台漏洞或题目非预期漏洞，视漏洞严重性进行加分奖励。</li>
                            <li>8.在每周比赛结束后的48小时以内，参赛选手必须提交本周已完成的所有题目的题解（writeup），否则题目分数无效。</li>
                            <li>9.每道题目前三位提交选手会有相应的分数奖励。</li>
                            <li>10.总榜累计积分，2 月 11 号比赛结束时统计排名，取前十为获胜选手予以奖励。</li>
                        </ul>
                        <p class="title">报名方式: </p>
                        <ul>
                            <li class="group">直接登陆https://ctf.vidar.club进行网上报名。</li>
                        </ul>
                        <p class="title">比赛时间 </p>
                        <ul>
                            <li> 1月17日 至 2月11日 共计4周</li>
                            <li>1月17号开始，1月17-21号为第一周，1月 24-26号为第二周，2月1号-2月5号为第三周，2月7号-2月11号为第四周。</li>
                        </ul>
                        <p class="title"> 获胜奖品 </p>
                        <ul>
                            <li>一等奖（一名）1000元等值奖品 + 一等奖奖状</li>
                            <li>二等奖（两名）500元等值奖品 + 二等奖奖状</li>
                            <li>三等奖（三名）200元等值奖品 + 三等奖奖状</li>
                            <li>三等奖 (三名) 纪念品 + 三等奖奖状优胜奖（四名）优胜奖奖状</li>
                            <li>优秀选手奖（四名）200元等值奖品＋优秀选手奖奖状</li>
                        </ul>
                        <p class="title">联系方式: </p>
                        <ul>
                            <li class="group">QQ Group: 134591168</li>
                        </ul>
                    </div>
                   
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/static/js/platform.js"></script>
<script type="text/javascript">
function sub_f () {
    $.get('/controll/sub_rank', function(data){
        $('#rank').html(data);
    });
    $.get('/controll/sub_sub', function(data){
        $('#sub').html(data);
    });
}
function week(id) {
    $('#main-body').html('<img src="/static/img/loading.gif"/>');
    $.get('/controll/week?id='+id, function(data) {
        $('#main-body').html(data);
    });
}
function typed(t) {
    $('#main-body').html('<img src="/static/img/loading.gif"/>');
    $.get('/controll/type?type='+t, function(data) {
        $('#main-body').html(data);
    });
}
sub_f();
</script>
