<?php 
include 'access.php';
header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.


error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'main.php';
require_once 'Connection.class.php';
require_once 'GroupDAO.class.php';


$id = isset($_GET['id']) ? $_GET['id'] : false;

if (empty($id)) {
  die('Id not supplied');
}


$c = new Connection();
$conn = $c->getConnection();
$groupDAO = new GroupDAO($conn);
$group = $groupDAO->select($id);

$title = $group['name'];

include 'head.html'; 

?>


<div class="row">

    <div class="col-xs-offset-2 col-xs-8 col-xs-offset-2 view-page">

      <h2><?php echo $title; ?></h2>

      <h3> Edat</h3>     
           <p>de <?php echo $ages[$group['age']]; ?></p> 

      <h3>Ubicació</h3>
          <p><?php echo $group['location']; ?></p>
      
      <h3>Dates</h3>
          <p>del <?php echo $group['date_start'] . ' al ' . $group['date_end']; ?></p>

      <h3>Pautes d'observació</h3>
          <p><?php echo empty($group['observations']) ? 'Cap' : $group['observations']; ?></p>

      <h3>Comentaris</h3>
         <p><?php echo empty($group['comments']) ? 'Cap' : $group['comments']; ?></p>
    
      <div class="row text-center">
          <button class="btn btn-primary link" type="button" data-link="group_form.php?id=<?php echo $id; ?>&action=update" >Modificar</span></button>
          <button class="btn btn-primary" type="button">Imprimir</span></button>
          <button class="btn btn-info link" type="button" data-link="group_list.php">Tornar</span></button>
      </div>

    </div>

</div>

<?php include 'foot.html'; ?>


