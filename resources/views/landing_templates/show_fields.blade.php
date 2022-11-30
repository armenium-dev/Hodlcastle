<?php
use Carbon\Carbon;
?>
<div class="row">
    <div class="col-sm-2 text-right">{!! Form::label('id', 'ID:') !!}</div>
    <div class="col-sm-10">{!! $template->id !!}</div>
</div>
<div class="row">
    <div class="col-sm-2 text-right">{!! Form::label('created_at', 'Created At:') !!}</div>
    <div class="col-sm-10">{!! Carbon::parse($template->created_at)->format('Y-m-d / H:i') !!}</div>
</div>
<div class="row">
    <div class="col-sm-2 text-right">{!! Form::label('updated_at', 'Updated At:') !!}</div>
    <div class="col-sm-10">{!! Carbon::parse($template->updated_at)->format('Y-m-d / H:i') !!}</div>
</div>
<div class="row">
    <div class="col-sm-2 text-right">{!! Form::label('is_public', 'Is Public:') !!}</div>
    <div class="col-sm-10">{!! $template->is_public ? 'Yes' : 'No' !!}</div>
</div>
<div class="row">
    <div class="col-sm-2 text-right">{!! Form::label('name', 'Name:') !!}</div>
    <div class="col-sm-10">{!! $template->name !!}</div>
</div>

<div class="row">
    <div class="col-sm-2 text-right">{!! Form::label('company_id', 'Company:') !!}</div>
    <div class="col-sm-10">{!! @$template->company->name !!}</div>
</div>
<div class="row">
    <div class="col-sm-2 text-right">{!! Form::label('url', 'Url:') !!}</div>
    <div class="col-sm-10"><a href="{{$template->url}}">{{$template->url}}</a></div>
</div>
<div class="row">
    <div class="col-sm-2 text-right">{!! Form::label('content', 'Content:') !!}</div>
    <div class="col-sm-10"><iframe src="{!! route('landingTemplates.preview', $template->id) !!}" frameborder="1" sandbox="allow-same-origin" allowfullscreen="" scrolling="auto" width="100%" height="600"></iframe></div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="btn-group">
            {!! Form::open(['route' => ['landingTemplates.destroy', $template->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
                <a href="{!! route('landingTemplates.index') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i> Back</a>
                <a href="{!! route('landingTemplates.edit', $template->id) !!}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                {!! Form::button('<i class="fa fa-trash"></i> Delete', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                <a href="{!! route('landingTemplates.preview', $template->id) !!}" class="btn btn-info" target="_blank"><i class="fa fa-link"></i> Open in New tab</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
