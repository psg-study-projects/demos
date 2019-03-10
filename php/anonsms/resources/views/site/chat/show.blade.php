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
            @foreach ($messages as $m)
                <li>
                    @include('site.activitymessages._show', ['obj'=>$m])
                </li>
            @endforeach
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
        var thisForm = $(this);
        (function () {
            return $.ajax({
                            url: thisForm.attr('action'),
                            type: 'POST',
                            data: thisForm.serializeArray()
            })
        })()
        .then( function(response) {
            var url = g_routes['GET.site.activitymessages.show'].replace(/{activitymessage}/, response.obj.slug);
            var payload = {
                partial: 'site.activitymessages._show'
            };
            $.getJSON( url, payload, function(response) {
                var el = $(response.html);
                el.hide().prependTo('ul.list-messages').fadeIn(700);
                thisForm.find('textarea').val('');
            });
        })
        .fail( function(errResponse) {
            console.log('ERROR: New message send failure');
        });
        return false;
    });
});

</script>
@endpush
