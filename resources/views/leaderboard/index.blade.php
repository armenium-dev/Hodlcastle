@extends('layouts.app')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('main.tabs', ['active' => 'leaderboard'])
        <div class="tab-content">

                <section class="mb-20">
                    <h1 class="d-none mb-20">Leaderboard</h1>
                    <div class="d-flex flex-row justify-space-between align-items-end">
                        <form id="js_search_form" method="get" action="">
                            <div class="d-flex flex-row align-items-end">
                                <div class="me-10">
                                    <label>Start date</label>
                                    <input type="text" name="start_date" class="datepicker form-control"
                                           data-min="2019-01-01" data-start="" data-end="" data-val="">
                                </div>
                                <div class="me-10">
                                    <label>End date</label>
                                    <input type="text" name="end_date" class="datepicker form-control"
                                           data-min="2019-01-01" data-start="" data-end="" data-val="">
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
                    @include('leaderboard.table')
                </div>

        </div>
    </div>
@endsection
