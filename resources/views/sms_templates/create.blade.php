@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>SMS Template</h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                {!! Form::open(['route' => 'smsTemplates.store', 'files' => false]) !!}
                @include('sms_templates.fields')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
