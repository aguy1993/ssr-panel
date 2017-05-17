<?php

class Code
{
    private $db;

    function __construct(){
        global $db;
        $this->db = $db;
    }

    function check_code($code) {
        $res = $this->db->select('code','month',[
            "AND" => [
                "code" => $code,
                "uid" => null
            ]
        ]);
        if(!$res){
            return 0;
        }else{
            return $res[0];
        }
    }

    function update($code,$uid,$month) {
        $this->db->update('code',[
            "uid" => $uid,
            "#use_date" => 'NOW()'
        ],[
            "code" => $code
        ]);

        $this->db->update('user',[
            "expire_date" => date('Y-m-d',strtotime($month.'month'))
        ],[
            "uid" => $uid
        ]);
    }

    function renew($code,$uid,$start_date,$month) {
        $this->db->update('code',[
            "uid" => $uid,
            "#use_date" => 'NOW()'
        ],[
            "code" => $code
        ]);

        $this->db->update('user',[
            "expire_date" => date('Y-m-d',strtotime($start_date.' + '.$month.'month'))
        ],[
            "uid" => $uid
        ]);
    }

}