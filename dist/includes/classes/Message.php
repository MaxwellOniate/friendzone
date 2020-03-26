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

      $query = $this->con->prepare("INSERT INTO messages (user_to, user_from, body, date, opened, deleted) VALUES(:userTo, :userFrom, :body, :date, :opened, :deleted)");
      $query->execute([
        ':userTo' => $userTo,
        ':userFrom' => $userLoggedIn,
        ':body' => $body,
        ':date' => $date,
        ':opened' => 'no',
        ':deleted' => 'no'
      ]);
    }
  }

  public function getMessages($otherUser)
  {
    $userLoggedIn = $this->user->getUsername();
    $data = "";

    $query = $this->con->prepare("UPDATE messages SET opened = :yes WHERE user_to = :un AND user_from = :otherUser");
    $query->execute([':yes' => 'yes', ':un' => $userLoggedIn, ':otherUser' => $otherUser]);

    $getMessagesQuery = $this->con->prepare("SELECT * FROM messages WHERE (user_to = :un AND user_from = :otherUser) OR (user_from = :un AND user_to = :otherUser)");
    $getMessagesQuery->execute([':un' => $userLoggedIn, ':otherUser' => $otherUser]);

    while ($row = $getMessagesQuery->fetch(PDO::FETCH_ASSOC)) {
      $userTo = $row['user_to'];
      $userFrom = $row['user_from'];
      $body = $row['body'];

      if ($userTo == $userLoggedIn) {
        $data .= "
        <li class='list-group-item'>
          <span class='message message-green'>$body</span>
        </li>
        ";
      } else {
        $data .= "
        <li class='list-group-item'>
          <span class='message message-blue'>$body</span>
        </li>
        ";
      }
    }

    return $data;
  }

  public function getConvos()
  {
    $userLoggedIn = $this->user->getUsername();
    $data = "";
    $convos = [];

    $query = $this->con->prepare("SELECT user_to, user_from FROM messages WHERE user_to = :un OR user_from = :un ORDER BY id DESC");
    $query->execute([':un' => $userLoggedIn]);

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $userToPush = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

      if (!in_array($userToPush, $convos)) {
        array_push($convos, $userToPush);
      }
    }

    foreach ($convos as $username) {
      $userFoundObj = new User($this->con, $username);
      $latestMessageDetails = $this->getLatestMessage($userLoggedIn, $username);

      $dots = (strlen($latestMessageDetails[0]) >= 30) ? "..." : "";
      $split = str_split($latestMessageDetails[0], 30);
      $split = $split[0] . $dots;

      $data .= "
      <li class='list-group-item'>
      <div class='convo'>
        <a href='messages.php?u=$username'>
        
          <div class='card-body'>
        
            <span>" . $userFoundObj->getFullName() . "</span>
            <small>" . $latestMessageDetails[1] . "</small>
            <small class='d-block'>" . $split . "</small>
          </div>
          </a>
        </div>
      </li>
      ";
    }

    return $data;
  }

  public function getLatestMessage($user, $user2)
  {
    $detailsArray = [];

    $query = $this->con->prepare("SELECT body, user_to, date FROM messages WHERE (user_to = :un AND user_from = :un2) OR (user_to = :un2 AND user_from = :un) ORDER BY id DESC LIMIT 1");
    $query->execute([':un' => $user, ':un2' => $user2]);

    $row = $query->fetch(PDO::FETCH_ASSOC);

    $dateAdded = $row['date'];

    array_push($detailsArray, $row['body']);
    array_push($detailsArray, Post::getDate($dateAdded));

    return $detailsArray;
  }

  public function getUnreadCount()
  {
    $userLoggedIn = $this->user->getUsername();

    $query = $this->con->prepare("SELECT * FROM messages WHERE opened = :opened AND user_to = :un");
    $query->execute([
      ':opened' => 'no',
      ':un' => $userLoggedIn
    ]);

    return $query->rowCount();
  }
}
