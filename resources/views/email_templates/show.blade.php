@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Email Template Preview</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                @include('email_templates.show_fields')
            </div>
        </div>
    </div>
@endsection
