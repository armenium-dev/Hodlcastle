<div class="form-group">
	{!! Form::label('domain_id', 'Domain:') !!}
	{!! Form::select('schedule[domain_id]', $domains, null, ['class' => 'form-control', 'id' => 'domain_id']) !!}
</div>
<div class="form-group">
	<div class="form-group">
        <input type="hidden" name="schedule[send_to_landing]" value="0">
		{{-- Form::checkbox('schedule[send_to_landing]', 1, isset($model->schedule) ? $model->schedule->send_to_landing : true, ['class' => 'flat-green', 'id' => 'send_to_landing'.$checkbox_id_suffix]) !!} {!! Form::label('send_to_landing'.$checkbox_id_suffix, 'Send to default landing') --}}
	</div>
	<div class="form-group">
        {!! Form::label('landing_template', 'Landing Template:') !!}
        {!! Form::select('schedule[redirect_url]', $landingTemplates, null, ['class' => 'form-control', 'id' => 'landing_template']) !!}

        {{--		or {!! Form::label('redirect_url', 'Redirect url:') !!}--}}
{{--		{!! Form::text('schedule[redirect_url]', null, ['class' => 'form-control', 'id' => 'redirect_url']) !!}--}}
	</div>
</div>

{{--
<div class="form-group">
	@if(Auth::user()->can('schedules.landing_select'))
    {!! Form::label('landing_id', 'Landing Page:') !!}
	{!! Form::select('schedule[landing_id]', $landings, null, ['class' => 'form-control', 'id' => 'landing_id']) !!}
	@endif
</div>
--}}
