<!-- Campaign Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('campaign_id', 'Campaign Id:') !!}
    {!! Form::text('campaign_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Customer Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_id', 'Customer Id:') !!}
    {!! Form::text('customer_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Redirect Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('redirect_id', 'Redirect Id:') !!}
    {!! Form::text('redirect_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- First Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('first_name', 'First Name:') !!}
    {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::text('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Ip Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ip', 'Ip:') !!}
    {!! Form::text('ip', null, ['class' => 'form-control']) !!}
</div>

<!-- Lat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lat', 'Lat:') !!}
    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
</div>

<!-- Lng Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lng', 'Lng:') !!}
    {!! Form::text('lng', null, ['class' => 'form-control']) !!}
</div>

<!-- Send Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('send_date', 'Send Date:') !!}
    {!! Form::text('send_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Reported Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reported', 'Reported:') !!}
    {!! Form::text('reported', null, ['class' => 'form-control']) !!}
</div>

<!-- Sent Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sent', 'Sent:') !!}
    {!! Form::text('sent', null, ['class' => 'form-control']) !!}
</div>

<!-- Open Field -->
<div class="form-group col-sm-6">
    {!! Form::label('open', 'Open:') !!}
    {!! Form::text('open', null, ['class' => 'form-control']) !!}
</div>

<!-- Click Field -->
<div class="form-group col-sm-6">
    {!! Form::label('click', 'Click:') !!}
    {!! Form::text('click', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('results.index') !!}" class="btn btn-default">Cancel</a>
</div>
