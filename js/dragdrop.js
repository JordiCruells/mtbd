// DragAndDrop = function(opts)
//
// This function returns and object that will manage the events for performing an specific drag and drop operation.
// Multiple types of drag and drop can be performed in a single document, each of them will be handled by different instances 
// of the Drag AndDrop object
//
// DOM manipulation will be perfomed using jQuery as complex manipulation DOM is required in some cases.
// This specific DOM manipulation will be specified by callback functions supplied to the constructor of this object
//
// Arguments: opts (Object) that can contain
//   - dragStartSelector: string
//   - dropInSelector : string
//   - dragStartCB : function
//   - dropInCB : function
//   - dragEndCB : function
//   - getDragSrc : function
// Returns: object

;(function($, musTeach, window, document, undefined) { 

          var DragAndDrop = musTeach.DragAndDrop = musTeach.DragAndDrop || function DragAndDrop(opts) {

              var 
                  // Object DragAndDrop returned
                  thisObject = {},

                  // The element that originally fired the dragg start event
                  srcEl, 
                  
                  // The element that will be dragged
                  dragSrcEl,  

                  // The selector for the elements that can be dragged
                  dragStartSelector = opts.dragStartSelector || '.ws-block', 
                  
                  // the selector for the elements where the dragged elements can be dropped in
                  dropInSelector = opts.dropInSelector || '.ws-block-container', 
                  
                  // The matching source elements that can be dragged in the document
                  srcElems, 

                  // The matching elements where the element can be dropped in in the document
                  destElems,

                  // dragStartCB = function(el)
                  // It is the action to perform with the dragged source element after being dropped in (remove it, change it, ...)
                  // It will be executed within the dropin event.
                  // Arguments:
                  //  - the element that is being dragged
                  // Return: undefined
                  dragStartCB = opts.dragStartCB || function(el) {}, 
                  
                  // dropInCB = function($el, dest, src) 
                  // It is the action to perform with the dragged final element (change it, append it other elements, ...)
                  // It will be executed within the dropin event.
                  // Arguments:
                  // - $el: jQuery object constructed upon the dataTransfer object that can be manipulated or expanded before adding to the DOM
                  // - dest: the DOM element that received de drop in event and which can be taken as a reference point from where to insert or update the DOM
                  // - src: the original element that fired the drag and drop operation (the actions to be performed when droping in an element may depend
                  //        from which was the origin or state of the initial dragged element)
                  // Return: undefined
                  dropInCB = opts.dropInCB || function($el, dest, src) {}, 

                  //dragEndCB = function (el)
                  // If specified will execute custom code when the dragged element is ended out of any dropping area (event captured at the document level)
                  // Useful if we want to delete the element when dragging outside 
                  // Arguments:
                  //  - the element that is being dragged
                  // Return: undefined
                  dragEndCB = opts.dragEndCB, 
                  
                  // getDragSrc = function(el)
                  // This function indicates how to obtain the whole element that will be dragged upon the source element that fires the dragstart event
                  // If not supplied both elements will be the same. Useful when we want to move some parent container with some other invisible sibling elements
                  // It will be executed at the dragstart event.
                  // Arguments:
                  //  - the original element that fires the drag start event
                  // Return: 
                  //  - the element that will actually be dragged
                  getDragSrc = opts.getDragSrc || function (el) { return el;};
                

              // matchSrcEl: This function looks if the dragged element matches the original selector (when mixing different drag'n drop operations in the same
              // page the callback functions must be bypassed when the element does not match the selector, meaning it was initiated by another drag'n drop operation)
              // A more accurate approach had beeen to activate/deactivate events at the dragstart, dragEnd, dropin events but this approach also works :)
              var matchSrcEl = function(e) {
                  console.log('dragStartSelector:' + dragStartSelector);
                  console.log(e.dataTransfer.getData('text/html'));
                  $block = $(e.dataTransfer.getData('text/html'));
                  return $block.find(dragStartSelector).andSelf().filter(dragStartSelector).length > 0;
              };

              
              // Callback functions:
              //  - handleDragStart
              //  - handleDragOver
              //  - handleDragEnter
              //  - handleDragLeave
              //  - handleDragEnd
              //  - handleDropIn 

              var handleDragStart = function(e) {

                console.log('handleDragStart: ' + this + ' . ' + this.className); 
                console.log('e.target: ' + e.target + ' - ' + e.target.className);
                console.log('e.currentTarget: ' + e.currentTarget + ' - ' + e.currentTarget.className);
                console.log('e.bubbles: ' + e.bubbles);

                if (!(e.currentTarget === e.target)) return true;

                srcEl = this;
                dragSrcEl = getDragSrc(srcEl);
                //e.dataTransfer.effectAllowed = 'move';      
                e.dataTransfer.setData('text/html', dragSrcEl.outerHTML);
                //this.style.opacity = '0.4';

              },

              handleDragOver = function(e) {

                if (!matchSrcEl(e)) return;
                console.log('handleDragOver: ' + this + ' . ' + this.className);
                
                if (e.preventDefault) {
                  e.preventDefault(); // Necessary. Allows us to drop.
                }

                e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

                return false;
              },

              handleDragEnter = function(e) {
                
                if (!matchSrcEl(e)) return;
                
                console.log('handleDragEnter: ' + this + ' . ' + this.className);
                //console.log('dragSrcElMatchesdragStartSelector: ' + dragSrcElMatchesdragStartSelector);

               //if (!dragSrcElMatchesdragStartSelector) return;  

                // this / e.target is the current hover target.
                this.classList.add('over');
              },

              handleDragLeave = function(e) {
                
                if (!matchSrcEl(e)) return;
                
                console.log('handleDragLeave: ' + this + ' . ' + this.className);
                //console.log('dragSrcElMatchesdragStartSelector: ' + dragSrcElMatchesdragStartSelector);

                //if (!dragSrcElMatchesdragStartSelector) return;
                this.classList.remove('over');  // this / e.target is previous target element.
              },  

              handleDragEnd = function(e) {

                console.log('handleDragEnd: ' + this + ' . ' + this.className);
                console.log('handleDragEnd: ' + dragSrcEl);

                if (!matchSrcEl(e)) return;
                
                if (dragEndCB && e.currentTarget === document) {
                  dragEndCB(dragSrcEl);
                }

                /*console.log('handleDragEnd: ' + this + ' . ' + this.className);
                console.log('target ' + e.target);
                console.log('current target ' + e.currentTarget);*/
                //console.log('dragSrcElMatchesdragStartSelector: ' + dragSrcElMatchesdragStartSelector);
                //if (!dragSrcElMatchesdragStartSelector) return;
               
                // this / e.target is the source node.

                [].forEach.call(srcElems, function (srcElem) {
                  srcElem.classList.remove('over');
                });

                //srcEl.style.opacity = '1';

                //dragSrcElMatchesdragStartSelector = false;
              }, 

              handleDropIn = function(e) {

                  console.log('handleDrop: ' + this + ' . ' + this.className);  
                  if (!matchSrcEl(e)) return;
                  console.log('continue drop in');

                  
            
                  if (e.stopPropagation) {
                    e.stopPropagation(); // stops the browser from redirecting.
                  }

                  $block = $(e.dataTransfer.getData('text/html'));
                  dropInCB($block, this, srcEl);

                  console.log('dragStartCB ' + dragStartCB);
                  dragStartCB(dragSrcEl);

                  //srcEl.style.opacity = '1';
                  //console.log('dropin initialize ' + $block.html());
                  //initialize($block.get(0));

                  return false;
              },

              // registerSrcEvents: registers the event dragstart (elements matching dragStartSelector)
              registerSrcEvents =  function(el) {
                  el.addEventListener('dragstart', handleDragStart, false);
              },

              // registerDestEvents: registers the rest of events (elements matching dropInSelector)
              registerDestEvents = function(el) {
                  el.addEventListener('dragenter', handleDragEnter, false);
                  el.addEventListener('dragover', handleDragOver, false);
                  el.addEventListener('dragleave', handleDragLeave, false); 
                  el.addEventListener('drop', handleDropIn, false);
                  //el.addEventListener('dragend', handleDragEnd, false);
              },

              init = function(root) {

                console.log('initialize ' + dragStartSelector + ' ' + dropInSelector);
                srcElems = root.querySelectorAll(dragStartSelector),
                destElems = root.querySelectorAll(dropInSelector);

                [].forEach.call(srcElems, function(srcElem) {
                  console.log('register ' + srcElem + ' - ' + srcElem.className);
                  registerSrcEvents(srcElem);
                });

                [].forEach.call(destElems, function(destElem) {
                  console.log('register dest Elemr ' + destElem + ' - ' + destElem.className);
                  registerDestEvents(destElem);
                });

                if (dragEndCB && root === document) {
                  document.addEventListener('dragend', handleDragEnd, false);
                }

                return thisObject;

              } ;


              // Object interface returned
              thisObject = {
                init: init
              };

              return thisObject;          
        };   



        // Object DragAndDropManager returned
        var thisObject = {};

        // Private static property to get track af all instances of DragAndDrop objects
        var registeredManagers = [];

        var defaultManagers = [];

        defaultManagers['blocks'] = DragAndDrop({
          dragStartSelector: '.ws-block', 
          dropInSelector: '.ws-block-container',
          getDragSrc: function(el) { return el.parentElement; },
          dragStartCB: function(el) {
            el.parentElement.removeChild(el);
          },
          dropInCB: function($el, dest, src) {
            /*console.log('destt ' + dest + dest.className);
            console.log('$el ' + $el.html());
            console.log('src ' + dest + src.className);
            console.log('id ' + dest.parentElement.parentElement.id);*/
            if (dest.parentElement.parentElement.id === 'ws-blocks-store') {
                $el.find('.ws-activity-wrapper').remove(); // When droppin out a block, also unlink all activities within it
                $el.find('.ws-block').removeClass('toggle-panel-click').removeAttr('data-toggle-panel-id');
            } else {
              // Add this class in order to toggle search box panel when clickin a selecte block
              $el.find('.ws-block').addClass('toggle-panel-click').attr('data-toggle-panel-id', 'search-box');
            }
            $el.insertBefore(dest.parentElement);
            defaultManagers['blocks'].init($el.get(0));
            defaultManagers['activitys'].init($el.get(0)); // Activate events for the new .ws-activity-container created div 
          }
        });

        defaultManagers['activitys'] = DragAndDrop({

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
            defaultManagers['activitys'].init($node.get(0));
          }
        });


        
        // initialize: initializes the events for all elements matching the selectors under a root element 
        // (if not root element is supplied it will use the whole document)
        var init = function(rootElem) {

            console.log('init drag drop');

            var root = rootElem || document,
                manager; 

            for (var i = 0; i < registeredManagers.length; i++) {
                manager = registeredManagers[i];
                manager.init(rootElem);
            }

            return thisObject;
        };


        var registerManagerFor = function(manager) {

          console.log('register drag drop ' + manager);
          console.log('manager: ' + defaultManagers[manager]);

          if (typeof manager === 'string' && defaultManagers[manager]) {
            console.log('register: ' + defaultManagers[manager]);
            registeredManagers.push(defaultManagers[manager]);
          } else {
            if (typeof manager === 'object' && manager.constructor.name === 'DragAndDrop') {
              registeredManagers.push(manager);  
            }
          }

          return thisObject;

        };

        thisObject = {
            registerManagerFor: registerManagerFor,
            init: init
        };


        var DragAndDropManager = musTeach.DragAndDropManager = musTeach.DragAndDropManager || function DragAndDropManager(opts) {
          return thisObject;
        };       



}($, window._musTeach = window._musTeach || {}, window, document));