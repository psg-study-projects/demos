@component('mail::message')
# Welcome {{ $user->firstname}} !

You have successfully registered on Anon SMS!

@php
    $url = route('home.welcome');
@endphp
@component('mail::button', ['url' => $url])
Start Here!
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
