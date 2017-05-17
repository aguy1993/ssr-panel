<?php

class Register
{

    private $db;

    function __construct(){
        global $db;
        $this->db = $db;
    }

    function reg($username,$email,$pass){

        $sspass = Comm::genStr();

        $this->db->insert('user',[
            "name" => $username,
            "email" => $email,
            "pass" => md5($pass),
            "passwd" =>  $sspass,
            "t" => '0',
            "u" => '0',
            "d" => '0',
            "transfer_enable" => '21474836480',
            "port" => $this->GetLastPort()+rand(1,5),
            "#reg_date" =>  'NOW()',
            "#expire_date" =>  'NOW()',
            "#update_date" =>  'NOW()'
        ]);
    }

    function get_uid($email) {
        $res = $this->db->select('user','uid',[
            "email" => $email
        ])[0];

        return $res;
    }

    function GetLastPort(){
        $datas = $this->db->select('user',"*",[
            "ORDER" => "uid DESC",
            "LIMIT" => 1
        ]);
        return $datas['0']['port'];
    }
}
