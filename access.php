<?php
  session_start();
  $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
  $uri = $uri[0];

//echo $uri;
//echo 'Location:http://' . $_SERVER['HTTP_HOST'] . '/music-teach/index.php';

  if ($uri !== '/music-teach/index.php') {
   //echo ' session ' . $_SESSION['logged'];
     if (!(isset($_SESSION['logged']))) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/music-teach/index.php');
        //exit;
     }
  }
?>