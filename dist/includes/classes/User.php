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
    return $this->sqlData['firstName'];
  }
  public function getLastName()
  {
    return $this->sqlData['lastName'];
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
    return $this->sqlData['profilePic'];
  }
  public function getPostCount()
  {
    return $this->sqlData['numPosts'];
  }
  public function getLikeCount()
  {
    return $this->sqlData['numLikes'];
  }
}
