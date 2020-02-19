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

      <div class="col-lg-3">
        <aside id="feed-details">
          <div class="card user-details mb-3">
            <div class="card-body">

              <div class="media">
                <a href="<?php echo $user->getUsername(); ?>" class="pr-3">
                  <img src="<?php echo $user->getProfilePic(); ?>" alt="<?php echo $user->getFullName(); ?>" class="img-fluid user-details-img">
                </a>

                <div class="media-body">
                  <a href="<?php echo $user->getUsername(); ?>" class="user-full-name"><?php echo $user->getFullName(); ?></a>
                  <p class="user-data">Posts: <?php echo $user->getPostCount(); ?></p>
                  <p class="user-data">Likes: <?php echo $user->getLikeCount(); ?></p>
                </div>
              </div>


            </div>

          </div>
        </aside>
      </div>

      <div class="col-lg-9">
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

          <?php
          $post = new Post($con, $userLoggedIn);
          $post->loadPosts();
          ?>
        </section>
      </div>

    </div>
  </div>

</section>


<?php require('includes/footer.php'); ?>