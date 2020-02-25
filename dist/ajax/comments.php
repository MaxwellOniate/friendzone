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
  $postCommentQuery = $con->prepare("INSERT INTO comments (post_body, posted_by, posted_to, date_added, removed, post_id) VALUES(:postBody, :postedBy, :postedTo, :dateAdded, :removed, :postID)");
  $postCommentQuery->execute([
    ':postBody' => $postBody,
    ':postedBy' => $userLoggedIn,
    ':postedTo' => $postedTo,
    ':dateAdded' => $dateTimeNow,
    ':removed' => 'no',
    ':postID' => $postID
  ]);
}

$getCommentsQuery = $con->prepare("SELECT * FROM comments WHERE post_id = :postID ORDER BY id ASC");
$getCommentsQuery->execute([':postID' => $postID]);

if ($getCommentsQuery->rowCount() != 0) {

  while ($comment = $getCommentsQuery->fetch(PDO::FETCH_ASSOC)) {
    $commentBody = $comment['post_body'];
    $postedTo = $comment['posted_to'];
    $postedBy = $comment['posted_by'];
    $dateAdded = $comment['date_added'];
    $removed = $comment['removed'];

    $dateTimeNow = date('Y-m-d H:i:s');
    $startDate = new DateTime($dateAdded);
    $endDate = new DateTime($dateTimeNow);
    $interval = $startDate->diff($endDate);
    if ($interval->y >= 1) {
      if ($interval == 1) {
        $timeMessage = $interval->y . " year ago.";
      } else {
        $timeMessage = $interval->y . " years ago.";
      }
    } else if ($interval->m >= 1) {
      if ($interval->d == 0) {
        $days = " ago.";
      } else if ($interval->d == 1) {
        $days = $interval->d . " day ago.";
      } else {
        $days = $interval->d . " days ago.";
      }

      if ($interval->m == 1) {
        $timeMessage = $interval->m . " month" . $days;
      } else {
        $timeMessage = $interval->m . " months" . $days;
      }
    } else if ($interval->d >= 1) {
      if ($interval->d == 1) {
        $timeMessage = "Yesterday.";
      } else {
        $timeMessage = $interval->d . " days ago.";
      }
    } else if ($interval->h >= 1) {
      if ($interval->h == 1) {
        $timeMessage = $interval->h . " hour ago.";
      } else {
        $timeMessage = $interval->h . " hours ago.";
      }
    } else if ($interval->i >= 1) {
      if ($interval->i == 1) {
        $timeMessage = $interval->i . " minute ago.";
      } else {
        $timeMessage = $interval->i . " minutes ago.";
      }
    } else {
      if ($interval->s < 30) {
        $timeMessage = "Just now.";
      } else {
        $timeMessage = $interval->s . " seconds ago";
      }
    }

    $userObj = new User($con, $postedBy);
  }
}
