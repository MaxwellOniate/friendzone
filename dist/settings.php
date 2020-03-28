<?php

require("includes/header.php");

if (isset($_POST['close-account'])) {
  $query = $con->prepare("UPDATE users SET user_closed = :userClosed WHERE username = :un ");
  $query->execute([':userClosed' => 'yes', ':un' => $userLoggedIn]);
  session_destroy();
  header("Location: register.php");
}

?>

<section id="settings">
  <div class="container">
    <div class="card mb-4">
      <div class="card-header">
        <h2 class="card-title">Profile Picture</h1>
      </div>
      <div class="card-body">


        <img src="<?php echo $user->getProfilePic(); ?>" alt="<?php echo $user->getFullName(); ?>" class="img-fluid pfp-100 mb-3 mr-3 d-block">


        <a href="upload.php" class="btn btn-primary mb-3">Upload Profile Picture</a>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">
        <h2 class="card-title">User Details</h2>
      </div>
      <div class="card-body">

        <span class="user-details-message"></span>

        <div class="form">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="firstName" class="firstName form-control" value="<?php echo $user->getFirstName(); ?>">
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="lastName" class="lastName form-control" value="<?php echo $user->getLastName(); ?>">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="email form-control" value="<?php echo $user->getEmail(); ?>">
          </div>
          <button onclick="updateUserDetails('.firstName', '.lastName', '.email')">Submit</button>
        </div>

      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header">
        <h2 class="card-title">Change Password</h2>
      </div>
      <div class="card-body">
        <span class="password-message"></span>
        <div class="form">
          <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="oldPassword" class="oldPassword form-control">
          </div>
          <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" class="password form-control">
          </div>
          <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="password2" class="password2 form-control">
          </div>
          <button onclick="updatePassword('.oldPassword', '.password', '.password2')">Submit</button>
        </div>
      </div>
    </div>

    <button data-toggle="modal" data-target="#close-account" class="btn btn-danger mb-4">Close Account</button>

    <div class="modal fade" id="close-account" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Are you sure?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>Your account page will be hidden from other users, but they can still see any posts and messages you've sent to them.</p>
            <p>You can always reopen your account by signing back in.</p>
          </div>
          <div class="modal-footer">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
              <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No, Cancel</button>
              <button type="submit" name="close-account" class="btn btn-danger">Yes, Close Account</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<script>
  let userLoggedIn = '<?php echo $userLoggedIn; ?>';
</script>


<?php require("includes/footer.php"); ?>