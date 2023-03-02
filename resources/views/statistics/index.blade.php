@extends('layouts.statistic')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('main.tabs', ['active' => 'statistics'])
        <div class="tab-content pr">
            <section class="section-statistics" style="padding: 30px 5px;">

                <!--Campaigns-->
                <div class="chart-container d-flex" style="position: relative">
                    <div class="form-group col-sm-11 p-0" style="padding: 0">
                        <div class="chart">
                            <canvas id="chartHome" style="height:400px"
                                    data-labels="{{ $campaigns_for_table->sortBy('created_at')->pluck('name')->implode(",") }}"
                                    data-short-labels="{{ $labels->implode(",") }}"
                                    data-data-sents="[{{ $campaigns_for_table->sortBy('created_at')->pluck('sentsCount')->implode(",") }}]"
                                    data-data-opens="[{{ $campaigns_for_table->sortBy('created_at')->pluck('opensCount')->implode(",") }}]"
                                    data-data-fakeauth="[{{ $campaigns_for_table->sortBy('created_at')->pluck('fake_auth')->implode(",") }}]"
                                    data-data-clicks="[{{ $campaigns_for_table->sortBy('created_at')->pluck('clicksCount')->implode(",") }}]"
                                    data-data-reports="[{{ $campaigns_for_table->sortBy('created_at')->pluck('reportsCount')->implode(",") }}]"
                                    data-data-attachments="[{{ $campaigns_for_table->sortBy('created_at')->pluck('attachmentsCount')->implode(",") }}]"
                                    data-data-smishs="[{{ $campaigns_for_table->sortBy('created_at')->pluck('smishsCount')->implode(",") }}]"
                            >
                            </canvas>
                        </div>
                    </div>
                    <div class="form-group col-sm-1 p-0">
                        <div class="row">
                            <h4>Baseline:</h4>
                        </div>
                        <div class="row">
                            @if($baseline || $baseline === 0)
                                <h1>{{ $baseline }} %</h1>
                            @else
                                <h1>-</h1>
                            @endif
                        </div>
                    </div>
                </div>
                <!--END Campaigns-->

                <!--Click rate versus reporting rate-->
                <div class="chart-container" style="position: relative">
                    <canvas id="chart1"
                            data-short-labels="{{ $report['labels'] }}"
                            data-data-clicks="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('clicksPercent')->implode(",") }}]"
                            data-data-reports="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('reportsPercent')->implode(",") }}]">
                    </canvas>
                </div>
                <!--END Click rate versus reporting rate-->

                <!--Compromised users-->
                <div class="chart-container" style="position: relative">
                    <canvas id="chart2"
                            data-short-labels="{{ $report['labels'] }}"
                            data-data-fake-auth="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('fakeAuthPercent')->implode(",") }}]"
                            data-data-attachments="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('attachmentsPercent')->implode(",") }}]">
                    </canvas>
                </div>
                <!--END Compromised users-->

                <!--
                <div class="chart-container" style="position: relative">
                    {{--<canvas id="chart3" data-short-labels="{{ $report['labels'] }}" data-data-no-response="[{{ $report['campaigns_for_table']->sortBy('created_at')->pluck('sentsOnlyPercent')->implode(",") }}]"></canvas>--}}
                </div>
                -->

                <!-- Smish rate-->
                <div class="chart-container" style="position: relative">
                    <canvas id="chart4"
                            data-short-labels="{{ $report['smishingLabels'] }}"
                            data-data-smishs="[{{ $report['smishing_campaigns_for_table']->sortBy('created_at')->pluck('smishsPercent')->implode(",") }}]">
                    </canvas>
                </div>
                <!-- END Smish rate-->

                <!--smishing Per Location-->
                <div class="chart-container" style="position: relative">
                    <canvas
                        id="chartSmishingPerLocation"
                        style="height:400px"
                        data-recipients="{{ $report['smishingPerLocation'] }}">
                    </canvas>
                </div>
                <!--END smishing Per Location-->



                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                            <a class="panel-title d-flex justify-space-between collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <span class="flex-1">Publishing results per campaign</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="bottom w-24 h-24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="top w-24 h-24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                                <!--Publishing results per campaign-->
                                <div class="chart-container" style="position: relative;">
                                    <h2 style="font-weight: bold;">Publishing results per campaign</h2>
                                    <div class="pie-charts d-flex" style="flex-wrap: wrap">
                                        @foreach($report['campaigns_for_table'] as $key => $campaign)
                                            @continue(!$campaign->recipients_count || (!$campaign->clicksCount && !$campaign->reportOnlyCount))

                                            <section class="d-flex" style="margin-top: 30px; width: 30%; flex-wrap: wrap">
                                                <div class="desc" style="width: 50%">
                                                    <h4 style="font-weight: bold;">Results of campaign: {{$campaign->name}}</h4>
                                                    <p style="margin: 0">{{$campaign->clicksCount}} of {{$campaign->recipients_count}}
                                                        found Susceptible</p>
                                                    <p style="margin: 0">Unique Recipients: {{$campaign->recipients_count}}</p>
                                                    <p style="margin: 0">Clicks: {{$campaign->clicksCount}}</p>
                                                    <p style="margin: 0">Reported Only Link: {{$campaign->reportOnlyCount}}</p>
                                                </div>
                                                <div class="chart" style="width: 50%; margin-left: -15px">
                                                    <canvas id="chart-campaign-{{$key}}" class="chart-campaign"
                                                            data-sents-only-count="{{$campaign->sentOnlyCount}}"
                                                            data-clicks-only-count="{{$campaign->clicksOnlyCount}}"
                                                            data-opens-only-count="{{$campaign->openOnlyCount}}"
                                                            data-reports-only-count="{{$campaign->reportOnlyCount}}"
                                                            data-clicks-count="{{$campaign->clicksCount}}"
                                                            data-report-count="{{$campaign->reportsCount}}"
                                                            data-open-count="{{$campaign->opensCount}}"
                                                            data-recipients-count="{{$campaign->recipients_count}}"
                                                    >
                                                    </canvas>
                                                </div>
                                            </section>
                                        @endforeach
                                    </div>
                                </div>
                                <!--END Publishing results per campaign-->
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingTwo">
                            <a class="panel-title d-flex justify-space-between collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <span class="flex-1">Smishing results per campaign</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="bottom w-24 h-24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="top w-24 h-24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <!--Smishing results per campaign-->
                                <div class="chart-container" style="position: relative;">
                                    <h2 style="font-weight: bold;">Smishing results per campaign</h2>
                                    <div class="pie-charts d-flex" style="flex-wrap: wrap">
                                        @foreach($report['smishing_campaigns_for_table'] as $key => $campaign)
                                            @continue(!$campaign->sentsOnlyPercent && !$campaign->smishsPercent)
                                            <section class="d-flex" style="margin-top: 30px; width: 30%; flex-wrap: wrap">
                                                <div class="desc" style="width: 50%">
                                                    <h4 style="font-weight: bold;">Results of campaign: {{$campaign->name}}</h4>
                                                    <p style="margin: 0">{{$campaign->smishsCount}} of {{$campaign->recipients_count}}
                                                        found Susceptible</p>
                                                    <p style="margin: 0">Unique Recipients: {{$campaign->recipients_count}}</p>
                                                    <p style="margin: 0">Smished: {{$campaign->smishsCount}}</p>
                                                </div>
                                                <div class="chart" style="width: 50%; margin-left: -15px">
                                                    <canvas id="chart-campaign-smish-{{$key}}" class="chart-campaign-smish"
                                                            data-data-sents="{{$campaign->sentsOnlyPercent}}"
                                                            data-data-smishs="{{ $campaign->smishsPercent}}"
                                                    >
                                                    </canvas>
                                                </div>
                                            </section>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- END Smishing results per campaign-->
                            </div>
                        </div>
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


