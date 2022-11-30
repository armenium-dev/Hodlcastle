@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Copy Email Template</h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($template, ['route' => ['landingTemplates.store'], 'files' => true]) !!}
                        @include('landing_templates.fields', ['mode' => 'copy'])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
