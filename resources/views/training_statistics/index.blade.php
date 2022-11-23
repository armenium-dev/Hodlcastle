@extends('layouts.app')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('trainings.tabs', ['active' => 'training_statistics'])
        <div class="tab-content">
            <div class="tab-pane active">
                <section class="content-header">
                    <h1 class="training-statistics-title">Training Statistics</h1>
                </section>
                <div class="nav-tabs-custom layout">
                    @include('training_statistics.tabs', ['active' => 'trainingStatistics'])
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="content">
                                <div class="clearfix"></div>

                                @include('flash::message')

                                <div class="clearfix"></div>
                                <div class="box box-primary">
                                    <div class="box-body">
                                        @include('training_statistics.table')
                                    </div>
                                </div>
                                <div class="text-center">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .training-statistics-title {
        padding-bottom: 25px;
    }
</style>
