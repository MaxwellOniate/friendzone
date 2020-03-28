<?php

require("includes/header.php");

if (isset($_GET['q'])) {
  $query = $_GET['q'];
} else {
  $query = "";
}

?>

<section id="search">
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h1 class="card-title">Search Results</h1>
      </div>
      <div class="card-body">
        <ul class="list-group">
          <?php
          if ($query == "") {
            echo "<li class='list-group-item'>You must enter something in the search box.</li>";
          } else {

            $names = explode(" ", $query);

            if (count($names) == 2) {
              $query = $con->prepare("SELECT * FROM users WHERE (first_name LIKE :names0 AND last_name LIKE :names1) AND user_closed = :closed ");
              $query->execute([
                ':names0' => $names[0] . '%',
                ':names1' => $names[1] . '%',
                ':closed' => 'no'
              ]);
            } else {
              $query = $con->prepare("SELECT * FROM users WHERE (first_name LIKE :names0 OR last_name LIKE :names0) AND user_closed = :closed ");
              $query->execute([
                ':names0' => $names[0] . '%',
                ':closed' => 'no'
              ]);
            }

            if ($query->rowCount() == 0) {
              echo "<li class='list-group-item'>We couldn't find anybody with that name.</li>";
            } else if ($query->rowCount() == 1) {
              echo "<span class='d-block mb-3'>" . $query->rowCount() . " " . "result found</span>";
            } else {
              echo "<span class='d-block mb-3'>" . $query->rowCount() . " " . "results found:</span>";
            }

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
              $userObj = new User($con, $userLoggedIn);

              if ($userLoggedIn != $row['username']) {
                $mutualFriends = "
                <span class='d-block small'>" . $userObj->getMutualFriendsCount($row['username']) . "</span>
                ";
              } else {
                $mutualFriends = "";
              }

              echo "
                <a href='" . $row['username'] . "'>
                  <div class='list-group-item'>
                    <div class='media align-items-center'>
                      <img src='" . $row['profile_pic'] . "' class='img-fluid pfp-50 mr-3'>
                      <div class='media-body'>
                        " . $row['first_name'] . " " . $row['last_name'] . "
                          $mutualFriends
                      </div>
                    </div>
                  </div>
                </a>
              ";
            }
          }
          ?>

        </ul>
      </div>
    </div>
  </div>
</section>