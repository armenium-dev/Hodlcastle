<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company') !!}
    {!! Form::select('company_id', $companies, null, ['class' => 'form-control']) !!}
</div>

<!-- Capture Credentials Field
<div class="form-group col-sm-6">
    {!! Form::label('capture_credentials', 'Capture Credentials:') !!}<br>
    {!! Form::checkbox('capture_credentials', 1, null, ['class' => 'flat-green']) !!}
</div>-->

<div class="form-group col-sm-6">
    {!! Form::label('content', 'Html:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control hide', 'id' => 'editor-html']) !!}

    <div id="editor">
        <p>{!! isset($landing) ? $landing->content : "" !!}</p>
    </div>
</div>

<div class="form-group col-sm-6">
    {!! Form::label('styles', 'Styles:') !!}
    {!! Form::textarea('styles', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('landings.index') !!}" class="btn btn-default">Cancel</a>
</div>
