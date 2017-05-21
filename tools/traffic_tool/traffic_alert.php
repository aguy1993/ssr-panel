<?php
require_once 'lib/init.php';

$res = $db->select('user','*');

$record_date = date('Y-m-d',strtotime('-10 day'));

if (date('d') == 1) {
    //月初清空流量
    foreach($res as $data)
    {
        $db->update('user',[
            //记录总流量
            "total[+]" => $data['u']+$data['d'],
            'u' => 0,
            'd' => 0
        ],[
            'uid' => $data['uid']
        ]);
    }

    echo date('Y-m-d H:i:s')." -- 使用流量已重置\n";

    $res = $db->query("SELECT * FROM user WHERE sckey IS NOT NULL AND expire_date > NOW()")->fetchAll();
    foreach ($res as $data) {
        $user = $db->select('user','*',[
            "uid" => $data['uid']
        ]);

        if($user) {
            $traffic = $db->query("SELECT SUM(traffic) FROM traffic WHERE uid =".$user[0]['uid']." AND record_date >= '".date('Y-m-d',strtotime('-1 month'))."'")->fetchAll()[0][0];
            $text = 'Hi,'.$user[0]['name']."\n\n".'上月共计使用'.format_bytes($traffic).'流量，累积使用流量'.format_bytes($user[0]['total']).'。'."\n\n本月流量已重置，GLHF!!!\n\n=￣ω￣=\n\nBy 赫萝酱\n\n".date('Y-m-d H:i:s');
            $json_res = json_decode(sc_send('SSR月流量提醒('.date('Ym',strtotime('-1 month')).')',$text,$data['sckey']));
            if(!$json_res->errno){
                echo date('Y-m-d H:i:s')." -- ".$user[0]['uid']." - ".$user[0]['email']."月流量提醒发送成功\n";
            }else{
                echo date('Y-m-d H:i:s')." -- ".$user[0]['uid']." - ".$user[0]['email']."月流量提醒发送失败\n";
            }

        }
    }

}else{
    //每天凌晨发送昨日流量提醒
    //加载所有开启通知的用户
    $res = $db->query("SELECT * FROM user WHERE sckey IS NOT NULL AND expire_date > NOW()")->fetchAll();
    foreach ($res as $data)
    {
        //加载用户信息
        $user = $db->select('user','*',[
            "uid" => $data['uid']
        ]);
        if($user)
        {
            //加载流量信息
            $traffic = $db->query('SELECT traffic from traffic WHERE uid = '.$user[0]['uid'].' AND record_date ='."'".$record_date."'")->fetchAll();
            if ($traffic && $traffic[0]['traffic'] != 0)
            {
                //拼接提醒信息
                $text = 'Hi,'.$user[0]['name']."\n\n".'昨日使用流量'.format_bytes($traffic[0]['traffic']).'，本月共计使用'.format_bytes($user[0]['u']+$user[0]['d']).'流量，剩余流量约'.format_bytes($user[0]['transfer_enable'] - $user[0]['u'] - $user[0]['d']).'。'."\n\n=￣ω￣=\n\nBy 赫萝酱\n\n".date('Y-m-d H:i:s');
                $json_res = json_decode(sc_send('SSR月流量提醒('.date('Ymd',strtotime('-1 day')).')',$text,$data['sckey']));
                if(!$json_res->errno)
                {
                    echo date('Y-m-d H:i:s')." -- ".$user[0]['uid']." - ".$user[0]['email']."每日流量提醒发送成功\n";
                }else
                {
                    echo date('Y-m-d H:i:s')." -- ".$user[0]['uid']." - ".$user[0]['email']."每日流量提醒发送失败\n";
                }
            }
        }
    }
}