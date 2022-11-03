<div class="col-sm-6">
    <div class="form-group">
        {!! Form::label('module_id', 'Module:') !!}
        {!! Form::select('module_id', $modules, null, ['class' => 'form-control', 'id' => 'module_id']) !!}
    </div>

    <div class="form-group">
        <label for="start_template_id">Tranining Start Notification Template:</label>
        <select id="start_template_id" class="js_templates_dd form-control" name="start_template_id">
            <option value="0">Select template</option>
            @foreach($templates[1] as $lang_name => $subtemplates)
                <optgroup label="{!! $lang_name !!}">
                    @foreach($subtemplates as $subtemplate_id => $item)
                        <option value="{!! $subtemplate_id !!}" module="{!! $item['module_id'] !!}">{!! $item['name'] !!}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="finish_template_id">Tranining Finish Notification Template:</label>
        <select id="finish_template_id" class="js_templates_dd form-control" name="finish_template_id">
            <option value="0">Select template</option>
            @foreach($templates[2] as $lang_name => $subtemplates)
                <optgroup label="{!! $lang_name !!}">
                    @foreach($subtemplates as $subtemplate_id => $item)
                        <option value="{!! $subtemplate_id !!}" module="{!! $item['module_id'] !!}">{!! $item['name'] !!}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="notify_template_id">Tranining Reminder Notification Template:</label>
        <select id="notify_template_id" class="js_templates_dd form-control" name="notify_template_id">
            <option value="0">Select template</option>
            @foreach($templates[3] as $lang_name => $subtemplates)
                <optgroup label="{!! $lang_name !!}">
                    @foreach($subtemplates as $subtemplate_id => $item)
                        <option value="{!! $subtemplate_id !!}" module="{!! $item['module_id'] !!}">{!! $item['name'] !!}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>
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

<script type="text/javascript">
	$(document).ready(function(){

		const changeModule = function(e){
			e.preventDefault();
			e.stopPropagation();

			const $this = $(this),
                module_id = $this.val();

			let $js_templates_dd = $('.js_templates_dd');

			$js_templates_dd
                .find('option[module]').hide()
                .end()
                .find('option[module='+module_id+']').show()
                .end()
                .val(0)
                .addClass('highlight');
		};

		const changeTemplate = function(e){
			e.preventDefault();
			e.stopPropagation();

			const $this = $(this);

			if(~~$this.val() == 0){
				$this.addClass('highlight');
            }else{
				$this.removeClass('highlight');
            }
        };

		const submitForm = function(e){
			e.preventDefault();
			//e.stopPropagation();

			let $form = $(this);
			let $js_templates_dd = $('.js_templates_dd');
			let submit = true;

			$js_templates_dd.each(function(){
				if($(this).val() == 0){
					submit = false;
                }
            });
            console.log(submit);

			if(submit){
				//$form.submit();
            }

			return submit;
        };

		$(document)
            //.on('submit', '#js_training_form', submitForm)
            .on('change', '#module_id', changeModule)
            .on('change', '.js_templates_dd', changeTemplate);

		$('#module_id').trigger('change');
	});
</script>
