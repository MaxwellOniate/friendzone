<?php

require('includes/classes/User.php');

$user = new User($con, $userLoggedIn);

?>

<section id="main-nav">
  <nav class="navbar navbar-expand-md navbar-dark">
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
            <a href="#" class="nav-link">

              <img src="<?php echo $user->getProfilePic(); ?>" alt="<?php echo $user->getFirstName(); ?>" class="img-fluid nav-profilePic">

              <?php echo $user->getFirstName(); ?>
            </a>
          </li>
          <div class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="fas fa-home"></i>
              <span class="d-md-none">Home</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-user-friends"></i>
              <span class="d-md-none">Friend Requests</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#" class="nav-link">
              <i class="fab fa-facebook-messenger"></i>
              <span class="d-md-none">Messages</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-bell"></i>
              <span class="d-md-none">Notifications</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-cog"></i>
              <span class="d-md-none">Settings</span>
            </a>
          </div>
      </div>

      </ul>

    </div>


    </div>
  </nav>
</section>