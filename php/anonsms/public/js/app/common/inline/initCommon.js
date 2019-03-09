$(document).ready(function() {

/*
    // Configure Bootstrap datetimepicker (may still need to do for dynamically created markup)
    $('.tag-bs3_datepicker').datetimepicker({
        //format: "yyyy-mm-dd",
        format: 'L'
        //todayBtn: "linked",
        //autoclose: true,
        //todayHighlight: true
    });
*/
    // Event to handle bootstrap tabs
    // or pages that have tabs/hashtags, add the hashtag per tab to URL, and go to that tab on page refresh
    // http://stackoverflow.com/questions/12131273/twitter-bootstrap-tabs-url-doesnt-change
    // http://stackoverflow.com/questions/18999501/bootstrap-3-keep-selected-tab-on-page-refresh?noredirect=1&lq=1
    // %PSG: this is great, but how do we skip the "jump"?
    $(function(){
        var hash = window.location.hash;
        hash && $('ul.nav a[href="' + hash + '"]').tab('show');

        $('.nav-tabs a').click(function (e) {
            e.preventDefault()
            if ( $(this).hasClass('tag-disable_link') ) {
                return false;
            }
            $(this).tab('show');
            var scrollmem = $('body').scrollTop() || $('html').scrollTop();
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
        });
    });

});
