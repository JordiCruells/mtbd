<?php 

function form_track($song=array('name' => '', 'duration' => '', 'file' => '')) {

$song_name = htmlentities($song['name'], ENT_QUOTES, 'UTF-8');
$song_duration = htmlentities($song['duration'], ENT_QUOTES, 'UTF-8');
$song_file = htmlentities($song['file'], ENT_QUOTES, 'UTF-8');


echo <<<EOT
    <div class="row form-track">
      <div class="col-xs-6">
        <div>
          <label  class="input">Nom de la pista</label>
        </div> 
        <input class="form-control" type="text" name="song_name[]" placeholder="Nom de la pista"  title="Emplena aquest camp" x-moz-errormessage="Emplena aquest camp" value="$song_name"/>
      </div>
      <div class="col-xs-1">
        <div>
          <label  class="input">Durada</label>
        </div> 
          <input class="form-control" type="text" name="song_duration[]" placeholder="mm:ss"  title="Durada en minuts i segons (mm:ss)" x-moz-errormessage="Durada en minuts i segons (mm:ss)" value="$song_duration" />
        </div>
      <div class="col-xs-5">
         <div>
          <label  class="input">Arxiu</label>
         </div>
         <input class="form-control" type="file" name="song_file[]" placeholder="Arxiu" value="$song_file"/>
      </div>
    </div>   
EOT;
}
?>
