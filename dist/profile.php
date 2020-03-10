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

    <div class="row">

      <div class="col-lg-4">

        <aside id="photos">
          <div class="card my-4">
            <div class="card-header">
              <i class="far fa-image photos-icon"></i>
              <a href="photos.php?=<?php echo $profile->getUsername(); ?>">
                Photos
              </a>
            </div>
            <div class="card-body">
            </div>
          </div>
        </aside>

        <aside id="friends-list">
          <div class="card my-4">
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

      </div>

      <div class="col-lg-8">
        <section id="profile-feed">
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