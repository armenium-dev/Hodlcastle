@extends('layouts.app')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('main.tabs', ['active' => 'dashboard'])
        <div class="tab-content">
            <div class="tab-pane active">

                <div class="content-header">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Welcome</h3>
                        </div>
                        <div class="box-body">
                            Welcome to Phishmanager. Your Dashboard will get populated over time with campaigns and
                            statistics. Visit our Documentation page for more information on how to get started.
                        </div>
                    </div>
                </div>

                <section class="content-header">
                    <div class="pull-right">
                        <a class="btn btn-success" href="{!! route('campaigns.create') !!}">Start New</a>
                    </div>
                    <h1>Campaigns</h1>
                </section>

                <div class="clearfix"></div>

                @include('flash::message')

                <div class="clearfix"></div>

                {{--@if(Auth::user()->hasRole('captain'))--}}
{{--                <div class="row">--}}
{{--                    <div class="form-group col-sm-11">--}}
{{--                        <div class="chart">--}}
{{--                            <canvas id="chartHome" style="height:400px"--}}
{{--                                    data-labels="{{ $campaigns_for_table->sortBy('created_at')->pluck('name')->implode(",") }}"--}}
{{--                                    data-short-labels="{{ $labels->implode(",") }}"--}}
{{--                                    data-data-sents="[{{ $campaigns_for_table->sortBy('created_at')->pluck('sentsCount')->implode(",") }}]"--}}
{{--                                    data-data-opens="[{{ $campaigns_for_table->sortBy('created_at')->pluck('opensCount')->implode(",") }}]"--}}
{{--                                    data-data-fakeauth="[{{ $campaigns_for_table->sortBy('created_at')->pluck('fake_auth')->implode(",") }}]"--}}
{{--                                    data-data-clicks="[{{ $campaigns_for_table->sortBy('created_at')->pluck('clicksCount')->implode(",") }}]"--}}
{{--                                    data-data-reports="[{{ $campaigns_for_table->sortBy('created_at')->pluck('reportsCount')->implode(",") }}]"--}}
{{--                                    data-data-attachments="[{{ $campaigns_for_table->sortBy('created_at')->pluck('attachmentsCount')->implode(",") }}]"--}}
{{--                                    data-data-smishs="[{{ $campaigns_for_table->sortBy('created_at')->pluck('smishsCount')->implode(",") }}]"--}}
{{--                            >--}}
{{--                            </canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="form-group col-sm-1">--}}
{{--                        <div class="row">--}}
{{--                            <h4>Baseline:</h4>--}}
{{--                        </div>--}}
{{--                        <div class="row">--}}
{{--                            @if($baseline || $baseline === 0)--}}
{{--                                <h1>{{ $baseline }} %</h1>--}}
{{--                            @else--}}
{{--                                <h1>-</h1>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="content">
                    @include('campaigns.table', ['show_status' => 1])
                </div>
            </div>
        </div>
    </div>
@endsection
