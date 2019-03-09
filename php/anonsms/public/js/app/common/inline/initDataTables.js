$(document).ready(function () {

    // %FIXME : move this to datatableUtils package since it's so tightly coupled to it
    if ( $('.tag-datatable.tag-dt_util_container').length ) {

        // Data Tables Support
        //   ~ assumes only one #table-datatable per page

        // Iterate over all data tables, extract url and resource key, then render...
        $('table.tag-datatable.tag-dt_util_container').each(function() {
            var context = $(this);
            //var resourceKey = context.attr("data-resource_key"); // eg 'widgets', 'users', etc...
            var resourceKey = context.data("resource_key"); // eg 'widgets', 'users', etc...
            var filters = {};
            if ( g_php2jsVars['datatables'][resourceKey].hasOwnProperty('filters') ) {
                filters = g_php2jsVars['datatables'][resourceKey]['filters'];
            }
            context.DataTable({
                serverSide : true,
                lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]],
                pageLength: 20,
                processing: true,
                order: [[0, 'asc']],
                searching  : true,
                autoWidth  : false,
                blengthChange  : false,
                ajax : {
                    "url": context.attr("data-route"),
                    // %FIXME: this is tightly coupled to the library package
                    "data": {
                        "meta": g_php2jsVars['datatables'][resourceKey]['meta'],
                        "filters": filters
                    },
                    "dataSrc": "records"
                },
                columns : g_php2jsVars['datatables'][resourceKey]['columns'], // %FIXME: messy, tightly coupled to DatatableContainer class
                initComplete: function(){
                    SiteUtils.fixFooter('body > .page-wrapper', 'body > #container-footer'); // must adjust for vertical space DataTable takes up
                    //$("div#DataTables_Table_0_filter").append('{{ link_to_route('site.accounts.create','Create New',null,["class"=>"btn btn-small btn-civix-blue tag-clickme_to_create_new_widget"]) }}');
                }
            });
        });
    }

}); //end document ready

/*
    // %FIXME : move this to datatableUtils package since it's so tightly coupled to it
    if ( $('.tag-datatable').length ) {

        // Data Tables Support
        //   ~ assumes only one #table-datatable per page

        // Iterate over all data tables, extract url and resource key, then render...
        $('table.tag-datatable').each(function() {
            var context = $(this);
            var resourceKey = context.attr("data-resource_key"); // eg 'widgets', 'users', etc...
            context.DataTable({
                serverSide : true,
                processing: true,
                order: [[0, 'asc']],
                searching  : true,
                autoWidth  : false,
                blengthChange  : false,
                ajax : {
                    "url": context.attr("data-route"),
                    "dataSrc": "records"
                },
                columns : g_php2jsVars['datatables'][resourceKey]['colconfig']['columns'], // %FIXME: messy, tightly coupled to DatatableContainer class
                initComplete: function(){
                    SiteUtils.fixFooter('body > .page-wrapper', 'body > #container-footer'); // must adjust for vertical space DataTable takes up
                    //$("div#DataTables_Table_0_filter").append('{{ link_to_route('site.accounts.create','Create New',null,["class"=>"btn btn-small btn-civix-blue tag-clickme_to_create_new_widget"]) }}');
                }
            });
        });
    }
*/
