var L5FormUtils = {

    // Various functionality to support Laravel forms (eg, render validation errors)
    //   ~ Assumes Bootstrap 4

    renderValidationErrors: function(thisForm,vErrors) {

        $.each( vErrors, function(key, value) {
            // Parse validation message
            var message = '';
            if ( (Array === value.constructor) && (value.length > 0) ) {
                message = value[0];
            } else if ( 'string' === typeof value ) {
                message = value;
            } else {
                return true; // skip to next iteration
            }
            // Find the element to attach to
            var parsed = key.split('.');
            var classStr = 'tag-'+key+'_verr';
            var compoundClassStr = 'tag-verr '+classStr;
            var thisElem;
            switch ( parsed.length ) {
                case 1:
                    thisElem = thisForm.find(':input[name="'+key+'"]');
                    thisElem.after('<div class="'+compoundClassStr+'">'+message+'</div>');
                    //thisForm.find(':input[name="'+key+'"]').after('<div class="'+compoundClassStr+'">'+message+'</div>');
                    console.log('    '+key+': '+message);
                    break;
                case 2:
                    console.log('    '+parsed[0]+"["+parsed[1]+"]"+': '+message);
                    console.log('===>>>>> Type 2 not supported');
                    //thisForm.find('.tag-formcomponent[data-fcslug="'+parsed[0]+'"]').find('.tag-formelement[data-eslug="'+parsed[1]+'"]').append('<div class="'+compoundClassStr+'">'+message+'</div>'); // this will catch checkboxes, etc, as well
                    break;
                default:
            }
        });
    },

    clearValidationErrors: function(thisForm) {
        var htmlTags = thisForm.find('.tag-verr').remove();
    },

};

