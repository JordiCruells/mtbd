<?php
  include 'access.php';
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  require_once 'main.php';
  require_once 'Connection.class.php';
  require_once 'WorkshopDAO.class.php';
  require_once 'ActivityDAO.class.php';
  

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
      $id = $_GET['id'] or die('id de taller no informat');
      break; 
    case 'update':
      $id = $_POST['id'] or die('id de taller no informat');
    case 'new':
      $date = isset($_POST['date']) ?  $_POST['date'] : '';
      $group_id = isset($_POST['group_id']) ?  $_POST['group_id'] : 0;
      $observations = isset($_POST['observations']) ?  $_POST['observations'] : '';
      $comments = isset($_POST['comments']) ?  $_POST['comments'] : '';
      $age = isset($_POST['age']) ?  $_POST['age'] : '';
      $favourite = isset($_POST['favourite']) ?  $_POST['favourite'] : '';

      if (empty($group_id) && (empty($favourite) || $favourite === 'n')) {
        die ('Grup no informat');
      }

      if (!empty($favourite) && $favourite !== 'n' && empty($age)) {
        die ("Grup d'edat no informat");
      }


      $activities = isset($_POST['activity']) ?  $_POST['activity'] : array();

      $workshop = array(
          'id' => $id,
          'date' => $date,
          'group_id' => $group_id,
          'observations' => $observations,
          'comments' => $comments,
          'favourite' => $favourite,
          '$age' => $age,
          'activities' => $activities
      );

      /*echo 'activities:';
      print_r($activities); */
      
  }

  /*
  echo 'action: '.$action;

  */
  
  $c = new Connection();
  $conn = $c->getConnection();
  $activityDAO = new ActivityDAO($conn);
  $workshopDAO = new WorkshopDAO($conn);

  switch($action) {

     case 'new':
        
        $insert_id = $workshopDAO->create($workshop);

        if (count(flatten($activities)) > 0 ) {          
          $activityDAO->linkActivitiesToWorkshop($insert_id, $activities);
        }

        header("Location: http://www.mondemusica.com/music-teach/workshop_list.php?r=".mt_rand(0, 9999999));
        break;

     case 'update':

        $workshopDAO->update($id);
        $activityDAO->unlinkActivitiesFromWorkshop($workshop['id']);

        if (count(flatten($activities)) > 0 ) {          
          $activityDAO->linkActivitiesToWorkshop($workshop['id'], $activities);
        }

        header("Location: http://www.mondemusica.com/music-teach/activity_list.php?r=".mt_rand(0, 9999999));
        break;

     case 'delete':
        //echo 'delete dao'.$id; exit;
        $activityDAO->delete($id);
        header("Location: http://www.mondemusica.com/music-teach/activity_list.php?r=".mt_rand(0, 9999999));
        break;    

  }

  

?>