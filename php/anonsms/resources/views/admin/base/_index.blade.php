<!-- PARTIAL: admin.base._index -->
@php
// requires: $resource_key
if ( empty($resource_key) ) {
    throw new \Exception('Partial admin.base._index requires resource_key');
}
$indexroute = 'admin.'.$resource_key.'.index';
$filters = !empty($g_php2jsVars['datatables'][$resource_key]['filters']) 
                ?  $filters = $g_php2jsVars['datatables'][$resource_key]['filters']
                : [];
@endphp

<div class="table-responsive">
    <table class="table tag-datatable tag-dt_util_container datatable table-striped tag-standard_table_light no-footer" id="table-datatable" data-resource_key="{{$resource_key}}" data-route="{{ route($indexroute, ['filters'=>$filters]) }}"></table>
</div>
