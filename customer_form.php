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

$action = isset($_GET['action']) ? $_GET['action'] : 'new';
$id = isset($_GET['id']) ? $_GET['id'] : -1;

if ($action !== 'update' && $action !== 'new') {
  die ('opció invalida');
  exit;
}


if ($action === 'update') {

  /*if ($id <= 0) {
    die 'if no valid'; exit;    
  }*/

  $c = new Connection();
  $conn = $c->getConnection();
  $customerDAO = new CustomerDAO($conn);
  $customer = $customerDAO->select($id);

} else {
  $customer = array(
          'id' => -1,
          'name' => '',
          'email' => '',
          'phone' => '',
          'date_end' => '',
          'participants' => '',
          'observations' => ''       
  );
}


$title = ($action === 'new') ? 'Nova família' : 'Modificar família';

include 'head.php'; 

?>


<br>

<h1 class="text-center"><?php echo $title; ?></h1>
<br>

<form action="customer.php" method="POST">

  <input type="hidden" name="action" value="<?php echo $action; ?>" /> 
  <input type="hidden" name="id" value="<?php echo $id; ?>" />

  <div class= "row">

    
    <div class="col-xs-12">

      <div class="controls form-inline">

          <div>
            <label>Nom de la mare/pare</label>
          </div>
          <div>
            <input class="form-control" type="text" name="name" size="70" placeholder="Nom de la mare/pare" required title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp"
            <?php html_value($customer['name']); ?>/>
          </div>

          <div>
            <label>Nen/a</label>
          </div>
          <div>
            <input class="form-control" type="text" name="participants" size="70" placeholder="Nen/a" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp"
            <?php html_value($customer['participants']); ?>/>
          </div>

          <div>
            <label>Email</label>
          </div>
          <div>
            <input class="form-control" type="email" name="email" size="70" placeholder="Email" required title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp"
            <?php html_value($customer['email']); ?>/>
          </div>

          <div>
            <label>Telèfon</label>
          </div>
          <div>
            <input class="form-control" type="text" name="phone" size="20" placeholder="Telèfon" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp"
            <?php html_value($customer['phone']); ?>/>
          </div>

          

          <div>
            <label>Observacions</label>
          </div>
          <div>
            <textarea class="form-control" name="observations" placeholder="Observacions" rows="10" cols="120">
             <?php echo nl2br($customer['observations']); ?>
             </textarea>
          </div>

          <br>

      </div>

    </div>



  <br>

  <div class="text-center">
    <button class="btn btn-primary" type="submit">Guardar <span class="glyphicon glyphicon-ok"></span></button>
    <button class="btn btn-info btn-back" type="button">Tornar</span></button>  
  </div>

  <br>

</form>

</div>

<?php include 'foot.php'; ?>


