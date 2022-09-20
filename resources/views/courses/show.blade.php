@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>
            Course
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('courses.show_fields')

                    <a href="{!! route('courses.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($pages->toArray()))
    <section class="content-header">
        <h1>
            Pages in Course
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                @include('courses.pages_table')
            </div>
        </div>
    </div>
    @endif

@endsection
