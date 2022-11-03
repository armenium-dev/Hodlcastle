<div class="form-group col-sm-4">
    {!! Form::label('name', 'Template Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('type_id', 'Template for Notification:') !!}<br>
    {!! Form::select('type_id', $types, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('module_id', 'Training Module:') !!}<br>
    {!! Form::select('module_id', $modules, null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::label('subject', 'Subject:') !!}
    {!! Form::text('subject', null, ['class' => 'form-control']) !!}
</div>

@if(Auth::check() && Auth::user()->can('email_template.set_company'))
    <div class="form-group col-sm-4">
        {!! Form::label('company_id', 'For Company:') !!}
        {!! Form::select('company_id', $companies, null, ['class' => 'form-control']) !!}
    </div>
@endif

@if(Auth::check() && Auth::user()->can('email_template.see_lang_tags_image'))
    <div class="form-group col-sm-4">
        {!! Form::label('language_id', 'Lang:') !!}
        {!! Form::select('language_id', $languages, $defult_language_id, ['class' => 'form-control']) !!}
    </div>
@endif

<div class="form-group col-sm-12">
    <div class="text_type text_type-1">
        <div id="summary_tinymce">
            <textarea class="form-control my-editor" id="summary-tinymce" name="content">
                {!! isset($template) ? $template->content : "" !!}
            </textarea>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="row">
        <div class="form-group col-sm-6">
            <table class="table">
                <thead>
                    <tr>
                        <th>Variable</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>@{{.FirstName}}</td>
                        <td>Target’s first name</td>
                        <td><a href="" class="copyLink" data-text="@{{.FirstName}}">Copy</a></td>
                    </tr>
                    <tr>
                        <td>@{{.LastName}}</td>
                        <td>Target’s last name</td>
                        <td><a href="" class="copyLink" data-text="@{{.LastName}}">Copy</a></td>
                    </tr>
                    <tr>
                        <td>@{{.Position}}</td>
                        <td>Target’s position</td>
                        <td><a href="" class="copyLink" data-text="@{{.Position}}">Copy</a></td>
                    </tr>
                    <tr>
                        <td>@{{.Department}}</td>
                        <td>Target’s department</td>
                        <td><a href="" class="copyLink" data-text="@{{.Department}}">Copy</a></td>
                    </tr>
                    <tr>
                        <td>@{{.Email}}</td>
                        <td>Target’s e-mail</td>
                        <td><a href="" class="copyLink" data-text="@{{.Email}}">Copy</a></td>
                    </tr>
                    <tr class="hidden">
                        <td>@{{.From}}</td>
                        <td>Source e-mail address</td>
                        <td><a href="" class="copyLink" data-text="@{{.From}}">Copy</a></td>
                    </tr>
                    <tr>
                        <td>@{{.URL}}</td>
                        <td>URL to start training</td>
                        <td><a href="" class="copyLink" data-text="@{{.URL}}">Copy</a></td>
                    </tr>
                    <tr>
                        <td>@{{.YEAR}}</td>
                        <td>Current year (number, example {!! date('Y') !!})</td>
                        <td><a href="" class="copyLink" data-text="@{{.YEAR}}">Copy</a></td>
                    </tr>
                    <tr>
                        <td>@{{.MONTH}}</td>
                        <td>Current month (number, example: {!! date('m') !!})</td>
                        <td><a href="" class="copyLink" data-text="@{{.MONTH}}">Copy</a></td>
                    </tr>
                    <tr>
                        <td>@{{.DAY}}</td>
                        <td>Current day (number, example: {!! date('d') !!})</td>
                        <td><a href="" class="copyLink" data-text="@{{.DAY}}">Copy</a></td>
                    </tr>
                   {{--
                    @if(count($landing_variables)  > 0)
                        @foreach($landing_variables as $landing_variable)
                            <tr>
                                <td>@{{.{!! $landing_variable['variable'] !!}}}</td>
                                <td>{!! $landing_variable['description']; !!}</td>
                                <td><a href="" class="copyLink" data-text="@{{.{!! $landing_variable['variable'] !!}}}">Copy</a></td>
                            </tr>
                        @endforeach
                    @endif
                    --}}
                </tbody>
            </table>
        </div>

        <div class="col-sm-6">
            @if(Auth::check() && Auth::user()->can('email_template.set_public'))
                <div class="form-group hidden">
                    {!! Form::label('is_public', 'Public template:') !!} {!! Form::checkbox('is_public', 1, $default_is_public, ['class' => '']) !!}
                </div>
            @endif

            <div class="form-group hidden">
                @if(isset($emailTemplate) && $emailTemplate->image)
                    <img src="{{ $emailTemplate->image->crop(135, 135, true) }}" alt="" />
                @endif
                {!! Form::label('image', 'Image:') !!}
                {!! Form::file('image', ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>

<div class="form-group col-sm-12">
    <div class="btn-group">
        <a href="{!! route('traningNotifyTemplates.index') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i> Cancel</a>
        {!! Form::button('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
		var mode = '{!! $mode !!}';
		var templates = {!! $default_templates !!};

        if($('.copyLink').length >= 0) {
            $('.copyLink').on('click', function (e) {
                e.preventDefault();
                var text = $(this).data('text');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(text).select();
                document.execCommand("copy");
                $temp.remove();
            })
        }

        if($('#summary_tinymce'.length)){
            var editor_config = {
                path_absolute: "{{ URL::to('/') }}/",
                selector:'textarea.my-editor',
                width: '100%',
                height: 300,
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: 1,
                remove_script_host: 1,
                file_browser_callback : function(field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file : cmsURL,
                        title : 'Filemanager',
                        width : x * 0.8,
                        height : y * 0.8,
                        resizable : "yes",
                        close_previous : "yes"
                    });
                },
				setup: function (editor) {
					editor.on('init', function () {
						if(mode == 'create'){
							var v = $('#type_id').val();
							editor.setContent(templates[v]);
						}
					});
				}
			};
            tinymce.init(editor_config);
        }

		$('#type_id').on('change', function(){
			if(mode == 'create'){
				var v = $(this).val();
				tinymce.activeEditor.setContent(templates[v]);
			}
        });

		$('#company_id').on('change', function(){
			var v = ~~$(this).val();
            $('#is_public').prop('checked', !(v > 0));
        }).trigger('change');
    });
</script>
