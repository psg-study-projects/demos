<!-- PARTIAL: admin.widgets._form -->

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
    {!! \App\Models\Widget::renderFormLabel('wname','Widget Name') !!}
    {{ Form::text('wname',null,['class'=>'form-control','autocomplete'=>'off']) }}
</div>
<div class="form-group">
    {!! \App\Models\Widget::renderFormLabel('account_id','Owning Account') !!}
    {{ Form::select('account_id', \App\Models\Account::getSelectOptions(true,'id'),null,['class'=>'form-control']) }}
</div>
<div class="form-group">
    {!! \App\Models\Widget::renderFormLabel('wstate','Widget State') !!}
    {{ Form::select('wstate', \App\Models\WstateEnum::getSelectOptions(true,'id'),null,['class'=>'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('description','Description') }}
    {{ Form::textarea('description',null,['class'=>'form-control','autocomplete'=>'off']) }}
</div>

<div class="form-group">
    {{ Form::submit('Save', ['class'=>'btn btn-default btn-lg tag-clickme_to_submit']) }}
</div>

</fieldset>
