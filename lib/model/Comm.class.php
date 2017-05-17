<?php

class Comm
{
    //根据邮箱获取头像
    static function Gravatar( $email, $s = 256, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
        //$url = 'http://gravatar.duoshuo.com/avatar/';
        $url = 'https://secure.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s";
//        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    //获取随机字符串
    static function genStr($len = 16) {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $len; $i++ ) {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }

    //格式化字节数
    static function format_bytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
    }

    //发送微信通知
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
}
