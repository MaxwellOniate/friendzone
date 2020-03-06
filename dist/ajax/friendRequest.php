<?php

require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');

$submit = $_POST['submit'];
$profile = $_POST['profile'];
$userLoggedIn = $_POST['userLoggedIn'];

if (isset($submit)) {
  if ($submit == 'remove') {

    // Remove person from your friend_array
    $query = $con->prepare("SELECT friend_array FROM users WHERE username = :un");
    $query->execute([':un' => $userLoggedIn]);
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $friendArray = $row['friend_array'];

    $newFriendArray = str_replace($profile . ",", "", $friendArray);

    $removeFriendQuery = $con->prepare("UPDATE users SET friend_array = :newFriendArray WHERE username = :un");
    $removeFriendQuery->execute([':newFriendArray' => $newFriendArray, ':un' => $userLoggedIn]);

    // Remove yourself from person's friend_array
    $query = $con->prepare("SELECT friend_array FROM users WHERE username = :un");
    $query->execute([':un' => $profile]);
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $friendArray = $row['friend_array'];

    $newFriendArray = str_replace($userLoggedIn . ",", "", $friendArray);

    $removeFriendQuery = $con->prepare("UPDATE users SET friend_array = :newFriendArray WHERE username = :un");
    $removeFriendQuery->execute([':newFriendArray' => $newFriendArray, ':un' => $profile]);

    echo "
    <button onclick='friendRequest(this)' class='btn btn-success' name='friend'>Add Friend</button>";
  } else if ($submit == 'accept') {

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

    echo "
    <button onclick='friendRequest(this)' class='btn btn-outline-danger' name='remove'>Remove Friend</button>";
  } else if ($submit == 'cancel') {
    $query = $con->prepare("DELETE FROM friend_requests WHERE user_to = :userTo AND user_from = :userFrom");
    $query->execute([':userTo' => $profile, ':userFrom' => $userLoggedIn]);

    echo "
    <button onclick='friendRequest(this)' class='btn btn-success' name='friend'>Add Friend</button>";
  } else {
    $query = $con->prepare("INSERT INTO friend_requests (user_to, user_from) VALUES(:userTo, :userFrom)");
    $query->execute([':userTo' => $profile, ':userFrom' => $userLoggedIn]);

    echo "
    <button onclick='friendRequest(this)' class='btn btn-secondary' name='cancel'>Friend Request Sent</button>";
  }
}
