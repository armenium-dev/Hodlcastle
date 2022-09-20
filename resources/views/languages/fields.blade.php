<div class="row">
    <div class="form-group col-sm-3">
        {!! Form::label('name', 'Name:') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-3">
        {!! Form::label('code', 'Code:') !!}
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
        <a href="{!! route('languages.index') !!}" class="btn btn-default">Cancel</a>
    </div>
</div>