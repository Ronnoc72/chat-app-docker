<?php

require("./includes/config.php");

$user = $_SESSION[$_SESSION["current_user"]];
$value = $_GET["value"];
$save_type = $_GET["type"];

$result = mysqli_query($con, "SELECT * FROM users WHERE $save_type='$value'");
if (mysqli_fetch_array($result)) {
  echo '{msg: "Already exists."}';
} else {
  mysqli_query($con, "UPDATE users SET $save_type='$value' WHERE username='$user'");
  echo '{msg: "Successfully changed."}';
}

?>