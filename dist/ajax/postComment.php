<?php
require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');
require('../includes/classes/Notification.php');

if (isset($_POST['postID'])) {
  $postID = $_POST['postID'];
  $post = new Post($con, $postID);
}

$query = $con->prepare("SELECT added_by, user_to FROM posts WHERE id = :id");
$query->execute([':id' => $postID]);
$row = $query->fetch(PDO::FETCH_ASSOC);

$postedTo = $row['added_by'];
$userTo = $row['user_to'];

if (isset($_POST['postCommentID'])) {
  $postBody = trim($_POST['postBody']);

  if ($postBody != "") {
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
    $userObj = new User($con, $userLoggedIn);

    echo "
    <div class='comment pb-3'>
      <div class='media'>
        <img src='" . $userObj->getProfilePic() . "' class='img-fluid comment-profile-pic' alt='" . $userObj->getFullName() . "'>
        <div class='media-body'>  
          <div class='comment-body'>
            <a href='" . $userObj->getUsername() . "'>" . $userObj->getFullName() . "</a>
            $postBody
          </div>
          <small class='d-block pl-2'>" . $post->getDate($dateTimeNow) . "</small>
        </div>
      </div>
    </div>
    ";

    if ($postedTo != $userLoggedIn) {
      $notification = new Notification($con, $userLoggedIn);
      $notification->insertNotification($postID, $postedTo, 'comment');
    }

    if ($userTo != "none" && $userTo != $userLoggedIn) {
      $notification = new Notification($con, $userLoggedIn);
      $notification->insertNotification($postID, $userTo, 'profile-comment');
    }

    $getCommentersQuery = $con->prepare("SELECT * FROM comments WHERE post_id = :postID");
    $getCommentersQuery->execute([':postID' => $postID]);

    $notifyUsers = [];

    while ($row = $getCommentersQuery->fetch(PDO::FETCH_ASSOC)) {
      if ($row['posted_by'] != $postedTo && $row['posted_by'] != $userTo && $row['posted_by'] != $userLoggedIn && !in_array($row['posted_by'], $notifyUsers)) {
        $notification = new Notification($con, $userLoggedIn);
        $notification->insertNotification($postID, $row['posted_by'], 'comment-nonOwner');

        array_push($notifyUsers, $row['posted_by']);
      }
    }
  }
}
