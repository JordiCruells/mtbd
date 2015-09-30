<?php
session_start();
include 'main.php';
include 'config.php';

$user = from_post('user', '');
$password = from_post('password', '');

//echo 'user: ' . $user;
//echo 'pass ' . $password;

if (credentials_check($user, $password)) {  
  $_SESSION['logged'] = 1;  
  header("Location: " . $siteUrl . "/activity_list.php");
} else {
  header("Location: " . $siteUrl . "/index.php'); 
}

?>