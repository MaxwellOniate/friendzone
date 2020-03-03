<?php
require('includes/header.php');

if (isset($_GET['profile_username'])) {
  $username = $_GET['profile_username'];
  $profile = new User($con, $username);
}
?>

<section id="profile">
  <div class="container">
    <header id="profile-header">
      <img src="<?php echo $profile->getProfilePic(); ?>" alt="<?php echo $profile->getFullName(); ?>" class="img-fluid profilePic">

      <div class="profile-info">
        <h1 class="display-1"><?php echo $profile->getFullName(); ?></h1>
        <div class="profile-stats lead">
          <p>Posts: <?php echo $profile->getPostCount(); ?></p>
          <p>Likes: <?php echo $profile->getLikeCount(); ?></p>
          <p>Friends: <?php echo $profile->getFriendCount(); ?></p>
        </div>
      </div>
    </header>

  </div>
</section>




<?php require('includes/footer.php'); ?>