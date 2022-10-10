@extends('layouts.app')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('main.tabs', ['active' => 'leaderboard'])
        <div class="tab-content">

                <section class="mb-20">
                    <h1 class="d-none mb-20">Leaderboard</h1>
                    <div class="d-flex flex-row justify-space-between align-item-end">
                        <form id="js_search_form" method="get" action="">
                            <div class="d-flex flex-row align-item-end">
                                <div class="me-10">
                                    <label>Start date</label>
                                    <input type="text" name="start_date" class="datepicker form-control"
                                           data-min="2019-01-01" data-start="" data-end="" data-val="">
                                </div>
                                <div class="me-10">
                                    <label>Start time</label>
                                    <input type="text" name="start_time" class="time_start form-control">
                                </div>
                                <input type="submit" class="btn btn-success" value="Filter">
                            </div>
                        </form>
                        <a id="js_export" class="btn btn-warning" href="#">Export to CSV</a>
                    </div>
                </section>

                <div class="clearfix"></div>

                @include('flash::message')

                <div class="clearfix"></div>

                <div class="content">
                    @include('leaderboard.table')
                </div>

        </div>
    </div>
@endsection
