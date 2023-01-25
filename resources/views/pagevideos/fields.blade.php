<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('url', 'Vimeo video Code:') !!}
    {!! Form::text('url', $pagecontent->url ? $pagecontent->url : null, ['class' => 'form-control']) !!}
</div>

@if($pagecontent->url)
    @php
        if(is_numeric($pagecontent->url)){
            $url = 'https://player.vimeo.com/video/'.$pagecontent->url;
        }else{
            $url = 'https://www.youtube.com/embed/'.$pagecontent->url;
        }
    @endphp
<div class="form-group col-sm-12">
    <iframe width="560" height="315" src="{!! $url !!}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    <div class="mt-5">Full URL: <a href="{!! $url !!}" target="_blank">{!! $url !!}</a></div>
</div>
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('courses.edit', ['id' => $page->course_id]) !!}" class="btn btn-default">Back</a>
</div>

