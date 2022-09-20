@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Scenario Entry Create</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                {!! Form::open(['route' => 'scenario.builder.store', 'files' => true]) !!}
                    <div class="row">
                        @include('scenarios.builder.fields')
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
