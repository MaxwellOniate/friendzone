<?php

require('../includes/config.php');
require('../includes/classes/User.php');
require('../includes/classes/Post.php');

$limit = 10;

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->loadProfilePosts($_REQUEST, $limit);
