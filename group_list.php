
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
require_once 'GroupPaginator.class.php';



$c = new Connection();
$conn = $c->getConnection();

/*$limit      = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 10;
$page       = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
$links      = ( isset( $_GET['links'] ) ) ? $_GET['links'] : 7;
$expanded   = ( isset( $_GET['expanded'] ) ) ? $_GET['expanded'] : 'on';*/

$limit       = from_get('limit', 10);
$page        = from_get('page',1);
$links       = from_get('links', 7);
$expanded    = from_get('expanded', 'on');
$search_date = from_post_or_get('search_date', '');



$query = "SELECT DISTINCT A.id, A.name, A.age, A.date_start, A.date_end, A.location, A.observations, A.comments, A.timestamp FROM wp_musicteach_group A ";

$conn->set_charset("utf8");

$groupPaginator  = new GroupPaginator($conn,$query,$search_date);

$results = $groupPaginator->getData($limit,$page);

include 'head.html'; ?>

<h1 class="text-center">Grups</h1>

<h2>Cerca</h2>
<div>
  <form action="group_list.php" class="search" method="get">
    <div class="row">
      <div class="col-xs-2">
        <div> 
          <label  class="input">Per data</label>
          <input type="date"  placeholder="Buscar per data" name="search_date" class="form-control" placeholder="AAAA-MM-DD" 
                 title="Format: AAAA-MM-DD" x-moz-errormessage="Format: AAAA-MM-DD" pattern="\d{4}\-\d{2}\-\d{2}" step="1" min="2015-01-01" max="2020-12-31" value="<?php echo $search_date; ?>" />
        </div>
      </div>
      <div class="col-xs-1 text-center">
         <button class="btn btn-primary" type="submit">Buscar <span class="glyphicon glyphicon-search"></span></button>
      </div>
    </div>
    <input type="hidden" name="page" value="1"/>    
    <input type="hidden" name="limit" id ="list-limit-id" value="<?php echo $limit; ?>"/>
    <input type="hidden" name="expanded" id="list-expanded-id" value="<?php echo $expanded; ?>"/>
    <input type="hidden" name="r" value="<?php echo mt_rand(0, 9999999); ?>"/>
  </form>
</div>

<br>

<div id="context-menu">
  <ul class="dropdown-menu" role="menu">
    <li>
      <a tabindex="-1"  data-action="view">Veure</a>
    </li>
    <li>
      <a tabindex="-1"  data-action="update">Modificar</a>
    </li>
    <li>
      <a tabindex="-1" data-action="delete">Eliminar</a>
    </li>
  </ul>
</div>

<?php echo $groupPaginator->createLinks( $links, 'pagination pagination-sm' ); ?> 

<div class="table-responsive table-bordered">
  <table class="table list expanded">

    <thead>
      <tr>        
        <th class="corner-left col-xs-1" width="">Data d'inici </th>
        <th class="corner-right col-xs-1">Data de finalització </th>
        <th class="corner-right col-xs-2">Ubicació </th>        
        <th class="corner-right col-xs-4">Pautes d'observació </th>        
        <th class="corner-right col-xs-4">Comentaris </th>        
      </tr>
    </thead>
    <tbody>  

  <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>

          <tr class="context relative title" data-toggle="context" data-target="#context-menu" data-model="group" data-id="<?php echo $results->data[$i]['id']; ?>">
            <td colspan='5'>
              <div><?php echo $results->data[$i]['name']; ?></div>
            </td>
          </tr>

          <tr class="context relative content" data-toggle="context" data-target="#context-menu" data-model="group" data-id="<?php echo $results->data[$i]['id']; ?>">
                <td><?php echo $results->data[$i]['date_start']; ?></td>
                <td><?php echo $results->data[$i]['date_end']; ?></td>
                <td><?php echo $results->data[$i]['location']; ?></td>
                <td><?php echo reduce_text($results->data[$i]['observations'], 300); ?></td>
                <td><?php echo reduce_text($results->data[$i]['comments'], 300); ?></td>                 
          </tr>
  <?php endfor; ?>


    </tbody>
  </table>
</div>
<?php echo $groupPaginator->createLinks( $links, 'pagination pagination-sm' ); ?> 



<?php include 'foot.html'; ?>

