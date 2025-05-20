<?php
function db_connect() {
    $host = 'localhost';     //your db host
    $user = 'sysadmin';      //your db username
    $pass = '';              //your db password
    $db   = 'dbJustInCase';  //ur db name

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    return $conn;
}
