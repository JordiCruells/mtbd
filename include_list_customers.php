
<div class="refreshable list" data-model="customer">
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
            <tr class="context relative title" data-toggle="context" data-target="#context-menu" data-model="customer" data-id="<?php echo $results->data[$i]['id']; ?>">
              <td colspan='4'>
                <div class="customer" draggable="true" data-id="<?php echo $results->data[$i]['id']; ?>"><?php echo $results->data[$i]['name']; ?></div>
                <div class="info"></div>
              </td>
            </tr>
            <tr class="context relative content" data-toggle="context" data-target="#context-menu" data-model="customer" data-id="<?php echo $results->data[$i]['id']; ?>">
                  <td><div class="heading">Email</div><div class="column-content hover expand"><?php echo $results->data[$i]['email']; ?></div></td>
                  <td><div class="heading">Telèfon</div><div class="column-content hover expand"><?php echo $results->data[$i]['phone']; ?></div></td>
                  <td><div class="heading">Nen / nena</div><div class="column-content hover expand"><?php echo $results->data[$i]['participants']; ?></div></td>
                  <td><div class="heading">Observacions</div><div class="column-content hover expand"><?php echo $results->data[$i]['observations']; ?></div></td>
            </tr>
      <?php endfor; ?>


      </tbody>
    </table>
  </div>
  <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?> 

</div>