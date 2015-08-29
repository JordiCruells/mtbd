<?php
  include 'access.php';
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  require_once 'Connection.class.php';
  require_once 'GroupDAO.class.php';
  

  if (isset($_POST['action'])) 
    {
      $action = $_POST['action'];
    } 

  else 

  {

    if (isset($_GET['action'])) 
    {
      $action = $_GET['action'];
    }

  }
  
  if (!isset($action)) {
    die('accio no informada'); exit;
  }


  

  $id = -1;
  
  if ($action !== 'new' && $action !== 'update' && $action !== 'delete') {
    die('accio incorrecta: '.$action );
  }

 

  switch($action) {

    case 'delete':
      $id = $_GET['id'] or die('id de grup no informat');
      break; 
    case 'update':
      $id = $_POST['id'] or die('id de grup no informat');
    case 'new':
      $name = $_POST['name'] or die('nom de grup no informat');
      $age =  $_POST['age'] or die('edat no informada');
      $date_start = $_POST['date_start'] or die('data inici informada');
      $date_end = $_POST['date_end'] or die('data fi informada');
      $location = $_POST['location'] or die('ubicació no informada');
      $observations = isset($_POST['observations']) ?  $_POST['observations'] : '';
      $comments = isset($_POST['comments']) ?  $_POST['comments'] : '';      
      
      $group = array(
          'id' => $id,
          'name' => $name,
          'age' => $age,
          'date_start' => $date_start,
          'date_end' => $date_end,
          'location' => $location,
          'comments' => $comments,
          'observations' => $observations
      );
      
  }

  /*
  echo 'action: '.$action;
  echo '$group';
  print_r($group);
  */
  
  $c = new Connection();
  $conn = $c->getConnection();
  $groupDAO = new GroupDAO($conn);

  switch($action) {

     case 'new':        
        $insert_id = $groupDAO->create($group);
        header("Location: http://www.mondemusica.com/music-teach/group_list.php?r=".mt_rand(0, 9999999));
        break;
     case 'update':
        $groupDAO->update($group);
        header("Location: http://www.mondemusica.com/music-teach/group_list.php?r=".mt_rand(0, 9999999));
        break;
     case 'delete':
        //echo 'delete dao'.$id; exit;
        $groupDAO->delete($id);
        header("Location: http://www.mondemusica.com/music-teach/group_list.php?r=".mt_rand(0, 9999999));
        break;    

  }

  

?>