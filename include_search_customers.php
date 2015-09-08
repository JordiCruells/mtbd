  <form action="customer_list.php" class="search form-horizontal" method="get">
   
      <div class="controls form-inline">

            <label class="control-label"> &nbsp; Nom mare / pare</label>
            <input class="form-control" type="text" name="search_name" placeholder="Nom mare / pare" title="Nom mare / pare" x-moz-errormessage="Nom mare / pare" 
             value="<?php echo $search_name; ?>" />

            <label class="control-label"> &nbsp; Nom nen / nena</label>
            <input class="form-control" type="text" name="search_participants" placeholder="Nom nen / nena" title="Nom nen / nena" x-moz-errormessage="Nom nen / nena" 
             value="<?php echo $search_participants; ?>" />
            
     </div>
    
    <input type="hidden" name="page" value="1"/>    
    <input type="hidden" name="limit" id ="list-limit-id" value="<?php echo $limit; ?>"/>
    <input type="hidden" name="expanded" id="list-expanded-id" value="<?php echo $expanded; ?>"/>
    <input type="hidden" name="r" value="<?php echo mt_rand(0, 9999999); ?>"/>
    
  </form>