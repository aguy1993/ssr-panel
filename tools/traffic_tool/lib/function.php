<?php
function sc_send($text,$desp='',$key) {
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

function format_bytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
}