
;(function($, musTeach, window, document, undefined) {


    var appendQueryParams = musTeach.appendQueryParams = musTeach.appendQueryParams || function(search, params)
    {
        var hasOwn = Object.hasOwnProperty;
        var key, value;
        var kvp = search ? search.split('&') : [];

        // Searh if some params exist and replace their values
        for (prop in params) {
          if (hasOwn.call(params, prop)) {
            key = encodeURI(prop); value = encodeURI(params[prop]);
            var i=kvp.length; var x; while(i--) 
            {
                x = kvp[i].split('=');
                if (x[0]==key)
                {
                    if(value) {
                      x[1] = value;
                      kvp[i] = x.join('=');
                      delete params[prop];
                    } else {
                      kpv.splice(i,1);
                    }
                    break;
                }
            }
          }
        }

        // Add the params that where not found
        for (prop in params) {
          if (hasOwn.call(params, prop)) {
            key = encodeURI(prop); value = encodeURI(params[prop]);
            if (value) {
              kvp[kvp.length] = [key,value].join('=');
            }
          }
        }

        return kvp.join('&'); 
    };


    var toggleDraggable = musTeach.toggleDraggable = musTeach.toggleDraggable || function(e) {
       if (e.type === 'focus') {
           $('body').removeAttr('draggable');
       } else {
           $('body').attr('draggable', 'true');
       }
    };



    // Detect changes in the DOM
    var observeDOMAppend =  musTeach.observeDOMAppend = musTeach.observeDOMAppend || (function(){

        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver,
            eventListenerSupported = window.addEventListener;

        return function(obj, callback){
            if( MutationObserver ){
                // define a new observer
                var obs = new MutationObserver(function(mutations, observer){

                    console.log('*********mutations length ' + mutations.length);
                    console.log('mutations[0].addedNodes.length ' + mutations[0].addedNodes.length);


                    //if( mutations[0].addedNodes.length) {

                        //for (var i = 0; i < mutations.length; i++) {
                          //console.log('mutations[i].type : ' + mutations[i].type);
                          //for (var j = 0; j < mutations[i].addedNodes.length; j++) {
                            //console.log('!!!!!!!!!!!mutations[' + 0 + '].addedNodes[' + 0 + '] ' +  mutations[0].addedNodes[0] + ' |' + mutations[0].addedNodes[0].className);
                            //callback({target: mutations[0].addedNodes[0]});
                          //}
                        //}

                        
                    //}

                    mutations.forEach(function(mutation) {
                      if (mutation.type === 'childList') {
                        for (var i = 0; i < mutation.addedNodes.length; i++) {
                            console.log('added node ' + mutation.addedNodes[i] + ' | ' + mutation.addedNodes[i].className );
                            callback({target: mutation.addedNodes[i]});
                        }
                      }
                    });  



                });
                // have the observer observe foo for changes in children
                obs.observe( obj, { childList:true, subtree:true });
            }
            else if( eventListenerSupported ){
                obj.addEventListener('DOMNodeInserted', callback, false);
            }
        }
    })();

    // Get an object from a form that contains all name - value pairs of the form
    var formValues = musTeach.formValues = musTeach.formValues || function(form) {

            var obj = {}, 
                elements = form.elements,
                el, 
                nameAttr, 
                value;
      
            for (var i = 0 ; i < elements.length ; i++) {
                 el = elements.item(i);
                 nameAttr = el.name;
                 value = el.value;

              if (nameAttr) {
                obj[nameAttr] = value;
              }
            };
      
            return obj;        
    };

    var  ajaxRefresh = musTeach.ajaxRefresh = musTeach.ajaxRefresh || function($url, $refresh, callback) {

            console.log('len ' + $refresh.addClass('ajax-loading').find('.ajax-mask').length);

            $refresh.addClass('ajax-loading');
            $ajaxMask =  $refresh.find('.ajax-mask');

            $ajaxMask.fadeTo(500, 0.1, function() {
               $.get($url,
                function(data) {                 
                 $refresh.replaceWith(data);
                 callback();
                 $ajaxMask.fadeTo(500, 1, function() { $refresh.removeClass('ajax-loading'); });                 
               });

            }); 
    };

    var enableTextSelection = musTeach.enableTextSelection = musTeach.enableTextSelection || function(node) {

      $(node).find('textarea, input, td, p, h1, h2, h3, h4, h5, h6')
           .andSelf()
           .filter('textarea, input, td, p, h1, h2, h3, h4, h5, h6')
           .on('focus blur', toggleDraggable); 
    }




}(jQuery, window._musTeach = window._musTeach || {}, window, document));