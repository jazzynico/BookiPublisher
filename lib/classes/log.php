<?php

class Log
{
    static public function insert($action, $username = null) {
        $tableLog = new Axon(DB_TABLE_LOG);
        $tableLog->username = $username ? $username : array_value($_SESSION, 'username');
        $tableLog->action = $action;
        $tableLog->date = date("Y-m-d H:i:s O");
        $tableLog->ipaddress = $_SERVER['REMOTE_ADDR'];
        $tableLog->save();
    }

    static public function lookup($username) {
        $tableLog = new Axon(DB_TABLE_LOG);
        return $tableLog->find("username='$username'", 'date desc');
    }
}
