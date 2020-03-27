<?php

class Notification
{
  private $con, $user;

  public function __construct($con, $username)
  {
    $this->con = $con;
    $this->user = new User($con, $username);
  }
  public function getUnreadCount()
  {
    $userLoggedIn = $this->user->getUsername();

    $query = $this->con->prepare("SELECT * FROM notifications WHERE opened = :yesOrNo AND user_to = :un");
    $query->execute([
      ':yesOrNo' => 'no',
      ':un' => $userLoggedIn
    ]);

    return $query->rowCount();
  }
  public function insertNotification($postID, $userTo, $type)
  {
    $userLoggedIn = $this->user->getUsername();
    $userFullName = $this->user->getFullName();
    $date = date("Y-m-d H:i:s");

    switch ($type) {
      case 'comment':
        $message = $userFullName . " commented on your post.";
        break;
      case 'like':
        $message = $userFullName . " liked your post.";
        break;
      case 'profile-post':
        $message = $userFullName . " posted on your profile.";
        break;
      case 'comment-nonOwner':
        $message = $userFullName . " commented on a post you commented on.";
        break;
      case 'profile-comment':
        $message = $userFullName . " commented on your profile post.";
        break;
    }

    $link = "post.php?id=" . $postID;

    $query = $this->con->prepare("INSERT INTO notifications (user_to, user_from, message, link, date, opened) VALUES(:userTo, :userFrom, :message, :link, :date, :opened)");
    $query->execute([
      ':userTo' => $userTo,
      ':userFrom' => $userLoggedIn,
      ':message' => $message,
      ':link' => $link,
      ':date' => $date,
      ':opened' => 'no'
    ]);
  }
  public function loadNotifications()
  {
    $userLoggedIn = $this->user->getUsername();

    $query = $this->con->prepare("SELECT * FROM notifications WHERE user_to = :un AND opened = :opened ORDER BY id DESC");
    $query->execute([':un' => $userLoggedIn, ':opened' => 'no']);

    if ($query->rowCount() == 0) {
      return "<li class='list-group-item'>You have no notifications.</li>";
    }

    $data = "";


    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $userFrom = new User($this->con, $row['user_from']);

      $data .= "
      <li class='list-group-item'>
        <div class='media'>
          <img src=" . $userFrom->getProfilePic() . " class='img-fluid pfp-50 mr-3'>
          <div class='media-body'>
          <a href='" . $row['link'] . "'>
            " . $row['message'] . "
          </a>
          <span class='d-block small'>
            " . Post::getDate($row['date']) . "
          </span> 
          </div>
        </div>
      </li>
      ";
    }

    return $data;
  }
}
