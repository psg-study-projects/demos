@extends('layouts.site.main')
@section('body-class') @parent()activitymessages index @stop()

@section('content')
<div class="supercontainer-view">

    <section class="subcontainer-main row">

        <article class="col-sm-12 col-md-12">

            <ul class="nav nav-tabs">
                <li class="active"><h1>{{ link_to('#crate-activitymessages','Messages', ['data-toggle'=>'tab']) }}</h1></li>
            </ul>

            <div class="tab-content">
                <div id="crate-activitymessages" class="tab-pane table-responsive fade in active">
                    <table class="table tag-datatable datatable tag-standard_table_light" data-resource_key="{{$resource_key}}" data-route="{{ route('site.activitymessages.index', ['filters'=>[],'options'=>[]]) }}"></table>
                </div>
            </div>

        </article>

    </section>

</div>
@endsection
