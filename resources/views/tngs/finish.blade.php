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

                <div class="form-group col-sm-12">
                    <h4>Course completion</h4>
                </div>

                <div class="form-group col-sm-12">
                    You have successfully completed the course: <b>{{ $course->name }}</b>
                </div>

                <div class="form-group col-sm-12 d-none">
                    <a class="btn btn-sm btn-success" href="{!! route('tng.get', ['code' => $code, 'course' => $course, 'page' => 'finish']) !!}">
                        Download Certificate <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection

