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
require_once 'SchedulingDAO.class.php';


$id = isset($_GET['id']) ? $_GET['id'] : false;

if (empty($id)) {
  die('Id not supplied');
}

$c = new Connection();
$conn = $c->getConnection();
$schedulingDAO = new SchedulingDAO($conn);
$scheduling = $schedulingDAO->select($id);

include 'head.php'; 

?>

<div class="row">

    <div class="col-xs-12 view-page">

      <h1>Fitxa de planificació </h1>

      <table class="table">
      
        <tr>
          <td>Periode</td>
          <td>del <?php echo $scheduling['scheduling_date_start']; ?> al <?php echo $scheduling['scheduling_date_end']; ?></td>
        </tr>

        <tr>
          <td>Observacions</td>
          <td><?php echo $scheduling['observations']; ?></td>
        </tr>

       <tr>
          <td>Edat: </td>
          <td><?php echo $ages[$scheduling['age']]; ?></td>
        </tr>        
     </table> 

     <h3>LLista d'activitats</h3> 
     <ul>
     <?php 
        foreach ($scheduling['activity'] as $block_id => $activities) 
        {
         foreach ($activities as $activity_id => $activity) {
           echo '<li>' . $activity['activity_name'] . '</li>';
          }
        }
      ?>
     </ul>

     <h3>Planificació per blocs</h3> 
      <div class="ws-blocks">            
            <?php 
              $ord = 1;
              foreach ($scheduling['activity'] as $block_id => $activities) {
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
          <button class="btn btn-primary btn-modify" type="button" data-link="scheduling_form.php?id=<?php echo $id; ?>&action=update" >Modificar</span></button>
          <button class="btn btn-primary" type="button">Imprimir</span></button>
          <button class="btn btn-info btn-back" type="button" data-link="scheduling_list.php">Tornar</span></button>
      </div>

    </div>

</div>

<?php include 'foot.php'; ?>


