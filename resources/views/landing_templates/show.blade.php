@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Landing Template Preview</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                @include('landing_templates.show_fields')
            </div>
        </div>
    </div>
@endsection
