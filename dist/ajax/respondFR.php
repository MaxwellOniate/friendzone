<?php

require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');

$requestID = $_POST['requestID'];
$submit = $_POST['submit'];
$profile = $_POST['profile'];
$userLoggedIn = $_POST['userLoggedIn'];

$profileObj = new User($con, $profile);

if (isset($submit)) {
  if ($submit == 'accept-' . $requestID) {
    // Add person to your friend array
    $query = $con->prepare("SELECT friend_array FROM users WHERE username = :un");
    $query->execute([':un' => $userLoggedIn]);
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $friendArray = $row['friend_array'];

    $newFriendArray = $friendArray . $profile . ",";

    $addFriendQuery = $con->prepare("UPDATE users SET friend_array = :friendArray WHERE username = :un");
    $addFriendQuery->execute([':friendArray' => $newFriendArray, ':un' => $userLoggedIn]);

    // Add yourself to other person's friend array
    $query = $con->prepare("SELECT friend_array FROM users WHERE username = :un");
    $query->execute([':un' => $profile]);
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $friendArray = $row['friend_array'];

    $newFriendArray = $friendArray . $userLoggedIn . ",";

    $addFriendQuery = $con->prepare("UPDATE users SET friend_array = :friendArray WHERE username = :un");
    $addFriendQuery->execute([':friendArray' => $newFriendArray, ':un' => $profile]);

    // Remove friend request from table
    $query = $con->prepare("DELETE FROM friend_requests WHERE user_to = :userTo AND user_from = :userFrom");
    $query->execute([':userTo' => $userLoggedIn, ':userFrom' => $profile]);


    echo "<div class='alert alert-success'>You have accepted " . $profileObj->getFullName() . "'s friend request!</div>";
  } else if ($submit == 'decline-' . $requestID) {

    // Remove friend request from table
    $query = $con->prepare("DELETE FROM friend_requests WHERE user_to = :userTo AND user_from = :userFrom");
    $query->execute([':userTo' => $userLoggedIn, ':userFrom' => $profile]);

    echo "<div class='alert alert-danger'>You have declined " . $profileObj->getFullName() . "'s friend request!</div>";
  }
}
