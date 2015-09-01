  <form action="activity_list.php" class="search" method="get">

    <div class="row">
      <div class="col-xs-4 xs-low-pad">
        <div> 
          <label  class="input">Per paraula o frase</label>
          <input type="text"  placeholder="Buscar per paraula o frase" name="search_string" class="form-control" value="<?php echo $search_string; ?>"/>
        </div>
   
        <div class="row order" style="margin-top:10px">
          <div class="col-xs-12 col-ld-3"><label>Ordena per</label></div>         
          <div class="col-xs-7 col-ld-5 xs-low-pad">
            <input type = "radio"
                   name = "search_order"
                   value = "changed"
                   id="sort-changed-id"
                   <?php echo ($search_order === 'changed' || $search_order === '') ? ' checked' : ''; ?> />
            <label for = "sort-changed-id">Darrers</label>
          </div>  
            
          <div class="col-xs-5 col-ld-3 xs-low-pad">
            <input type = "radio"
                   name = "search_order"
                   value = "name"
                   id="sort-name-id" 
                   <?php echo ($search_order === 'name') ? ' checked' : ''; ?> />
            <label for = "sort-name-id">Títol</label>
         </div>      

        </div>
         
      </div> 
     <div class="col-xs-3 xs-low-pad">
       <label class="control-label input">Per tipus d'activitat</label>
       <div>
       <select name="search_types[]" class="form-control" multiple>
        <?php options($types, $search_types); ?>
       </select> 
       </div>       
     </div>

     <div id="age-select" class="col-xs-2 col-ld-1 xs-low-pad">
       <label class="control-label input">Per edats </label>
       <div>
       <select name="search_ages[]"  class="form-control" multiple>
        <?php options($ages, $search_ages); ?>
       </select> 
       </div>
     </div>

     <div class="col-xs-3 xs-low-pad">
       <label class="control-label input">Per temàtica </label>
       <div>
       <select name="search_song_themes[]"  class="form-control" multiple>
        <?php options($song_themes, $search_song_themes); ?>
       </select> 
       </div>
     </div>
    
    <?php /*<div class="col-xs-1 text-center">
       <button class="btn btn-primary" type="submit">Buscar <span class="glyphicon glyphicon-search"></span></button>
     </div> */
    ?> 

    </div>
    
    <input type="hidden" name="page" value="1"/>    
    <input type="hidden" name="limit" id ="list-limit-id" value="<?php echo $limit; ?>"/>
    <input type="hidden" name="expanded" id="list-expanded-id" value="<?php echo $expanded; ?>"/>
    <input type="hidden" name="r" value="<?php echo mt_rand(0, 9999999); ?>"/>
    
  </form>