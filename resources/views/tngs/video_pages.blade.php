@extends('layout_trainings.app')

@section('content')

    <div class="masthead clearfix">
        <div class="inner">
            <div class="masthead-img">
                <img src="{{ $company && $company->logo ? $company->logo->crop(40, 40, true) : '/public/img/logo.png' }}" class="img-circle" alt="Company Image" />
            </div>
            <h3 class="masthead-brand">{{ $company->name }}</h3>
        </div>
    </div>

    <div class="inner cover">
        <div class="well">
            <div class="clearfix"></div>

            @include('flash::message')

            <div class="clearfix"></div>

            <div class="row">

                {{--@include('results.table')--}}

                <div class="form-group col-sm-12">
                    <h4>{{ $page_name }}</h4>
                </div>

                <div class="form-group col-sm-12">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{ $page_content->url }}"></iframe>
                    </div>
                </div>

                <div class="col-sm-12">
                    <a class="btn btn-sm btn-primary" href="{!! route('tng.get', ['page' => $page_number, 'code' => $code, 'course' => $course]) !!}">
                        Next Page <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection

