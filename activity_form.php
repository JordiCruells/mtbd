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
require_once 'partial_form_track.php';

$action = from_get('action','new');
$id = from_get('id',-1);

if ($action !== 'update' && $action !== 'new') {
  die ('opció invalida');
  exit;
}

if ($action === 'update') {

  $c = new Connection();
  $conn = $c->getConnection();
  $activityDAO = new ActivityDAO($conn);
  $activity = $activityDAO->select($id);
  $songDAO = new SongDAO($conn);
  $songs = $songDAO->selectFromActivity($id);


} else {
  $activity = array(
          'id' => -1,
          'activity_name' => '',
          'description' => '',
          'goals' => '',
          'materials' => '',
          'observations' => '',
          'assesment' => '',
          'comments' => '',
          'keywords' => '',
          'types' => '',
          'song_themes' => '',
          'ages' => ''
      );
  $songs = array();
}


$title = ($action === 'new') ? 'Nova activitat' : 'Modificar activitat';

include 'head.html'; 

?>


<br>
<div>

<h1 class="text-center"><?php echo $title; ?></h1>
<br>

<form action="activity.php" method="POST">
  <input type="hidden" name="action" value="<?php echo $action; ?>" /> 
  <input type="hidden" name="id" value="<?php echo $id; ?>" />
  <div class="row">
    <div class="col-xs-7">
     <label class="input"> Característiques:</label>     
     <div class="fieldset">
      <?php checkboxes($types, 'type[]', $activity['types']); ?> 
     </div>      
    </div> 

    <div class="col-xs-1">
     <label class="input"> Edats:</label>     
     <div class="fieldset">
          <?php checkboxes($ages, 'ages[]', $activity['ages']); ?>
       
     </div>
    </div>      

    <div class="col-xs-4">
     <label class="input"> Temàtica cancó:</label>     
     <div class="fieldset">       
       <?php checkboxes($song_themes, 'song_themes[]', $activity['song_themes']); ?>
     </div>
    </div>    
   

  </div>
  <br>
  <div class="row">
    <div class="col-xs-12">
      <div>
        <label  class="input">Nom de l'activitat</label>
      </div>

      <div>
        <input class="form-control" type="text" name="activity_name" placeholder="Nom de l'activitat" required title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp"
        <?php html_value($activity['activity_name']); ?>/>
      </div>

    </div>
  </div>
  <br>
  
  <?php 
      for ($i=0; $i < count($songs); $i++) { 
        form_track($songs[$i]); 
      }      
  ?>

  <div class="row" style="margin-top: 5px">
      <div class="col-xs-12">
         <a href='#' class="add-track-link">Afegir pista</a>
         <a href='#' class="remove-track-link">Eliminar pista</a>
      </div>    
  </div>

  <br>  
  <div class="row">

    <div class="col-xs-6">
        <div>
           <label  class="input">Descripció del procés</label>
        </div>
        <textarea class="form-control" name="description" rows="13" placeholder="Descripció del procés" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $activity['description']; ?></textarea>
        <div>
           <label  class="input">Aspectes que es treballen</label>
        </div>
        <textarea class="form-control" name="goals" rows="12" placeholder="Aspectes educatius" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $activity['goals']; ?></textarea>
    </div>
    
    <div class="col-xs-6">
      <div class="row">
        <div>
          <label  class="input">Materials</label>
        </div>
        <textarea class="form-control" name="materials" rows="4" placeholder="Materials emprats" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $activity['materials']; ?></textarea>
      </div>
      <div class="row">
        <div>
          <label class="input">Pautes d'observació</label>
        </div>
        <textarea class="form-control" name="observations" rows="4" placeholder="Observacions pedagògiques" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $activity['observations']; ?></textarea>
      </div>

      <div class="row">
        <div>
          <label class="input">Valoració</label>
        </div>
        <textarea class="form-control" name="assesment" rows="4" placeholder="Valoració" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $activity['assesment']; ?></textarea>
      </div>
      
      <div class="row">
        <div>
          <label class="input">Comentaris</label>
        </div>
        <textarea class="form-control" name="comments" rows="4" placeholder="Comentaris" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $activity['comments']; ?></textarea>
      </div>

      <div class="row">
        <div>
           <label  class="input">Paraules clau</label>
        </div>
        <input class="form-control" type="text"  name="keywords" placeholder="Paraules clau (separades per comes)" <?php html_value($activity['keywords']); ?>/>
      </div>
    </div>


  </div>
  
  <br>

  <div class="text-center">
    <button class="btn btn-primary" type="submit">Guardar <span class="glyphicon glyphicon-ok"></span></button>
    <button class="btn btn-info btn-back" type="button">Tornar</span></button>
  </button></div>
</form>

</div>


<div id="form-track">
    <?php form_track(); ?>
</div>


<?php include 'foot.html'; ?>


