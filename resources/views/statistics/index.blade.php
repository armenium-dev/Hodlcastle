@extends('layouts.statistic')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('main.tabs', ['active' => 'statistics'])
        <div class="tab-content pr">
            <section class="section-statistics" style="padding: 30px;">
                <div class="chart-container" style="position: relative">
                    <canvas id="chart1"
                            data-short-labels="{{ $report['labels'] }}"
                            data-data-clicks="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('clicksPercent')->implode(",") }}]"
                            data-data-reports="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('reportsPercent')->implode(",") }}]">
                    </canvas>
                    <canvas id="chart2"
                            data-short-labels="{{ $report['labels'] }}"
                            data-data-fake-auth="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('fakeAuthPercent')->implode(",") }}]"
                            data-data-attachments="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('attachmentsPercent')->implode(",") }}]">
                    </canvas>
                    <canvas id="chart3"
                            data-short-labels="{{ $report['labels'] }}"
                            data-data-no-response="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('noResponsePercent')->implode(",") }}]">
                    </canvas>
                </div>
                <div class="chart-container" style="position: relative;">
                    <h2 style="font-weight: bold;">Publishing results per campaign</h2>
                    <div class="pie-charts d-flex" style="flex-wrap: wrap">
                        @foreach($report['campaigns_for_table'] as $key => $campaign)
                            @continue((!$campaign->clicksCount && !$campaign->reportsCount && !$campaign->noResponseCount) || !$campaign->recipients->count())
                            <section class="d-flex" style="margin-top: 30px; width: 30%; flex-wrap: wrap">
                                <div class="desc" style="width: 50%">
                                    <h3 style="font-weight: bold;">Results of campaign: {{$campaign->name}}</h3>
                                    <p style="margin: 0">{{$campaign->clicksCount}} of {{$campaign->recipients->count()}} found Susceptible</p>
                                    <p style="margin: 0">Unique Recipients: {{$campaign->recipients->count()}}</p>
                                    <p style="margin: 0">Clicked Link: {{$campaign->clicksCount}}</p>
                                    <p style="margin: 0">Reported Only Link: {{$campaign->reportsCount}}</p>
                                </div>
                                <div class="chart" style="width: 40%; margin-left: -25px">
                                    <canvas id="chart-campaign-{{$key}}" class="chart-campaign"
                                            data-clicks-count="{{$campaign->clicksPercent}}"
                                            data-no-response-count="{{$campaign->noResponsePercent}}"
                                            data-report-count="{{$campaign->reportsPercent}}"
                                    >
                                    </canvas>
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
                <div class="chart-container" style="position: relative">
                    <canvas id="chart4"
                            data-short-labels="{{ $report['labels'] }}"
                            data-data-smishs="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('smishsPercent')->implode(",") }}]">
                    </canvas>
                </div>
                <div class="chart-container" style="position: relative;">
                    <h2 style="font-weight: bold;">Smishing results per campaign</h2>
                    <div class="pie-charts d-flex" style="flex-wrap: wrap">
                        @foreach($report['campaigns_for_table'] as $key => $campaign)
                            @continue((!$campaign->noResponseCount && !$campaign->smishsCount) || !$campaign->recipients->count())
                            <section class="d-flex" style="margin-top: 30px; width: 30%; flex-wrap: wrap">
                                <div class="desc" style="width: 60%">
                                    <h3 style="font-weight: bold;">Results of campaign: {{$campaign->name}}</h3>
                                    <p style="margin: 0">{{$campaign->smishsCount}} of {{$campaign->recipients->count()}} found Susceptible</p>
                                    <p style="margin: 0">Unique Recipients: {{$campaign->recipients->count()}}</p>
                                    <p style="margin: 0">Smished: {{$campaign->smishsCount}}</p>
                                </div>
                                <div class="chart" style="width: 40%">
                                    <canvas id="chart-campaign-smish-{{$key}}" class="chart-campaign-smish"
                                            data-no-response-count="{{$campaign->noResponsePercent}}"
                                            data-data-smishs="{{ $campaign->smishsPercent }}"
                                    >
                                    </canvas>
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        $(function () {
            Statistic.init();
        });
    </script>
@endsection


