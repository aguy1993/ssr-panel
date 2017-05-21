<?php
header("Content-type: application/json");
require_once '../lib/init.php';
require_once  '../action/_verifyCaptcha.php';

$res = new Register();
$code = new Code();
$user = new User();
$reg = new Register();

$email = $_POST['email'];
$email = strtolower($email);
$passwd = $_POST['passwd'];
$name = $_POST['name'];
$reg_code = $_POST['reg_code'];

$a['result'] = 'fail';

if(!$rs_ca){
    $a['msg'] = '验证码错误';
}elseif (strlen($email) > 32 || strlen($email) < 6 || $email == '') {
    $a['msg'] = "邮箱不符合规范！";
}elseif (strlen($passwd) > 16 || strlen($passwd) < 6 || $passwd == '') {
    $a['msg'] = "密码不符合规范！";
}elseif (strlen($name) > 16 || strlen($name) < 4 || $name == '') {
    $a['msg'] = "昵称不符合规范！";
}elseif (strlen($reg_code) != 16 || $reg_code == '') {
    $a['msg'] = "兑换码不符合规范！";
}elseif(!$user->is_email_used($email)){
    $a['msg'] = "邮箱已被注册！";
}elseif (!$code->check_code($reg_code)) {
    $a['msg'] = "兑换码不存在或已被激活！";
}else{
    $month = $code->check_code($reg_code);
    $reg->reg($name,$email,$passwd);
    $res = $reg->get_uid($email);
    $code->update($reg_code,$res,$month);

    $a['result'] = 'success';
    $a['msg'] = "注册成功！";
}
echo json_encode($a);