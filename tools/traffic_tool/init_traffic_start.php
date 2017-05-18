<?php
require_once 'lib/init.php';

$res = $db->select('user','*');

foreach($res as $data)
{
	$db->update('user',[
		"day_start" => $data['u']+$data['d']
	],[
		'uid' => $data['uid']
	]);
}

echo date('Y-m-d H:i:s')." -- record start traffic all\n";