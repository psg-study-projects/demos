@extends('layouts.master.main')

@section('body-class') @parent()admin @stop()

@section('head')
    <title>AnonSMS Admin Panel</title>
    <meta name="description" content="AnonSMS Admin Panel">
    <meta name="keywords" content="psgc admin panel">
@endsection

@section('header')
    <section class="row">
        <article class="first-col-header col-xs-12 col-md-3">
            <a href="{{route('home.welcome')}}">
                <h1>AnonSMS Admin Panel</h1>
                {{--<img class="tag-main_logo" alt="AnonSMS logo" width="64" src="@yield('logo_image', asset('images/psgc-logo.jpg'))">--}}
            </a>
        </article>
    
        <nav class="tag-majorNav second-col-header tag-adminTabs col-xs-12 col-md-7">
            <ul>
                <li>{{ link_to_route('admin.home.index','Home',[],['class'=>'btn btn-primary ']) }}</li>
                <li>{{ link_to_route('admin.accounts.index','Accounts',[],['class'=>'btn btn-primary ']) }}</li>
                <li>{{ link_to_route('admin.invites.index','Invites',[],['class'=>'btn btn-primary ']) }}</li>
                <li>{{ link_to_route('admin.widgets.index','Widgets',[],['class'=>'btn btn-primary ']) }}</li>
                <li>{{ link_to_route('admin.users.index','Users',[],['class'=>'btn btn-primary ']) }}</li>
                @auth
                <li>{{ link_to('/logout','Logout',['class'=>'btn btn-primary tag-clickme_to_logout']) }}</li>
                <h4>Welcome {{ Auth::user()->username }}</h4>
                @else
                @endauth
            </ul>
        </nav>
    
        <article class="third-col-header col-xs-12 col-md-2">
            @if ( \Auth::check() )
            <div class="tag-adminuser crate-user_summary">User:{{\Auth::user()->email}}</div>
            @endif
        </article>
    </section>
@endsection
