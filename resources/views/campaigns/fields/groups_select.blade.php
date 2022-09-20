<div class="form-group">
	{!! Form::label('group_id', 'Groups:') !!}
	<div class="row">
		@foreach($groups as $group_id => $group_name)
			<div class="col-sm-6">
				<input type="checkbox" name="groups[{{$group_id}}]" value="{{$group_id}}" id="group-{{$group_id}}-{{$checkbox_id_suffix}}" {{isset($campaign) && $campaign->groups->contains($group_id) ? 'checked' : ''}}/>
				<label for="group-{{$group_id}}-{{$checkbox_id_suffix}}">{{$group_name}}</label>
			</div>
		@endforeach
	</div>
</div>
