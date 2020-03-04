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

        <form id='profile-form'>
          <?php

          if ($username != $userLoggedIn) {
            if ($profile->isFriend($userLoggedIn)) {
              echo "<input type='hidden' value='" . $profile->getUsername() . "'>
            <button onclick='removeFriend(this)' class='btn btn-outline-danger' name='remove-friend'>Remove Friend</button>";
            } else {
              echo "<input type='hidden' value='" . $profile->getUsername() . "'>
              <button onclick='addFriend(this)' class='btn btn-outline-success' name='add-friend'>Add Friend</button>";
            }
          }
          ?>
        </form>

      </div>
    </header>

  </div>
</section>

<script>
  let userLoggedIn = '<?php echo $userLoggedIn; ?>';

  function addFriend(addFriendBtn) {
    $('#profile-form').one('submit', function(e) {
      e.preventDefault();
      $.post("ajax/addFriend.php", {
        submit: $(addFriendBtn).attr("name"),
        addFriend: $(addFriendBtn).prev().val(),
        userLoggedIn: userLoggedIn
      }).done(function() {

      });
    });
  }

  function removeFriend(removeFriendBtn) {
    $('#profile-form').one('submit', function(e) {
      e.preventDefault();
      $.post("ajax/addFriend.php", {
        submit: $(removeFriendBtn).attr("name"),
        removeFriend: $(removeFriendBtn).prev().val(),
        userLoggedIn: userLoggedIn
      }).done(function() {

      });
    });
  }
</script>

<?php require('includes/footer.php'); ?>