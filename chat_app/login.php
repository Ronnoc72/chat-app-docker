<?php

require("./includes/config.php");
require("./includes/classes/Account.php");

$error_array = array();
$account = new Account($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];
  $success = $account->login($username, $password);
  $success = mysqli_fetch_array($success);
  if ($success) {
    mysqli_query($con, 
      "UPDATE `users` SET online=true WHERE username='$username'");
    $current_user = (string)$success["id"];
    $_SESSION["user$current_user"] = $success["username"];
    $_SESSION["current_user"] = "user$current_user";
    header("Location: home.php");
  } else {
    array_push($error_array, "Account doesn't exist. Try Signing up.");
    array_push($error_array, "<a id='register-btn' href='register.php'>Register</a>");
  }
}

function getInputValue($name) {
  if(isset($_POST[$name])) {
    echo $_POST[$name];
  }
}

?>

<?php require("./includes/header.php"); ?>
<head>
  <link rel="stylesheet" href="assets/css/login.css" />
</head>

<h2>Login Page</h2>
<section id="login">
  <form method="post">
    <?php foreach ($error_array as $error): ?>
      <p><?= $error; ?></p>
    <?php endforeach; ?>
    <div>
      <label for="username"></label>
      <input id="username" name="username" type="text" placeholder="username" 
        value="<?php getInputValue("username") ?>" required></input>

      <label for="password"></label>
      <input id="password" name="password" type="password" 
        placeholder="password" value="<?php getInputValue("password") ?>" required></input>
    </div>
    <button>Login</button>
  </form>
</section>

<button id="back">Go Back</button>

<script>

document.getElementById("back").addEventListener("click", (e) => {
  let url = window.location.href.split("/");
  url[url.length-1] = "index.php";
  window.location.href = url.join("/");
});

</script>

<?php require("./includes/footer.php"); ?>