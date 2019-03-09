<!-- PARTIAL: admin.accounts._form -->

{{-- Default display of ValidationRequests trait validation errors --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<fieldset>

{{--
<div class="form-group">
    {{ Form::label('atype','Agency Type') }}
    {{ Form::select('atype', \App\Models\Enums\Agency\AtypeEnum::getSelectOptions(),null,['class'=>'form-control']) }}
</div>
--}}

<div class="form-group">
    {!! \App\Models\Account::renderFormLabel('aname','Account Name') !!}
    {{ Form::text('aname',null,['class'=>'form-control','autocomplete'=>'off']) }}
</div>
<div class="form-group">
    {!! \App\Models\Account::renderFormLabel('accountid','Account ID') !!}
    {{ Form::text('accountid',null,['class'=>'form-control','autocomplete'=>'off']) }}
<div class="form-group">
    {!! \App\Models\Account::renderFormLabel('owner_id','Owner') !!}
    {{ Form::select('owner_id', \App\Models\Account::getOwnerSelectOptions(true,'id'),null,['class'=>'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('description','Description') }}
    {{ Form::textarea('description',null,['class'=>'form-control','autocomplete'=>'off']) }}
</div>

<div class="form-group">
    {{ Form::submit('Save', ['class'=>'btn btn-default btn-lg tag-clickme_to_submit']) }}
</div>

</fieldset>
