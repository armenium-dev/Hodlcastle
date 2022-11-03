@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Email Template</h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::model($template, ['route' => ['traningNotifyTemplates.update', $template->id], 'method' => 'patch', 'files' => true]) !!}

                    @include('training_templates.fields', ['mode' => 'edit'])

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection