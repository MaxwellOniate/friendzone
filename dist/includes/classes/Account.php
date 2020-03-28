<?php

class Account
{
  private $con;
  private $errorArray = [];

  public function __construct($con)
  {
    $this->con = $con;
  }

  public function login($em, $pw)
  {
    $pw = hash('sha512', $pw);

    $query = $this->con->prepare("SELECT * FROM users WHERE email = :em AND password = :pw");
    $query->execute([':em' => $em, ':pw' => $pw]);

    if ($query->rowCount() == 1) {
      $userClosedQuery = $this->con->prepare("SELECT * FROM users WHERE email = :em AND user_closed = 'yes'");
      $userClosedQuery->execute([':em' => $em]);

      if ($userClosedQuery->rowCount() == 1) {
        $reopenAccountQuery = $this->con->prepare("UPDATE users SET user_closed ='no' WHERE email = :em");
        $reopenAccountQuery->execute([':em' => $em]);
      }
      return true;
    }

    array_push($this->errorArray, Constants::$loginFailed);
    return false;
  }

  public function register($fn, $ln, $em, $em2, $pw, $pw2)
  {
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateEmails($em, $em2);
    $this->validatePasswords($pw, $pw2);

    if (empty($this->errorArray)) {
      return $this->insertUserDetails($fn, $ln, $em, $pw);
    }

    return false;
  }

  public function getInputValue($name)
  {
    if (isset($_POST[$name])) {
      echo $_POST[$name];
    }
  }

  public function getError($error)
  {
    if (in_array($error, $this->errorArray)) {
      return "<div class='alert alert-danger' role='alert'>$error</div>";
    }
  }

  public function getFirstError()
  {
    if (!empty($this->errorArray)) {
      return $this->errorArray[0];
    }
  }

  public function updateDetails($fn, $ln, $em, $un)
  {
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateNewEmail($em, $un);

    if (empty($this->errorArray)) {
      $query = $this->con->prepare("UPDATE users SET first_name = :fn, last_name = :ln, email = :em WHERE username = :un");

      return $query->execute([
        ":fn" => $fn,
        ":ln" => $ln,
        ":em" => $em,
        ":un" => $un
      ]);
    }

    return false;
  }

  public function updatePassword($old, $new, $new2, $un)
  {
    $this->validateOldPassword($old, $un);
    $this->validatePasswords($new, $new2);

    if (empty($this->errorArray)) {
      $query = $this->con->prepare("UPDATE users SET password = :new WHERE username = :un");

      $new = hash("sha512", $new);

      return $query->execute([':new' => $new, ':un' => $un]);
    }

    return false;
  }

  private function validateFirstName($fn)
  {
    if (strlen($fn) < 2 || strlen($fn) > 25) {
      array_push($this->errorArray, Constants::$firstNameCharacters);
      return;
    }
  }

  private function validateLastName($ln)
  {
    if (strlen($ln) < 2 || strlen($ln) > 25) {
      array_push($this->errorArray, Constants::$lastNameCharacters);
      return;
    }
  }

  private function validateEmails($em, $em2)
  {
    if ($em != $em2) {
      array_push($this->errorArray, Constants::$emailsDontMatch);
      return;
    }

    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
      array_push($this->errorArray, Constants::$emailInvalid);
      return;
    }

    $query = $this->con->prepare("SELECT * FROM users WHERE email = :em");
    $query->execute([':em' => $em]);

    if ($query->rowCount() != 0) {
      array_push($this->errorArray, Constants::$emailTaken);
    }
  }

  private function validatePasswords($pw, $pw2)
  {
    if ($pw != $pw2) {
      array_push($this->errorArray, Constants::$passwordsDontMatch);
      return;
    }

    if (strlen($pw) < 8 || strlen($pw) > 99) {
      array_push($this->errorArray, Constants::$passwordLength);
    }
  }

  private function insertUserDetails($fn, $ln, $em, $pw)
  {
    $pw = hash('sha512', $pw);
    $pp = "assets/img/profile-pics/_temp.jpeg";
    $un = $this->createUsername($fn, $ln);


    $query = $this->con->prepare("INSERT INTO users (first_name, last_name, username, email, password, profile_pic, user_closed, friend_array) VALUES (:fn, :ln, :un, :em, :pw, :pp, :uc, :fa) ");

    return $query->execute([
      ':fn' => $fn,
      ':ln' => $ln,
      ':un' => $un,
      ':em' => $em,
      ':pw' => $pw,
      ':pp' => $pp,
      ':uc' => 'no',
      ':fa' => ','
    ]);
  }

  private function createUsername($fn, $ln)
  {
    $un = strtolower($fn . "-" . $ln);

    $usernameQuery = $this->con->prepare("SELECT username FROM users WHERE username = :un");
    $usernameQuery->execute([':un' => $un]);

    $i = 0;
    $tempUsername = $un;

    while ($usernameQuery->rowCount() != 0) {
      $tempUsername = $un;
      $i++;
      $tempUsername = $un . "-" . $i;
      $usernameQuery = $this->con->prepare("SELECT username FROM users WHERE username = :un");
      $usernameQuery->execute([':un' => $tempUsername]);
    }

    return $un = $tempUsername;
  }

  private function validateNewEmail($em, $un)
  {
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
      array_push($this->errorArray, Constants::$emailInvalid);
      return;
    }

    $query = $this->con->prepare("SELECT * FROM users WHERE email = :em AND username != :un");
    $query->execute([':em' => $em, ':un' => $un]);

    if ($query->rowCount() != 0) {
      array_push($this->errorArray, Constants::$emailTaken);
    }
  }

  private function validateOldPassword($old, $un)
  {
    $pw = hash("sha512", $old);

    $query = $this->con->prepare("SELECT * FROM users WHERE username = :un AND password = :pw");
    $query->execute([':un' => $un, ':pw' => $pw]);

    if ($query->rowCount() == 0) {
      array_push($this->errorArray, Constants::$passwordIncorrect);
    }
  }
}
