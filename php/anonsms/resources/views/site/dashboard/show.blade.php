@extends('layouts.site.main')
@section('body-class') @parent()dashboard show @stop()

{{--
@section('sidebar')
@include('site.common._majorNav')
@endsection
--}}

@section('content')
<div class="supercontainer-view">

    <section class="subcontainer-header row">
        <article class="col">
            <h2>Welcome to the dashboard</h2>
        </article>
    </section>

    <section class="subcontainer-main row">

        <article class="col">
            <p>Placeholder content for Dashboard</p>

            <section class="row">
                <article class="col-sm-6">


                </article>
            </section>

        </article>

    </section>
 
</div>
@endsection

