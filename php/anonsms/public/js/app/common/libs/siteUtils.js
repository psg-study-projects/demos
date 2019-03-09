var SiteUtils = {

    // Sticky footer
    // http://foundation.zurb.com/forum/posts/629-sticky-footer
    fixFooter: function(contentID, footerID)
    {
        // content: The div above footer which must take up remainder of space under <body> tag!  This 
        //   is what we will fill out with margin-bottom to make the footer 'stick'
        var content = $(contentID); 
        var footer = $(footerID);

        if (footer.length == 0 ) {
            return; // no footer
        }

        var windowHeight = $(window).height();
        var footerPos = footer.position();
        var footerHeight = footer.height();
        var contentHeight = content.height();

        var availableHeightForContent = windowHeight - footerHeight;
        if ( availableHeightForContent > contentHeight ) {
            var marginSize = availableHeightForContent - contentHeight;
            var newHeight = availableHeightForContent;
            content.css({
                'margin-bottom': marginSize + 'px'
                //'height': newHeight + 'px' // this doesn't resize for some reason %FIXME
            });
            // %TODO %NOTE: we can also adjust the height of a sub-div inside content to fill 100% down to footer, 
            // as long as it's in the top layout, which this is unavoidably tightly coupled to
        }
        /*
        var availableHeightForFooter = windowHeight - footerPos.top;
        //height = height - footer.height();
        if ( availableHeightForFooter > footer.height() ) {
            var marginSize = availableHeightForFooter - footer.height();
            footer.css({
                'margin-top': marginSize + 'px'
            });
        }
        */
        footer.css('visibility', 'visible'); // workaround to remove flicker
    },

    fixFooterOrig: function(divID)
    {
        //var footer = $('footer');
        var footer = $(divID);
        if (footer.length == 0 ) {
            return;
        }
        var pos = footer.position();
        var height = $(window).height();
        height = height - pos.top;
        height = height - footer.height();
        if (height > 0) {
            footer.css({
                'margin-top': height + 'px'
            });
        }
        footer.css('visibility', 'visible'); // workaround to remove flicker
    },

    init: function() {
    }

}; 

