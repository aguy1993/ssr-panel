<?php
require_once 'lib/init.php';
session_start();
if(isset($_SESSION['uid']) && $_SESSION['uid'] != null){
    $uid = $_SESSION['uid'];
}else{
    header("Location:register.php");
}
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
    <title>Hi,<?php echo $user_info['name'];?> - moehoro - SSR</title>

    <script src="https://o3hyb3eh5.qnssl.com/ss/asset/js/jQuery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/style.css" />
    <link rel="stylesheet" href="static/jquery-confirm.min.css" />
    <script src="static/jquery-confirm.min.js"></script>
    <style>
        body{
            background-image: url("./static/background/<?php echo rand(1,3);?>.jpg");
        }
    </style>

</head>

<body>
<div id="page">

    <header>
        <img src="lib/avatar/<?php echo rand(1,6);?>.jpg" id="head"/>

        <h1 id="name">Hi，<?php echo $user_info['name'];?></h1>
        <h2 id="info">
            <p>Email:<?php echo $user_info['email'];?></p>
            <p>Expire Date:<?php echo $user_info['expire_date'];?>(<a href="#" onclick="renew()">续费</a>)</p>
            <p>兑换码购买：<a href="http://tb.am/qtowh" target="_blank">年卡</a> <a href="http://moehoro.yunfaka.com" target="_blank">月卡</a></p>
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

    <a href="#" class="l_a" onclick="showDownPage()">
        客户端
    </a>
    <input type="text" id="sckey" value="<?php echo $user_info['sckey'] ;?>" hidden="hidden">
    <a href="#" onclick="setSckey()">微信提醒</a>
    <a target="_blank" href="traffic.php">流量详情</a>
    <nav id="btndiv">
        <p></p>
        <?php
            $servers = $server->list_server();
            foreach ($servers as $key) {
                echo '<a href="#" onclick="'."showQrcode('ssr://".base64_encode($key['host'].':'.$user_info['port'].':'.$key['protocol'].':'.$key['method'].':'.$key['obfs'].':'.$base64pass.'/?'.$key['param'])."')".'"">'.$key['name'].'</a> ';
            }
        ?>
    </nav>
</div>

<script>
    var circle = document.getElementById("c1");
    var percent = <?php echo round(($user_info['u']+$user_info['d'])/$user_info['transfer_enable'],2);?>, perimeter = Math.PI * 2 * 36;
    circle.setAttribute('stroke-dasharray', perimeter * percent + " " + perimeter * (1- percent));
</script>

<script>
    var showQrcode = function (ssr) {
        $.dialog({
            title: '扫描二维码',
            animation: 'scale',
            content: '<img src="https://ssr.moehoro.com/genCode.php?url='+ssr+'">',
        });
    };
    var showDownPage = function () {
        $.dialog({
            title: '客户端下载',
            confirmButtonClass: 'btn-primary',
            content: '<p>PC:<a href="https://github.com/shadowsocksr/shadowsocksr-csharp/releases" target="_blank">link</a></p><p>MAC: <a href="https://github.com/qinyuhang/ShadowsocksX-NG/releases" target="_blank">link1</a> <a href="https://github.com/yichengchen/ShadowsocksX-R/releases" target="_blank">link2</a></p><p>Android:<a href="https://github.com/shadowsocksr/shadowsocksr-android/releases" target="_blank">link</a></p><p>IOS:<a href="https://itunes.apple.com/us/app/shadowrocket/id932747118" target="_blank">shadowrocket</a></p>',
        });
    };
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
    var setSckey = function () {
      if ($('#sckey').val()) {
        alert('您已经开通了微信提醒功能，无需重复开通！');
      }else{
        var sckey_input = prompt('请输入SCKEY，获取方法请联系管理员');
        if (sckey_input != null && sckey_input != '') {
          //alert('您输入的'+sckey_input);
          $.ajax({
              type:"POST",
              url:"action/_setsckey.php",
              dataType:"json",
              data:{
                  uid: <?php echo $uid;?>,
                  sckey: sckey_input
              },
              success:function(data){
                  if(data.ok){
                      alert(data.msg);
                  }else{
                      alert(data.msg);
                  }
              },
              error:function(jqXHR){
                  alert("后台错误："+jqXHR.status);
              }
          });
        }
      }
    };
</script>
</body>
</html>
