<?php
require('includes/header.php');

$limit = 7;

?>

<section id="notifications">
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h1 class="card-title">Notifications</h1>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <?php echo $notifications->loadNotifications($con, $limit); ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php require('includes/footer.php'); ?>