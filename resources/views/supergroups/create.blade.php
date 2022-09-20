@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Supergroup</h1>
    </section>
    <div class="content" id="app">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                    {!! Form::open(['route' => 'supergroups.store']) !!}

                    @include('supergroups.form_tabs')

                    {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
