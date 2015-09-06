
<div class="refreshable list" data-model="scheduling">
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
    
      <tbody class="ajax-mask">  

      <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>

            <tr class="context relative title" data-toggle="context" data-target="#context-menu" data-model="scheduling" data-id="<?php echo $results->data[$i]['id']; ?>">
              <td colspan='2'>
                <div class="ws-workshop" draggable="true" data-id="<?php echo $results->data[$i]['id']; ?>">
                  <?php echo 'Planificació de ' . $ages[$results->data[$i]['age']]; ?> 
                  <?php echo ' anys pel periode del ' . $results->data[$i]['scheduling_date_start'] . ' al ' . $results->data[$i]['scheduling_date_end']; ?> 
                </div>
              </td>
            </tr>
            <tr class="context relative content" data-toggle="context" data-target="#context-menu" data-model="scheduling" data-id="<?php echo $results->data[$i]['id']; ?>">
                  <td><div class="heading">Observacions</div><div class="column-content hover expand"><?php echo  nl2br($results->data[$i]['observations']); ?></div></td>
                  <td><div class="heading">Comentaris</div><div class="column-content hover expand"><?php echo  nl2br($results->data[$i]['comments']); ?></div></td>
            </tr>
      <?php endfor; ?>


      </tbody>
    </table>
  </div>
  <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?> 

</div>