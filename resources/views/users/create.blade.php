@extends('layouts.app')
@section('title', '| Create User')
@section('content')
{!! Form::open(array('route' => 'users.store', 'files' => true)) !!}
<div class="row">
    <div class='col-lg-8 col-lg-offset-2'>
        <h1><i class='fa fa-user-plus'></i> Create User</h1>
        <hr>
        @include('users.fields')
        <div class="clearfix col-sm-6">
            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
        </div>
    </div>
</div>
{{ Form::close() }}
@endsection