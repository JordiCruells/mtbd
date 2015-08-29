(function($) {

  $(document).ready(function(){

    var config = window._musTeach.config,
        appendQueryParams = window._musTeach.appendQueryParams,
        toggleDraggable = window._musTeach.toggleDraggable,
        observeDOMAppend = window._musTeach.observeDOMAppend,
        formValues = window._musTeach.formValues,
        listsEvents = window._musTeach.listsEvents(),
        DragAndDrop = window._musTeach.DragAndDrop,
        togglePanels = window._musTeach.togglePanels;
        

    var dragAndDrop = [], // Stored drag and drop operations
        panels = togglePanels();
        
            
    
    // START UPDATE EVENT LISTENERS AT DOM CHANGE
    observeDOMAppend( document.querySelector('body > .container') ,function(e){ 
        // Activate toggle draggable inside new nodes appended to the DOM
        $(e.target).find('textarea, input, td, p, h1, h2, h3, h4, h5, h6').andSelf().filter('textarea, input, td, p, h1, h2, h3, h4, h5, h6').on('focus blur', toggleDraggable);
        //$(e.target).find('.ws-block').andSelf().filter('.ws-block').click(toggleRight('show', $('#search-box'), $('.toggle-right-shrink')));
        panels.init($(e.target));
    });
    // END UPDATE EVENT LISTENERS AT DOM CHANGE


    // INITIALIZE SEARCH FORM AND LIST EVENTS
    //----------------------------------------
    listsEvents.init($('.form-list'));
 
    // --------------------------
    // END 


    // START DRAG'N DROP EVENTS
    // --------------------------
    // Check browser has drag'n drop API first
    if (Modernizr.draganddrop) {

      dragAndDrop['block'] = DragAndDrop({
        dragStartSelector: '.ws-block', 
        dropInSelector: '.ws-block-container',
        getDragSrc: function(el) { return el.parentElement; },
        dragStartCB: function(el) {
          el.parentElement.removeChild(el);
        },
        dropInCB: function($el, dest, src) {
          
          console.log('destt ' + dest + dest.className);
          console.log('$el ' + $el.html());
          console.log('src ' + dest + src.className);
          console.log('id ' + dest.parentElement.parentElement.id);

          if (dest.parentElement.parentElement.id === 'ws-blocks-store') {
              $el.find('.ws-activity-wrapper').remove(); // When droppin out a block, also unlink all activities within it
              $el.find('.ws-block').removeClass('toggle-panel-click').removeAttr('data-toggle-panel-id');
          } else {
            // Add this class in order to toggle search box panel when clickin a selecte block
            $el.find('.ws-block').addClass('toggle-panel-click').attr('data-toggle-panel-id', 'search-box');
          }

          $el.insertBefore(dest.parentElement);
          dragAndDrop['block'].initialize($el.get(0));
          dragAndDrop['activity'].initialize($el.get(0)); // Activate events for the new .ws-activity-container created div 
        }
      }).initialize();

      dragAndDrop['activity'] = DragAndDrop({

        dragStartSelector: '.ws-activity', 
        dropInSelector: '#ws-blocks .ws-activity-container',
        getDragSrc: function(el) { 
          if ($(el).parent().hasClass('ws-activity-wrapper')) {
            return el.parentElement; 
          } else {
            return el;
          }
        },
        dragEndCB: function(el) {
          if ($(el).hasClass('ws-activity-wrapper')) $(el).remove();
        },
        dropInCB: function($el, dest, src) {          
          var isOld = $(src).parent().hasClass('ws-activity-wrapper'),
              parentBlock = dest.parentElement.parentElement.parentElement,
              blockId = parentBlock.getAttribute('data-id'),
              activityId = $el.data('id') || $el.find('.ws-activity').data('id'),
              $node, 
              $actContainer, 
              $input;

          //console.log('is old ' + isOld);
          //console.log('activityId ' + activityId);
          //console.log('blockId ' + blockId);
          
          if (!isOld) {
            $actContainer = $('<div class="ws-activity-container" draggable="true"><div>');
            $input = $('<input type="hidden" name="activity[' + blockId + '][' + activityId + ']" value="1">');
            $node = $('<div class="ws-activity-wrapper" draggable="true"></div>');                        
            $node.prepend($el).prepend($input).prepend($actContainer);
          } else {
            $input = $el.find('input').attr('name', 'activity[' + blockId + '][' + activityId + ']');
            $node = $el;
          }

          $node.insertBefore(dest.parentElement);
          dragAndDrop['activity'].initialize($node.get(0));
        }
      }).initialize();

      console.log('her ' + dragAndDrop['activity']);
      
      //dragAndDrop.map(function(dragAndDropOp) { console.log('draganddrop ' + dragAndDropOp); dragAndDropOp.initialize()} );
        
    } else {
      alert('Aquest navegador no està recomanat, utiltza la última versió de Firefox o de Chrome');
    }

    // The body element has the draggable attribute (in order to capture drag end events in the whole document and do somtehing useful with it) 
    // In order to enable text selection within some text elements the draggable attribute must be temporarily 'deactivated'
    $('textarea, input, td, p, h1, h2, h3, h4, h5, h6').on('focus blur', toggleDraggable);

    // END 
  
 
    // PANELS
    panels.init();
    // END


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
