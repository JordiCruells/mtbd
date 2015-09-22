
;(function($, musTeach, window, document, undefined) { 

   // External module dependecies
   var config = musTeach.config,
       appendQueryParams = musTeach.appendQueryParams,
       ajaxRefresh = musTeach.ajaxRefresh,
       formValues = musTeach.formValues;
       //bootUp = musTeach.bootUp;

   var listsEvents = musTeach.listsEvents = musTeach.listsEvents || function(opts) {

      var thisObject = {},
          searchForm,
          refreshListForm,
          $switchListMode,
          model; 

      var getRefreshURL = function(queryString) {
        return config.getPath(model, 'list', queryString);
      };

      thisObject.init = function(rootNode, initiators, $modal) { // Initiators are methods which init method must run after a new fresh list has beeen loaded

          if (!rootNode || !rootNode.querySelector || !$modal.length) return; // It's not a node , nothing to do
          
          //bootUp = bootUp || musTeach.bootUp;

          searchForm = rootNode.querySelector('form.search');
          refreshListForm = rootNode.querySelector('.pagination.form');
          model = rootNode.getAttribute('data-model');
          $rootNode = $(rootNode);

          // .refresh-list buttons
          $rootNode.find('.refresh-list').click(function(e) {

            e.preventDefault();
            $refresh =  $rootNode.find('.refreshable.list');
            var queryString = appendQueryParams($(searchForm).serialize(), formValues(refreshListForm));
            ajaxRefresh(getRefreshURL(queryString), $refresh, function() {
              thisObject.init(rootNode, initiators, $modal); // initialize list and search form events
              initiators.forEach(function(initiator) { // initialize other events or features
                 initiator.init(rootNode);
              });
            });

          });

          // pagination links must be followewd in ajax
          $rootNode.find('.pagination a').click(function(e) {
            e.preventDefault();
            var queryString =  $(this).attr('href').substring(1);
            $refresh =  $rootNode.find('.refreshable.list');
            ajaxRefresh(getRefreshURL(queryString), $refresh, function() {
              thisObject.init(rootNode, initiators, $modal); // initialize list and search form events
              initiators.forEach(function(initiator) { // initialize other events or features
                 if (initiator && initiator.init) {
                    initiator.init(rootNode);
                 }
              });
            });
            return false;
          });

              
          $rootNode.find('.list-limit-change').change(function() {
             searchForm.elements.namedItem('limit').value = $(this).val();
          });

          // Hide or show detailed info in lists and update listsExpandedInfo  value
          $switchListMode = $rootNode.find(".switch").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
            console.log('toggle');
            refreshListForm.elements.namedItem('expanded').value = state ? 'on' : 'off';
            searchForm.elements.namedItem('expanded').value = refreshListForm.elements.namedItem('expanded').value;
            console.log('value ' + refreshListForm.elements.namedItem('expanded').value);
            $rootNode.find('table.list').toggleClass('expanded');      
          });

          // Initialize show mode after page loaded
          if ($switchListMode.length && !($switchListMode.bootstrapSwitch('state'))) {
              $('table.list').removeClass('expanded'); 
          }        

          // Context menu selected row in a list
          $rootNode.find('.context').contextmenu({
            target:'#context-menu', 
            //before: function(e,context) {
              // execute code before context menu if shown
            //},
            onItem: function(context,e) {

              var id = $(context).data('id');
              var model = $(context).data('model');
              var action = $(e.target).data('action');
 
              switch(action) {

                  case 'view':
                  case 'update':

                     // Open modal and intialize it 
                     $body = $modal.find('.modal-body');
                     $body.load(config.getPath(model, action, id), function (response, status, xhr) {
                        if (status == "success") {
                          $modal.modal({ show: true });
                          //bootUp($body[0]);
                        }
                     });
                     break;

                  case 'delete':
                     if (confirm("Segur que vols eliminar el registre seleccionat ?")) {
                        window.location.href = config.getPath(model, 'delete', id);
                     }
                     break;
              }
            }
          });

          //Mouse click action over rows
          $rootNode.find('.context').click(function() {
              var id = $(this).data('id');
              var model = $(this).data('model');
              //window.location.href = config.getPath(model, 'view', id);
              window.open(config.getPath(model, 'view', id));
          });
          

          // Hover/unhover tr.title & tr.content as a single block
          $rootNode.find('tr.title, tr.content').hover(
            function(evt) {
              $(this).addClass('hovered');
              if ($(this).hasClass('title')) {
                $(this).next().addClass('hovered');
              } else {
                $(this).prev().addClass('hovered');
              } 
            },
            function(evt) {
             $(this).removeClass('hovered');
              if ($(this).hasClass('title')) {
                  $(this).next().removeClass('hovered');
              } else {
                  $(this).prev().removeClass('hovered');
              } 
            }
          );


          return thisObject;
      };

      return thisObject;
    }
}($, window._musTeach, window, document));
