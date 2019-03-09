@extends('layouts.site.main')
@section('body-class')@parent() auth login @stop()

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h2 class="col-md-8 col-md-offset-2 text-center">Sign In</h2>

                    @include('auth._login_form')

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
