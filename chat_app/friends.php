<?php

require("./includes/config.php");
require("./includes/classes/Conversation.php");

$user = $_SESSION[$_SESSION["current_user"]];
$conversation = new Conversation($con);
$query = $conversation->get_friends($user);
$success = '';
$text = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $text = '';
  if (isset($_POST["friendUsername"])) {
    $success = $conversation->add_friend($user, $_POST["friendUsername"]);
  }
  if (isset($_POST["friend"])) {
    $text = $conversation->start_convo($user, $_POST["friend"]);
  }
}

?>

<?php require("./includes/header.php"); ?>
<head>
  <link rel="stylesheet" href="./assets/css/friends.css" />
</head>

<h4>Friends: </h4>
<section id="friends">
  <p name="text"><?= $text ?></p>
  <?php if ($query): ?>
    <?php foreach ($query as $friend): ?>
      <?php if ($friend): ?>
        <form method="post" id="start-convo">
          <input name="friend" value="<?= $friend ?>" readonly></input>
          <button>Start Conversation</button>
        </form>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php else: ?>
    <p>No Friends</p>
  <?php endif; ?>
</section>

<button id="add-friend">Add Friends</button>
<p style="text-align: center;"><?= $success ?></p>
<form id="friend-form" method="post" style="display: none;">
  <input name="friendUsername" type="text" placeholder="Username"></input>
  <button>Submit</button>
</form>
<a href="home.php" id="home-page">Home</a>

<script>
const friendForm = document.getElementById("friend-form");
document.getElementById("add-friend").addEventListener("click", (e) => {
  if (friendForm.style.display == "flex") {
    friendForm.style.display = "none";
    return;
  }
  friendForm.style.display = "flex";
});

friendForm.addEventListener("onsubmit", (e) => {
  friendForm.style.display = "flex";
})
</script>

<?php require("./includes/footer.php"); ?>