<?php

class Conversation {
  private $con;

  public function __construct($con) {
    $this->con = $con;
  }

  private function get_convos_json($username, $username2) {
    if ($username2 == "") {
      $query = mysqli_query($this->con, 
        "SELECT user2 FROM conversations WHERE user1='$username'");
      if ($query->num_rows) {
        return $query;
      } else {
        $query = mysqli_query($this->con, 
          "SELECT user1 FROM conversations WHERE user2='$username'");
        return $query;
      }
    }
    $query = mysqli_query($this->con, 
      "SELECT texts FROM conversations WHERE user1='$username' AND user2='$username2'");
    $query = $query->fetch_assoc()["texts"];
    $results = json_decode($query, true);
    if (!$results) {
      $query = mysqli_query($this->con, 
        "SELECT texts FROM conversations WHERE user1='$username2' AND user2='$username'");
      $query = $query->fetch_assoc()["texts"];
      $results = json_decode($query, true);
    }
    return $results;
  }

  private function convo_exist($user1, $user2) {
    $results = mysqli_query($this->con, 
      "SELECT * FROM conversations WHERE user1='$user1' AND user2='$user2'");
    if (mysqli_fetch_array($results)) {
      return true;
    }
    return false;
  }

  public function add_friend($username, $friend_name) {
    $query = mysqli_query($this->con, 
      "SELECT * FROM users WHERE username='$friend_name'");
    if (mysqli_fetch_array($query)) {
      $friends = mysqli_query($this->con, 
        "SELECT friends FROM users WHERE username='$username'");
      $friends = $friends->fetch_assoc()["friends"];
      $friends_list = explode(',', $friends);
      $user_friends = mysqli_query($this->con, 
        "SELECT friends FROM users WHERE username='$friend_name'");
      $user_friends = $user_friends->fetch_assoc()["friends"];
      $user_friends_list = explode(',', $user_friends);
      if (in_array($friend_name, $friends_list) || in_array($username, $user_friends_list)) {
        return "Friend Already Added.";
      } else {
        $friends .= $friend_name . ',';
        $user_friends .= $username . ',';
        mysqli_query($this->con, 
          "UPDATE users SET friends='$friends' WHERE username='$username'");
        mysqli_query($this->con, 
          "UPDATE users SET friends='$user_friends' WHERE username='$friend_name'");
        return "Friend Added.";
      }
    } else {
      return "User Doesn't Exist.";
    }
  }

  public function start_convo($username, $friend_name) {
    if ($this->convo_exist($username, $friend_name) || $this->convo_exist($friend_name, $username)) {
      return "You Already have a Conversation with $friend_name";
    }
    mysqli_query($this->con, 
      "INSERT INTO `conversations` (`id`, `user1`, `user2`, `texts`) 
      VALUES (NULL, '$username', '$friend_name', '');");
    header("Location: chat.php?user=" . $friend_name);
  }

  public function get_friends($username) {
    $friends = mysqli_query($this->con, 
      "SELECT friends FROM users WHERE username='$username'");
    $friends = $friends->fetch_assoc()["friends"];
    $friends_list = explode(',', $friends);
    unset($friends_list[count($friends_list)]);
    return $friends_list;
  }

  public function get_online($friend) {
    $result = mysqli_query($this->con, 
      "SELECT online FROM users WHERE username='$friend'");
    $result = $result->fetch_assoc();
    return "online".$result["online"];
  }

  public function get_all_convos($username) {
    $results = $this->get_convos_json($username, "");
    return $results;
  }

  public function get_convos($username, $username2) {
    $results = $this->get_convos_json($username, $username2);
    return $results;
  }

  public function send_message($username, $username2, $message) {
    $results = $this->get_convos_json($username, $username2);
    $current_time = (string)time();
    $json = array("date" => $current_time, "content" => $message, "user" => $username);
    if ($results) {
      end($results);
      $text_id = key($results);
      $text_id = ((int)str_replace("text", "", $text_id))+1;
      $results["text$text_id"] = $json;
      $json_result = json_encode($results);
    } else {
      $results["text"] = $json;
      $json_result = json_encode($results);
    }
    mysqli_query($this->con, 
      "UPDATE conversations SET texts='$json_result' WHERE user1='$username' AND user2='$username2'");
    mysqli_query($this->con, 
      "UPDATE conversations SET texts='$json_result' WHERE user1='$username2' AND user2='$username'");
  }
}

?>