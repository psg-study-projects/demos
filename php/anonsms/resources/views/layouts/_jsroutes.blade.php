<!-- BEGIN ROUTES (LARAVEL)-->
<?php $g_routes = \App\Libs\Utils::getJSRoutes(); ?>
<script>
var g_routes = {};
@foreach ($g_routes as $k => $r) 
g_routes['{{$k}}'] = '{{$r}}';
@endforeach
</script>
<!-- END ROUTES -->
