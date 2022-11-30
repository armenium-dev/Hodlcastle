@extends('layouts.app')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('trainings.tabs', ['active' => 'trainings'])
        <div class="tab-content">
            <div class="tab-pane active">
                <section class="content-header">
                    <div class="pull-right">
                        <a class="btn btn-success" href="{!! route('trainings.create') !!}">Start New</a>
                    </div>
                    <h1>Trainings</h1>
                </section>
                <div class="content">
                    <div class="clearfix"></div>

                    @include('flash::message')

                    <div class="clearfix"></div>
                    <div class="box box-primary">
                        <div class="box-body">
                            @include('trainings.table')
                        </div>
                    </div>
                    <div class="text-center">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
