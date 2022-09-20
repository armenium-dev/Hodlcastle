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
                    <b>{{ $page_content->name }}</b>
                </div>

                <div class="form-group col-sm-12">
                    {!! $page_content->text !!}
                </div>

                <div class="form-group col-sm-12">
                @foreach ($page_content->questions as $question)
                    <div class="{{ $page_content->type }}">
                        <label>
                            <input class="answer" type="{{ $page_content->type }}" name="quiz" value="0">
                            <input class="correct" type="hidden" value="{{ $question->correct }}">
                        </label>
                        {{ $question->answer }}
                    </div>
                @endforeach
                </div>

                <div class="col-sm-12">
                    <button type="button" class="btn btn-sm btn-success btn-check">
                        Check <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                    <a class="btn btn-sm btn-primary btn-next" href="{!! route('tng.get', ['code' => $code, 'course' => $course, 'page' => $page_number]) !!}" style="pointer-events: none;" disabled>
                        Next Page <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                    </a>
                </div>

            </div>
        </div>
    </div>

@endsection