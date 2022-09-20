<!-- Campaign Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaign_id', 'Campaign Id:') !!}
    {!! Form::text('campaign_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Supergroup Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('supergroup_id', 'Supergroup Id:') !!}
    {!! Form::text('supergroup_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Template Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email_template_id', 'Email Template Id:') !!}
    {!! Form::text('email_template_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Landing Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('landing_id', 'Landing Id:') !!}
    {!! Form::text('landing_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Domain Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('domain_id', 'Domain Id:') !!}
    {!! Form::text('domain_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Schedule Start Field -->
<div class="form-group col-sm-6">
    {!! Form::label('schedule_start', 'Schedule Start:') !!}
    {!! Form::text('schedule_start', null, ['class' => 'form-control']) !!}
</div>

<!-- Schedule End Field -->
<div class="form-group col-sm-6">
    {!! Form::label('schedule_end', 'Schedule End:') !!}
    {!! Form::text('schedule_end', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('schedules.index') !!}" class="btn btn-default">Cancel</a>
</div>
