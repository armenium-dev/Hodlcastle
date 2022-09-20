<div class="form-group col-sm-6">
    {!! Form::label('module_id', 'Module:') !!}
    {!! Form::select('module_id', $modules, null, ['class' => 'form-control', 'id' => 'module_id']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('group_id', 'Groups:') !!}
    <div class="row">
    @foreach($groups as $group_id => $group_name)
        <div class="col-sm-6">
            <input type="checkbox" name="groups[{{ $group_id }}]" value="{{ $group_id }}" id="group-{{ $group_id }}"
                   {{ isset($campaign) && $campaign->groups->contains($group_id) ? 'checked' : '' }}
            />
            <label for="group-{{ $group_id }}">{{ $group_name }}</label>
        </div>
    @endforeach
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    <div class="btn-group">
        <a href="{!! route('trainings.index') !!}" class="btn btn-info"><i class="fa fa-caret-left"></i> Cancel</a>
        {!! Form::button(isset($training) ? '<i class="fa fa-paper-plane"></i> Update training' : '<i class="fa fa-paper-plane"></i> Start training', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
    </div>
</div>
