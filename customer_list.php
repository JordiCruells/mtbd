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
require_once 'CustomerPaginator.class.php';

$c = new Connection();
$conn = $c->getConnection();

$limit       = from_get('limit', 10);
$page        = from_get('page',1);
$links       = from_get('links', 7);
$expanded    = from_get('expanded', 'on');
$search_name = from_get('search_name', '');
$search_participants = from_get('search_participants', '');


$query = "SELECT DISTINCT A.id, A.name, A.email, A.phone, A.participants, A.observations, A.timestamp FROM wp_musicteach_customer A ";
$conn->set_charset("utf8");

$Paginator  = new CustomerPaginator($conn, $query, $search_name, $search_participants);

$results = $Paginator->getData($limit,$page);


if (isAjaxRequest()) {
  include 'include_list_customers.php';  
} else {

  include 'head.html'; ?>
  <h1 class="text-center">Cercador de famílies</h1>
  <div id="form-list" class="form-list" data-model="customer"> 
    <?php 
    include 'include_search_customers.php'; 
    include 'include_list_customers.php';
    ?>
  </div>
<?php
  include 'foot.html'; 
}

?>

