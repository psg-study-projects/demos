@component('mail::message')
@component('mail::panel')
# You've been invited to Anon SMS

To get started, you will need to create your account by clicking the link below.

@component( 'mail::button', [ 'url' => $invite->getAcceptURL() ] )
Sign Up Now
@endcomponent

@endcomponent
@endcomponent
