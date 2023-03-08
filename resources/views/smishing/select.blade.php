@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Smishing - One-Click Setup</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::open(['route' => ['campaigns.store'], 'method' => 'post']) !!}
                    {!! Form::hidden('is_short', 1) !!}
                    {!! Form::hidden('email', config('mail.email_service_desk')) !!}
                    {!! Form::hidden('schedule[sms_template_id]', $sms_template->id) !!}
                    {!! Form::hidden('schedule[send_to_landing]', 1) !!}
                    {!! Form::hidden('schedule[email_template_id]', 0) !!}
                    {!! Form::hidden('type', 'sms') !!}
                    @include('smishing.fields')
                {!! Form::close() !!}
            </div>
        </div>
        <div class="text-center"></div>
    </div>
@endsection

