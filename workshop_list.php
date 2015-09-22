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
require_once 'WorkshopPaginator.class.php';
require_once 'GroupDAO.class.php';

$c = new Connection();
$conn = $c->getConnection();

$limit       = from_get('limit', 10);
$page        = from_get('page',1);
$links       = from_get('links', 7);
$expanded    = from_get('expanded', 'on');
$search_group = from_get('search_group', '');
$search_from_date = from_get('search_from_date', '');
$search_to_date = from_get('search_to_date', '');
$search_favourite = from_get('search_favourite', '');
$search_ages = from_get('search_ages', '');

$query = "SELECT A.id, A.workshop_date, A.group_id, A.observations, A.comments, A.favourite, A.age, A.timestamp, B.name FROM wp_musicteach_workshop A JOIN wp_musicteach_group B ON A.group_id = B.id ";
$group_by = "";
$conn->set_charset("utf8");

$groupDAO = new GroupDAO($conn);
$groups = $groupDAO->getAllGroupsKeysAndNames();

$Paginator  = new WorkshopPaginator($conn, $query, $group_by, $search_group, $search_from_date, $search_to_date, $search_favourite, $search_ages);

$results = $Paginator->getData($limit,$page);

if (isAjaxRequest()) {
  include 'include_list_workshops.php';  
} else {

  include 'head.php'; ?>
  <h1 class="text-center">Cercador de tallers</h1>
  <div id="form-list" class="form-list" data-model="workshop"> 
    <?php 
    include 'include_search_workshops.php'; 
    include 'include_list_workshops.php';
    ?>
  </div>
<?php
  include 'foot.php'; 
}

?>

