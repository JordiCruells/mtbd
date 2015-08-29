
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
  <table class="table list expanded">

    <thead>
      <tr>        
        <th class="corner-left col-xs-6" width="">Descripció del procés </th>
        <th class="corner-right col-xs-3">Pautes d'observació </th>
        <th class="corner-right col-xs-3">Valoració </th>        
        <?php /*<th class="corner-right">Paraules clau </th> */ ?>
        
      </tr>
    </thead>
    <tbody>  

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
                <td><div class="hover expand"><?php echo  nl2br($results->data[$i]['description']); ?></div></td>
                <td><div class="hover expand"><?php echo  nl2br($results->data[$i]['observations']); ?></div></td>
                <td><div class="hover expand"><?php echo  nl2br($results->data[$i]['assesment']); ?></div></td>
                <?php /*<td><?php echo $results->data[$i]['keywords']; ?></td> */ ?>
                 
          </tr>
  <?php endfor; ?>


    </tbody>
  </table>
</div>
<?php echo $Paginator->createLinks( $links, 'pagination pagination-sm' ); ?> 