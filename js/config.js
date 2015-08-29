// Configuration

;(function($, musTeach, window, document, undefined) { 

  var config = musTeach.config = musTeach.config || 

  {
    siteUrl : 'http://www.mondemusica.com/',
    appPrefix : 'music-teach'          
  };

  
  config.getPath = function(model, task, param) {
    
    switch(task) {
      case 'view':
        return this.siteUrl + this.appPrefix + '/' + model + '_view.php?id=' + param;
        break;
      case 'list':
        return this.siteUrl + this.appPrefix + '/' + model + '_list.php?' + param;
        break;
      case 'update':       
        return this.siteUrl + this.appPrefix + '/' + model + '_form.php?id=' + param + '&action=update';
        break;
      case 'new':
        return this.siteUrl + this.appPrefix + '/' + model + '_form.php?id=' + param + '&action=new';
        break;
      case 'delete':
        return this.siteUrl + this.appPrefix + '/' + model + '.php?id=' + param + '&action=delete';
        break;
    }

  };


}($, window._musTeach = window._musTeach || {}, window, document));