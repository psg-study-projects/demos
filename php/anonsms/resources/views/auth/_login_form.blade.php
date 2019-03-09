<!-- PARTIAL: auth._login_form -->
<form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <div class="form-group {{ $errors->has('username') ? ' has-error ' : '' }}">
        <div class="col-md-8 col-md-offset-2">
            <label for="username" class="control-label">Username</label>

            <input id="username" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

            @if ($errors->has('username'))
                <span class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group {{ $errors->has('password') ? ' has-error ' : '' }}">
        <div class="col-md-8 col-md-offset-2">
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
        <div class="col-md-4 col-md-offset-2">
            <div class="">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
            </div>
        </div>
        <p class="col-md-4 text-right">
            <a href="{{ route('password.request') }}">
                Forgot Password?
            </a>
        </p>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
            <button type="submit" class="btn btn-primary btn-block btn-civix-blue">
                Login
            </button>
        </div>
    </div>
    <div class="col-md-8 col-md-offset-2 text-center">
        <p>Don't have an account? {{ link_to_route('register', 'Sign Up') }}</p>
    </div>
</form>
