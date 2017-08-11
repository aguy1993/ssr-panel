<?php
require_once 'lib/init.php';
require_once 'action/_check.php';

$user = new User();
$user_info = $user->get_user_info($uid);
$comm = new Comm();
$server = new Server();
$base64pass = base64_encode($user_info['passwd']);
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>Hi,<?php echo $user_info['name'];?> - moehoro - SSR</title>
    <script src="https://o3hyb3eh5.qnssl.com/ss/asset/js/jQuery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/style.css" />
    <link rel="stylesheet" href="static/jquery-confirm.min.css" />
    <script src="static/jquery-confirm.min.js"></script>
    <link rel="icon" href="./favicon.ico" type="image/x-icon" />

</head>

<body>
<div id="page">

    <header>
        <img src="lib/avatar/<?php echo rand(1,6);?>.jpg" id="head"/>

        <h1 id="name">Hi，<?php echo $user_info['name'];?></h1>
        <h2 id="info">
            <p>Email:<?php echo $user_info['email'];?></p>
            <p>Expire Date:<span id="expire-date"><?php echo $user_info['expire_date'];?></span>(<a href="#" onclick="renew()">续费</a>)</p>
        </h2>

        <section id="tag">
            <svg width="80" height="80">
                <text style="fill:black;" font-size="20" x="25" y="45" width="80" height="80"><?php echo round(($user_info['u']+$user_info['d'])/$user_info['transfer_enable'],2)*100;echo "%";?></text>
                <circle cx="40" cy="40" r="36" stroke-width="7" stroke="#C9CACA" fill="none"></circle>
                <circle id="c1" cx="40" cy="40" r="36" stroke-width="8" stroke="#E73468" fill="none" transform="matrix(0,-1,1,0,0,80)" stroke-dasharray=""></circle>
            </svg>
            <p>流量：<?php echo $comm::format_bytes(($user_info['u']+$user_info['d']));?> / <?php echo $comm::format_bytes($user_info['transfer_enable']);?></p>
            <br>
            <p>累计使用流量：<?php echo $comm::format_bytes($user_info['u']+$user_info['d']+$user_info['total']); ?></p>
        </section>
    </header>
    <a href="#" class="l_a" onclick="showPage('purchase-page','激活码购买')">激活码购买</a>
    <a href="#" style="color:red;" class="l_a" onclick="$.dialog({title:'使用帮助',content:'<img src=./static/ssr-help.jpg>'})">使用帮助</a>
    <br>
    <a href="#" class="l_a" onclick="showPage('client-page','客户端下载')">客户端</a>
    <input type="text" id="sckey" value="<?php echo $user_info['sckey'] ;?>" hidden="hidden">
    <a href="#" onclick="setSckey()">微信提醒</a>
    <a target="_blank" href="traffic.php">流量详情</a>
    <hr style="height:1px;border:none;border-top:1px dashed #000;" />
    <nav id="btndiv">
        <p></p>
        <?php
            $servers = $server->list_server();
            foreach ($servers as $key) {
                echo '<a href="#" onclick="'."showQrcode('ssr://".
                    base64_encode($key['host'].
                        ':'.
                        $user_info['port'].
                        ':'.
                        $key['protocol'].
                        ':'.
                        $key['method'].
                        ':'.
                        $key['obfs'].
                        ':'.
                        $base64pass.
                        '/?'.
                        $key['param']).
                    "','".$key['host']."','".$user_info['port']."','".$user_info['passwd']."','".$key['method']."','".$key['protocol']."','".$key['obfs']."')".
                    '"">'.
                    $key['name'].'</a> ';
            }
        ?>
    </nav>
