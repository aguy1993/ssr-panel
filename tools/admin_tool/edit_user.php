<?php
require_once 'lib/init.php';
$admin = new Admin();
if (!empty($_GET['uid'])){
    $user_info = $admin->get_user_info($_GET['uid']);
    ?>
    <div class="form-group">
        <input id="name" type="text" class="form-control" placeholder="昵称" value="<?php echo $user_info['name'];?>"/>
        <input disabled="disabled" type="text" class="form-control" placeholder="邮箱" value="<?php echo $user_info['email'];?>"/>
        <input id="passwd" type="text" class="form-control" placeholder="密码" value=""/>
        <input id="" type="text" class="form-control" placeholder="套餐流量" value="<?php echo $admin->format_bytes($user_info['transfer_enable']);?>"/>
        <input id="expire_date" type="text" class="form-control" placeholder="过期日期" value="<?php echo $user_info['expire_date'];?>"/>
        <input id="sckey" type="text" class="form-control" placeholder="sckey" value="<?php echo $user_info['sckey'];?>"/>
        <input disabled="disabled" type="text" class="form-control" placeholder="最后登录时间" value="<?php echo $user_info['last_login'];?>"/>
        <input disabled="disabled" type="text" class="form-control" placeholder="最后更新时间" value="<?php echo $user_info['update_date'];?>"/>
        <input id="remark" type="text" class="form-control" placeholder="备注" value="<?php echo $user_info['remark'];?>"/>
    </div>
    <?php
}else{
    exit('null uid');
}
?>
