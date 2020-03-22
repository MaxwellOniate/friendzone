<?php
require('includes/header.php');

if (isset($_GET['profile'])) {
  $username = $_GET['profile'];
  $profile = new User($con, $username);
}

?>

<section id="friends">
  <div class="container">

    <header class="profile-banner mb-4 card">
      <div class="card-body">
        <img src="<?php echo $profile->getProfilePic(); ?>" alt="<?php echo $profile->getFullName(); ?>" class="img-fluid profilePic">

        <div class="profile-name">
          <h1 class="display-1"><?php echo $profile->getFullName(); ?></h1>

          <form id='friend-request-form'>
            <?php echo $user->friendRequestBtn($profile->getUsername()); ?>
          </form>
        </div>
      </div>

    </header>

    <div class="card friends-list mb-4">
      <div class="card-header">
        <h1 class="card-title">
          <i class="fas fa-user-friends text-secondary"></i>Friends
        </h1>
      </div>
      <div class="card-body">
        <div class="friends">
          <?php $profile->displayFriends(""); ?>
        </div>
      </div>
    </div>

  </div>
</section>

<?php require('includes/footer.php'); ?>