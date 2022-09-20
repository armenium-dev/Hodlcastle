{!! Form::hidden('email_template_id', $scenario->email_template_id, ['id' => 'email_template_id']) !!}
{!! Form::hidden('domain_id', $scenario->domain_id, ['id' => 'domain_id']) !!}
{!! Form::hidden('send_to_landing', $scenario->send_to_landing, ['id' => 'send_to_landing']) !!}
{!! Form::hidden('redirect_url', $scenario->redirect_url, ['id' => 'redirect_url']) !!}
{{--{!! Form::hidden('landing_id', '', ['id' => 'landing_id']) !!}--}}

<div class="row">
	<div class="col-max-width-650">

		<table class="scenario-params">
			<thead>
				<tr>
					<th colspan="2">1. Selected Scenario:</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						@if($scenario->image)
							<img src="{{ $scenario->image->crop(60, 60, true) }}" />
						@endif
					</td>
					<td>
						<div><strong>{!! $scenario->name !!}</strong></div>
						<div>{!! $scenario->description !!}</div>
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>
				<tr><th colspan="2">2. Select Groups:</th></tr>
				<tr>
					<td colspan="2">
					@foreach($groups as $group_id=>$group_name)
						<div class="col-sm-6">
							<input type="checkbox" name="groups[{{ $group_id }}]" value="{{ $group_id }}" id="group-{{ $group_id }}" {{ isset($campaign) && $campaign->groups->contains($group_id) ? 'checked' : '' }}/>
							<label for="group-{{ $group_id }}">{{ $group_name }}</label>
						</div>
					@endforeach
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>
				<tr><th colspan="2">3. Select Scheduled type:</th></tr>
				<tr>
					<td colspan="2">
						<label style="margin-right: 10px">{!! Form::radio('scheduled_type', \App\Models\Campaign::TYPE_SEND_NOW, true) !!} Send campaign now</label>
						<label>{!! Form::radio('scheduled_type', \App\Models\Campaign::TYPE_SCHEDULED, false) !!} Schedule campaign</label>
					</td>
				</tr>
				<tr><td colspan="2" class="scheduled_type"><hr></td></tr>
				<tr><th colspan="2" class="scheduled_type">4. Select Schedule params:</th></tr>
				<tr>
					<td colspan="2" class="scheduled_type">

						<div class="form-group">
							{!! Form::label('date_range', 'Date range:') !!}
							<input name="schedule[schedule_range]" id="date_range" class="datepicker daterange form-control" data-start="{{ isset($model->schedule) ? $model->schedule->schedule_start : '' }}" data-end="{{ isset($model->schedule) ? $model->schedule->schedule_end : '' }}" />
						</div>

						<div class="form-group">
							{!! Form::label('time_range', 'Time range:') !!}
							<div class="row">
								<div class="col-xs-6">
									<input type="text" name="schedule[time_start]" class="form-control time_start" value="{{ isset($model->schedule) ? \Carbon\Carbon::parse($model->schedule->time_start)->format('H:i') : '' }}" />
								</div>
								<div class="col-xs-6">
									<input type="text" name="schedule[time_end]" class="form-control time_end" value="{{ isset($model->schedule) ? \Carbon\Carbon::parse($model->schedule->time_end)->format('H:i') : '' }}" />
								</div>
							</div>
						</div>

						<div class="form-group">
							{!! Form::checkbox('schedule[send_weekend]', 1, isset($model->schedule) ? $model->schedule->send_weekend : null, ['class' => 'flat-green', 'id' => 'send_weekend']) !!}
							{!! Form::label('send_weekend', 'Send on Weekend') !!}
						</div>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"><hr></td></tr>
				<tr><th colspan="2">Finish:</th></tr>
				{{--@if(Auth::user()->hasRole('captain'))
				<tr>
					<td colspan="2">
						<div class="form-group">
							{!! Form::label('mail_driver', 'Mail driver:') !!}
							{!! Form::select('mail_driver', $mail_drivers, null, ['class' => 'form-control', 'id' => 'mail_driver']) !!}
						</div>
					</td>
				</tr>
				@endif--}}
				<tr>
					<td colspan="2">
						<div class="btn-group">
							<a href="{!! route('scenarios.index') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i> Cancel</a>
							<a class="btn btn-warning" href="javascript:void(0);"
							   data-request-url="{!! route('campaigns.test') !!}"
							   data-request-data="email_template_id,landing_id,domain_id,send_to_landing,redirect_url"
							><i class="fa fa-envelope"></i> Send test email</a>
							{!! Form::button('<i class="fa fa-paper-plane"></i> Start campaign', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
						</div>
					</td>
				</tr>
			</tfoot>
		</table>

	</div>
</div>