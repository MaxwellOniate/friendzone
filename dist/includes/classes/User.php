<?php


class User
{
  private $con, $email, $sqlData;

  public function __construct($con, $email)
  {
    $this->con = $con;
    $this->email = $email;

    $query = $con->prepare("SELECT * FROM users WHERE email = :em");
    $query->execute([':em' => $email]);

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
  public function getUsername()
  {
    return $this->sqlData['username'];
  }
  public function getEmail()
  {
    return $this->email;
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
}
