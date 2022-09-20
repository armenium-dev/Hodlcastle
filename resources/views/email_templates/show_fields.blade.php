<?php
use Carbon\Carbon;
?>
<div class="row">
    <div class="col-sm-1 text-right">{!! Form::label('id', 'ID:') !!}</div>
    <div class="col-sm-2">{!! $emailTemplate->id !!}</div>
    <div class="col-sm-1 text-right">{!! Form::label('language_id', 'Language:') !!}</div>
    <div class="col-sm-3">{!! @$emailTemplate->language->name !!}</div>
</div>
<div class="row">
    <div class="col-sm-1 text-right">{!! Form::label('is_public', 'Is Public:') !!}</div>
    <div class="col-sm-2">{!! $emailTemplate->is_public ? 'Yes' : 'No' !!}</div>
    <div class="col-sm-1 text-right">{!! Form::label('name', 'Name:') !!}</div>
    <div class="col-sm-3">{!! $emailTemplate->name !!}</div>
</div>
<div class="row">
    <div class="col-sm-1 text-right">{!! Form::label('created_at', 'Created At:') !!}</div>
    <div class="col-sm-2">{!! Carbon::parse($emailTemplate->created_at)->format('Y-m-d / H:i') !!}</div>
    <div class="col-sm-1 text-right">{!! Form::label('subject', 'Subject:') !!}</div>
    <div class="col-sm-3">{!! $emailTemplate->subject !!}</div>
</div>
{{--
<div class="row">
    <div class="col-sm-1 text-right">{!! Form::label('tags', 'Tags:') !!}</div>
    <div class="col-sm-2">{!! $emailTemplate->tags !!}</div>
</div>
--}}
<div class="row">
    <div class="col-sm-1 text-right">{!! Form::label('updated_at', 'Updated At:') !!}</div>
    <div class="col-sm-2">{!! Carbon::parse($emailTemplate->updated_at)->format('Y-m-d / H:i') !!}</div>
    <div class="col-sm-1 text-right">{!! Form::label('company_id', 'Company:') !!}</div>
    <div class="col-sm-3">{!! $emailTemplate->company->name !!}</div>
</div>
{{--
@if(isset($emailTemplate) && $emailTemplate->image)
<div class="row">
    <div class="col-sm-1 text-right">{!! Form::label('image', 'Image:') !!}</div>
    <div class="col-sm-11">
        <img src="{!! $emailTemplate->image->crop(50, 50, true) !!}" alt="" />
    </div>
</div>
@endif
--}}
<div class="row">
    <div class="col-sm-1 text-right">{!! Form::label('html', 'Html Content:') !!}</div>
    <div class="col-sm-11"><iframe src="{!! route('emailTemplates.preview', $emailTemplate->id) !!}" frameborder="1" sandbox="allow-same-origin" allowfullscreen="" scrolling="auto" width="100%" height="600"></iframe></div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="btn-group">
            {!! Form::open(['route' => ['emailTemplates.destroy', $emailTemplate->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
                <a href="{!! route('emailTemplates.index') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i> Back</a>
                <a href="{!! route('emailTemplates.edit', $emailTemplate->id) !!}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                {!! Form::button('<i class="fa fa-trash"></i> Delete', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                <a href="{!! route('emailTemplates.preview', $emailTemplate->id) !!}" class="btn btn-info" target="_blank"><i class="fa fa-link"></i> Open in New tab</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

