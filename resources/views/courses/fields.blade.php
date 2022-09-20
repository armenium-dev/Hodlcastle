<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('language_id', 'Language:') !!}
    {!! Form::select('language_id', $languages, $defult_language_id, ['placeholder' => 'Please select ...', 'class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('module_id', 'Module:') !!}
    {!! Form::select('module_id', $modules, null, ['placeholder' => 'Please select ...', 'class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('public', 'Is public:') !!}<br>
    {!! Form::checkbox('public', 1, isset($course) ? $course->public : false) !!}
</div>

<div class="form-group col-sm-12">
    @if(isset($course))
        <draggables id="{{ $course->id }}"></draggables>
    @else
        <draggables></draggables>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('courses.index') !!}" class="btn btn-default">Cancel</a>
</div>

