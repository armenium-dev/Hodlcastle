<!-- Fields -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $course->id !!}</p>
</div>

<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $course->name !!}</p>
</div>

<div class="form-group">
    {!! Form::label('language', 'Language:') !!}
    <p>{!! $course->language->name !!}</p>
</div>

<div class="form-group">
    {!! Form::label('module', 'Module:') !!}
    <p>{!! $course->module->name !!}</p>
</div>

<div class="form-group">
    {!! Form::label('public', 'Public:') !!}
    <p>{!! $course->public ? 'yes' : 'no' !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $course->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{!! $course->updated_at !!}</p>
</div>

