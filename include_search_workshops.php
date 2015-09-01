  <form action="workshop_list.php" class="search form-horizontal" method="get">
   
      <div class="controls form-inline">

            <label class="control-label">Per grup</label>
            <select name="search_group"  class="form-control">
              <option value="0">Selecciona un grup</option>
              <?php options($groups, array($workshop['group_id'])); ?>
            </select> 
            
            <label class="control-label"> &nbsp; Des de la data</label>
            <input class="form-control" type="date" name="search_from_date" placeholder="Data a partir de la que buscar" title="Format: AAAA-MM-DD" x-moz-errormessage="Format: AAAA-MM-DD" step="1" min="2015-01-01" max="2020-12-31"
             value="<?php echo $search_from_date; ?>" pattern="\d{4}\-\d{2}\-\d{2}" />
            
            
            <label class="control-label"> &nbsp; Fins la data</label>
            <input class="form-control" type="date" name="search_to_date" placeholder="Data fins la que buscar" title="Format: AAAA-MM-DD" x-moz-errormessage="Format: AAAA-MM-DD" step="1" min="2015-01-01" max="2020-12-31"
                value="<?php echo $search_to_date; ?>" pattern="\d{4}\-\d{2}\-\d{2}" />
            
            <label class="control-label"> &nbsp; Tallers favorits ?</label> 
            <input type="checkbox" name="favourite" value="y" 
                 <?php echo (!empty($search_favourite) &&  $search_favourite !== 'n') ? ' checked ' : ''; ?> > 
            
            <label class="control-label"> &nbsp; Per edats </label>
            <select name="search_ages[]" class="form-control" multiple>
              <?php options($ages, $search_ages); ?>
            </select>   


     </div>


    
    <input type="hidden" name="page" value="1"/>    
    <input type="hidden" name="limit" id ="list-limit-id" value="<?php echo $limit; ?>"/>
    <input type="hidden" name="expanded" id="list-expanded-id" value="<?php echo $expanded; ?>"/>
    <input type="hidden" name="r" value="<?php echo mt_rand(0, 9999999); ?>"/>
    
  </form>