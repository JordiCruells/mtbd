<?php
  include 'access.php';
  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  require_once 'Connection.class.php';
  require_once 'ActivityDAO.class.php';
  require_once 'SongDAO.class.php';
  

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
      $id = $_GET['id'] or die('id de canço no informat');
      break; 
    case 'update':
      $id = $_POST['id'] or die('id de canço no informat');
    case 'new':
      $activity_name = $_POST['activity_name'] or die('nom de canço no informat');
      $description = $_POST['description']; // or die('descripción no informada');;
      $goals = $_POST['goals']; // or die('descripción no informada');;
      $materials = isset($_POST['materials']) ?  $_POST['materials'] : '';
      $observations = isset($_POST['observations']) ?  $_POST['observations'] : '';
      $assesment = isset($_POST['assesment']) ?  $_POST['assesment'] : '';
      $comments = isset($_POST['comments']) ?  $_POST['comments'] : '';
      $keywords = isset($_POST['keywords']) ?  $_POST['keywords'] : '';
      $types = isset($_POST['type']) ?  $_POST['type'] : array();
      $song_themes = isset($_POST['song_themes']) ?  $_POST['song_themes'] : array();
      $ages = isset($_POST['ages']) ?  $_POST['ages'] : array();
      $song_names = isset($_POST['song_name']) ?  $_POST['song_name'] : array();
      $song_durations = isset($_POST['song_duration']) ?  $_POST['song_duration'] : array();
      $song_files = isset($_POST['song_file']) ?  $_POST['song_file'] : array();
      $activity = array(
          'id' => $id,
          'activity_name' => $activity_name,
          'description' => $description,
          'goals' => $goals,
          'materials' => $materials,
          'observations' => $observations,
          'assesment' => $assesment,
          'comments' => $comments,
          'keywords' => $keywords,
          'types' => join(',', $types),
          'song_themes' => join(',',$song_themes),
          'ages' => join(',',$ages)
      );

      /*echo 'SONG nameS:';
      print_r($song_names);*/

      $songs = array();
      for ($i=0; $i < count($song_names) ; $i++) { 
        $songs[] = array('name' => $song_names[$i], 'duration' => (empty($song_durations[$i]) ? '' : $song_durations[$i]), 'file' => '');
      }

      /*echo 'SONGS:';
      print_r($songs);*/
      
  }

  /*
  echo 'action: '.$action;
  echo '$activity';
  print_r($activity);
  */
  
  $c = new Connection();
  $conn = $c->getConnection();
  $activityDAO = new ActivityDAO($conn);
  $songDAO = new SongDAO($conn);

  switch($action) {

     case 'new':        
        $insert_id = $activityDAO->create($activity);
        if (count($songs) > 0 ) {          
          $songDAO->createSongs($insert_id, $songs);
        }

        header("Location: http://www.mondemusica.com/music-teach/activity_list.php?r=".mt_rand(0, 9999999));
        break;
     case 'update':
        $activityDAO->update($activity);
        $songDAO->unlinkFromActivity($activity['id']);
        $songDAO->createSongs($activity['id'], $songs);
        header("Location: http://www.mondemusica.com/music-teach/activity_list.php?r=".mt_rand(0, 9999999));
        break;
     case 'delete':
        //echo 'delete dao'.$id; exit;
        $activityDAO->delete($id);
        header("Location: http://www.mondemusica.com/music-teach/activity_list.php?r=".mt_rand(0, 9999999));
        break;    

  }

  

?>