<!-- PARTIAL: admin.invites._form -->

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

<div class="form-group">
    {{ Form::label('role_id','User Role') }}
    {{ Form::select('role_id', \App\Models\Role::getSelectOptions(),null,['class'=>'form-control']) }}
</div>
<div class="form-group">
    {{ Form::label('email','Contact Email') }}
    {{ Form::text('email',null,['class'=>'form-control','autocomplete'=>'off']) }}
</div>
<div class="form-group">
    {{ Form::submit('Save', ['class'=>'btn btn-default btn-lg tag-clickme_to_submit_form']) }}
</div>

</fieldset>
