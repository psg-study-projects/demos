@extends('layouts.admin.main')
@section('body-class') @parent()users show @stop()

@section('sidebar')
@include('admin.base._minorNav',['routes'=>$minor_nav['routes']])
@endsection

@section('content')

<div class="supercontainer-view">

    <section class="row">
        <article class="col-sm-12">
            <h1>{{$pageheading}}: {{ $identifier or $obj->slug }}</h1>
        </article>
    </section>

    <section class="row">
        <article class="col-sm-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active">{{ link_to('#crate-show_user_info','Info',['role'=>'tab','data-toggle'=>'tab']) }}</li>
                <li>{{ link_to('#crate-show_user_accounts','Accounts',['role'=>'tab','data-toggle'=>'tab']) }}</li>
            </ul>
        </article>
    </section>

    <section class="row">
        <article class="tab-content col-sm-12">
            <div role="tabpanel" class="tab-pane active" id="crate-show_user_info">
                @include('admin.base._show',['obj'=>$obj])
            </div>
            <div role="tabpanel" class="tab-pane" id="crate-show_user_accounts">
                @include('admin.base._index',['g_php2jsVars'=>$g_php2jsVars, 'resource_key'=>'accounts'])
            </div>
        </article>
    </section>

</div>
@endsection
