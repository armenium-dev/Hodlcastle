<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('url', 'Vimeo video Code:') !!}
    {!! Form::text('url', $pagecontent->url ? $pagecontent->url : null, ['class' => 'form-control']) !!}
</div>

@if($pagecontent->url)
<div class="form-group col-sm-12">
    <iframe width="560" height="315" src="https://player.vimeo.com/video/{!! $pagecontent->url !!}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    <div class="mt-5">Full URL: <a href="https://player.vimeo.com/video/{!! $pagecontent->url !!}" target="_blank">player.vimeo.com/video/{!! $pagecontent->url !!}</a></div>
</div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('courses.edit', ['id' => $page->course_id]) !!}" class="btn btn-default">Back</a>
</div>

