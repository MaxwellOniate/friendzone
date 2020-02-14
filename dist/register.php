<?php

require('includes/config.php');
require('includes/classes/Account.php');
require('includes/classes/FormSanitizer.php');
require('includes/classes/Constants.php');



if (isset($_POST['regButton'])) {
  $firstName = FormSanitizer::sanitizeFormString($_POST['regFirstName']);
  $lastName = FormSanitizer::sanitizeFormString($_POST['regLastName']);
  $email = FormSanitizer::sanitizeFormString($_POST['regEmail']);
  $email2 = FormSanitizer::sanitizeFormString($_POST['regEmail2']);
  $password = FormSanitizer::sanitizeFormString($_POST['regPassword']);
  $password2 = FormSanitizer::sanitizeFormString($_POST['regPassword2']);
}


?>


<?php require('includes/header.php'); ?>

<section class="authentication">
  <div class="container">
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
      <div class="form-group">
        <input type="text" name="regFirstName" class="form-control" placeholder="First Name" required>
      </div>
      <div class="form-group">
        <input type="text" name="regLastName" class="form-control" placeholder="Last Name" required>
      </div>
      <div class="form-group">
        <input type="email" name="regEmail" class="form-control" placeholder="Email" required>
      </div>
      <div class="form-group">
        <input type="email" name="regEmail2" class="form-control" placeholder="Confirm Email" required>
      </div>
      <div class="form-group">
        <input type="password" name="regPassowrd" class="form-control" placeholder="Password" required>
      </div>
      <div class="form-group">
        <input type="password" name="regPassword2" class="form-control" placeholder="Confirm Password" required>
      </div>
      <div class="form-group">
        <input type="submit" name="regButton" class="btn btn-primary" value="Register">
      </div>
    </form>
  </div>
</section>


<?php require('includes/footer.php'); ?>