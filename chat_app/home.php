<?php

require("./includes/config.php");
require("./includes/classes/Conversation.php");

$user = $_SESSION[$_SESSION["current_user"]];
$conversation = new Conversation($con);
$query = $conversation->get_all_convos($user);

$key = array_keys(mysqli_fetch_array($query))[1];

$query = $conversation->get_all_convos($user);
?>

<?php require("./includes/header.php"); ?>
<head>
  <link rel="stylesheet" href="./assets/css/home.css" />
</head>

<h2>Welcome <?= $user ?></h2>
<section id="account"></section>
<section id="conversations">
  <h4>Conversations: </h4>
  <?php if ($query): ?>
    <?php while ($row = $query->fetch_assoc()): ?>
      <div class="convo">
        <a href="chat.php?user=<?= $row["$key"] ?>"><?= $row["$key"] ?></a>
        <span class="online-user <?= $conversation->get_online($row["$key"]); ?>"></span>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No Conversation</p>
  <?php endif; ?>
</section>

<section id="links">
  <div>
    <p>Add Conversations with Friends</p>
    <a href="friends.php">Friends</a>
  </div>
  <div>
    <p>Sign out of your Account</p>
    <a href="logout.php?user=<?= $user ?>">Logout</a>
  </div>
</section>
<span id="account-img">
  <a href="account.php"><img src="./assets/imgs/user.png" width="50px" height="50px" alt="user profile" /></a>
</span>

<?php require("./includes/footer.php"); ?>