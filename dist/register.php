<?php

require('includes/config.php');
require('includes/classes/Account.php');
require('includes/classes/FormSanitizer.php');
require('includes/classes/Constants.php');

$account = new Account($con);

if (isset($_POST['logButton'])) {
  $email = FormSanitizer::sanitizeFormEmail($_POST['logEmail']);
  $password = FormSanitizer::sanitizeFormPassword($_POST['logPassword']);

  $success = $account->login($email, $password);

  if ($success) {
    $_SESSION['userLoggedIn'] = $email;
    header('Location: index.php');
  }
}

if (isset($_POST['regButton'])) {
  $firstName = FormSanitizer::sanitizeFormString($_POST['regFirstName']);
  $lastName = FormSanitizer::sanitizeFormString($_POST['regLastName']);
  $email = FormSanitizer::sanitizeFormEmail($_POST['regEmail']);
  $email2 = FormSanitizer::sanitizeFormEmail($_POST['regEmail2']);
  $password = FormSanitizer::sanitizeFormPassword($_POST['regPassword']);
  $password2 = FormSanitizer::sanitizeFormPassword($_POST['regPassword2']);

  $success = $account->register($firstName, $lastName, $email, $email2, $password, $password2);

  if ($success) {

    $_SESSION["userLoggedIn"] = $email;

    header('Location: index.php');
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Friendzone - Log In or Sign Up</title>
  <script src="https://kit.fontawesome.com/52d1564875.js" crossorigin="anonymous"></script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>

  <section id="login">
    <nav class="navbar navbar-light">
      <div class="container">

        <a class="navbar-brand">friendzone</a>

        <?php echo $account->getError(Constants::$loginFailed); ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form-inline">


          <input type="text" name="logEmail" class="form-control" placeholder="Email" value="<?php echo $account->getInputValue('logEmail'); ?>" required>

          <input type="password" name="logPassword" class="form-control" placeholder="Password" required>

          <input type="submit" name="logButton" class="btn btn-sm" value="Log In">

        </form>
      </div>
    </nav>
  </section>

  <section id="register" class="m-5">
    <div class="container">
      <div class="row">

        <div class="col-md-6">
          <h2>Connect with friends and the world around you on Friendzone.</h2>
          <ul>
            <li><i class="fas fa-photo-video"></i> See photos and updates from friends in News Feed.</li>
            <li><i class="fas fa-hourglass-half"></i> Share what's new in your life on your Timeline.</li>
            <li><i class="fas fa-search"></i> Find more of what you're looking for with Friendzone Search.</li>
          </ul>
        </div>

        <div class="col-md-6">

          <h1>Create an Account</h1>
          <p class="lead">It's quick and easy.</p>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

            <?php echo $account->getError(Constants::$firstNameCharacters); ?>
            <div class="form-group">
              <input type="text" name="regFirstName" class="form-control" placeholder="First Name" value="<?php $account->getInputValue('regFirstName'); ?>" required>
            </div>

            <?php echo $account->getError(Constants::$lastNameCharacters); ?>
            <div class="form-group">
              <input type="text" name="regLastName" class="form-control" placeholder="Last Name" value="<?php $account->getInputValue('regLastName'); ?>" required>
            </div>

            <?php echo $account->getError(Constants::$emailsDontMatch); ?>
            <?php echo $account->getError(Constants::$emailInvalid); ?>
            <?php echo $account->getError(Constants::$emailTaken); ?>
            <div class="form-group">
              <input type="email" name="regEmail" class="form-control" placeholder="Email" value="<?php $account->getInputValue('regEmail'); ?>" required>
            </div>

            <div class="form-group">
              <input type="email" name="regEmail2" class="form-control" placeholder="Confirm Email" value="<?php $account->getInputValue('regEmail2'); ?>" required>
            </div>

            <?php echo $account->getError(Constants::$passwordsDontMatch) ?>
            <?php echo $account->getError(Constants::$passwordLength) ?>
            <div class="form-group">
              <input type="password" name="regPassword" class="form-control" placeholder="Password" required>
            </div>

            <div class="form-group">
              <input type="password" name="regPassword2" class="form-control" placeholder="Confirm Password" required>
            </div>

            <div class="form-group">
              <input type="submit" name="regButton" class="btn" value="Register">
            </div>

          </form>

        </div>
      </div>
    </div>
  </section>


  <?php require('includes/footer.php'); ?>