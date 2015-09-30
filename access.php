<?php
  session_start();
  $uri = explode('?', $_SERVER['REQUEST_URI'], 2);
  $uri = $uri[0];

  require_once 'main.php';

  if ($uri !== '/' . $sitePrefix . '/index.php') {
   //echo ' session ' . $_SESSION['logged'];
     if (!(isset($_SESSION['logged']))) {
        header('Location: ' . $siteUrl . '/index.php');
        //exit;
     }
  }
?>