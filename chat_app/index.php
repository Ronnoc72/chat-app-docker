<?php

session_start();

?>

<?php require("./includes/header.php"); ?>
<head>
  <link rel="stylesheet" href="assets/css/index.css" />
</head>

<div id="banner">
  <h1>Conversation Cove</h1>
  <p style="font-weight: 500;">Simple chatting with friends.</p>
</div>
<div id="account">
  <div>
    <a href="login.php">LOGIN</a>
    <p>Returning Member.</p>
  </div>
  <div>
    <a href="register.php">REGISTER</a>
    <p>First Time Visiter.</p>
  </div>
</div>

<?php require("./includes/footer.php"); ?>
