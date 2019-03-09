@extends('layouts.master.main')

@section('body-class') @parent()site @stop()

@section('header')
    <nav class="navbar navbar-expand-sm navbar-light">

        <a class="navbar-brand" href="#">
            <h2>Anon SMS</h2>
            {{--
            <img src="/images/og/my-logo.png" alt="logo">
            --}}
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample03">

            <ul class="navbar-nav mr-auto">
                <li class="nav-item OFF-active">
                    {{link_to_route('home.welcome', 'Home', null, ['class'=>'nav-link']) }}
                </li>
                <li class="nav-item">
                    {{link_to_route('site.dashboard.show', 'Dashboard', null, ['class'=>'nav-link']) }}
                </li>
                {{--
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li>
                --}}
            </ul>


            <form class="form-inline my-2 my-md-0">
                <input class="form-control" type="text" placeholder="Search">
            </form>

            @auth
            <div class="tag-pipe d-none d-md-block">&nbsp;</div>
            <div class="dropdown">
                <i class="fa fa-cog fa-lg dropdown-toggle" id="dropdown-user_settings" data-toggle="dropdown" ara-haspopup="true" area-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="dropdown-user_settings">
                    {{ link_to_route('site.employees.editDashboard', 'Edit Dashboard', 'tbd-username', ['class'=>'dropdown-item']) }}
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
            <ul class="navbar-nav">
                <li><a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">{{Auth::user()->renderName()}}</a></li>
            </ul>
            <form class="form-inline logout-button">
                {{ link_to_route('logout', 'Sign Out', null, ['class'=>'btn btn-sm btn-outline-secondary']) }}
            </form>
            @endauth

        </div>
    </nav>
@endsection
