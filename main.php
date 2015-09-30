<?php 

  require_once 'config.php';
  $siteUrl = $SITE['baseUrl'] . $SITE['prefix'];
  $sitePrefix = $SITE['prefix'];

  $types = array(
    '14' => 'So-silenci',
    '4' => 'Descoberta de sons',
    '6' => 'Música associada a un moviment',
    '2' => 'Instruments',
    '15' => 'Intensitat',
    '16' => 'Durada',
    '17' => 'Alçada',
    '20' => 'Timbre',
    '3' => 'Audició',
    '8' => 'Ritme-pulsacio',
    '10' => 'Ritme-motriu',
    '11' => 'Ritme-figures', 
    '9' => 'Ritme-moviment',
    '1' => 'Cançó',
    '18' => 'La nostra cultura',    
    '19' => 'Rituals',
    //'5' => 'Moviment',
    //'7' => 'Ritme',    
    //'12' => 'Música associada a un moviment',       
    '13' => 'No classificat'
  );

  $song_themes = array(
        '1' => "Moixaina",
        '2' => "Falda/joc de falda",
        '3' => "De bressol",
        '4' => "Mimada",
        '5' => "De diada",
        '6' => "Eliminativa",
        '7' => "Màgica / Temps i natura",
        '8' => "D’ofici",
        '9' => "De dansa/joc",
        '10' => "En anglès",
        '11' => "Instrumentada",
        '12' => "D'animals",
        '13' => "Del cos",
        '14' => "Altres cançons"
  );

  $sub_types = array(
    '1' => array(
        '1' => "Moixaina",
        '2' => "Falda/joc de falda",
        '3' => "De bressol",
        '4' => "Mimada",
        '5' => "De diada",
        '6' => "Eliminativa",
        '7' => "Màgica / Temps i natura",
        '8' => "D’ofici",
        '9' => "De dansa/joc",
        '10' => "En anglès",
        '11' => "Instrumentada",
        '12' => "D'animals",
        '13' => "Del cos",
        '14' => "Altres cançons"),
    '2' => array(
        '1' => 'Instruments categoria 1',
        '1' => 'Instruments categoria 2'
    ),
    '3' => array(
        '1' => 'Audició categoria 1',
        '1' => 'Audició categoria 2'
    ),
    '4' => array(
        '1' => 'Descoberta de sons categoria 1',
        '1' => 'Descoberta de sons categoria 2'
    ),
    /*'5' => array(
        '1' => 'Instruments categoria 1',
        '1' => 'Instruments categoria 2'
    ),*/
    '6' => array(
        '1' => 'Música associada a un moviment categoria 1',
        '1' => 'Música associada a un moviment categoria 2'
    ),
    /*'7' => array(
        '1' => 'Instruments categoria 1',
        '1' => 'Instruments categoria 2'
    ),*/
    '8' => array(
        '1' => 'Ritme-pulsacio categoria 1',
        '1' => 'Ritme-pulsacio categoria 2'
    ),
    '9' => array(
        '1' => 'De dansa/joc categoria 1',
        '1' => 'De dansa/joc categoria 2'
    ),
    '10' => array(
        '1' => 'Ritme-motriu categoria 1',
        '1' => 'Ritme-motriu categoria 2'
    ),
    '11' => array(
        '1' => 'Ritme-figures categoria 1',
        '1' => 'Ritme-figures categoria 2'
    ),
    '12' => array(
        '1' => "D'animals categoria 1",
        '1' => "D'animals categoria 2"
    ),
    '13' => array(
        '1' => 'No classificat categoria 1',
        '1' => 'No classificat categoria 2'
    ),
    '14' => array(
        '1' => 'So-silenci categoria 1',
        '1' => 'So-silenci categoria 2'
    ),
    '15' => array(
        '1' => 'Intensitat categoria 1',
        '1' => 'Intensitat categoria 2'
    ),
    '16' => array(
        '1' => 'Durada categoria 1',
        '1' => 'Durada categoria 2'
    ),
    '17' => array(
        '1' => 'Alçada categoria 1',
        '1' => 'Alçada categoria 2'
    ),
    '18' => array(
        '1' => 'La nostra cultura categoria 1',
        '1' => 'La nostra cultura categoria 2'
    )

  );


  $materialsIds = array(

// 001 a 99: instruments
// 101 a 199: materials de psicomotricitat
// 201 a 299: titelles
// 901 a 999: altres

    'Instruments' => array(
      '1' => 'Maraques',
      '2' => 'Cascavells',
      '3' => 'Panderetes',
      '4' => "Flauta d'èmbol",
      '5' => "Instruments variats",
      '6' => 'Trons'
     ),

    'Titelles' => array(
      '101' => "Caputxeta",
      '102' => "Granota"
     ),

    'Psicomotricitat' => array(
      '201' => "Tela gran",
      '202' => "Cintes",
      '203' => 'Plomes',
      '204' => 'Mocadors',
      '205' => 'Pareos',
      '206' => 'Paracaigudes',
      '207' => 'Cèrcols',
      '208' => 'Pilotes',
      
    ),
    //...
    'Altres' => array(
      '901' => "Petit teatret",
      '902' => "Canyetes"
    )    
  );

  
  $ages = array(
    '1' => '0 a 1',
    '2' => '1 a 3',
    '3' => '3 a 6',
    '4' => '> 6'
  );

  /*$workshop_blocks = array(

    '1' => 'Cançó',
    '2' => 'Audició',
    '3' => 'Relaxació',
    '4' => 'Comiat'
  );*/
  

  function from_get($key, $default) {
    return isset($_GET[$key]) ? $_GET[$key] : $default;
  }

  function from_post($key, $default) {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
  }

  function from_post_or_get($key, $default) {
    if (isset($_POST[$key])) {
      return $_POST[$key];
    } else {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        } else {
            return $default;
        }
    }
  }

