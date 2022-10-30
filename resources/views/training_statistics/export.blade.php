@extends('layouts.app')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('training_statistics.tabs', ['active' => 'trainingStatisticsExport'])
        <div class="tab-content">

                <section class="mb-20">
                    <h1 class="d-none mb-20">Export Data</h1>
                    <div class="d-flex flex-row justify-space-between align-item-end">
                        <form id="js_search_form" method="get" action="">
                            <div class="d-flex flex-row align-item-end">
                                <div class="me-10">
                                    <label>From date</label>
                                    <input type="text" name="start_date" class="datepicker form-control"
                                           data-min="2019-01-01" data-start="" data-end="" data-val="">
                                </div>
                                <div class="me-10">
                                    <label>To date</label>
                                    <input type="text" name="end_date" class="datepicker form-control"
                                           data-min="2019-01-01" data-start="" data-end="" data-val="">
                                </div>
                                @if(Auth::user()->hasRole('captain'))
                                <div class="me-10">
                                    <label>Company</label>
                                    <select name="company" class="form-control">
                                        <option value="-1">Unset</option>
                                        @foreach($companies as $k => $v)
                                        <option value="{!! $k !!}">{!! $v !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="me-10">
                                    <label>Module</label>
                                    <select name="module" class="form-control">
                                        <option value="-1">Unset</option>
                                        @foreach($modules as $k => $v)
                                        <option value="{!! $k !!}">{!! $v !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="me-10">
                                    <label>Finish status</label>
                                    <select name="is_finish" class="form-control">
                                        <option value="-1">Unset</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                                <input type="submit" class="btn btn-success me-10" value="Filter">
                                <input type="reset" class="btn btn-default" value="Reset" id="js_form_reset">
                            </div>
                        </form>
                        <a id="js_export" class="btn btn-warning" href="#">Export to CSV</a>
                    </div>
                </section>

                <div class="clearfix"></div>

                @include('flash::message')

                <div class="clearfix"></div>

                <div class="content">
                    @include('training_statistics.export-table')
                </div>

        </div>
    </div>
@endsection
