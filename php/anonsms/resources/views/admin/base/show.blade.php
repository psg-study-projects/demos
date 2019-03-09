@extends('layouts.admin.main')
@section('body-class') @parent()base show @stop()

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
            @include('admin.base._show',['obj'=>$obj])
        </article>
    </section>
</div>
@endsection
