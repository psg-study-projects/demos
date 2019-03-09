@extends('layouts.admin.main')
@section('body-class') @parent()invites create @stop()

@section('sidebar')
@include('admin.base._minorNav',['routes'=>$minor_nav['routes']])
@endsection

@section('content')
<div class="tag-view module-admin view-invites tag-create">

    <section class="row">
        <article class="col-sm-12">
            <h1>Create New User Invitation</h1>
        </article>
    </section>

    <section class="row">
        <article class="col-sm-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active">{{ link_to('#crate-create_invite_form','Info',['role'=>'tab','data-toggle'=>'tab']) }}</li>
            </ul>
        </article>
    </section>

    <section class="row">
        <article class="tab-content col-sm-9">
            <div role="tabpanel" class="tab-pane active" id="crate-create_invite_form">
                {!! Form::model(null,['route' => ['admin.invites.store'],'method'=>'POST','class'=>'store-invite']) !!}
                @include('admin.invites._form',['errors'=>$errors])
                {!! Form::close() !!}
            </div>
        </article>
    </section>

</div>

@endsection
