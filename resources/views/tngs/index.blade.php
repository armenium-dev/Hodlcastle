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
                    <h4>{{ $module->name }}</h4>
                </div>

                @foreach ($courses as $course)
                    <div class="form-group col-sm-12">
                        <a class="btn btn-sm btn-warning" href="{!! route('tng.get', ['code' => $code, 'courses' => $course->id, 'page' => 1]) !!}">
                            <img src="/img/pmflags/{{ $course->language->code }}.png" /> {{ $course->name }}
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection

