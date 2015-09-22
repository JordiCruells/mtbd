// This function initializes events and state in a given area of the document
// -----------------------------------------------------------------------------------
// Usage: call the bootUp function passing the rott node form which the initialization
// tasks must be performed 

;(function($, musTeach, window, document, undefined) { 

    // External module dependecies
    var config = musTeach.config,
        appendQueryParams = musTeach.appendQueryParams,
        ajaxRefresh = musTeach.ajaxRefresh,
        formValues = musTeach.formValues,
        enableTextSelection = musTeach.enableTextSelection,
        DragAndDrop = musTeach.DragAndDropManager
        TogglePanels = musTeach.togglePanels;

    // Private vars    
    var panelManager,
        dragAndDropManager,
        listManager,
        list,
        panel,
        form,
        $rootNode,
        $modalRef = $('#modal'),        
        modalId = 0;

    
    var bootUp = musTeach.bootUp = musTeach.bootUp || function(rootNode) {

          
      console.log('modalId ' + modalId);

       // alert('bootup ' + rootNode.className);

        if (!rootNode || !rootNode.querySelector) {
          return; 
        } 

        $rootNode = $(rootNode);        
        list = rootNode.querySelector('.form-list');
        panel = rootNode.querySelector('.toggle-panel-right');  
        form = rootNode.querySelector('form');

        var $thisModal = $rootNode.closest('.modal');
        
        
        // Clone modals in order to let multiple modals be opened simultneously
        // The last opened modal must be always visible so we append it after the previous ones
        modalId++;
        var $modal = $modalRef.clone().attr('id', 'modal-' + modalId).insertAfter($thisModal.length ? $thisModal : $modalRef);
        //loaded['$modal'] = $modal;
        //$modal = $modalRef;
        //.modal('hide'); 
        //$modal = $modalRef.modal('hide');

        //alert('cloned');

        /*if (rootNode === document) {
          alert('1');
          $modal = $('#modal');  
        } else {
          alert('2');
          $modal = $('#modal').clone();
          $rootNode.append($modal);
        }*/        


        if (panel)  {
           panelManager = TogglePanels().init(rootNode);
        }

        if (Modernizr.draganddrop) {   
            dragAndDropManager = DragAndDrop()
                                .registerManagerFor('blocks')
                                .registerManagerFor('activitys')
                                .addInitiator(panelManager)
                                .init(rootNode);
        } else {
           alert('Aquest navegador no està recomanat, utiltza la última versió de Firefox o de Chrome');
        } 

        if (list) {
            modalList = window._musTeach.listsEvents().init(list, [dragAndDropManager], $modal);    
        }

        
        // Forms submitting
        $(form).submit( function(e) {

            e.preventDefault();
            var $this = $(this);
            
            var url = $this.attr('action');

            $.ajax({
                   type: "POST",
                   url: url,
                   data:  $this.serialize(),
                   success: function(data)
                   {
                       $modal.modal('toggle'); // Submit form inside modals closes the modal
                   }
                 });

            return false;
        });


        $rootNode.find('.btn-back').click(function(e) {
            if ($thisModal.length) {
              console.log('hide' + $thisModal.length + $thisModal.attr('class'));
              $thisModal.modal('hide');              
            } else {
              window.document.location = document.referrer || 'activity_list.php';
            }
        });

        $rootNode.find('.btn-modify').click(function(e) {

            var modifyUrl = $(this).data('link');

            if ($modal.length) {              
              if (modifyUrl) {
                 $body = $modal.find('.modal-body');
                 $modal.off('hidden.bs.modal').on('hidden.bs.modal', function () {
                    $modal.data('bs.modal', null);
                    $body.load(modifyUrl, function (response, status, xhr) {
                      if (status == "success") {
                          $modal.modal({ show: true });
                      }
                    });
                }); 
                $modal.modal('hide');                
              }              

            } else {
              if (modifyUrl) {
                window.document.location = modifyUrl;
              }
            }
        });


        $rootNode.find('form .add-track-link').bind('click.addTrack', function() {
           var $newEl = $rootNode.find('#form-track').children('div').clone();
           $newEl.insertBefore($(this).parent().parent()); 
           $rootNode.find('form .remove-track-link').show();
           enableTextSelection($newEl[0]);
        });

        $rootNode.find('form .remove-track-link').bind('click.removeTrack', function() {
           $formTracks =  $('form .form-track');
           var length =  $formTracks.length;
           $formTracks.last().remove(); 
           if (length === 1) {
              $(this).hide();
           }
        });    

        enableTextSelection(rootNode);


        // Button links
        $rootNode.find('button.link').click(function() {
            var link = $(this).data('link');
            window.document.location = link;
        });


        // Initialize events inside a modal after being loaded

        $thisModal.on('hidden.bs.modal', function () {
          console.log('$thisModal hidden callback');
          $modal.remove();
          modalId--;
        });

        $modal.on('shown.bs.modal', function (e) {

              console.log('show ' + $modal.find('.modal-body')[0]);
              bootUp($modal.find('.modal-body')[0]);

             
            
        }); 

        $modal.on('hidden.bs.modal', function () {
                  console.log('hide callback');

                  

                  $modal.data('bs.modal', null);
                  //$modal.off('hidden.bs.modal');                  
                  console.log('modalId after close ' + modalId);
                  //$thisModal.modal({show: true});
                  // For someone reason unknown $thisModal has changed to scroll:hidden                  
                  $thisModal.css('overflow-y', 'scroll');  

        });

       



    };

}($, window._musTeach, window, document));    

    