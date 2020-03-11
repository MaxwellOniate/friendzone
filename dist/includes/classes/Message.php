<?php

class Message
{
  private $con, $user;

  public function __construct($con, $username)
  {
    $this->con = $con;
    $this->user = new User($con, $username);
  }

  public function getMostRecentUser()
  {
    $userLoggedIn = $this->user->getUsername();

    $query = $this->con->prepare("SELECT user_to, user_from FROM messages WHERE user_to = :un OR user_from = :un ORDER BY id DESC LIMIT 1");
    $query->execute([':un' => $userLoggedIn]);

    if ($query->rowCount() == 0) {
      return false;
    }

    $row = $query->fetch(PDO::FETCH_ASSOC);
    $userTo = $row['user_to'];
    $userFrom = $row['user_from'];

    if ($userTo != $userLoggedIn) {
      return $userTo;
    } else {
      return $userFrom;
    }
  }

  public function sendMessage($userTo, $body, $date)
  {
    if ($body != "") {
      $userLoggedIn = $this->user->getUsername();

      $query = $this->con->prepare("INSERT INTO messages (user_to, user_from, body, date, opened, viewed, deleted) VALUES(:userTo, :userFrom, :body, :date, :opened, :viewed, :deleted)");
      $query->execute([
        ':userTo' => $userTo,
        ':userFrom' => $userLoggedIn,
        ':body' => $body,
        ':date' => $date,
        ':opened' => 'no',
        ':viewed' => 'no',
        ':deleted' => 'no'
      ]);
    }
  }
}
