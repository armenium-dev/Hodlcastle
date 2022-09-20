@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Scenario Entry</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    @include('scenarios.builder.fields-show')
                </div>
            </div>
        </div>
    </div>
@endsection
