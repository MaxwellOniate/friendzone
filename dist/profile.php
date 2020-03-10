<?php
require('includes/header.php');

if (isset($_GET['profile_username'])) {
  $username = $_GET['profile_username'];
  $profile = new User($con, $username);
}
?>

<section id="profile">
  <div class="container">
    <header id="profile-header" class="mb-4">
      <img src="<?php echo $profile->getProfilePic(); ?>" alt="<?php echo $profile->getFullName(); ?>" class="img-fluid profilePic">

      <div class="profile-name">
        <h1 class="display-1"><?php echo $profile->getFullName(); ?></h1>

        <form id='friend-request-form'>
          <?php echo $user->friendRequestBtn($profile->getUsername()); ?>
        </form>
      </div>

    </header>

    <div class="row">

      <div class="col-lg-4">

        <aside id="profile-info">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-globe-americas profile-icon"></i> Profile Info
            </div>
            <div class="card-body">

              <p>Posts: <?php echo $profile->getPostCount(); ?></p>
              <hr>
              <p>Likes: <?php echo $profile->getLikeCount(); ?></p>
              <hr>
              <p>Friends: <?php echo $profile->getFriendCount(); ?></p>

            </div>
          </div>
        </aside>

        <aside id="friends-list">
          <div class="card mb-4">
            <div class="card-header">
              <i class="fas fa-user-friends friends-icon"></i>
              <a href="friends.php?=<?php echo $profile->getUsername(); ?>">
                Friends - <?php echo $profile->getFriendCount(); ?>
              </a>
            </div>
            <div class="card-body">
              <div class="friends">
                <?php echo $profile->displayFriends(); ?>
              </div>
            </div>
          </div>
        </aside>

        <aside id="photos">
          <div class="card mb-4">
            <div class="card-header">
              <i class="far fa-image photos-icon"></i>
              <a href="photos.php?=<?php echo $profile->getUsername(); ?>">
                Photos
              </a>
            </div>
            <div class="card-body">
              <p>No photos to show.</p>
            </div>
          </div>
        </aside>

      </div>

      <div class="col-lg-8">
        <section id="profile-feed">

          <div class="card create-post">
            <div class="card-header">Create Post</div>
            <div class="card-body">

              <form class="post-form">
                <div class="form-group">
                  <textarea placeholder="What's on your mind, <?php echo $user->getFirstName(); ?>?" class="post-body form-control"></textarea>
                </div>
                <input onclick="submitPost(this)" type="submit" name="post" class="btn main-btn btn-block" value="Post">
              </form>

            </div>
          </div>

          <div class="posts">
          </div>

          <div id="loading">
            <img src="assets/img/loading.gif" alt="Loading">
          </div>
        </section>
      </div>

    </div>

  </div>
</section>

<script>
  let userLoggedIn = '<?php echo $userLoggedIn; ?>';
  let profile = '<?php echo $profile->getUsername(); ?>';

  // Load Profile Posts
  $(function() {
    let inProgress = false;

    loadPosts(); //Load first posts

    $(window).scroll(function() {
      let bottomElement = $('.post').last();
      let noMorePosts = $('.posts')
        .find('.no-posts')
        .val();

      // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
      if (isElementInView(bottomElement[0]) && noMorePosts == 'false') {
        loadPosts();
      }
    });

    function loadPosts() {
      if (inProgress) {
        //If it is already in the process of loading some posts, just return
        return;
      }

      inProgress = true;
      $('#loading').show();

      let page =
        $('.posts')
        .find('.next-page')
        .val() || 1; //If .next-page couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'

      $.ajax({
        url: 'ajax/loadProfilePosts.php',
        type: 'POST',
        data: 'page=' +
          page +
          '&userLoggedIn=' +
          userLoggedIn +
          '&profile=' +
          profile,
        cache: false,

        success: function(response) {
          $('.posts')
            .find('.next-page')
            .remove(); //Removes current .next-page
          $('.posts')
            .find('.no-posts')
            .remove(); //Removes current .next-page
          $('.posts')
            .find('.no-posts-text')
            .remove(); //Removes current .next-page

          $('#loading').hide();
          $('.posts').append(response);

          inProgress = false;
        }
      });
    }

    //Check if the element is in view
    function isElementInView(el) {
      let rect = el.getBoundingClientRect();

      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <=
        (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
      );
    }
  });
</script>

<?php require('includes/footer.php'); ?>