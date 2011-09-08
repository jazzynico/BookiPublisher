<?php
include_once('../config.inc.php');
include_once('../lib/init.inc.php');

$users = json_decode(file_get_contents('../data/userInfo.json'));
$books = json_decode(file_get_contents('../data/bookInfo.json'));

$tableUsers = new Axon(DB_TABLE_USERS);
$tableUsers->load();
if (!$tableUsers->dry()) $tableUsers->erase();
foreach ($users as $user) {
    $user = (array) $user;
    $tableUsers->username = $user['username'];
    $tableUsers->password = sha1($user['password'] . BOOKI_SALT);
    unset($user['username']);
    unset($user['password']);
    $tableUsers->plugins = serialize($user);
    $tableUsers->save();
    $tableUsers->reset();
}

$tableBooks = new Axon(DB_TABLE_BOOKS);
$tableBooks->load();
if (!$tableBooks->dry()) $tableBooks->erase();
foreach ($books as $book) {
    F3::set('book', (array) $book);
    $tableBooks->copyFrom('book');
    $tableBooks->save();
    $tableBooks->reset();
}

echo "done\n";
