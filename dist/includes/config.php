<?php

ob_start();
session_start();

date_default_timezone_set('America/New_York');

$dsn = 'mysql:host=us-cdbr-iron-east-01.cleardb.net;dbname=heroku_ba43b6be402db6d';
$user = 'be662a7b7b2e61';
$pass = 'c640f8e0';

try {
  $con = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
  echo 'Connection Error: ' . $e;
}
