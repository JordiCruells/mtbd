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
require_once 'ActivityDAO.class.php';
require_once 'SongDAO.class.php';
//require_once 'partial_form_track.php';

$id = isset($_GET['id']) ? $_GET['id'] : false;

if (empty($id)) {
  die('Id not supplied');
}


$c = new Connection();
$conn = $c->getConnection();
$activityDAO = new ActivityDAO($conn);
$activity = $activityDAO->select($id);
$songDAO = new SongDAO($conn);
$songs = $songDAO->selectFromActivity($id);


$title = $activity['activity_name'];

include 'head.html'; 

?>


<div class="row">

    <div class="col-xs-offset-2 col-xs-8 col-xs-offset-2 view-page">

      <h2><?php echo $title; ?></h2>
      
      <br>
      
      <?php echo nl2br($activity['description']); ?>
  
      <br>

      <table class="table">
      
        <tr>
          <td>Característiques</td>
          <td><?php list_types($activity['types']); ?></td>
        </tr>

        <tr>
          <td>Edats</td>
          <td><?php list_ages($activity['ages']); ?></td>
        </tr>

        <tr>
          <td>Tema</td>
          <td><?php  list_song_themes($activity['song_themes']);  ?></td>
        </tr>

        <tr>
          <td>Pistes</td>
          <td>
            <ol>
            <?php 
              for ($i=0; $i < count($songs); $i++) { 
                $duration = empty($songs[$i]['duration']) ? '' : ' ('. $songs[$i]['duration'] .')';
                echo '<li>' . $songs[$i]['name'] . $duration . '</li>'; 
              }      
            ?>
            </ol>
          </td>
        </tr>

        <tr>
          <td>Edats</td>
          <td><?php list_ages($activity['ages']); ?></td>
        </tr>

        <tr>
          <td>Aspectes que es treballen</td>
          <td><?php echo nl2br($activity['goals']); ?></td>
        </tr>

        <tr>
          <td>Materials</td>
          <td><?php echo $activity['materials']; ?></td>
        </tr>
  
        <tr>
          <td>Observacions</td>
          <td><?php echo empty($activity['observations']) ? 'Cap' : $activity['observations']; ?></td>
        </tr>


        <tr>
          <td>Valoració</td>
          <td><?php echo empty($activity['assesment']) ? 'Cap' : $activity['assesment']; ?></td>
        </tr>

        <tr>
          <td>Comentaris</td>
          <td><?php echo empty($activity['comments']) ? 'Cap' : $activity['comments']; ?></td>
        </tr>

        <tr>
          <td>Paraules clau</td>
          <td><?php echo empty($activity['keywords']) ? 'Cap' : $activity['keywords']; ?></td>
        </tr>

      </table>



<?php /*           
           <p><?php list_types($activity['types']); ?></p> 


      <h3>Edats</h3>
           <p><?php list_ages($activity['ages']); ?></p> 

      <?php if ($activity['song_themes'] !== '') { ?>
        <h3> Temàtica cancó:</h3>     
           <p><?php  list_song_themes($activity['song_themes']);  ?></p> 
      <?php } ?>     

      <?php if (count($songs) > 0)  { ?>
        <h3>Pistes</h3>
          <ol>
          <?php 
            for ($i=0; $i < count($songs); $i++) { 
              $duration = empty($songs[$i]['duration']) ? '' : ' ('. $songs[$i]['duration'] .')';
              echo '<li>' . $songs[$i]['name'] . $duration . '</li>'; 
            }      
          ?>
          </ol>
      <?php } ?>     

      <h3>Descripció del procés</h3>
          <p><?php echo nl2br($activity['description']); ?></p>

      <h3>Aspectes que es treballen</h3>
          <p><?php echo nl2br($activity['goals']); ?></p>

      <h3>Materials</h3>
          <p><?php echo $activity['materials']; ?></p>
      <h3>Pautes d'observació</h3>
          <p><?php echo empty($activity['observations']) ? 'Cap' : $activity['observations']; ?></p>
      <h3>Valoració</h3>
         <p><?php echo empty($activity['assesment']) ? 'Cap' : $activity['assesment']; ?></p>
      <h3>Comentaris</h3>
         <p><?php echo empty($activity['comments']) ? 'Cap' : $activity['comments']; ?></p>
      <h3>Paraules clau</h3>
          <p><?php echo empty($activity['keywords']) ? 'Cap' : $activity['keywords']; ?></p>


          */ ?>

      <div class="row text-center">
          <button class="btn btn-primary link" type="button" data-link="activity_form.php?id=<?php echo $id; ?>&action=update" >Modificar</span></button>
          <button class="btn btn-primary" type="button">Imprimir</span></button>
          <button class="btn btn-info link" type="button" data-link="activity_list.php">Tornar</span></button>
      </div>

    </div>

</div>

<?php include 'foot.html'; ?>


