<?php
require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');

if (isset($_POST['postID'])) {
  $postID = $_POST['postID'];
}

$query = $con->prepare("SELECT added_by, user_to FROM posts WHERE id = :id");
$query->execute([':id' => $postID]);
$row = $query->fetch(PDO::FETCH_ASSOC);

$postedTo = $row['added_by'];

if (isset($_POST['postCommentID'])) {
  $postBody = $_POST['postBody'];
  $dateTimeNow = date('Y-m-d H:i:s');
  $userLoggedIn = $_POST['userLoggedIn'];
  $query = $con->prepare("INSERT INTO comments (post_body, posted_by, posted_to, date_added, removed, post_id) VALUES(:postBody, :postedBy, :postedTo, :dateAdded, :removed, :postID)");
  $query->execute([
    ':postBody' => $postBody,
    ':postedBy' => $userLoggedIn,
    ':postedTo' => $postedTo,
    ':dateAdded' => $dateTimeNow,
    ':removed' => 'no',
    ':postID' => $postID
  ]);
}
