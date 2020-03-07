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
          <?php
          if ($userLoggedIn != $profile->getUsername()) {
            echo "<button class='btn btn-outline-dark' data-toggle='modal' data-target='#post-modal'>Post to Wall</button>";
          }
          ?>
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
                <p>This post will be visible to all your friends and <?php echo $profile->getFirstName(); ?>'s friends on the newsfeed!</p>
                <form id="wall-post-form">
                  <div class="form-group">
                    <textarea placeholder="What's on your mind, <?php echo $user->getFirstName(); ?>?" class="form-control wall-post-body"></textarea>
                  </div>
                  <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                  <input onclick="wallPost(this)" type="submit" name="wall-post-submit" class="btn btn-primary" value="Submit">
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
  let profile = '<?php echo $profile->getUsername(); ?>';

  function friendRequest(friendRequestBtn) {
    $('#friend-request-form').one('submit', function(e) {
      e.preventDefault();
      $.post("ajax/friendRequest.php", {
        submit: $(friendRequestBtn).attr("name"),
        profile: profile,
        userLoggedIn: userLoggedIn
      }).done(function(data) {
        $('#friend-request-form').html(data);
      });
    });
  }

  function wallPost(wallPostBtn) {
    $('#wall-post-form').one('submit', function(e) {
      e.preventDefault();
      $.post("ajax/wallpost.php", {
        submit: $(wallPostBtn).attr("name"),
        postBody: $('.wall-post-body').val(),
        profile: profile,
        userLoggedIn: userLoggedIn
      }).done(function() {
        $('#post-modal').modal('toggle');
        $('.wall-post-body').val("");
      });
    })
  }
</script>

<?php require('includes/footer.php'); ?>