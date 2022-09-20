<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('text', 'Text:') !!}
    {!! Form::textarea('text', $pagecontent->text ? $pagecontent->text : null, ['class' => 'form-control my-editor']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('courses.edit', ['id' => $page->course_id]) !!}" class="btn btn-default">Back</a>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        if($('textarea.my-editor'.length)){
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
                relative_urls: false,
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