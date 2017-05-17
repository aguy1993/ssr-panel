<?php
require_once '../lib/init.php';
$login = new Login();

$email = $_POST['email'];
$email = strtolower($email);
$passwd = $_POST['passwd'];
$rem = $_POST['remember_me'];

if($login->login_check($email,$passwd)){
    $ext = 3600;
    $rs['ok'] = '1';
    $rs['msg'] = "欢迎回来";
    if($rem= "week"){
        $ext = 3600*24*7;
    }
    session_set_cookie_params($ext);
    session_start();
    $_SESSION['uid'] = $login->get_uid($email);
    $login->login_success($_SESSION['uid']);
}else{
    $rs['msg'] = "邮箱或者密码错误";
}

echo json_encode($rs);