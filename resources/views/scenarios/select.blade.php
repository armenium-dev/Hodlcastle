@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Scenario 1-Click Setup</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                {!! Form::open(['route' => ['scenarios.finish', $scenario->id], 'method' => 'post']) !!}
                @include('scenarios.fields')
                {!! Form::close() !!}
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

