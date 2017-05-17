<?php
require_once '../lib/init.php';
$code = new Code();
$user = new User();

$renew_code = strtolower($_POST['code']);
$uid = $_POST['uid'];

if (!$code->check_code($renew_code)) {
    $a['msg'] = "兑换码不存在或已被激活！";
}else{
    $res = $code->check_code($renew_code);
    if(!$user->check_expire($uid)) {
        $start_date = date('Y-m-d');
    }else{
        $start_date = $user->check_expire($uid);
    }
    $code->renew($renew_code,$uid,$start_date,$res);
    $a['ok'] = 1;
    $a['msg'] = $res;

}
echo json_encode($a);