<?php

require("./includes/config.php");
require("./includes/classes/Conversation.php");

$user = $_SESSION[$_SESSION["current_user"]];
$friend = $_GET["user"];

$conversation = new Conversation($con);

$query = $conversation->get_convos($user, $friend);
$query = array_slice($query, -8);
echo json_encode($query);

?>