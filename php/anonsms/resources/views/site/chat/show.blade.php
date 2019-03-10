@extends('layouts.site.main')
@section('body-class') @parent()chat show @stop()

@section('content')
<div class="supercontainer-view">

    <section class="subcontainer-header row">
        <article class="col">
            <h2>Conversation with {{ $partner->renderName() }} </h2>
        </article>
    </section>

    <section class="subcontainer-main row">

        <article class="col-8">
            <div>
                {{ Form::open(['route'=>'site.activitymessages.store','method'=>'POST','class'=>'store activitymessages form-inline mb-2']) }}
                {{ Form::hidden('conversation_id',$conversation->id) }}
                {{ Form::hidden('sender_id',$session_user->id) }}
                {{ Form::hidden('receiver_id',$partner->id) }}
                {{ Form::textarea('amcontent',null,['class'=>'form-control', 'rows'=>1, 'autocomplete'=>'off']) }}
                {{ Form::submit('Send', ['class'=>'tag-clickme_to_send btn btn-primary ml-2']) }}
                {{ Form::close() }}
            </div>
            <ul class="list-messages">
            @include('site.chat._messagelist', ['messages'=>$messages])
            </ul>

        </article>

    </section>
 
</div>
@endsection


@push('blade_inlinejs')
<script type="text/javascript">

$( document ).ready(function() {

    $(document).on('submit', 'form.store.activitymessages', function(e) {
        e.preventDefault();
        var context = $(this);
        (function () {
            return $.ajax({
                            url: context.attr('action'),
                            type: 'POST',
                            data: context.serializeArray()
            })
        })()
        .then( function(response) {
            // ajax get call to messagelist partial, display here with some kind of save effect (for the new message only?)
            $('#modal-placeholder').modal('hide');
            $('#modal-placeholder').html('');
            window.location.reload(true);
        })
        .fail( function(errResponse) {
            console.log('ERROR: Could not update profile data');
            NlFormbuilderUtils.renderValidationErrors(context, errResponse.responseJSON.errors);
        });
        return false;
    });
});

</script>
@endpush
