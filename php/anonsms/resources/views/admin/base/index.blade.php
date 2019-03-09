@extends('layouts.admin.main')
@section('body-class') @parent()base index @stop()

@section('sidebar')
@include('admin.base._minorNav',['routes'=>$minor_nav['routes']])
@endsection

@section('content')
<div class="supercontainer-view">
    <section class="row">
        <article class="col-sm-12">
            <h1>{{$pageheading}}</h1>
        </article>
    </section>
    <section class="row">
        <article class="col-sm-12">
        @include('admin.base._index',['g_php2jsVars'=>$g_php2jsVars, 'resource_key'=>$tablename])
        </article>
    </section>
</div>
@endsection
