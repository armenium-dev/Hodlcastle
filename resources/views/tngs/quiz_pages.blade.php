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

            <form id="js_course_form" method="post" action="{!! route('tng.get', ['code' => $code, 'course' => $course, 'page' => $page_number]) !!}">
                @csrf
                <input type="hidden" name="code" value="{!! $code !!}">
                <input type="hidden" name="course" value="{{ $course->id }}">
                <input type="hidden" name="company" value="{!! $company->id !!}">
                <input type="hidden" name="page_number" value="{!! $page_number-1 !!}">
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
                @foreach ($page_content->questions as $k => $question)
                    <div data-type="{{ $page_content->type }}">
                        <label>
                            <input class="answer" type="{{ $page_content->type }}" name="answers[{!! $page_content->type !!}][user][]" value="{!! $question->id !!}" @if($page_content->type == 'radio' && $k == 0) checked @endif>
                            <input class="correct" type="hidden" name="answers[{!! $page_content->type !!}][correct][{!! $question->id !!}]" value="{{ $question->correct }}">
                            {{ $question->answer }}
                        </label>
                    </div>
                @endforeach
                </div>

                <div class="col-sm-12">
                    <button id="js_submit" type="submit" class="btn btn-sm btn-success btn-check">
                        Submit <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </button>
                    <a id="js_next" class="btn btn-sm btn-primary btn-next" disabled="disabled" href="{!! route('tng.get', ['code' => $code, 'course' => $course, 'page' => $page_number]) !!}">
                        Next Page <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                    </a>
                </div>

            </div>
            </form>

        </div>
    </div>

@endsection