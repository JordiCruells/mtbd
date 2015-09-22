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
require_once 'GroupDAO.class.php';
require_once 'ActivityDAO.class.php';
require_once 'SongDAO.class.php';
require_once 'ActivityPaginator.class.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'new';
$id = isset($_GET['id']) ? $_GET['id'] : -1;

$limit       = from_get('limit', 10);
$page        = from_get('page',1);
$links       = from_get('links', 7);
$expanded    = from_get('expanded', 'on');
$search_string = from_get('search_string', '');
$search_order = from_get('search_order', '');
$search_types = from_get('search_types', array());
$search_song_themes = from_get('search_song_themes', array());
$search_ages = from_get('search_ages', array());

if ($action !== 'update' && $action !== 'new') {
  die ('opció invalida');
  exit;
}

$c = new Connection();
$conn = $c->getConnection();

if ($action === 'update') {

  $activityDAO = new ActivityDAO($conn);
  $workshopDAO = new WorkshopDAO($conn);
  $workshop = $workshopDAO->select($id);

} else {
  $workshop = array(
          'id' => -1,
          'workshop_date' => '',
          'group_id' => '',
          'observations' => '',
          'comments' => '',
          'favourite' => '',
          'age' => '',
          'activity' => array()
      );
}

$groupDAO = new GroupDAO($conn);
$groups = $groupDAO->getCurrentGroupsKeysAndNames();

$title = ($action === 'new') ? 'Nou taller' : 'Modificar taller';

$query = "SELECT DISTINCT A.id, A.activity_name, A.description, A.goals, A.materials, A.observations, A.assesment, A.comments, A.keywords, A.types, A.song_themes, A.ages, A.timestamp, group_concat(C.name separator ', ') FROM wp_musicteach_activity A LEFT JOIN wp_musicteach_activity_song B ON A.id = B.activity_id LEFT JOIN wp_musicteach_song C ON B.song_id = C.id";
$group = " GROUP BY A.id ";

$Paginator  = new ActivityPaginator($conn, $query, $group, $search_string, $search_order, $search_types, $search_song_themes, $search_ages);

$results = $Paginator->getData($limit,$page);

include 'head.php'; 

?>


<div class="toggle-panel-shrink form-page">

<h1 class="text-center"><?php echo $title; ?></h1>

<form action="workshop.php" method="POST">

  <input type="hidden" name="action" value="<?php echo $action; ?>" /> 
  <input type="hidden" name="id" value="<?php echo $id; ?>" />

  <br>

  <div class="row">
    
      <div class="col-xs-2">
            <div>
               <label  class="input">Data del taller</label>
            </div>
            <input class="form-control" type="date" name="workshop_date" placeholder="Data del taller" title="Format: AAAA-MM-DD" x-moz-errormessage="Format: AAAA-MM-DD" step="1" min="2015-01-01" max="2020-12-31"
            <?php html_value($workshop['workshop_date']); ?> pattern="\d{4}\-\d{2}\-\d{2}" required />
      </div>

      <div class="col-xs-10">
        <div>
            <label  class="input">Grup</label>
        </div>
        <select name="group_id"  class="form-control">
          <option value="0">Selecciona un grup</option>
          <?php options($groups, array($workshop['group_id'])); ?>
        </select> 
      </div>

  </div>

  <br>

  <div id="workshop-schedule" class="row">
        <div class="col-xs-12">
          <label>Estructura del taller (desplaça els blocs necessaris a la part inferior i a continuació clica a cada bloc per afegir-hi activitats) </label>
        </div>        
        <br>
        <div><?php workshop_blocks($workshop['activity']); ?></div>
        <hr>
        <div>
          <div id="ws-blocks" >            
            <?php 
              foreach ($workshop['activity'] as $block_id => $activities) {
                block_wrapper_start($block_id);
                foreach ($activities as $activity_id => $activity) {
                    activity_wrapper($block_id, $activity);
                }
                activity_remainder();
                block_wrapper_end($block_id);
              }
              block_remainder();
              //<div class="remainder"><div class="ws-block-container remainder" draggable="true"></div></div>            
            ?>
          </div>
        </div>
  </div>
  
  <br>  

  <div class="row">

    <div class="col-xs-6">
     
        <div>
          <label class="input">Pautes d'observació</label>
        </div>
        <textarea class="form-control" name="observations" rows="4" placeholder="Observacions pedagògiques" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $workshop['observations']; ?></textarea>
      
    </div>

    <div class="col-xs-6">
     
        <div>
          <label class="input">Comentaris</label>
        </div>
        <textarea class="form-control" name="comments" rows="4" placeholder="Comentaris" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $workshop['comments']; ?></textarea>

    </div>

  </div>

  <br>  

  <div class="row">
        <div class="col-xs-12">
          <input type="checkbox" name="favourite" value="y" 
             <?php echo (!empty($workshop['favourite']) &&  $workshop['favourite'] !== 'n') ? ' checked ' : ''; ?> 
          ><label> &nbsp;Marcar com a favorit ?</label>        
         <label> &nbsp;&nbsp;Per a quines edats ?</label>     
         <?php checkboxes($ages,'age[]', $workshop['age']); ?>  
       </div>
  </div>


  <br>
  <div class="text-center">
    <button class="btn btn-primary" type="submit">Guardar <span class="glyphicon glyphicon-ok"></span></button>
    <button class="btn btn-info btn-back" type="button">Tornar</span></button>
  </button></div>
</form>


</div>


 <div id="search-box" class="col-xs-4 toggle-panel toggle-panel-right toggle-panel-autoclose form-list" data-model="activity">
      <div class="controls"><a class="close toggle-panel-click" data-toggle-panel-id="search-box">&times;</a></div>
      <div id="search-box-form"><?php include 'include_search_activitys.php'; ?></div>
      <div id="search-box-list"><?php include 'include_list_activitys.php'; ?></div>
 </div>



<?php include 'foot.php'; ?>


