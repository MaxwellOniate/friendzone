<?php
require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');

$id = $_POST['postID'];
$userLoggedIn = $_POST['userLoggedIn'];

if (isset($_POST['submit'])) {
  $query = $con->prepare("UPDATE posts SET deleted = :deleted WHERE id = :postID AND added_by = :un");
  $query->execute([':deleted' => 'yes', ':postID' => $id, ':un' => $userLoggedIn]);
}
