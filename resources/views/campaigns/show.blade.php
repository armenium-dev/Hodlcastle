@extends('layouts.app')

@section('content')
    <?php $campaign->clicks()?>
    <section class="content-header">
        <h1>Campaign</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    <div class="col-md-6">
                        <div class="chart-block">
                            <div class="box-header with-border">
                                <h3 class="box-title">Results Map</h3>
                            </div>
                            <div id="mapid" data-markers="{{ $campaign->resultsMarkers() }}"></div>
                        </div>

                        <div class="chart-block">
                            <div class="box-header with-border">
                                <h3 class="box-title">Results Chart</h3>
                            </div>
                            <!--<canvas id="chartResults" style="height:250px"
                                data-sent="{{ $campaign->countResults('sent') }}"
                                data-open="{{ $campaign->countResults('open') }}"
                                data-click="{{ $campaign->countResults('click') }}"
                                data-fake-auth="{{ $campaign->countResults('fake_auth') }}"
                                data-report="{{ $campaign->countResults('report') }}"
                            ></canvas>-->
                            <div class="chart">
                                <canvas id="barChart" style="height:230px"
                                        data-labels="{{ implode(',', \App\Models\Result::sTypeTitles()) }}"
                                        data-data="{{ json_encode($campaign->resultsDataChart) }}"
                                ></canvas>
                            </div>
                        </div>

                        <!--<div class="chart-block">
                            <div class="box-header with-border">
                                <h3 class="box-title">User Agents</h3>
                            </div>
                            <canvas id="chartBrowsers" style="height:250px"
                                    data-data="{{ $campaign->browsers }}"
                            ></canvas>
                        </div>-->

<!-- mw: tijdelijk verwijderd, niet mvp-waardig
                        <div class="chart-block">
                            <div class="box-header with-border">
                                <h3 class="box-title">Clicks Per Weekday</h3>
                            </div>
                            <div class="chart">
                                <canvas id="barChart" style="height:230px"
                                        data-labels="Sun,Mon,Tue,Wed,Thu,Fri,Sat"
                                        data-data="{{ json_encode($campaign->clicks()) }}"
                                ></canvas>
                            </div>
                        </div>
-->

                    </div>
                    <div class="col-md-6">
                        @include('campaigns.show_fields')
                    </div>
                    <input type="hidden" id="campaign_id" value="{{ $campaign->id }}">
                    <a class="btn btn-success {{ $campaign->isActive ? '' : 'disabled' }}" href="javascript:void(0);"
                       data-request-url="{!! route('campaigns.end') !!}"
                       data-request-data="campaign_id"
                    >End campaign</a>
<!-- mw: Temporarily remove CSV export
                    <form action="{!! route('campaigns.export', ['id' => $campaign->id]) !!}" enctype="multipart/form-data" style="display: inline">
                        <button class="btn btn-primary" type="submit">CSV Export</button>
                    </form>
-->
                    <a href="{!! route('campaigns.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
