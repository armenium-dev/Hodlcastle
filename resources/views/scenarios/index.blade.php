@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Scenarios</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        @include('scenarios.tabs')
    </div>
@endsection