function isAjaxRequest() {
   return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'); 
}

function modal($id='modal', $title='') {

$title = htmlentities($title);

echo <<<EOT
<div id="$id" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">$title</h4>
      </div>
      <div class="modal-body">        
      </div>
      <div class="modal-footer">
        <!--button type="button" class="btn btn-default" data-dismiss="modal">Close</button-->
      </div>
    </div>

  </div>
</div>
EOT;
}

  function list_controls($limit=10, $expanded='on') {

    $checked = ($expanded === 'on') ? ' checked' : '';

    echo '<form action="#" method="GET" class="pagination pagination-sm form" >' .
             ' <div class="switch-container"><input type="checkbox" name="expanded" class="switch" data-size="mini" data-on-text="Si" data-off-text="No" ' . $checked . ' /> <span class="text-primary"> &nbsp;Informació ampliada ? </span></div>'  .
             ' &nbsp; <span class="text-primary"> Mostrar </span><input class="list-limit-change" type="number" name="limit" value="' . $limit .'" size="3" min="5" max="100" step="5" /> <span class="text-primary"> ocurrències per pàgina </span>' .
             ' &nbsp; <button type="button" title="Refresca" class="refresh-list btn-primary"><span class="glyphicon glyphicon-refresh"></span></button>' . 
         '</form>'; 
  }


  function activity_wrapper($block_id, $activity, $summary=false) {

    $more = $summary ? '' : '<div class="more">' . 
                                '<div class="field description"><header>Descripció</header><div>' . nl2br($activity['description']) . '</div></div>' . 
                                '<div class="field songs"><header>Pistes</header><div>' . nl2br($activity['songs']) . '</div></div>' . 
                                '<div class="field materials"><header>Materials</header><div>' . nl2br($activity['materials']) . '</div></div>' .
                                '<div class="field observations"><header>Observacions</header><div>' . nl2br($activity['observations']) . '</div></div>' .
                             '</div>';

    echo '<div class="ws-activity-wrapper" draggable="true">' .
           '<div class="ws-activity-container" draggable="true">' .
             '<div></div>' .
           '</div>' .
           '<input type="hidden" value="1" name="activity[' . $block_id . '][' . $activity['id'] . ']">' .
           '<div class="ws-activity pastels-' . $block_id . '" data-id="' . $activity['id'] . '" draggable="true">' . $activity['activity_name'] . $more . '</div>' .
         '</div>';
  }

  function activity_remainder() {
    echo '<div class="remainder">' .
            '<div class="ws-activity-container remainder over"></div>' .
         '</div>';
  }


  function block_wrapper_start($block_id, $ord=0) {
     global $types;
     $order = $ord > 0 ? '<span class="order">' . $ord . '. </span>' : '';
     echo '<div class="ws-block-wrapper" draggable="true">' .
            '<div class="ws-block-container" draggable="true"></div>' .
            '<div class="ws-block toggle-panel-click pastels-' . $block_id. '" draggable="true" data-id="' . $block_id . '" data-toggle-panel-id="search-box">' .
              '<header class="ws-title text-center"><div>' . $order . $types[$block_id] . '</div></header>' .
              '<div class="content">';
  }

  function block_wrapper_end() {
      echo '</div></div></div>';
  }

  function block_remainder() {
    echo '<div class="remainder">' .
           '<div class="ws-block-container remainder over" draggable="true"></div>' .
         '</div>';
  }
  
  function key_compare_func($key1, $key2)
  {
      if ($key1 == $key2)
          return 0;
      else if ($key1 > $key2)
          return 1;
      else
          return -1;
  }

  function workshop_blocks($exclude_blocks) {
    //global $workshop_blocks;
    global $types;

    /*echo 'exclude_blocks ';
    print_r($exclude_blocks);*/

    $blocks = array_diff_ukey($types, $exclude_blocks, 'key_compare_func');

    //print_r($blocks); exit;

    echo '<div id="ws-blocks-store">';
    foreach ($blocks as $key => $value) {       
      echo '<div class="ws-block-wrapper" draggable="true">';
         echo '<div class="ws-block-container" draggable="true"></div>';
         echo '<div class="ws-block pastels-' . $key . '" data-id="' . $key . '" draggable="true"><header class="ws-title text-center"><div>' . $value . '</div></header><div class="content"><div class="remainder"><div class="ws-activity-container remainder"></div></div></div></div>';
      echo '</div>';
    }
    echo '<div class="remainder"><div class="ws-block-container remainder" draggable="true"></div></div></div>';
  }

  function get_type($i) {
    global $types;
    return $types[$i];
  }
  /* OBSOLETE
  function get_material($i) {
    global $materialsIds;
    return $materialsIds[$i];
  }
  */
  function get_age($i) {
    global $ages;
    return $ages[$i];
  }
  function get_song_theme($i) {
    global $song_themes;
    return $song_themes[$i];
  }
  function list_types($str) {
    if ($str == '') return '';
    $ids = explode(',', $str);
    $types = array_map('get_type',$ids);
    ul($types);
  }
  
  /*OBSOLETE
  function list_materials($str) {
    if ($str == '') return '';
    $ids = explode(',', $str);
    $materials = array_map('get_material',$ids);
    ul($materials);
  }
  }*/
  
   function list_ages($str) {
    if ($str == '') return '';
    $ids = explode(',', $str);
    $ages = array_map('get_age',$ids);
    ul($ages);
  }
  
   function list_song_themes($str) {
    if ($str == '') return '';
    $ids = explode(',', $str);
    $song_themes = array_map('get_song_theme',$ids);
    ul($song_themes);
  }

  function enumerate_types($str, $class = 'type') {
    if ($str == '') return '';
    $ids = explode(',', $str);
    $types = array_map('get_type',$ids);
    if (count($types) === '') return '';
    //return 'Característiques: ' . '<span class="text-primary">' . join(', ', $types) . '</span>';
    //return ' <span class="glyphicon glyphicon-list"></span> ' . join(', ', $types);
    return '<span class="' . $class . '"> <label class="fill rounded orange"><span class="glyphicon glyphicon-folder-open"></span></label> &nbsp;' . join(', ', $types) . "</span>";
  }
  
  function enumerate_ages($str, $class = 'age') {
    if ($str == '') return '';
    $ids = explode(',', $str);
    $ages = array_map('get_age',$ids);
    if (count($ages) === '') return '';
    //return ' | Edats: de <span class="text-primary">' . join(', ', $ages) . '</span>';
    //return ' &nbsp;<span class="glyphicon glyphicon-user"></span> de ' . join(', ', $ages);
    return '<span class="' . $class . '"> <label class="fill rounded green"><span class="glyphicon glyphicon-user"></span></label> de ' . join(', ', $ages) . "</span>";
  }
  
  function enumerate_song_themes($str, $class = 'song') {
    if ($str == '') return '';
    $ids = explode(',', $str);
    $song_themes = array_map('get_song_theme',$ids);
    
    if (count($song_themes) === '') return '';

    //return ((count($song_themes) > 1) ? ' | Temàtiques: ' : ' | Temàtica: ') . '<span class="text-primary">' . join(', ', $song_themes) . '</span>';
    //return ' &nbsp;<span class="glyphicon glyphicon-music"></span> Cançó ' . strtolower(join(', ', $song_themes));
    return '<span class="' . $class . '"> <label class="fill rounded grey"><span class="glyphicon glyphicon-picture"></span></label> cançó ' . mb_strtolower(join(', ', $song_themes), "UTF-8") . "</span>";
  
  }

  function enumerate_materials($str, $class='material') {
    if ($str == '') return '';
    
    /*$ids = explode(',', $str);
    $song_themes = array_map('get_song_theme',$ids);
    if (count($song_themes) === '') return ''; */

    return '<span class="' . $class . '"> <label class="fill rounded blue blue"><span class="glyphicon glyphicon-wrench"></span></label> ' . mb_strtolower($str, "UTF-8") . "</span>";
  
  }

  function enumerate_songs($str, $class='song') {
    if (empty($str)) return '';
    return '<span class="' . $class . '"> <label class="fill rounded red"><span class="glyphicon glyphicon-music"></span></label> '. $str . '</span>';  
  }
  

  

  function options($arr, $selected=array()) {  
    $html = '';
    if (sizeof($arr) > 0) {
      foreach ($arr as $key => $value) {
       $sel = in_array($key, $selected) ? ' selected' : '';
       $html .= '<option value="'. $key .'"' . $sel . '>' . $value . '</option>';
      }
    }
    echo $html;
  }

  function ul($arr) {
    if (sizeof($arr) > 0 && strlen($arr[0]) > 0) {
      $html = '<ul class="list-unstyled">';
      for ($i=0; $i <sizeof($arr); $i++) { 
        $html .= '<li>'.$arr[$i].'</li>';
      }
      $html .= '</ul>';
    }
    echo $html;
  }

