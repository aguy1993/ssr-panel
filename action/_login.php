<?php
header("Content-type: application/json");

require_once '../lib/init.php';
require_once  '../action/_verifyCaptcha.php';
$login = new Login();

$email = $_POST['email'];
$email = strtolower($email);
$passwd = $_POST['passwd'];
$rem = $_POST['remember_me'];

$rs['result'] = 'fail';

if(!$rs_ca){
    $rs['msg'] = '验证码错误';
}elseif(!$login->login_check($email,$passwd)){
    $rs['msg'] = "邮箱或者密码错误";
}else{
    $ext = 3600;
    $rs['result'] = 'success';
    $rs['msg'] = "欢迎回来";
    if($rem= "week"){
        $ext = 3600*24*7;
    }
    session_set_cookie_params($ext);
    session_start();
    $_SESSION['uid'] = $login->get_uid($email);
    $login->login_success($_SESSION['uid']);
}

echo json_encode($rs);