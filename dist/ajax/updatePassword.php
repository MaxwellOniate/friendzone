<?php

require('../includes/config.php');
require('../includes/classes/Account.php');
require('../includes/classes/FormSanitizer.php');
require('../includes/classes/Constants.php');

$account = new Account($con);

$username = $_POST['username'];
$oldPassword = FormSanitizer::sanitizeFormPassword($_POST['oldPassword']);
$password = FormSanitizer::sanitizeFormPassword($_POST['password']);
$password2 = FormSanitizer::sanitizeFormPassword($_POST['password2']);

if ($account->updatePassword($oldPassword, $password, $password2, $username)) {
  echo "
    <div class='alert alert-success' role='alert'>
      Password updated.
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
