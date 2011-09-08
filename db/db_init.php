<?php
include_once('../config.inc.php');
include_once('../lib/init.inc.php');

$tableUsers = new Axon(DB_TABLE_USERS);
$tableBooks = new Axon(DB_TABLE_BOOKS);
$tableBlogs = new Axon(DB_TABLE_BLOGS);
$tableLog = new Axon(DB_TABLE_LOG);

$tableUsers->load();
if (!$tableUsers->dry()) $tableUsers->erase();
$tableUsers->username = 'admin';
$tableUsers->password = sha1('admin' . BOOKI_SALT);
$tableUsers->email = 'admin@localhost';

$plugins = array();
$dh = @opendir('../plugins/');
while (($dirname = readdir($dh)) !== false){
    if (($dirname !=='.') && ($dirname!=='..') && ($dirname!=='login')) {
        $plugins[$dirname] = 'on';
	};
}
closedir($dh);
$tableUsers->plugins = $plugins ? serialize($plugins) : null;
$tableUsers->save();

$tableBooks->load();
if (!$tableBooks->dry()) $tableBooks->erase();

$tableLog->load();
if (!$tableLog->dry()) $tableLog->erase();

echo "done\n";
