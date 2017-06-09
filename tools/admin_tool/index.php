<?php
require_once 'lib/init.php';
$admin = new Admin();
$all_user = $admin->get_all_user();
$count_info = $admin->get_count();
?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="https://v3.bootcss.com/favicon.ico" />
    <title>SSR Admin tool</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="https://v3.bootcss.com/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="https://v3.bootcss.com/examples/dashboard/dashboard.css" rel="stylesheet" />
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>

    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="http://v3.bootcss.com/assets/js/vendor/holder.min.js"></script>
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="http://v3.bootcss.com/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="http://v3.bootcss.com/assets/js/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.0/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.0/jquery-confirm.min.js"></script>
    <style id="style-1-cropbar-clipper">/* Copyright 2014 Evernote Corporation. All rights reserved. */
        .en-markup-crop-options {
            top: 18px !important;
            left: 50% !important;
            margin-left: -100px !important;
            width: 200px !important;
            border: 2px rgba(255,255,255,.38) solid !important;
            border-radius: 4px !important;
        }

        .en-markup-crop-options div div:first-of-type {
            margin-left: 0px !important;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
            <a class="navbar-brand" href="#">moeHoro SSR</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Dashboard</a></li>
            </ul>
            <form class="navbar-form navbar-right" _lpchecked="1">
                <input type="text" class="form-control" placeholder="Search..." />
            </form>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">

        <div class="col-sm-12 main">
            <h1 class="page-header">Dashboard</h1>
            <button type="button" class="btn btn-info">发送通知</button>
            <button type="button" class="btn btn-danger">清空流量</button>
            <button type="button" class="btn btn-success">新增用户</button>
            <br><br>
            <pre><?php echo "系统统计 -- 注册用户：".$count_info['count_user']." 活跃用户：".$count_info['count_active']." 月流量：".$admin->format_bytes($count_info['count_traffic'])." 过期用户：".$count_info['count_expire_user']." 告警用户：".$count_info['count_alert_user']." ";?></pre>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>流量占比</th>
                        <th>流量</th>
                        <th>总流量</th>
                        <th>过期日期</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($all_user as $data){
                        ?>
                    <tr>
                        <?php
                        echo "<td>".$data['uid']."</td>";
                        echo "<td><a href='#' onclick='update_user(".$data['uid'].")'>".$data['name']."</a></td>";
                        echo "<td>".$data['email']."</td>";
                        ?>
                        <td>
                        <div class="progress progress-striped active"> 
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round(($data['u']+$data['d'])/$data['transfer_enable'],2)*100;echo "%";?>;">
                                <span class="sr-only" title="<?php echo $admin->format_bytes($data['u']+$data['d']).' / '.$admin->format_bytes($data['transfer_enable']);?>"><?php echo round(($data['u']+$data['d'])/$data['transfer_enable'],2)*100;echo "%";?> 完成</span>
                            </div> 
                        </div>
                        </td>
                    <?php
                        echo "<td>".$admin->format_bytes($data['u']+$data['d']).' / '.$admin->format_bytes($data['transfer_enable'])."</td>";
                        echo "<td>".$admin->format_bytes($data['u']+$data['d']+$data['total'])."</td>";
                        echo "<td>".$data['expire_date']."</td>";
                        echo "<td><button type=\"button\" class=\"btn btn-success\">清空流量</button>";
                        echo " <button type=\"button\" class=\"btn btn-danger\">删除</button>";
                        echo " <button type=\"button\" class=\"btn btn-info\" onclick=\"send_alert('".$data['sckey']."')\"";
                        if ($data['sckey'] == ""){ echo "disabled='disabled'";}
                        echo ">通知</button>";
                        echo "</td>";
                    ?>
                    </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="http://v3.bootcss.com/assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="static/jquery-confirm.min.js"></script>
<script>
    var add_user = function () {
        $.alert({
            title: 'Alert!',
            content: 'Simple alert!',
        });
    };
    var update_user = function (uid) {
        $.confirm({
            title:"更新用户",
            content:'url:edit_user.php?uid='+uid,
        });

    };
    var clear_traffic = function (uid) {
        $.alert({
            title: 'Alert!',
            content: 'Simple alert!',
        });
    };
    var send_alert = function (sckey) {
        $.confirm({
            title: '发送微信提醒!',
            content: '<input type="text" class="form-control" id="alert-title"><textarea class="form-control" rows="3" id="alert-msg"></textarea>',
            buttons: {
                confirm: function () {
                    $.ajax({
                        type:"POST",
                        url:"action/_send_alert.php",
                        dataType:"json",
                        data:{
                            title: $("#alert-title").val(),
                            msg: $("#alert-msg").val(),
                            key: sckey
                        },
                        success:function(data){
                            if(data.ok === 'success'){
                                $.alert('发送成功');
                            }else{
                                $.alert(data.msg);
                            }
                        },
                        error:function(jqXHR){
                            alert("后台错误："+jqXHR.status);
                        }
                    });
                }
            },
        });
    };
</script>
</body>
</html>
