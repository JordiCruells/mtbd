<?php 

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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
EOT;
}
?>
