@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="pull-right">
           <a class="btn btn-success" href="{!! route('smsTemplates.create') !!}"><i class="fa fa-plus"></i> Add New</a>
        </div>
        <h1>SMS Templates</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Public SMS templates</a></li>
                <li><a href="#tab_2" data-toggle="tab">Custom SMS templates</a></li>
                @if(Auth::user()->hasRole('captain'))
                <li><a href="#tab_3" data-toggle="tab">Blacklisted SMS terms</a></li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    @include('sms_templates.table_public')
                </div>
                <div class="tab-pane" id="tab_2">
                    @include('sms_templates.table_private')
                </div>
                @if(Auth::user()->hasRole('captain'))
                <div class="tab-pane" id="tab_3">
                    @include('options.blacklisted_sms_terms')
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

