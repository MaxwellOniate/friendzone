<?php

require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');

if (isset($_POST['postID']) && isset($_POST['userLoggedIn'])) {
  $postID = $_POST['postID'];
  $userLoggedIn = $_POST['userLoggedIn'];
}

$getPostLikes = $con->prepare("SELECT likes, added_by FROM posts WHERE id = :postID");
$getPostLikes->execute([':postID' => $postID]);
$postLikesRow = $getPostLikes->fetch(PDO::FETCH_ASSOC);
$totalLikes = $postLikesRow['likes'];


$postCreator = $postLikesRow['added_by'];
$userDetails = $con->prepare("SELECT * FROM users WHERE username = :postCreator");
$userDetails->execute([':postCreator' => $postCreator]);
$userDetailsRow = $userDetails->fetch(PDO::FETCH_ASSOC);
$totalUserLikes = $userDetailsRow['num_likes'];


if (isset($_POST['likeStatusID'])) {
  $likeQuery = $con->prepare("SELECT * FROM likes WHERE username = :un AND post_id = :postID");
  $likeQuery->execute([
    ':un' => $userLoggedIn,
    ':postID' => $postID
  ]);

  if ($likeQuery->rowCount() == 0) {
    $totalLikes++;
    $postLikesQuery = $con->prepare("UPDATE posts SET likes = :totalLikes WHERE id = :postID");
    $postLikesQuery->execute([
      ':totalLikes' => $totalLikes,
      ':postID' => $postID
    ]);

    $addLikeQuery = $con->prepare("INSERT INTO likes (username, post_id) VALUES(:un, :postID)");
    $addLikeQuery->execute([
      ':un' => $userLoggedIn,
      ':postID' => $postID
    ]);

    $totalUserLikes++;
    $userLikesQuery = $con->prepare("UPDATE users SET num_likes = :totalUserLikes WHERE username = :postCreator");
    $userLikesQuery->execute([
      ':totalUserLikes' => $totalUserLikes,
      ':postCreator' => $postCreator
    ]);

    echo "
      <span class='liked'>
        <i class='fas fa-thumbs-up'></i> Liked
      </span>
    ";
  } else {
    $totalLikes--;
    $postLikesQuery = $con->prepare("UPDATE posts SET likes = :totalLikes WHERE id = :postID");
    $postLikesQuery->execute([
      ':totalLikes' => $totalLikes,
      ':postID' => $postID
    ]);

    $deleteLikeQuery = $con->prepare("DELETE FROM likes WHERE username = :un AND post_id = :postID");
    $deleteLikeQuery->execute([
      ':un' => $userLoggedIn,
      ':postID' => $postID
    ]);

    $totalUserLikes--;
    $userLikesQuery = $con->prepare("UPDATE users SET num_likes = :totalUserLikes WHERE username = :postCreator");
    $userLikesQuery->execute([
      ':totalUserLikes' => $totalUserLikes,
      ':postCreator' => $postCreator
    ]);

    echo "
      <span class='like'>
        <i class='far fa-thumbs-up'></i> Like
      </span>
    ";
  }
}
