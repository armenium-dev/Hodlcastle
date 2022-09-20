@extends('layouts.app')

@section('content')
    <section class="content-header">
        @if(Auth::user()->hasRole('captain'))
        <div class="pull-right">
           <a class="btn btn-success" href="{!! route('domains.create') !!}"><i class="fa fa-plus"></i> Add New</a>
        </div>
        @endif
        <h1>Domains</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Public Domains</a></li>
                <li><a href="#tab_2" data-toggle="tab">Custom Domains</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    @include('domains.table_public', ['domains' => $domainsPublic])
                </div>
                <div class="tab-pane" id="tab_2">
                    @include('domains.table')
                </div>
            </div>
        </div>
    </div>
@endsection

