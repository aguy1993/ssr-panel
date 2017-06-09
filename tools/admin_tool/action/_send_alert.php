<?php
require_once '../lib/init.php';
header('Content-type: application/json');
$admin = new Admin();

$rs['result'] = 'fail';

if (!empty($_POST['title']) && !empty($_POST['msg']) && !empty($_POST['key'])){

    $json_res = json_decode($admin->send_alert($_POST['title'],$_POST['msg'],$_POST['key']));
    if(!$json_res->errno) {
        $rs['result'] = 'success';
    }else {
        $rs['msg'] = $json_res->errmsg;
    }
}else{
    $rs['msg'] = '缺少参数';
}
echo json_encode($rs);
?>