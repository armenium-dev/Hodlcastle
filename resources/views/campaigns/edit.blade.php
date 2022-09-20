@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Campaign</h1>
   </section>
   <div class="content">
       @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
           {!! Form::model($campaign, ['route' => ['campaigns.update', $campaign->id], 'method' => 'patch']) !!}
               @if($type == 'email')
                   @include('campaigns.fields')
               @elseif($type == 'sms' && $smishing)
                   @include('campaigns.fields_sms')
               @endif
           {!! Form::close() !!}
           </div>
       </div>
   </div>
@endsection