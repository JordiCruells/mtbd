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
require_once 'WorkshopDAO.class.php';


$id = isset($_GET['id']) ? $_GET['id'] : false;

if (empty($id)) {
  die('Id not supplied');
}


$c = new Connection();
$conn = $c->getConnection();
$workshopDAO = new WorkshopDAO($conn);
$workshop = $workshopDAO->select($id);

include 'head.html'; 

?>

<div class="row">

    <div class="col-xs-12 view-page">

      <h1>Fitxa de taller</h1>

      <table class="table">
      
        <tr>
          <td>Grup</td>
          <td><?php echo $workshop['group_name']; ?></td>
        </tr>

        <tr>
          <td>Día</td>
          <td><?php echo $workshop['workshop_date']; ?></td>
        </tr>

        <tr>
          <td>Observacions</td>
          <td><?php echo $workshop['observations']; ?></td>
        </tr>

       <tr>
          <td>Marcat com a favorit ?</td>
          <td><?php echo ($workshop['favourite'] === 'y') ? 'Sí' : 'No'; ?></td>
        </tr>

       <tr>
          <td>Edats recomanades</td>
          <td><?php list_ages($workshop['age']); ?></td>
        </tr>        
     </table> 

     <h3>Estructura</h3> 
      <div class="ws-blocks">            
            <?php 
              $ord = 1;
              foreach ($workshop['activity'] as $block_id => $activities) {
                block_wrapper_start($block_id, $ord);
                foreach ($activities as $activity_id => $activity) {
                    activity_wrapper($block_id, $activity);
                }
                activity_remainder();
                block_wrapper_end($block_id);
                $ord += 1;
              }
              block_remainder();
              //<div class="remainder"><div class="ws-block-container remainder" draggable="true"></div></div>            
            ?>
      </div>     


      
    
      <div class="row text-center">
          <button class="btn btn-primary link" type="button" data-link="workshop_form.php?id=<?php echo $id; ?>&action=update" >Modificar</span></button>
          <button class="btn btn-primary" type="button">Imprimir</span></button>
          <button class="btn btn-info link" type="button" data-link="workshop_list.php">Tornar</span></button>
      </div>

    </div>

</div>

<?php include 'foot.html'; ?>


