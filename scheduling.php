<?php
  include 'access.php';
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  require_once 'main.php';
  require_once 'Connection.class.php';
  require_once 'SchedulingDAO.class.php';
  require_once 'ActivityDAO.class.php';

  $response = array('status'=>'1', 'message' => '');
  $isAjax = isAjaxRequest();
  
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
      $id = $_GET['id'] or die('id de planificacio no informat');
      break; 
    case 'update':
      $id = $_POST['id'] or die('id de planificacio no informat');
    case 'new':
      $scheduling_date_start = isset($_POST['scheduling_date_start']) ?  $_POST['scheduling_date_start'] : '';
      $scheduling_date_end = isset($_POST['scheduling_date_end']) ?  $_POST['scheduling_date_end'] : '';
      $observations = isset($_POST['observations']) ?  $_POST['observations'] : '';
      $comments = isset($_POST['comments']) ?  $_POST['comments'] : '';
      $age = isset($_POST['age']) ?  $_POST['age'] : '';

     
      if (empty($scheduling_date_start)) {
        die ('Data inici no informada');
      }
      if (empty($scheduling_date_end)) {
        die ('Data fi no informada');
      }

      if (empty($age)) {
        die ("Grup d'edat no informat");
      }

      $activities = isset($_POST['activity']) ?  $_POST['activity'] : array();

      $scheduling = array(
          'id' => $id,
          'scheduling_date_start' => $scheduling_date_start,
          'scheduling_date_end' => $scheduling_date_end,
          'observations' => $observations,
          'comments' => $comments,
          'age' => $age,
          'activities' => $activities
      );
      break;     
      
  } 
    
  $c = new Connection();
  $conn = $c->getConnection();
  $schedulingDAO = new SchedulingDAO($conn);

  switch($action) {

     case 'new':

        /*echo 'case new';
        print_r($scheduling);*/
        
        $insert_id = $schedulingDAO->create($scheduling);

        if (count(flatten($activities)) > 0 ) {          
          $schedulingDAO->linkActivities($insert_id, $activities);
        }

        break;

     case 'update':

        $schedulingDAO->update($scheduling);
        $schedulingDAO->unlinkActivities($scheduling['id']);

        if (count(flatten($activities)) > 0 ) {          
          $schedulingDAO->linkActivities($scheduling['id'], $activities);
        }

        break;

     case 'delete':
        $schedulingDAO->unlinkActivities($id);
        $schedulingDAO->delete($id);
        break;    

  }


  if ($isAjax) {
    echo json_encode($response); exit;
  } else {
    header("Location: " . $siteUrl . "/scheduling_list.php?r=" . mt_rand(0, 9999999));
  }


  function trace() {
    global $action, $activities, $scheduling;
    echo '<br>action: '.$action;
    echo '<br>activities:';
    print_r($activities); 
    echo '<br>scheduling:';
    print_r($scheduling); 
    echo '<br>flattened activities: ' . count(flatten($activities));
    exit;
  }

  

?>