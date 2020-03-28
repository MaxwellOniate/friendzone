<?php

require('../includes/config.php');
require('../includes/classes/Account.php');
require('../includes/classes/FormSanitizer.php');
require('../includes/classes/Constants.php');


$account = new Account($con);

$username = $_POST['username'];
$firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
$lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
$email = FormSanitizer::sanitizeFormEmail($_POST["email"]);

if ($account->updateDetails($firstName, $lastName, $email, $username)) {
  echo "
    <div class='alert alert-success' role='alert'>
      Account details updated.
    </div>
    ";
} else {
  $errorMessage = $account->getFirstError();

  echo "
    <div class='alert alert-danger' role='alert'>
      $errorMessage
    </div>
    ";
}
