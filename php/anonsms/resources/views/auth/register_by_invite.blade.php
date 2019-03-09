@extends('auth.register')

@section('token')
    <input id="token" type="hidden" class="form-control" name="token" value="{{ $invite->token }}">
@endsection

@section('intro')
<h1>Create an Account (by invite)</h1>
<p class="small">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  Dolor in reprehenderit in voluptate velit esse cillum dolore</p>
@endsection

@section('input_email')
    <input id="email" type="email" class="form-control" name="email" value="{{ $invite->email }}" readonly required>
@endsection

