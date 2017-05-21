<?php
require_once 'lib/init.php';

$res = $db->select('user','*');

$record_date = date('Y-m-d',strtotime('-1 day'));

//每天凌晨记录昨天发生流量
foreach($res as $data)
{
	if (($data['u']+$data['d']-$data['day_start']) > 0) {

		$db->insert('traffic',[
			"uid" => $data['uid'],
			"record_date" => $record_date,
			"traffic" => $data['u']+$data['d']-$data['day_start']
		]);
	}
}

echo date('Y-m-d H:i:s')." -- 记录每日流量成功\n";



// //每天凌晨记录今天初始流量
foreach($res as $data)
{
	$db->update('user',[
		"day_start" => $data['u']+$data['d']
	],[
		'uid' => $data['uid']
	]);
}

echo date('Y-m-d H:i:s')." -- 今日流量已记录\n";
