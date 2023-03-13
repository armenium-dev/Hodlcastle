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
        <div class="well intro">
            <div class="clearfix"></div>

            @include('flash::message')

            <div class="clearfix"></div>

            <h4>{{ $module->name }}</h4>

            <div class="buttons">
            @foreach ($courses as $course)
                    <a class="btn btn-warning" href="{!! route('tng.get', ['code' => $code, 'courses' => $course->id, 'page' => 1]) !!}">
                        <img src="/img/pmflags/{{ $course->language->code }}.png" /> {{ $course->name }}
                    </a>
            @endforeach
            </div>
        </div>
    </div>

@endsection

