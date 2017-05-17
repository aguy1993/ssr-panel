<?php

class Server
{
    private $db;

    function __construct() {
        global $db;
        $this->db = $db;
    }

    function list_server() {
        $res = $this->db->select('service','*');
        return $res;
    }
}