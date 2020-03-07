<?php

require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');

$userLoggedIn = $_POST['userLoggedIn'];
$profile = $_POST['profile'];
$postBody = $_POST['postBody'];

if (isset($_POST['submit'])) {
  $post = new Post($con, $userLoggedIn);
  $post->submitPost($postBody, $profile);
}
