@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="pull-right">
           <a class="btn btn-success" href="{!! route('results.create') !!}"><i class="fa fa-plus"></i> Add New</a>
        </div>
        <h1>Results</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('results.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

