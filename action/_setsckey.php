<?php
header("Content-type: application/json");
require_once '../lib/init.php';
require_once '_check.php';

$user = new User();
$comm = new Comm();

$sckey = strtolower($_POST['sckey']);
session_start();
$uid = $_SESSION['uid'];

$json_res = json_decode($comm->sc_send('SSR微信提醒测试','Hi，这是一条测试消息。 随机字符：'.$comm->genStr(),$sckey));
$rs['ok'] = 0;
if(!$json_res->errno) {
  //测试通过
  $user->set_sckey($sckey,$uid);
  $rs['ok'] = 1;
  $rs['msg'] = '设置成功！测试消息已发送，请注意查收🎉';

}else{
  $rs['msg'] = '测试未通过，请检查sckey！';
}

echo json_encode($rs);
?>
