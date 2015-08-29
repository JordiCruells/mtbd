<?php
session_start();
include 'main.php';
include 'credentials.php';

$user = from_post('user', '');
$password = from_post('password', '');

//echo 'user: ' . $user;
//echo 'pass ' . $password;

if (credentials_check($user, $password)) {  
  $_SESSION['logged'] = 1;  
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/music-teach/activity_list.php');
} else {
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/music-teach/index.php'); 
}

?>