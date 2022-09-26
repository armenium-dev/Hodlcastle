@php $checkbox_id_suffix = '-s'; @endphp
<div class="row">
	<div class="col-max-width-650">

		<table class="scenario-params">
			<thead>
				<tr>
					<th colspan="2">1. Selected SMS template:</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="130">
						@if($sms_template->image)
							<img src="{{ $sms_template->image->crop(100, 100, true) }}" />
						@else
							<img src="/img/thumbnail-phishmanager.png" />
						@endif
					</td>
					<td>
						<div><strong>{!! $sms_template->name !!}</strong></div>
						<div>{!! nl2br($sms_template->content) !!}</div>
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>
				<tr><th colspan="2">2. Enter Name:</th></tr>
				<tr>
					<td colspan="2">
						{!! Form::text('name', null, ['class' => 'form-control']) !!}
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>
				<tr><th colspan="2">3. Select Groups:</th></tr>
				<tr>
					<td colspan="2">
					@foreach($groups as $group_id=>$group_name)
						<div class="col-sm-6">
							<input type="checkbox" name="groups[{{$group_id}}]" value="{{$group_id}}" id="group-{{$group_id}}-{{$checkbox_id_suffix}}" {{isset($sms_template->campaign) && $sms_template->campaign->groups->contains($group_id) ? 'checked' : ''}}/>
							<label for="group-{{$group_id}}-{{$checkbox_id_suffix}}">{{$group_name}}</label>
						</div>
					@endforeach
					</td>
				</tr>
				<tr><td colspan="2" class=""><hr></td></tr>
				<tr><th colspan="2" class="">4. Select Schedule type:</th></tr>
				<tr>
					<td colspan="2" class="">
						@include('schedules.fields.type', ['model' => isset($sms_template->campaign) ? $sms_template->campaign : null, 'checkbox_id_suffix' => $checkbox_id_suffix])
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr><td colspan="2"><hr></td></tr>
				<tr><th colspan="2">Finish:</th></tr>
				<tr>
					<td colspan="2">
						<div class="btn-group">
							<a href="{!! route('smishing') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i> Cancel</a>
							{!! Form::button('<i class="fa fa-paper-plane"></i> Start campaign', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
						</div>
					</td>
				</tr>
			</tfoot>
		</table>

	</div>
</div>