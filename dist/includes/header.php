<?php

require('includes/config.php');
require('includes/classes/Post.php');

if (!isset($_SESSION["userLoggedIn"])) {
  header("Location: register.php");
}

$userLoggedIn = $_SESSION["userLoggedIn"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Friendzone</title>

  <!-- SCRIPTS -->
  <script src="https://kit.fontawesome.com/52d1564875.js" crossorigin="anonymous"></script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/jquery.Jcrop.js"></script>
  <script src="assets/js/jcrop_bits.js"></script>

  <!-- CSS -->
  <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/jquery.Jcrop.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>

  <?php require('includes/navbar.php'); ?>