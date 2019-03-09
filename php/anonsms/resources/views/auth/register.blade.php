@extends('layouts.site.main')
@section('body-class')@parent() auth register @stop()

@section('content')

    <div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-1">

            @section('intro')
            <h1>Create an Account</h1>
            <p class="small">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  Dolor in reprehenderit in voluptate velit esse cillum dolore</p>
            @show

            <h4>Sign Up Information</h4>

            {{-- %FIXME rewrite this with Form::open() --}}

            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <div class="panel panel-default">
                    <div class="panel-body">

                        @section('token') {{-- for registration by invite --}}
                        @show

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <div class="col-md-7">
                            <label for="firstname" class="control-label">First Name</label>

                                <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <div class="col-md-7">
                            <label for="lastname" class="control-label">Last Name</label>

                                <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required >

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-md-7">
                            <label for="username" class="control-label">User Name</label>

                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required >

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-md-7">
                            <label for="email" class="control-label">E-Mail Address</label>

                                @section('input_email') {{-- for registration by invite --}}
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                @show

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-md-7">
                            <label for="password" class="control-label">Password</label>

                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                            <label for="password-confirm" class="control-label">Confirm Password</label>

                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row">

                <div class="col-md-5 ">
                    <button type="submit" class="btn btn-primary btn-block btn-civix-blue">
                        Sign Up
                    </button>
                </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
