<?php

require("./includes/config.php");
require("./includes/classes/Conversation.php");

$user = $_SESSION[$_SESSION["current_user"]];
$friend = $_GET["user"];
$conversation = new Conversation($con);

$query = $conversation->get_convos($user, $friend);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $message = $_POST["message"];
  $conversation->send_message($user, $friend, $message);
  $query = $conversation->get_convos($user, $friend);
}

?>

<?php require("./includes/header.php"); ?>
<head>
  <link rel="stylesheet" href="./assets/css/chat.css" />
</head>

<h1>Chat</h1>
<section id="chat-window">
  <div id="window">
    <div id="top-label">
      <span><span class="special">You: </span><?= $user ?></span>
      <span><span class="special">Friend: </span><?= $friend ?></span>
    </div>
    <div id="texts"></div>
    <form id="send" method="post">
      <div>
        <input type="text" name="message" id="message" placeholder="Message Friend" maxlength="250"></input>
        <button>Send</button>
      </div>
    </form>
  </div>
</section>

<a href="home.php" id="home-page">Home</a>

<script>
function getConvos() {
  const xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const textDiv = document.getElementById("texts");
      textDiv.innerHTML = "";
      const texts = JSON.parse(this.responseText);
      for (let text in texts) {
        const p = document.createElement("p");
        const friend = "<?= $friend ?>";
        if (texts[text].user == friend) {
          p.style.color = "red";
        } else {
          p.style.color = "blue";
        }
        p.innerHTML = texts[text].user+': '+texts[text].content;
        textDiv.appendChild(p);
      }
    }
  }
  xmlhttp.open("GET", "getconvos.php?user=<?= $friend ?>", true);
  xmlhttp.send();
}

getConvos();

setInterval(getConvos, 5000);
</script>

<?php require("./includes/footer.php"); ?>