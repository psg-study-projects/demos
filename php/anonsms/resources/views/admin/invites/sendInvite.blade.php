@extends('layouts.admin.site-sidebar')
@section('content')
    <section class="row">
        <article class="tag-heading col-sm-12">
            <h1>Invite Users</h1>
        </article>
    </section>

    <section class="row tag-emails_to_invite">
        <article class="tag-heading col-sm-12">
            <span class="btn btn-default tag-click_me_to_add_email_invite_input" role="button">Add More Emails</span>
        </article>
        {!! Form::open(['route' => ['admin.users.emailInvites'],'id'=>'form-email_invites']) !!}
        <div class="form-group col-sm-7">
            {{ Form::text('emails[]', null, ['class'=>'form-control tag-email_invite_input', 'type'=>'email', 'placeholder'=>'Enter a valid email address']) }}
        </div>
        <div class="form-group col-sm-7">
            {{ Form::submit('Email Invites', ['class'=>'tag-submit_email_invites btn btn-large btn-default']) }}
        </div>
        {!! Form::close() !!}
    </section>
@stop

@section('sidebar')
    <aside class="tag-sidebar tag-panel_sidebar">
        @if ( empty($partial_indexSearch) )
            @include('admin.base._indexSearch')
        @endif

        <h3>Options</h3>
        <div class="crate-minorNav">
            @include('admin.base._minorNav',['routes'=>$minor_nav['routes']])
        </div>
    </aside>
@stop

@push('blade_inlinejs')
<script>
    $( document ).ready(function() {
        $( document ).on('click', '.tag-click_me_to_add_email_invite_input', function(e) {
            $('.tag-email_invite_input:last').clone().insertAfter('.tag-email_invite_input:last');
        });
    });
</script>
@endpush