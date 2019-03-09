@extends('layouts.admin.main')
@section('body-class') @parent()widgets edit @stop()

@section('sidebar')
@include('admin.base._minorNav',['routes'=>$minor_nav['routes']])
@endsection

@section('content')
<div class="subcontainer-view">

    <section class="row">
        <article class="col-sm-12">
            <h1>{{$pageheading or "Edit"}}</h1>
        </article>
    </section>

    <section class="row">
        <article class="col-sm-12">
            <div class="" id="form-editWidget">
                {!! Form::model($obj,['route' => ['admin.widgets.update',$obj->id],'method'=>'PUT','class'=>'tag-basicForm']) !!}
                @include('admin.widgets._form',['obj'=>$obj,'errors'=>$errors])
                {!! Form::close() !!}
            </div>
        </article>
    </section>

</div>

@endsection
