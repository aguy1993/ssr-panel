<?php


class Admin
{
    private $db;

    function __construct(){
        global $db;
        $this->db = $db;
    }

    //获取所有用户信息
    function get_all_user() {
        $res = $this->db->select('user','*');
        return $res;
    }

    //获取用户信息
    function get_user_info($uid) {
        $res = $this->db->select('user','*',[
            "uid" => $uid
        ]);
        return $res[0];
    }

    //格式化字节数
    function format_bytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
    }

    //获取系统统计信息
    function get_count() {
        $sql = "SELECT COUNT(*) as count_user,(SELECT COUNT(*) FROM `user` WHERE `t` != 0 AND `u` != 0) AS count_active,(SELECT SUM(`u`+`d`) FROM `user`) AS count_traffic,(SELECT COUNT(*) FROM `user` WHERE expire_date < NOW()) AS count_expire_user,(SELECT COUNT(*) FROM `user` WHERE TIMESTAMPDIFF(DAY,NOW(),`expire_date`)<=30 AND TIMESTAMPDIFF(DAY,NOW(),`expire_date`)>30) AS count_alert_user FROM `user`";
        $res = $this->db->query($sql)->fetchAll();
        return $res[0];
    }

    //清空流量
    function clear_traffic($uid=0) {
        if ($uid) {
            $this->db->update('user',[
                "u" => 0,
                "t" => 0
            ],[
                "uid" => $uid
            ]);
        }else{
            $this->db->update('user',[
                "u" => 0,
                "t" => 0
            ]);
        }
    }

    //发送微信通知
    function send_alert($text,$desp='',$key) {
        $postdata = http_build_query(
            array(
                'text' => $text,
                'desp' => $desp
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        return $result = file_get_contents('http://sc.ftqq.com/'.$key.'.send', false, $context);
    }

    //发送通知
    function send_alerts($uid=0) {

    }

    //新增用户
    function add_user() {

    }

    //修改用户
    function update_user() {

    }

    //删除用户
    function delete_user() {

    }
}