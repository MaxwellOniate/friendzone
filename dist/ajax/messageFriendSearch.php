<?php

require("../includes/config.php");
require("../includes/classes/User.php");

$search = $_POST['search'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $search);

if (strpos($search, "-") !== false) {
  $query = $con->prepare("SELECT * FROM users WHERE username LIKE :search AND user_closed = :yesOrNo LIMIT 8");
  $query->execute([':search' => "$search%", ':yesOrNo' => 'no']);
} else if (count($names) == 2) {
  $query = $con->prepare("SELECT * FROM users WHERE (first_name LIKE :names0 AND last_name LIKE :names1) AND user_closed = :yesOrNo LIMIT 8");
  $query->execute([':names0' => "%$names[0]%", ":names1" => "%$names[1]%", "yesOrNo" => "no"]);
} else {
  $query = $con->prepare("SELECT * FROM users WHERE (first_name LIKE :names0 OR last_name LIKE :names0) AND user_closed = :yesOrNo LIMIT 8");
  $query->execute([':names0' => "%$names[0]%", "yesOrNo" => "no"]);
}

if ($search != "") {
  while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $user = new User($con, $userLoggedIn);

    if ($row['username'] != $userLoggedIn) {
      $mutualFriends = $user->getMutualFriendsCount($row['username']);
    } else {
      $mutualFriends = "";
    }

    if ($user->isFriend($row['username'])) {
      echo "
      <div class='card result'>
        <div class='card-body'>
          <a href='messages.php?u=" . $row['username'] . "'>
            <div class='media align-items-center'>
              <img src='" . $row['profile_pic'] . "' class='img-fluid search-profile-pic'>
              <div class='media-body'>
                " . $row['first_name'] . " " . $row['last_name'] . "
                <small class='d-block'>$mutualFriends</small>
              </div>
            </div>
          </a>
        </div>
      </div>";
    }
  }
}
