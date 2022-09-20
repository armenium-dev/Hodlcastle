@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>SMS Template Entry View</h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                @include('sms_templates.show_fields')
            </div>
        </div>
    </div>
@endsection
