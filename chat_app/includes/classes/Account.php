<?php

class Account {
  private $con;

  public function __construct($con) {
    $this->con = $con;
  }

  private function sanitizeFormPassword($inputText) {
    $inputText = strip_tags($inputText);
    return $inputText;
  }
  
  private function sanitizeFormUsername($inputText) {
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    return $inputText;
  }

  public function register($username, $password, $email) {
    $password = $this->sanitizeFormPassword($password);
    $password = md5($password);
    $username = $this->sanitizeFormUsername($username);
    $last_user = mysqli_query($this->con, "SELECT * FROM users ORDER BY ID DESC LIMIT 1");
    $user_type = $last_user->fetch_assoc()["id"];
    $result = mysqli_query($this->con, 
      "INSERT INTO `users` (`id`, `username`, `password`, `user_type`, `email`, `friends`, `online`) 
      VALUES (NULL, '$username', '$password', '$user_type', '$email', '', false);");
    return $result;
  }

  public function confirm_account($username) {
    $username = $this->sanitizeFormUsername($username);
    $result = mysqli_query($this->con, 
      "SELECT * FROM users WHERE username='$username'");
    return $result;
  }

  public function login($username, $password) {
    $password = $this->sanitizeFormPassword($password);
    $password = md5($password);
    $username = $this->sanitizeFormUsername($username);
    $result = mysqli_query($this->con, 
      "SELECT * FROM users WHERE username='$username' AND password='$password'");
    return $result;
  }
}

?>