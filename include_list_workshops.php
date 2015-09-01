
<div class="refreshable list" data-model="workshop">
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
      <li>
        <a tabindex="-1" data-action="copy">Copiar</a>
      </li>
      <li>
        <a tabindex="-1"  data-action="workshops">Veure tallers</a>
      </li>
      <li class="divider"></li>
      <li>
        <a tabindex="-1"  data-action="print">Imprimir</a>
      </li>
    </ul>
  </div>

  <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?> 

  <?php list_controls($limit, $expanded); ?>

  <div class="table-responsive table-bordered">

    <div class="ajax-loader"><div></div></div>
    <table class="table list expanded">

      <thead>
        <tr>        
          <th class="corner-left col-xs-8" width="">Observacions </th>
          <th class="corner-right col-xs-4">Comentaris </th>
        </tr>
      </thead>

      <tbody class="ajax-mask">  

      <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>

            <tr class="context relative title" data-toggle="context" data-target="#context-menu" data-model="workshop" data-id="<?php echo $results->data[$i]['id']; ?>">
              <td colspan='2'>
                <div class="ws-workshop" draggable="true" data-id="<?php echo $results->data[$i]['id']; ?>"><?php echo $results->data[$i]['group_name'] . ' &nbsp;&nbsp; <label class="fill rounded blue">' . $results->data[$i]['workshop_date'] .'</span>' ; ?>
                </div>
                <div class="info">
                  <?php 
                  echo 
                    enumerate_ages($results->data[$i]['age']);
                  ?>
                </div>

              </td>
            </tr>
            <tr class="context relative content" data-toggle="context" data-target="#context-menu" data-model="activity" data-id="<?php echo $results->data[$i]['id']; ?>">
                  <td><div class="hover expand"><?php echo  nl2br($results->data[$i]['observations']); ?></div></td>
                  <td><div class="hover expand"><?php echo  nl2br($results->data[$i]['comments']); ?></div></td>
            </tr>
      <?php endfor; ?>


      </tbody>
    </table>
  </div>
  <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?> 

</div>