<?php
  include 'access.php';
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  require_once 'main.php';
  require_once 'Connection.class.php';
  require_once 'WorkshopDAO.class.php';
  require_once 'ActivityDAO.class.php';
  
  $action = from_post_or_get('action', '');
  
  if (empty($action)) {
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
      $workshop_date = isset($_POST['workshop_date']) ?  $_POST['workshop_date'] : '';
      $group_id = isset($_POST['group_id']) ?  $_POST['group_id'] : 0;
      $observations = isset($_POST['observations']) ?  $_POST['observations'] : '';
      $comments = isset($_POST['comments']) ?  $_POST['comments'] : '';
      $age = isset($_POST['age']) ?  $_POST['age'] : '';
      $favourite = isset($_POST['favourite']) ?  $_POST['favourite'] : '';

      if (empty($group_id)) {
        die ('Grup no informat');
      }

      if (empty($workshop_date)) {
        die ('Data no informada');
      }

      if (!empty($favourite) && $favourite !== 'n' && empty($age)) {
        die ("Grup d'edat no informat");
      }

      $activities = isset($_POST['activity']) ?  $_POST['activity'] : array();

      $workshop = array(
          'id' => $id,
          'workshop_date' => $workshop_date,
          'group_id' => $group_id,
          'observations' => $observations,
          'comments' => $comments,
          'favourite' => $favourite,
          'age' => $age,
          'activities' => $activities
      );
      break;     
      
  } 
    
  $c = new Connection();
  $conn = $c->getConnection();
  $workshopDAO = new WorkshopDAO($conn);

  switch($action) {

     case 'new':
        
        $insert_id = $workshopDAO->create($workshop);

        if (count(flatten($activities)) > 0 ) {          
          $workshopDAO->linkActivities($insert_id, $activities);
        }

        header("Location: http://www.mondemusica.com/music-teach/workshop_list.php?r=".mt_rand(0, 9999999));
        break;

     case 'update':

        $workshopDAO->update($workshop);
        $workshopDAO->unlinkActivities($workshop['id']);

        if (count(flatten($activities)) > 0 ) {          
          $workshopDAO->linkActivities($workshop['id'], $activities);
        }

        header("Location: http://www.mondemusica.com/music-teach/workshop_list.php?r=".mt_rand(0, 9999999));
        break;

     case 'delete':
        $workshopDAO->delete($id);
        header("Location: http://www.mondemusica.com/music-teach/workshop_list.php?r=".mt_rand(0, 9999999));
        break;    

  }


  function trace() {
    global $action, $activities, $workshop;
    echo '<br>action: '.$action;
    echo '<br>activities:';
    print_r($activities); 
    echo '<br>workshop:';
    print_r($workshop); 
    echo '<br>flattened activities: ' . count(flatten($activities));
    exit;
  }

  

?>