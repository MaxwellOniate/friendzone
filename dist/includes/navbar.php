<?php

require('includes/classes/User.php');
require('includes/classes/Message.php');
require('includes/classes/Notification.php');

$user = new User($con, $userLoggedIn);

$messages = new Message($con, $userLoggedIn);
$notifications = new Notification($con, $userLoggedIn);
$messageCount = $messages->getUnreadCount();
$notificationsCount = $notifications->getUnreadCount();

$friendRequestCount = $user->friendRequestCount();

?>

<section id="main-nav">

  <nav class="navbar navbar-expand-md navbar-dark fixed-top">

    <div class="container">

      <a href="index.php" class="navbar-brand"><i class="fab fa-facebook-square"></i></a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-content">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="nav-content">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search">
            <div class="input-group-append">
              <button type="button" name="submit" class="btn"><i class="fa fa-search"></i></button>
            </div>
          </div>
        </form>

        <ul class="navbar-nav ml-auto">

          <li class="nav-item">
            <a href="<?php echo $user->getUsername(); ?>" class="nav-link d-flex align-items-center">

              <img src="<?php echo $user->getProfilePic(); ?>" alt="<?php echo $user->getFirstName(); ?>" class="img-fluid pfp-30 rounded-circle mr-1">

              <?php echo $user->getFirstName(); ?>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="fas fa-home"></i>
              <span class="d-md-none">Home</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="requests.php" class="nav-link">
              <i class="fas fa-user-friends"></i>
              <?php
              if ($friendRequestCount > 0) {
                echo "
                <span class='notification-badge'>
                  $friendRequestCount
                </span>
                ";
              }
              ?>
              <span class="d-md-none">Friend Requests</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="messages.php" class="nav-link">
              <i class="fas fa-envelope"></i>

              <?php
              if ($messageCount > 0) {
                echo "
                <span class='notification-badge'>
                  $messageCount
                </span>
                ";
              }

              ?>
              <span class="d-md-none">Messages</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="notifications.php" class="nav-link">
              <i class="fas fa-bell"></i>
              <?php
              if ($notificationsCount > 0) {
                echo "
                <span class='notification-badge'>
                  $notificationsCount
                </span>
                ";
              }

              ?>
              <span class="d-md-none">Notifications</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-cog"></i>
              <span class="d-md-none">Settings</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="fas fa-sign-out-alt"></i>
              <span class="d-md-none">Logout</span>
            </a>
          </li>

        </ul>

      </div>


    </div>

  </nav>

</section>