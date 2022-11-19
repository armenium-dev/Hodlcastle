@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Landing Template</h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($template, ['route' => ['landingTemplates.update', $template->id], 'method' => 'patch', 'files' => true]) !!}

                    @include('landing_templates.fields', ['mode' => 'edit'])

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
