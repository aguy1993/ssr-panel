<?php

class Login
{
    private $db;

    function __construct(){
        global $db;
        $this->db = $db;
    }

    //login check
    function login_check($username,$passwd){
        if($this->db->has("user",[
            "AND" => [
                "email" => $username,
                "pass" => md5($passwd)
            ]
        ])){
            return 1;
        }else{
            return 0;
        }
    }

    //获取UID
    function get_uid($email) {
        $res = $this->db->select('user','uid',[
            "email" => $email
        ]);
        return $res[0];
    }

    //登陆成功后处理
    function login_success($uid) {
        $this->db->update('user',[
            '#last_login' => 'NOW()'
        ],[
            'uid' => $uid
        ]);
    }
}