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
require_once 'SchedulingPaginator.class.php';

$c = new Connection();
$conn = $c->getConnection();

$limit       = from_get('limit', 10);
$page        = from_get('page',1);
$links       = from_get('links', 7);
$expanded    = from_get('expanded', 'on');
$search_date = from_get('search_date', '');
$search_age = from_get('search_age', '');

$query = "SELECT A.id, A.scheduling_date_start, A.scheduling_date_end, A.observations, A.comments, A.age, A.timestamp FROM wp_musicteach_scheduling A ";
$group_by = "";
$conn->set_charset("utf8");

$Paginator  = new SchedulingPaginator($conn, $query, $group_by, $search_date, $search_age);

$results = $Paginator->getData($limit,$page);

if (isAjaxRequest()) {
  include 'include_list_schedulings.php';  
} else {

  include 'head.html'; ?>
  <h1 class="text-center">Cercador de planificacions</h1>
  <div id="form-list" class="form-list" data-model="scheduling"> 
    <?php 
    include 'include_search_schedulings.php'; 
    include 'include_list_schedulings.php';
    ?>
  </div>
<?php
  include 'foot.html'; 
}

?>

