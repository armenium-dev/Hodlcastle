@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Scenario Entry Edit</h1>
    </section>
    <div class="content">
        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                {!! Form::model($scenario, ['route' => ['scenario.builder.update', $scenario->id], 'method' => 'patch', 'files' => true]) !!}
                    <div class="row">
                        @include('scenarios.builder.fields')
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
