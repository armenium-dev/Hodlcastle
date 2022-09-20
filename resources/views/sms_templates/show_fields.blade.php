<?php
use Carbon\Carbon;
?>

<div class="row">
    <div class="col-sm-6">
        
        <div class="row">
            <div class="col-sm-3 text-right">{!! Form::label('id', 'ID:') !!}</div>
            <div class="col-sm-9">{!! $smsTemplate->id !!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3 text-right">{!! Form::label('is_public', 'Is Public:') !!}</div>
            <div class="col-sm-9">{!! $smsTemplate->is_public ? 'Yes' : 'No' !!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3 text-right">{!! Form::label('name', 'Name:') !!}</div>
            <div class="col-sm-3">{!! $smsTemplate->name !!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3 text-right">{!! Form::label('content', 'Content:') !!}</div>
            <div class="col-sm-9">{!! $smsTemplate->content !!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3 text-right">{!! Form::label('company_id', 'Company:') !!}</div>
            <div class="col-sm-3">{!! $smsTemplate->company->name !!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3 text-right">{!! Form::label('language_id', 'Language:') !!}</div>
            <div class="col-sm-9">{!! @$smsTemplate->language->name !!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3 text-right">{!! Form::label('created_at', 'Created At:') !!}</div>
            <div class="col-sm-9">{!! Carbon::parse($smsTemplate->created_at)->format('Y-m-d / H:i') !!}</div>
        </div>
        <div class="row">
            <div class="col-sm-3 text-right">{!! Form::label('updated_at', 'Updated At:') !!}</div>
            <div class="col-sm-9">{!! Carbon::parse($smsTemplate->updated_at)->format('Y-m-d / H:i') !!}</div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                {!! Form::open(['route' => ['smsTemplates.destroy', $smsTemplate->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('smsTemplates.index') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i> Back</a>
                    <a href="{!! route('smsTemplates.edit', $smsTemplate->id) !!}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                    {!! Form::button('<i class="fa fa-trash"></i> Delete', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    <a href="{!! route('smsTemplates.preview', $smsTemplate->id) !!}" class="btn btn-info" target="_blank"><i class="fa fa-link"></i> Open in New tab</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>

