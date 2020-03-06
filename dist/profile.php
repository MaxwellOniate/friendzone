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

        <div class="profile-btns">
          <form id='friend-request-form'>
            <?php echo $user->friendRequestBtn($profile->getUsername()); ?>
          </form>
          <button class="btn btn-primary" data-toggle="modal" data-target="#post-modal">Post on Wall</button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="post-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="modal-title">Post to <?php echo $profile->getFirstName(); ?>'s wall.</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Post will be visible to all your friends and <?php echo $profile->getFirstName(); ?>'s friends on the newsfeed!</p>
                <form>
                  <div class="form-group">
                    <textarea name="post-textarea" placeholder="What's on your mind, <?php echo $user->getFirstName(); ?>?" class="form-control"></textarea>
                  </div>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <input type="submit" class="btn btn-primary" value="Submit">
                </form>

              </div>


            </div>
          </div>
        </div>


      </div>
    </header>

  </div>
</section>

<script>
  let userLoggedIn = '<?php echo $userLoggedIn; ?>';

  function friendRequest(friendRequestBtn) {
    $('#friend-request-form').one('submit', function(e) {
      e.preventDefault();
      $.post("ajax/friendRequest.php", {
        submit: $(friendRequestBtn).attr("name"),
        profile: '<?php echo $profile->getUsername(); ?>',
        userLoggedIn: userLoggedIn
      }).done(function(data) {
        $('#friend-request-form').html(data);
      });
    });
  }
</script>

<?php require('includes/footer.php'); ?>