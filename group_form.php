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
require_once 'GroupDAO.class.php';

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
  $GroupDAO = new GroupDAO($conn);
  $group = $GroupDAO->select($id);

} else {
  $group = array(
          'id' => -1,
          'name' => '',
          'age' => '',
          'date_start' => '',
          'date_end' => '',
          'location' => '',
          'observations' => '',
          'comments' => ''          
  );
}


$title = ($action === 'new') ? 'Nou grup' : 'Modificar grup';

include 'head.html'; 

?>


<br>
<div class="row">

<h1 class="text-center"><?php echo $title; ?></h1>
<br>

<form action="group.php" method="POST">

  <input type="hidden" name="action" value="<?php echo $action; ?>" /> 
  <input type="hidden" name="id" value="<?php echo $id; ?>" />

  <div class= "row">

    <div class="col-xs-1">
     <label class="input"> Edat:</label>     
     <div class="fieldset">
          <?php radiobuttons($ages,'age', $group['age'], ''); ?>       
     </div>
    </div>  

    <div class="col-xs-11">

      <div class="row">
        <div class="col-xs-12">
          <div>
            <label  class="input">Nom del grup</label>
          </div>
          <div>
            <input class="form-control" type="text" name="name" placeholder="Nom del grup" required title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp"
            <?php html_value($group['name']); ?>/>
          </div>
        </div>
      </div>

      <br>

      <div class="row">
        
        <div class="col-xs-6">
          <div>
             <label  class="input">Ubicació</label>
          </div>
          <input class="form-control" type="text" name="location" placeholder="Ubicació de l'espai"  title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp"
          <?php html_value($group['location']); ?> required/>
        </div>

        <div class="col-xs-3">
            <div>
               <label  class="input">Data d'inici</label>
            </div>
            <input class="form-control" type="date" name="date_start" placeholder="Data d'inici" title="Format: AAAA-MM-DD" x-moz-errormessage="Format: AAAA-MM-DD" step="1" min="2015-01-01" max="2020-12-31"
            <?php html_value($group['date_start']); ?> pattern="\d{4}\-\d{2}\-\d{2}" required />
        </div>
        
        <div class="col-xs-3">
            <div>
               <label  class="input">Data de finalització</label>
            </div>
            <input class="form-control" type="date" name="date_end" placeholder="Data de finalització" title="Format: AAAA-MM-DD" x-moz-errormessage="Format: AAAA-MM-DD" step="1" min="2015-01-01" max="2020-12-31"
            <?php html_value($group['date_end']); ?>  pattern="\d{4}\-\d{2}\-\d{2}" required />
        </div>

      </div>

    </div>


  </div>

  <br>

 
  <div class="row">

      <div class="col-xs-6">
        <div>
          <label class="input">Pautes d'observació</label>
        </div>
        <textarea class="form-control" name="observations" rows="4" placeholder="Pautes d'observació" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $group['observations']; ?></textarea>
      </div>

      <div class="col-xs-6">
        <div>
          <label class="input">Comentaris</label>
        </div>
        <textarea class="form-control" name="comments" rows="4" placeholder="Comentaris" title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" ><?php echo $group['comments']; ?></textarea>
      </div>

  </div>


  <br>

  <div class="text-center">
    <button class="btn btn-primary" type="submit">Guardar <span class="glyphicon glyphicon-ok"></span></button>
    <button class="btn btn-info btn-back" type="button">Tornar</span></button>  
  </div>

</form>

</div>

<?php include 'foot.html'; ?>


