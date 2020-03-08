<?php require('includes/header.php'); ?>

<?php

if (isset($_POST['post'])) {
  $post = new Post($con, $userLoggedIn);
  $post->submitPost($_POST['post-text'], 'none');
  header("Location: index.php");
}

?>

<section id="main-feed">
  <div class="container">
    <div class="row">

      <div class="col-lg-4">
        <aside id="feed-details">
          <div class="card user-details mb-4">
            <div class="card-body">

              <div class="media">
                <a href="<?php echo $user->getUsername(); ?>" class="pr-3">
                  <img src="<?php echo $user->getProfilePic(); ?>" alt="<?php echo $user->getFullName(); ?>" class="img-fluid user-details-img">
                </a>

                <div class="media-body">
                  <a href="<?php echo $user->getUsername(); ?>" class="user-full-name"><?php echo $user->getFullName(); ?></a>
                  <p class="user-data">Posts: <?php echo $user->getPostCount(); ?></p>
                  <p class="user-data">Likes: <?php echo $user->getLikeCount(); ?></p>
                  <p class="user-data">Friends: <?php echo $user->getFriendCount(); ?></p>
                </div>
              </div>


            </div>

          </div>

          <div class="card trends">
            <div class="card-body">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate doloribus ex aliquam quo consequatur earum reiciendis rerum soluta dolorum officia quibusdam, magni molestiae at quis voluptatibus expedita? Facilis pariatur cumque blanditiis, enim sit eos natus distinctio illo! Omnis voluptas, maiores ipsam labore laudantium recusandae quis obcaecati! Ipsam, neque tempore facere sit totam nesciunt culpa minima deleniti corporis similique quis. Vero velit doloribus necessitatibus non ratione autem ea deleniti inventore soluta! Dolorum facere corporis illum dolore, fuga, accusantium praesentium eum veritatis molestias, voluptate repudiandae dicta incidunt.
            </div>
          </div>

        </aside>
      </div>

      <div class="col-lg-8">
        <section id="news-feed">

          <div class="card create-post">
            <div class="card-header">Create Post</div>
            <div class="card-body">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="post-form">
                <div class="form-group">
                  <textarea name="post-text" placeholder="What's on your mind, <?php echo $user->getFirstName(); ?>?" class="form-control"></textarea>
                </div>
                <div class="form-group">
                  <input type="submit" name="post" class="btn main-btn btn-block" value="Post">
                </div>
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

  // Load Index Posts
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
        url: 'ajax/loadPosts.php',
        type: 'POST',
        data: 'page=' + page + '&userLoggedIn=' + userLoggedIn,
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