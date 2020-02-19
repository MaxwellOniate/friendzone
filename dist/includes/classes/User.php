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
    return $this->sqlData['first_name'] . " " . $this->sqlData['last_name'];
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
  public function isClosed()
  {
    if ($this->sqlData['user_closed'] == 'yes') {
      return true;
    } else {
      return false;
    }
  }
}
