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
                        @php
                        if(is_numeric($page_content->url)){
							$url = 'https://player.vimeo.com/video/'.$page_content->url;
                        }else{
							$url = 'https://www.youtube.com/embed/'.$page_content->url;
                        }
                        @endphp
                        <iframe src="{{ $url }}?title=0&byline=0&portrait=0" class="embed-responsive-item" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        <script src="https://player.vimeo.com/api/player.js"></script>
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

