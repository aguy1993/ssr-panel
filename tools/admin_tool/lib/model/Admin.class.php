<?php


class Admin
{
    private $db;

    function __construct(){
        global $db;
        $this->db = $db;
    }

    function get_all_user() {
        $res = $this->db->select('user','*');
        return $res;
    }

    //格式化字节数
    function format_bytes($size) {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
    }
}