</div>
<script>
    /**
     * 绘制流量使用饼图
     *
     */
    var circle = document.getElementById("c1");
    var percent = <?php echo round(($user_info['u']+$user_info['d'])/$user_info['transfer_enable'],2);?>, perimeter = Math.PI * 2 * 36;
    circle.setAttribute('stroke-dasharray', perimeter * percent + " " + perimeter * (1- percent));
    $(document).ready(function () {
        var remainDay = Math.floor((Date.parse($("#expire-date").html())-Date.parse('<?php echo date('Y-m-d');?>'))/(24*3600*1000));
        if(remainDay <= 30 && remainDay >= 0){
            $.dialog({
                title:'续费提醒',
                content:'服务时间剩余'+remainDay+'天，请及时续<del>命</del>费<br><a href="#" onclick="renew()">点我续费</a>',
            });
            $("#expire-date").css('color','red');
        }else if(remainDay < 0){
            $.dialog({
                title:'续费提醒',
                content:'服务已到期，请及时续<del>命</del>费<br><a href="#" onclick="renew()">点我续费</a>',
            });
            $("#expire-date").html('已过期');
            $("#expire-date").css('color','red');
        }
    });

    /**
     * Copy ssr链接
     */
    var jsCopy = function () {
//        alert($("#ssr-url").val());
        $("#ssr-url").select();
        tag=document.execCommand("Copy");
        if(tag){
            $.dialog({
                title:'成功',
                content:'已复制到粘贴板',
            });
        }else{
            $.dialog({
                title:'失败',
                content:'你的浏览器不支持此操作，请手动复制',
            });
        }
    };
    var showPage = function (page,name) {
        $.dialog({
            title:name,
            content:'url:./static/'+page+'.html',
        });
    };
    /**
     * 弹出二维码
     * @param ssr
     */
    var showQrcode = function (ssr,ip,port,pass,method,protocol,obfs) {
        $.dialog({
            title: '扫描二维码',
            animation: 'scale',
            content: '<img src="https://ssr.moehoro.com/genCode.php?url='+ssr+'">' +
            '<div class="input-group">' +
            '<input id="ssr-url" class="form-control" type="url" value="'+ssr+'" />' +
            '<span class="input-group-addon btn btn-info" onclick="jsCopy()">复制</span>' +
            '</div><br>' +
            '<div class="input-group"> ' +
            '<span class="input-group-addon">服务器IP</span>' +
            '<input type="text" class="form-control" value="'+ip+'">' +
            '</div><br>' +
            '<div class="input-group"> ' +
            '<span class="input-group-addon">服务器端口</span>' +
            '<input type="text" class="form-control" value="'+port+'">' +
            '</div><br>' +
            '<div class="input-group"> ' +
            '<span class="input-group-addon">密码</span>' +
            '<input type="text" class="form-control" value="'+pass+'">' +
            '</div><br>' +
            '<div class="input-group"> ' +
            '<span class="input-group-addon">加密</span>' +
            '<input type="text" class="form-control" value="'+method+'">' +
            '</div><br>' +
            '<div class="input-group"> ' +
            '<span class="input-group-addon">协议</span>' +
            '<input type="text" class="form-control" value="'+protocol+'">' +
            '</div><br>' +
            '<div class="input-group"> ' +
            '<span class="input-group-addon">混淆</span>' +
            '<input type="text" class="form-control" value="'+obfs+'">' +
            '</div><br>',
        });
    };
    /**
     * 续费
     */
    var renew = function () {
        var code = prompt("请输入兑换码");
        if(code != null && code != '' && code.length == 16){
            $.ajax({
                type:"POST",
                url:"action/_renew.php",
                dataType:"json",
                data:{
                    uid: <?php echo $uid;?>,
                    code: code
                },
                success:function(data){
                    if(data.ok){
                        alert("成功续费"+data.msg+"月");
                    }else{
                        alert(data.msg);
                    }
                },
                error:function(jqXHR){
                    alert("后台错误："+jqXHR.status);
                }
            });
        }
    };
    /**
     * 设置微信提醒
     */
    var setSckey = function () {
        $.dialog({
            title:'设置微信提醒',
            content:'<div class="input-group">' +
            '<input id="input-sckey" class="form-control" type="text" value="'+$("#sckey").val()+'" placeholder="请输入SCKEY" />' +
            '<span onclick="setSckeyAction()" class="input-group-addon btn btn-info">绑定</span>' +
            '</div><br><p>获取SCKEY: <a target="_blank" href="http://sc.ftqq.com/3.version">点我</a><p>'
        });
    };
    var setSckeyAction = function () {
        if($("#sckey").val() != $("#input-sckey").val() && $("#input-sckey").val != null && $("#input-sckey").val() != ''){
            $.ajax({
                type:"POST",
                url:"action/_setsckey.php",
                dataType:"json",
                data:{
                    sckey: $("#input-sckey").val()
                },
                success:function(data){
                    if(data.ok){
                        $("#sckey").val($("#input-sckey").val());
                        $.alert({title:'设置成功',content:data.msg,});
                    }else{
                        $.alert({title:'设置失败',content:data.msg,});
                    }
                },
                error:function(jqXHR){
                    alert("后台错误：" + jqXHR.status);
                }
            });
        }
    };
</script>

</body>
</html>