function checkboxes($arr, $name, $checkeds='' , $required='') {

    $arrCheckeds = explode(',', $checkeds); 
    if (sizeof($arr) > 0) {
      foreach ($arr as $key => $value) {
        echo "<div style='display:inline-block'><input class='form-inline' type='checkbox' name='{$name}' value='{$key}'";
        if (in_array($key, $arrCheckeds)) {
           echo " checked ";
        }
        echo $required;
        echo "/>&nbsp{$value} &nbsp;&nbsp; </div>";
      }
    }
 }

 function radiobuttons($arr, $name, $checked='', $class='') {


    $class = empty($class) ? '' : ' class="' . $class . '"';

    if (sizeof($arr) > 0) {

      foreach ($arr as $key => $value) {
        //echo 'KEY' . $key;
        //echo 'CHECKED' . $checked;

        $check = ($key == $checked) ? " checked />" : " /> ";

        //echo 'CHECK ' . $check;

        echo "<div" . $class . "><input type='radio' name='". $name . "' value = '" . $key . "' required " . $check;
        echo "<label>" . $value . "</label></div>";
      }
    }
 }

 function html_value($val) {
   if (!empty($val)) { 
    echo " value='" . htmlentities($val, ENT_QUOTES, 'UTF-8') . "'";
  }
 }

 function reduce_text($text, $long = 340) {

    if (strlen($text) <= $long) return $text;

    $text = substr($text, 0, $long);
    $text = substr($text, 0, strrpos($text, ' ')) . " ...";
    return $text;

 }

  function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
 }


  

?>