@extends('layouts.app')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('main.tabs', ['active' => 'leaderboard'])
        <div class="tab-content">

                <section class="content-header">
                    <div class="pull-right">
                        <a class="btn btn-warning" href="#">Export</a>
                    </div>
                    <h1>Leaderboard</h1>
                </section>

                <div class="clearfix"></div>

                @include('flash::message')

                <div class="clearfix"></div>

                <div class="content">
                    Coming soon...
                    {{--@include('campaigns.table', ['show_status' => 1])--}}
                </div>

        </div>
    </div>
@endsection
