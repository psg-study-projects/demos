@extends('layouts.demo.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3>Below me is a vuejs example component</h3>
            <example-component></example-component>
            <h3>Above me is the vuejs example component</h3>
        </div>
    </div>


</div>
@endsection
