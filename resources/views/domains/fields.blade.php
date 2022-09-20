<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('domain', 'Domain:') !!}
    {!! Form::text('domain', null, ['class' => 'form-control']) !!}
</div>
@if(Auth::user()->can('domain.view_company'))
<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company:') !!}
    {!! Form::select('company_id', $companies, null, ['class' => 'form-control']) !!}
</div>
@endif
@if(Auth::user()->can('domains.set_public'))
    <div class="form-group col-sm-6">
        {!! Form::label('is_public', 'Is public:') !!}<br>
        {!! Form::checkbox('is_public', 1, isset($domain) ? $domain->is_public : false) !!}
    </div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('domains.index') !!}" class="btn btn-default">Cancel</a>
</div>
