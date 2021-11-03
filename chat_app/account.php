<?php

require("./includes/config.php");

function get_friends($friend_str) {
  $friends_list = explode(',', $friend_str);
  unset($friends_list[count($friends_list)]);
  return $friends_list;
}

$user = $_SESSION[$_SESSION["current_user"]];
$info = mysqli_query($con, "SELECT * FROM users WHERE username='$user'");
$info = $info->fetch_assoc();
$friends = get_friends($info["friends"]);

?>

<?php require("./includes/header.php"); ?>
<head>
  <link rel="stylesheet" href="./assets/css/account.css" />
</head>

<h2>Account</h2>
<section id="info">
  <?php if (!empty($info)): ?>
    <span>Username: <div class="inline" id="username">
      <p><?= $info["username"] ?></p><button class="edit">edit</button>
    </div><div id="input-username" style="display: none;"><input type="text" /><button class="save">Save</button></div></span>
    <span>Email: <div class="inline" id="email">
      <p><?= $info["email"] ?></p><button class="edit">edit</button>
    </div><div id="input-email" style="display: none;"><input type="text" /><button class="save">Save</button></div></span>
    <span>Friends:</span>
    <div>
      <?php foreach ($friends as $friend): ?>
        <?php if ($friend): ?>
          <p class="friend"><?= $friend ?></p>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<a href="home.php" id="home-page">Home</a>

<script>
function saveValues(value, saveType) {
  const xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = () => {
    if (this.readyState == 4 && this.status == 200) {
      const msg = JSON.parse(this.responseText);
      console.log(msg);
    }
  }
  console.log(`saveaccount.php?value=${value}&type=${saveType}`);
  xmlhttp.open("GET", `saveaccount.php?value=${value}&type=${saveType}`, true);
  xmlhttp.send();
}

const divs = document.getElementsByClassName("inline");

for (let i = 0; i < divs.length; i++) {
  divs[i].children[1].addEventListener("click", () => {
    const index = "input-"+divs[i].id;
    console.log(index);
    const input = document.getElementById(index);
    if (input.style.display === "none") {
      input.style.display = "block";
    } else {
      input.style.display = "none";
    }
  });
}

const saveBtns = document.getElementsByClassName("save");

for (let i = 0; i < saveBtns.length; i++) {
  saveBtns[i].addEventListener("click", () => {
    const value = saveBtns[i].parentElement.children[0].value;
    const saveType = saveBtns[i].parentElement.id;
    let arr = saveType.split("input-")[1];
    saveValues(value, arr);
  });
}


</script>


<?php require("./includes/footer.php"); ?>