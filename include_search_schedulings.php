  <form action="scheduling_list.php" class="search form-horizontal" method="get">
   
      <div class="controls form-inline">

            <label class="control-label"> &nbsp; Data</label>
            <input class="form-control" type="date" name="search_date" placeholder="Data en la que buscar" title="Format: AAAA-MM-DD" x-moz-errormessage="Format: AAAA-MM-DD" step="1" min="2015-01-01" max="2020-12-31"
             value="<?php echo $search_date; ?>" pattern="\d{4}\-\d{2}\-\d{2}" />
            
            <label class="control-label"> &nbsp; Edat </label>
            <?php radiobuttons($ages,'age', $search_age, 'inline radio'); ?>  
     </div>


    
    <input type="hidden" name="page" value="1"/>    
    <input type="hidden" name="limit" id ="list-limit-id" value="<?php echo $limit; ?>"/>
    <input type="hidden" name="expanded" id="list-expanded-id" value="<?php echo $expanded; ?>"/>
    <input type="hidden" name="r" value="<?php echo mt_rand(0, 9999999); ?>"/>
    
  </form>