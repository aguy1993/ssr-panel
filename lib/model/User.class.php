<?php

class User
{
    private $db;

    function __construct(){
        global $db;
        $this->db = $db;
    }

    //判断email是否占用
    function is_email_used($email){
        if($this->db->has("user",[
            "email" => $email
        ])){
            return 0;
        }else{
            return 1;
        }
    }

    //根据UID获取用户信息
    function get_user_info($uid){
        $res = $this->db->select('user','*',[
           "uid" => $uid
        ]);
        if($res){
            return $res[0];
        }else{
            return $res;
        }
    }

    //判断是否过期
    function check_expire($uid) {
        $res = $this->db->select('user','expire_date',[
            "uid" => $uid
        ]);
        if ($res[0] < date('Y-m-d') ) {
            return 0;
        }else{
            return $res[0];
        }
    }

    //获取一月内使用流量
    function get_traffic($uid) {
        $lastDate = date('Y-m-d',strtotime('-1 day'));
        $firstDate = date('Y-m-d',strtotime('-30 day'));
        $a = $this->db->query("SELECT * FROM traffic WHERE uid=".$uid." AND record_date <='".$lastDate."' AND record_date >='".$firstDate."' ORDER BY record_date ASC")->fetchAll();
        //添加起点
        if ($a[0]['record_date'] != $firstDate) {
            array_unshift($a,array('record_date' => $firstDate, 'traffic' => 0));
        }
        //添加终点
        if ($a[count($a)-1]['record_date'] != $lastDate) {
            $a[] = array('record_date' => $lastDate, 'traffic' => 0);
        }
        $last = $lastDate;

        $res['date'] = "[";
        $res['traffic'] = "[";
        foreach($a as $r) {
          while($last && $last < $r['record_date']) {
            $res['date'] .= "'".$last."',";
            $res['traffic'] .= "0,";
            $last = date('Y-m-d', $last = strtotime("+1 day {$last}"));
          }
          $res['date'] .= "'".$r['record_date']."',";
          $res['traffic'] .= round($r['traffic']/1048576,2).",";
          $last = date('Y-m-d', strtotime("+1 day {$r['record_date']}"));
        }
        $res['date'] = substr($res['date'],0,strlen($res['date'])-1);
        $res['traffic'] = substr($res['traffic'],0,strlen($res['traffic'])-1);
        $res['date'] .= "]";
        $res['traffic'] .= "]";
        return $res;
    }

    //更新sckey
    function set_sckey($sckey,$uid) {
      $res = $this->db->update('user',[
        "sckey" => $sckey
      ],[
        "uid" => $uid
      ]);
    }
}
