@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Campaign</h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        @include('flash::message')
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                @if($type == 'email')
                <li class="active"><a href="#tab_1" data-toggle="tab">Standard</a></li>
                <li><a href="#tab_2" data-toggle="tab">File-based</a></li>
                @endif
                @if($smishing && $type == 'sms')
                <li class="active"><a href="#tab_3" data-toggle="tab">SMS-based</a></li>
                @endif
            </ul>
            <div class="tab-content">
                @if($type == 'email')
                <div class="tab-pane active" id="tab_1">
                    {!! Form::open(['route' => 'campaigns.store']) !!}
                    @include('campaigns.fields')
                    {!! Form::close() !!}
                </div>
                <div class="tab-pane" id="tab_2">
                    {!! Form::open(['route' => 'campaigns.store']) !!}
                    @include('campaigns.fields_attach')
                    {!! Form::close() !!}
                </div>
                @endif
                @if($smishing && $type == 'sms')
                <div class="tab-pane active" id="tab_3">
                    {!! Form::open(['route' => 'campaigns.store']) !!}
                    @include('campaigns.fields_sms')
                    {!! Form::close() !!}
                </div>
                @endif
            </div>
        </div>

    </div>
@endsection
