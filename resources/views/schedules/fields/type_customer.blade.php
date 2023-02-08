@php
use App\Models\Campaign;
use Carbon\Carbon;
@endphp

<div class="form-group">
	<label>
		{!! Form::radio('scheduled_type', Campaign::TYPE_SCHEDULED, true) !!}
		Schedule campaign
	</label>
</div>

<div class="form-group scheduled_type">
	<div class="row">
		<div class="col-xs-6">
			{!! Form::label('date_range', 'Start Date:') !!}
			<input name="schedule[schedule_range]" id="date_range_custom" class="-daterange form-control"
				   data-val="{{ isset($model->schedule) ? $model->schedule->schedule_start : '' }}"
				   data-start="{{ isset($model->schedule) ? $model->schedule->schedule_start : '' }}"
				   data-end="{{ isset($model->schedule) ? $model->schedule->schedule_end : '' }}" />
		</div>
		<div class="col-xs-6">
			<input type="hidden" name="schedule[time_start]" class="form-control time_start"
				   value="{{Carbon::now()->startOfDay()->format('H:i')}}" />
		</div>
		{{--
		<div class="col-xs-6">
			<input type="text" name="schedule[time_end]" class="form-control time_end"
			value="{{ isset($model->schedule) ? Carbon::parse($model->schedule->time_end)->format('H:i') : '' }}" />
		</div>
		--}}
	</div>
</div>

<div class="form-group scheduled_type">
	@if($display_send_weekend)
		{!! Form::checkbox('schedule[send_weekend]', 1, isset($model->schedule) ? $model->schedule->send_weekend : null, ['class' => 'flat-green', 'id' => 'send_weekend'.$checkbox_id_suffix]) !!} {!! Form::label('send_weekend'.$checkbox_id_suffix, 'Send on Weekend:') !!}
	@else
		{!! Form::hidden('schedule[send_weekend]', 0) !!}
	@endif
</div>
