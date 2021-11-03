<?php

ob_start();
session_start();

$hostname = "localhost";
$username = "connor";
$password = "eBctJad93KLP@it";
$db = "chat_app";

$timezone = date_default_timezone_set("America/Phoenix");

$con = mysqli_connect($hostname, $username, $password, $db);

if(mysqli_connect_errno()) {
  echo "Failed to connect: " . mysqli_connect_errno();
}

?>