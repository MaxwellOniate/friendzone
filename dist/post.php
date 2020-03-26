<?php
require('includes/header.php');


if (isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  $id = 0;
}

$post = new Post($con, $userLoggedIn);

?>

<section id="post">
  <div class="container">
    <?php $post->loadSinglePost($id); ?>
  </div>
</section>

<?php require('includes/footer.php'); ?>