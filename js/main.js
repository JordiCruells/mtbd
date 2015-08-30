(function($) {

  $(document).ready(function(){

    var config = window._musTeach.config,
        appendQueryParams = window._musTeach.appendQueryParams,
        toggleDraggable = window._musTeach.toggleDraggable,
        observeDOMAppend = window._musTeach.observeDOMAppend,
        formValues = window._musTeach.formValues,
        listsEvents = window._musTeach.listsEvents(),
        DragAndDropManager = window._musTeach.DragAndDropManager(),
        DragAndDrop = window._musTeach.DragAndDrop,
        panels = window._musTeach.togglePanels();
        
            
    
    // START UPDATE EVENT LISTENERS AT DOM CHANGE
    observeDOMAppend( document.querySelector('body > .container') ,function(e){ 
        // Activate toggle draggable inside new nodes appended to the DOM
        $(e.target).find('textarea, input, td, p, h1, h2, h3, h4, h5, h6').andSelf().filter('textarea, input, td, p, h1, h2, h3, h4, h5, h6').on('focus blur', toggleDraggable);
        //$(e.target).find('.ws-block').andSelf().filter('.ws-block').click(toggleRight('show', $('#search-box'), $('.toggle-right-shrink')));
        panels.init($(e.target));
    });
    // END UPDATE EVENT LISTENERS AT DOM CHANGE


    // DRAG'N DROP
    // --------------------------
    // First check if the browser has the drag'n drop API
    if (Modernizr.draganddrop) {           
      DragAndDropManager.registerManagerFor('blocks').registerManagerFor('activitys').init(document);
    } else {
       alert('Aquest navegador no està recomanat, utiltza la última versió de Firefox o de Chrome');
    }


    // INITIALIZE EVENTS FOR SEARCH FORMS AND LISTS
    //----------------------------------------
    listsEvents.init(document.querySelector('.form-list'), [DragAndDropManager] ); 
    // --------------------------
    //


    // The body element has the draggable attribute in order to be able to capture drag end events in the whole document
    // If we want to enable text selection within some text elements, the draggable attribute must be temporarily 'deactivated'
    $('textarea, input, td, p, h1, h2, h3, h4, h5, h6').on('focus blur', toggleDraggable);
  
 
    // PANELS
    panels.init();
    //


    // ACTIVITY FORM
    // --------------------------
    // Dinamically add or remove song tracks to the activty form
    $('form .add-track-link').click(function() {
       $('#form-track').children('div').clone().insertBefore($(this).parent().parent()); 
       $('form .remove-track-link').show();
    });

    $('form .remove-track-link').click(function() {
       $formTracks =  $('form .form-track');
       var length =  $formTracks.length;
       $formTracks.last().remove(); 
       if (length === 1) {
          $(this).hide();
       }
    });

    // END

    // GLOBAL EVENTS
    // --------------------------
    // Buttons acting as links
    $('button.link').click(function() {
        var link = $(this).data('link');
        window.document.location = link;
    });

    //Back buttons
    $('.btn-back').click(function() {
        window.document.location = document.referrer || 'activity_list.php';
    });

    // END GLOBAL EVENTS

  });

} (jQuery) );
