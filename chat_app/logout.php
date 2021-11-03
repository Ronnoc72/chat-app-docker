<?php

require("./includes/config.php");

$username = $_GET["user"];
mysqli_query($con, 
  "UPDATE `users` SET online=false WHERE username='$username'");

session_destroy();
unset($_SESSION["user"]);
header("Location: index.php");

?>