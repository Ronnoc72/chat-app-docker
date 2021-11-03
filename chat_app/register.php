<?php

require("./includes/config.php");
require("./includes/classes/Account.php");

$error_array = array();
$account = new Account($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  if ($password != $_POST["confirm-password"]) {
    array_push($error_array, "Passwords Do Not Match.");
  }
  if (strlen($password) < 10) {
    array_push($error_array, "Password is too short.");
  } elseif (strlen($password) > 60) {
    array_push($error_array, "Password is too long.");
  }
  if (strlen($username) < 5) {
    array_push($error_array, "Username is too short.");
  } elseif (strlen($username) > 30) {
    array_push($error_array, "Username is too long.");
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($error_array, "Email is invalid.");
  }
  $hasAccount = $account->confirm_account($username);
  if (mysqli_fetch_array($hasAccount)) {
    array_push($error_array, "Account already exists. Try Signing in.");
  }

  if (empty($error_array)) {
    $successful = $account->register($username, $password, $email);
    if ($successful) {
      header("Location: index.php");
    }
  }
}

function getInputValue($name) {
  if(isset($_POST[$name])) {
    echo $_POST[$name];
  }
}

?>

<?php require("./includes/header.php") ?>
<head>
  <link rel="stylesheet" href="./assets/css/register.css" />
</head>

<h2>Register Account</h2>
<section id="register">
  <form method="post">
    <?php foreach ($error_array as $error): ?>
      <p><?= $error; ?></p>
    <?php endforeach; ?>
    <div>
      <label for="username"></label>
      <input id="username" name="username" type="text" placeholder="username" 
        value="<?php getInputValue("username") ?>" required></input>

      <label for="email"></label>
      <input id="email" name="email" type="email" placeholder="email" 
        value="<?php getInputValue("email") ?>" required></input>

      <label for="password"></label>
      <input id="password" name="password" type="password" 
        placeholder="password" value="<?php getInputValue("password") ?>" required></input>

      <label for="confirm-password"></label>
      <input id="confirm-password" name="confirm-password" type="password" 
        placeholder="confirm-password" value="<?php getInputValue("confirm-password") ?>" required></input>
    </div>
    <button>Regsiter</button>
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

<?php require("./includes/footer.php") ?>
