@if(Auth::check() && Auth::user()->can('email_template.set_public'))
<div class="col-sm-6">
    <div class="row">
        <div class="form-group col-sm-6">
            {!! Form::label('is_public', 'Public:') !!}<br>
            {!! Form::checkbox('is_public', 1, null, ['class' => '']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('with_attach', 'With attachment:') !!}<br>
            {!! Form::checkbox('with_attach', 1, null, ['class' => '']) !!}
            {{--{!! Form::select('attachment_id', $attachments, 0, ['class' => 'form-control']) !!}--}}
        </div>
    </div>
</div>
@endif

<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('subject', 'Subject:') !!}
    {!! Form::text('subject', null, ['class' => 'form-control']) !!}
</div>
@if(Auth::check() && Auth::user()->can('email_template.set_company'))
<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company') !!}
    {!! Form::select('company_id', $companies, null, ['class' => 'form-control']) !!}
</div>
@endif

<div class="form-group col-sm-12">
    <label style="margin-right: 10px">
        {!! Form::radio('text_type', \App\Models\EmailTemplate::TYPE_PLAIN, true) !!}
        Plain Text
    </label>
    <label>
        {!! Form::radio('text_type', \App\Models\EmailTemplate::TYPE_HTML, false) !!}
        HTML
    </label>

    <div class="text_type text_type-1">
        {{--{!! Form::textarea('html', null, ['class' => 'form-control', 'id' => 'editor-html']) !!}--}}

        {{--<div id="editor">{!! isset($emailTemplate) ? $emailTemplate->html : "" !!}</div>--}}

        <div id="summary_tinymce">
            <textarea class="form-control my-editor" id="summary-tinymce" name="html">
                {!! isset($emailTemplate) ? $emailTemplate->html : "" !!}
            </textarea>
        </div>
    </div>
</div>

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
        <tr>
            <td>@{{.From}}</td>
            <td>Source e-mail address</td>
            <td><a href="" class="copyLink" data-text="@{{.From}}">Copy</a></td>
        </tr>
        <tr>
            <td>@{{.URL}}</td>
            <td>URL to tracking handler (per engagement)</td>
            <td><a href="" class="copyLink" data-text="@{{.URL}}">Copy</a></td>
        </tr>
        <tr>
            <td>@{{.YEAR}}</td>
            <td>Current year (example 2021)</td>
            <td><a href="" class="copyLink" data-text="@{{.YEAR}}">Copy</a></td>
        </tr>
        <tr>
            <td>@{{.MONTH}}</td>
            <td>Current month (number, example: 12)</td>
            <td><a href="" class="copyLink" data-text="@{{.MONTH}}">Copy</a></td>
        </tr>
        <tr>
            <td>@{{.DAY}}</td>
            <td>Current day (example: 27)</td>
            <td><a href="" class="copyLink" data-text="@{{.DAY}}">Copy</a></td>
        </tr>
        @if(count($landing_variables)  > 0)
            @foreach($landing_variables as $landing_variable)
                <tr>
                    <td>@{{.{!! $landing_variable['variable'] !!}}}</td>
                    <td>{!! $landing_variable['description']; !!}</td>
                    <td><a href="" class="copyLink" data-text="@{{.{!! $landing_variable['variable'] !!}}}">Copy</a></td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

@if(Auth::check() && Auth::user()->can('email_template.see_lang_tags_image'))
<div class="form-group col-sm-6">
    {!! Form::label('language_id', 'Lang:') !!}
    {!! Form::select('language_id', $languages, $defult_language_id, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    {!! Form::label('tags', 'Tags:') !!}
    {!! Form::text('tags', '', ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-6">
    @if(isset($emailTemplate) && $emailTemplate->image)
        <img src="{{ $emailTemplate->image->crop(135, 135, true) }}" alt="" />
    @endif
    {!! Form::label('image', 'Image:') !!}
    {!! Form::file('image', ['class' => 'form-control']) !!}
</div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    <div class="btn-group">
        <a href="{!! route('emailTemplates.index') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i> Cancel</a>
        <a class="btn btn-warning" href="javascript:void(0);"
           data-request-url="{!! route('emailTemplates.test') !!}"
           data-request-data="subject,editor-html,company_id"
        ><i class="fa fa-envelope"></i> Send test email</a>
        {!! Form::button('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
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
                height: 600,
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
                }
            };
            console.log(editor_config);
            tinymce.init(editor_config);
        }

    });
</script>
