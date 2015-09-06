
<div class="refreshable list" data-model="activity">
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

     <?php 
       /*<thead>
        <tr>        
          <th class="corner-left col-xs-6" width="">Descripció del procés </th>
          <th class="corner-right col-xs-3">Pautes d'observació </th>
          <th class="corner-right col-xs-3">Valoració </th>        
        </tr>
      </thead> */
      ?>

      <tbody class="ajax-mask">  

      <?php for( $i = 0; $i < count( $results->data ); $i++ ) : ?>

            <tr class="context relative title" data-toggle="context" data-target="#context-menu" data-model="activity" data-id="<?php echo $results->data[$i]['id']; ?>">
              <td colspan='3'>
                <div class="ws-activity"draggable="true" data-id="<?php echo $results->data[$i]['id']; ?>"><?php echo $results->data[$i]['activity_name']; ?>

                <?php /*<div class="row-controls">
                  <span class="glyphicon glyphicon-eye-open"></span>
                  <span class="glyphicon glyphicon-pencil"></span>
                  <span class="glyphicon glyphicon-remove"></span>
                  <span class="glyphicon glyphicon-print"></span>
                </div>
                */ ?>

                </div>
                <div class="info">
                  <?php 
                  echo 
                    enumerate_types($results->data[$i]['types']) . 
                    enumerate_ages($results->data[$i]['ages']) . 
                    enumerate_song_themes($results->data[$i]['song_themes']) . 
                    enumerate_materials($results->data[$i]['materials']) . 
                    enumerate_songs($results->data[$i]['song_name']);
                  ?>
                </div>

              </td>
            </tr>
            <tr class="context relative content" data-toggle="context" data-target="#context-menu" data-model="activity" data-id="<?php echo $results->data[$i]['id']; ?>">
                  <td><div><div class="heading">Descripció del procés</div><div class="column-content hover expand"><?php echo  nl2br($results->data[$i]['description']); ?></div></div></td>
                  <td><div><div class="heading">Pautes d'observació</div><div class="column-content hover expand"><?php echo  nl2br($results->data[$i]['observations']); ?></div></div></td>
                  <td><div><div class="heading">Valoració</div><div class="column-content hover expand"><?php echo  nl2br($results->data[$i]['assesment']); ?></div></div></td>
                  <?php /*<td><?php echo $results->data[$i]['keywords']; ?></td> */ ?>
                   
            </tr>
      <?php endfor; ?>


      </tbody>
    </table>
  </div>
  <?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?> 

</div>