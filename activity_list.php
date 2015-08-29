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
require_once 'ActivityPaginator.class.php';

$c = new Connection();
$conn = $c->getConnection();

$limit       = from_get('limit', 10);
$page        = from_get('page',1);
$links       = from_get('links', 7);
$expanded    = from_get('expanded', 'on');
$search_string = from_get('search_string', '');
$search_order = from_get('search_order', '');
$search_types = from_get('search_types', array());
$search_song_themes = from_get('search_song_themes', array());
$search_ages = from_get('search_ages', array());


//$query = "SELECT DISTINCT A.id, A.activity_name, A.description, A.goals, A.materials, A.observations, A.assesment, A.comments, A.keywords, A.types, A.song_themes, A.ages, A.timestamp FROM wp_musicteach_activity A ";
//$query = "SELECT DISTINCT A.id, A.activity_name, A.description, A.goals, A.materials, A.observations, A.assesment, A.comments, A.keywords, A.types, A.song_themes, A.ages, A.timestamp, C.name FROM wp_musicteach_activity A 
//LEFT JOIN wp_musicteach_activity_song B ON A.id = B.activity_id LEFT JOIN wp_musicteach_song C ON B.song_id = C.id";
$query = "SELECT DISTINCT A.id, A.activity_name, A.description, A.goals, A.materials, A.observations, A.assesment, A.comments, A.keywords, A.types, A.song_themes, A.ages, A.timestamp, group_concat(C.name separator ', ') FROM wp_musicteach_activity A LEFT JOIN wp_musicteach_activity_song B ON A.id = B.activity_id LEFT JOIN wp_musicteach_song C ON B.song_id = C.id";
$group = " GROUP BY A.id ";
$conn->set_charset("utf8");

$Paginator  = new ActivityPaginator($conn, $query, $group, $search_string, $search_order, $search_types, $search_song_themes, $search_ages);

$results = $Paginator->getData($limit,$page);

if (isAjaxRequest()) {
  include 'include_list_activitys.php';  
} else {

  include 'head.html'; ?>
  <h1 class="text-center">Cercador d'activitats</h1>
  <div id="form-list" class="form-list" data-model="activity"> 
    <?php 
    include 'include_search_activitys.php'; 
    include 'include_list_activitys.php';
    ?>
  </div>
<?php
  include 'foot.html'; 
}

?>

