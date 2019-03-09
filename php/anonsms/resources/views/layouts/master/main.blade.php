<!DOCTYPE html> 
<html class="h-100" lang="en">

<head>
    <!-- SERVER: <?php echo env('SERVER_ID'); ?> -->
    <!-- RELEASE: 20190205.a -->
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Peter S. Gorgone">
@section('head')
    <title>AnonSMS Intranet</title>
    <meta name="description" content="AnonSMS">
    <meta name="keywords" content="">
@show
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! $g_assetMgr->renderCssInlines() !!}

    {{--
    <!-- INFO: {{ \App\Models\User::renderUserMeta() }} -->
    --}}
</head>

<body class="@section('body-class')@show d-flex flex-column h-100">

    <header class="">
        @yield('header')
    </header>

    <main role="main" class="d-flex flex-column flex-grow">
        <div class="d-flex flex-column flex-grow container-fluid">
            <section class="flex-grow row">
                @if(array_key_exists('sidebar', View::getSections()))
                <aside class="flex-grow col-12 col-md-3" id="main-sidebar">
                    @yield('sidebar')
                </aside>
                <main class="tag-withSidebar col-12 col-md-9" id="main-content">
                    @yield('content')
                </main>
                @else
                <main class="tag-noSidebar col-12" id="main-content">
                    @yield('content')
                </main>
                @endif
            </section>
        </div>
    </main>
    <footer class="footer mt-auto py-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <span class="text-muted float-right">Copyright &copy; {{date('Y')}} Jeffer Mangels Butler & Mitchell LLP</span>
                </div>
            </div>
        </div>
    </footer>

{{-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --}}

    <div id="modal-placeholder" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="global_modal-title" aria-hidden="true">
    @section('global-modal')
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="global_modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Example Global Modal
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
    @show
    </div> <!-- MODAL -->

<script type="application/javascript">
    var g_php2jsVars = <?php echo json_encode($g_php2jsVars); ?>;
    var g_csrf_token='{{ csrf_token() }}';
</script>

<!-- renderJsLibs() -->
{!! $g_assetMgr->renderJsLibs() !!}
<!-- renderJsInlines() -->
{!! $g_assetMgr->renderJsInlines() !!}

<!-- blade_inlinejs -->
@push('blade_inlinejs')
<script type="application/javascript">

$(document).ready(function () {

    $('nav a[href="' + location.href + '"]').addClass('active');

    // CSRF Token for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': g_csrf_token }
    });

/*
    // Site-wide generic handler for opening a modal
    $(document).on('click', '.tag-clickme_to_open_modal.tag-global_modal', function (e) {
        e.preventDefault();
        var context = $(this);
        var url = context.attr('href');
        var payload = {};
        $.getJSON(url, payload, function(response) {
            $('#modal-placeholder').html(response.html);
            $('#modal-placeholder').modal('toggle');
            //if ( response.hasOwnProperty('cb_func') ) {
           // }
        });
        return false;
    });
*/
});

</script>
@endpush

@stack('blade_inlinejs')

@include('layouts._jsroutes', [])
</body>

</html>
