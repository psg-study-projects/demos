@component('mail::message')
@component('mail::panel')
# First Time User?

Welcome to LogixKDM, {{$user->firstname}} {{$user->lastname}}!

Thank you for signing up for LogixKDM, a cloud-based application designed to ...

To get started, you will need to login to your account by visiting [www.logixkdm.com](http://www.logixkdm.com).
That's it. Really!

Welcome to LogixKDM.

@component('mail::button', ['url' => route('login')])
Get Started Now
@endcomponent

The NewLogix Team

@endcomponent
@endcomponent
