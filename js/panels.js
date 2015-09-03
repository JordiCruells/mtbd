;(function($, musTeach, window, document, undefined) {

    var togglePanels = musTeach.togglePanels = musTeach.togglePanels || function(options) {

        var thisObject = {},
            opts = {}
            options = options || {};

        opts.selShrink = options.selShrink || '.toggle-panel-shrink';
        opts.selClick  = options.selClick  || '.toggle-panel-click';
        opts.selPanel  = options.selPanel  || '.toggle-panel';
        opts.selIdRefPanel = 'toggle-panel-id';

        var autoclose = opts.selPanel.substr(1) + "-autoclose";

        var $shrinkEls = $(opts.selShrink), // $shrinkEls references the block/s that have to be shrinked when panel toggling
            panels = [];

        var regZone = new RegExp("^" + opts.selPanel.substr(1) + "\\-(right|left|up|down)$");
        
        

        // Private methods

        var autoClosePanels = function() {
          return panels.filter(function(panel, index){
              return panel.autoclose && panel.active;
          });   
        };

        var parsePanel = function($el) {

          console.log('in parse panel');
          var el = {},
              zone,
              classes = $($el).get(0).className.split(/\s+/);          

          el.ref = $el;
          el.autoclose = false;
          el.active = false;
          el.w = $el.width();
          
          for (var i = 0; i < classes.length; i++) {

              console.log(classes[i]);
              
              zone = classes[i].match(regZone);
              
              if (zone) {
                console.log('yes zone ' + zone[1]);
                el.zone = zone[1];            
              } else {
                if (classes[i] === autoclose) {
                  el.autoclose = true;
                }
              }

          }

          console.log('final zone ' + el.zone);

          if (!el.zone) return null;

          return el;

        };

        var findPanelByRef = function($el) {
          console.log('in findPanelByRef');
          for (var i = 0; i < panels.length; i++) {
            if (panels[i].ref[0] === $el[0]) {
              return panels[i];
            }
          }
          return null;
        };

        var findPanelByZone = function(zone) {
          console.log('in findPanelByZone');
          for (var i = 0; i < panels.length; i++) {
            if (panels[i].zone === zone) {
              return panels[i];
            }
          }
          return null;      
        };

        var removePanel = function(el) {
          var index;
          for (var i = 0; i < panels.length; i++) {
            if ((panels[i].ref)[0] === el.ref[0]) {
              index = i;
              break;
            }
          }
          panels.splice(index, 1);
        };

        var addPanel = function(el) {
          panels.push(el);
        };

        var toggle = function($el) {
            
            if ($el) {
              console.log('in toggle ' + $el.get(0));
            } else {
              console.log('in toggle null');
            }  

            return function() {

                var panel;

                // If no panel is supplied look for any panels with autoclose class and close them
                if (!$el) {

                   console.log('autoclosing ' + autoClosePanels().length + ' panels ');
                   
                   autoClosePanels().map(function(panel, index){
                        togglePanel(panel, 'hide');
                   }); 

                } else {

                  console.log('find panel ...');
                  panel = findPanelByRef($el);

                  console.log('panel is ' + panel);
                  
                  if (panel.active) {  // Panel is toggled and its the same supplied, just untoggle it
                    console.log('is active');
                    togglePanel(panel, 'hide');

                  } else {  // Panel is not toggled
                      console.log('is not active');

                      // 1: untoggle any panel in the same direction
                      console.log('find if untoggle previuos');
                      previousPanel = findPanelByZone(panel.zone);
                      if (previousPanel && previousPanel.active) {
                        console.log('untoggling previuos');
                        togglePanel(previousPanel, 'hide'); 
                      }

                      console.log('toggle on');
                      // 2: toggle the new panel               
                      togglePanel(panel, 'show');
                    
                  }
                }
            };

        };

        var init = function(panel) {

          var delta = panel.w + 100;
          
          switch(panel.zone) {
            case 'right': 
              var op = '-' + delta + 'px';
              $el.css({right: op});   
              break;      
            case 'left': 
            case 'up':
            case 'down': 
              break;
          }

          panels.push(panel);

        };

        var togglePanel = function(panel, action) {

          console.log('toggle panel ' + action );

          var $el = panel.ref,
              delta2 = panel.w,            
              op1, op2;
          var delta1 = delta2 + 100;

          switch(panel.zone) {

            case 'right':
              switch(action) {
                case 'show':              
                  op1 = '+=' + delta1; op2 = '-=' + delta2; 
                  panel.active = true;
                  break;
                case 'hide':
                  op1 = '-=' + delta1; op2 = '+=' + delta2;         
                  panel.active = false;
                  break;
              }         
              console.log('toggle with action ' + action + ' | ' + op1 + ' | ' + op2 ); 
              break;
                

            case 'left': break;
            case 'up': break;
            case 'down': break;
          }          

          $el.animate({right: op1 }, 'fast');
          if ($shrinkEls && $shrinkEls.length > 0) {
              $shrinkEls.animate({width: op2 }, 'fast');
          }

          console.log('exit toggle panel');
          console.log('panels lenght ' + panels.length);
          console.log('panel 0 ' + panels[0].active);

        };

        // Public Object
        
        thisObject.init = function(rootEl) {

              var $root = rootEl ? $(rootEl) : $(document);

              console.log('in init ' + $root.get(0) + ' | ' + $root.get(0).className);

              if (!$root.length) return;

              console.log('autoclose event listening');
              // Autoclose panels when clicking outside any panel or any clickable related area
              $(document).click(function(e) {
                if (!$(e.target).parents(opts.selPanel + ',' + opts.selClick).length) { 
                   toggle(null)();
                }
              });


              // Initialize and store new panels 
              $root.find(opts.selPanel).andSelf().filter(opts.selPanel).each(function(index, el) {
                $el = $(el);
                console.log('new panel ' + $el.get(0) + ' | ' + $el.get(0).className);
                if (!findPanelByRef($el))  {
                  console.log('panel not found');
                  var panel = parsePanel($el);
                  if (panel) {              
                    console.log('panel initiated');
                    console.log('panel.w ' + panel.w);
                    console.log('panel.zone ' + panel.zone);
                    console.log('panel.autoclose ' + panel.autoclose );
                    init(panel);
                    console.log('panel 0 ' + panels[0].ref);
                  }
                }
              });

              // Register click toggle events
              $root.find(opts.selClick).andSelf().filter(opts.selClick).each(function(index, el) {
                $el = $(el);
                var $targetPanel = $('#' + $el.data(opts.selIdRefPanel));
                
                if ($targetPanel.length === 1 && findPanelByRef($targetPanel)) {
                  console.log('register click toggle '); 
                  $el.click(toggle($targetPanel));
                }
              });

              
              return thisObject;
        };

        return thisObject; 

    };

}(jQuery, window._musTeach = window._musTeach || {}, window, document));