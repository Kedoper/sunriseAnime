<?php
include __DIR__ . "/rb-mysql.php";
$host = "127.0.0.1:3050";
$db_name = "anime";
$db_userName = "root";
$db_password = "123";
R::setup("mysql:host={$host};dbname={$db_name}",
    $db_userName, $db_password);

R::ext('xdispense', function ($type) {
    return R::getRedBean()->dispense($type);
});

session_start();