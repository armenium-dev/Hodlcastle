@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Copy SMS Template</h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::model($smsTemplate, ['route' => ['smsTemplates.store'], 'files' => false]) !!}
                    @include('sms_templates.fields')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection