<?php


class User
{
  private $con, $username, $sqlData;

  public function __construct($con, $username)
  {
    $this->con = $con;
    $this->username = $username;

    $query = $con->prepare("SELECT * FROM users WHERE username = :un");
    $query->execute([':un' => $username]);

    $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
  }
  public function getFirstName()
  {
    return $this->sqlData['first_name'];
  }
  public function getLastName()
  {
    return $this->sqlData['last_name'];
  }
  public function getFullName()
  {
    return $this->getFirstName() . " " . $this->getLastName();
  }
  public function getUsername()
  {
    return $this->username;
  }
  public function getEmail()
  {
    return $this->sqlData['email'];
  }
  public function getProfilePic()
  {
    return $this->sqlData['profile_pic'];
  }
  public function getPostCount()
  {
    return $this->sqlData['num_posts'];
  }
  public function getLikeCount()
  {
    return $this->sqlData['num_likes'];
  }
  public function getFriendArray()
  {
    return $this->sqlData['friend_array'];
  }
  public function getFriendCount()
  {
    return (substr_count($this->sqlData['friend_array'], ",")) - 1;
  }
  public function getMutualFriendsCount($username)
  {
    $mutualFriends =  0;
    $friendArray = $this->getFriendArray();;
    $friends = explode(",", substr($friendArray, 1, -1));

    $query = $this->con->prepare("SELECT friend_array FROM users WHERE username = :un");
    $query->execute([':un' => $username]);
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $userFriendArray = $row['friend_array'];
    $userFriends = explode(",", substr($userFriendArray, 1, -1));

    foreach ($friends as $i) {
      foreach ($userFriends as $j) {
        if ($i == $j && $i != "") {
          $mutualFriends++;
        }
      }
    }

    if ($mutualFriends == 1) {
      return $mutualFriends . " Mutal Friend";
    } else if ($mutualFriends == 0) {
      return false;
    } else {
      return $mutualFriends . " Mutual Friends";
    }
  }
  public function isClosed()
  {
    if ($this->sqlData['user_closed'] == 'yes') {
      return true;
    } else {
      return false;
    }
  }
  public function isFriend($username)
  {
    $usernameStr = "," . $username . ",";

    if (strstr($this->sqlData['friend_array'], $usernameStr) || $username == $this->username) {
      return true;
    }
    return false;
  }

  public function friendRequestBtn($userTo)
  {
    $user = new User($this->con, $this->username);
    if ($this->username != $userTo) {
      $query = $this->con->prepare("SELECT friend_array FROM users WHERE username = :un");
      $query->execute([':un' => $this->username]);
      $row = $query->fetch(PDO::FETCH_ASSOC);
      $friendArray = $row['friend_array'];

      if ($user->isFriend($userTo)) {
        echo "
        <button onclick='friendRequest(this)' class='btn btn-outline-danger btn-sm' name='remove'>Remove Friend</button>";
      } else if ($user->receivedRequest($userTo)) {
        echo "
        <button onclick='friendRequest(this)' class='btn btn-success btn-sm' name='accept'>Accept Friend Request</button>";
      } else if ($user->sentRequest($userTo)) {
        echo "
        <button onclick='friendRequest(this)' class='btn btn-secondary btn-sm' name='cancel'>Friend Request Sent</button>";
      } else {
        echo "
        <button onclick='friendRequest(this)' class='btn btn-success btn-sm' name='friend'>Add Friend</button>";
      }
    }
  }

  public function displayFriends($limit)
  {
    if ($this->getFriendCount() > 0) {
      $friendArray = $this->getFriendArray();;

      $friends = explode(",", substr($friendArray, 1, -1));

      $i = 0;
      foreach ($friends as $friend) {
        $friend = new User($this->con, $friend);
        echo "
        <div class='friend'>
          <a href='" .  $friend->getUsername() . "'>
            <img src='" . $friend->getProfilePic() . "' class='img-fluid'>
            <p class='text-center'>" . $friend->getFullName() . "</p>
          </a>
        </div>
        ";
        if (++$i == $limit) break;
      }
    } else {
      echo "No friends to show.";
    }
  }

  private function receivedRequest($userFrom)
  {
    $query = $this->con->prepare("SELECT * FROM friend_requests WHERE user_to = :userTo AND user_from = :userFrom");
    $query->execute([':userTo' => $this->username, ':userFrom' => $userFrom]);

    if ($query->rowCount()) {
      return true;
    }

    return false;
  }
  private function sentRequest($userTo)
  {
    $query = $this->con->prepare("SELECT * FROM friend_requests WHERE user_to = :userTo AND user_from = :userFrom");
    $query->execute([':userTo' => $userTo, ':userFrom' => $this->username]);

    if ($query->rowCount()) {
      return true;
    }

    return false;
  }
}
