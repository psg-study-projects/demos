/*
 FROM: https://gist.github.com/JeffreyWay/5112282

 Laravel 5.4 update: https://gist.github.com/soufianeEL/3f8483f0f3dc9e3ec5d9

 Examples: 
        <a href="posts/2" data-method="delete"> <---- We want to send an HTTP DELETE request
    - Or, request confirmation in the process -
        <a href="posts/2" data-method="delete" data-confirm="Are you sure?">
*/
 
(function() {
 
  var deleteUtils = {
    initialize: function() {
      this.methodLinks = $('a[data-method]');
 
      this.registerEvents();
    },
 
    registerEvents: function() {
        //this.methodLinks.on('click', this.handleMethod);
        $(document).on('click', 'a[data-method]', function(e) {
            e.preventDefault();
            
            var link = $(this);
            var httpMethod = link.data('method').toUpperCase();
            var form;
 
            // If the data-method attribute is not PUT or DELETE,
            // then we don't know what to do. Just ignore.
            if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
                return;
            }
    
            // Allow user to optionally provide data-confirm="Are you sure?"
            if ( link.data('confirm') ) {
                if ( ! deleteUtils.verifyConfirm(link) ) {
                    return false;
                }
            }
 
            form = deleteUtils.createForm(link);
            form.submit();
 
            return false;
        });
    },
 
    verifyConfirm: function(link) {
      return confirm(link.data('confirm'));
    },
 
    createForm: function(link) {
      var form = 
      $('<form>', {
        'method': 'POST',
        'action': link.attr('href')
      });
 
      var token = 
      $('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': link.data('token')
        });
 
      var hiddenInput =
      $('<input>', {
        'name': '_method',
        'type': 'hidden',
        'value': link.data('method')
      });
 
      return form.append(token, hiddenInput)
                 .appendTo('body');
    }
  };
 
  deleteUtils.initialize();
 
})();
