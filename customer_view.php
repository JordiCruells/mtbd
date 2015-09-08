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
require_once 'CustomerDAO.class.php';


$id = isset($_GET['id']) ? $_GET['id'] : false;

if (empty($id)) {
  die('Id not supplied');
}

$c = new Connection();
$conn = $c->getConnection();
$customerDAO = new CustomerDAO($conn);

$customer = $customerDAO->select($id);

$title = "Fitxa de mare / pare"; 

include 'head.html'; 

?>

<div class="row">

    <div class="col-xs-12 view-page">
  
      <h2><?php echo $title; ?></h2>
      <table class="table">
        <tr>
          <td width="70px">Nom</td>
          <td><?php echo $customer['name']; ?></td>
        </tr>
        <tr>
          <td>Email</td>
          <td><?php echo $customer['email']; ?></td>
        </tr>
        <tr>
          <td>Telèfon</td>
          <td><?php echo $customer['phone']; ?></td>
        </tr>
        <tr>
          <td>Nen / nena</td>
          <td><?php echo $customer['participants']; ?></td>
        </tr>
        <tr>
          <td>Observacions</td>
          <td><?php echo $customer['observations']; ?></td>
        </tr>

      </table>
    
      <div class="row text-center">
          <button class="btn btn-primary link" type="button" data-link="customer_form.php?id=<?php echo $id; ?>&action=update" >Modificar</span></button>
          <button class="btn btn-primary" type="button">Imprimir</span></button>
          <button class="btn btn-info link" type="button" data-link="customer_list.php">Tornar</span></button>
      </div>

    </div>

</div>

<?php include 'foot.html'; ?>


