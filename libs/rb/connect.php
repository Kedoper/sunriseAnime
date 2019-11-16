<?php
include __DIR__ . "/rb-mysql.php";
$host = "127.0.0.1";
$db_name = "anime";
$db_userName = "root";
$db_password = "";
R::setup("mysql:host={$host};dbname={$db_name}",
    $db_userName, $db_password);

R::ext('xdispense', function ($type) {
    return R::getRedBean()->dispense($type);
});

session_start();

if (!R::testConnection() && $_SERVER['REQUEST_URI'] != "/oops") {
    $_SESSION['http_error'] = "bd";
    header("Location: /oops");
}