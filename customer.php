<?php
  include 'access.php';
  error_reporting(E_ALL);
  
  ini_set("display_errors", 1);
  require_once 'main.php';
  require_once 'Connection.class.php';
  require_once 'CustomerDAO.class.php';

  $response = array('status'=>'1', 'message' => '');
  $isAjax = isAjaxRequest();

  $action = from_post_or_get('action','');

  if (!isset($action)) {
    die('accio no informada'); exit;
  }
 
  $id = -1;
  
  if ($action !== 'new' && $action !== 'update' && $action !== 'delete') {
    die('accio incorrecta: '.$action );
  } 

  switch($action) {

    case 'delete':
      $id = $_GET['id'] or die('id de client no informat');
      break; 
    case 'update':
      $id = $_POST['id'] or die('id de client no informat');
    case 'new':
      $name = $_POST['name'] or die('nom de client no informat');
      $email =  isset($_POST['email']) ? $_POST['email'] : '';
      $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
      $participants = isset($_POST['participants']) ? $_POST['participants'] : '';
      $observations = isset($_POST['observations']) ? $_POST['observations'] : '';
      
      $customer = array(
          'id' => $id,
          'name' => $name,
          'email' => $email,
          'phone' => $phone,
          'participants' => $participants,
          'observations' => $observations
      );
      
  }

  /*
  echo 'action: '.$action;
  echo '$customer';
  print_r($customer);
  */
  
  $c = new Connection();
  $conn = $c->getConnection();

  $customerDAO = new CustomerDAO($conn);
  
  switch($action) {

     case 'new':        
        $insert_id = $customerDAO->create($customer);
        break;
     case 'update':
        $customerDAO->update($customer);
        break;
     case 'delete':
        //echo 'delete dao'.$id; exit;
        $customerDAO->delete($id);
        break;    
  }

  if ($isAjax) {
    echo json_encode($response); exit;
  } else {
    header("Location: " . $siteUrl . "/customer_list.php?r=" . mt_rand(0, 9999999));
  }

  

?>