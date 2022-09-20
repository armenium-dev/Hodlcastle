@extends('layouts.app')
@section('title', '| Update User')
@section('content')
{{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT', 'files' => true)) }}
<div class="row">
    <div class='col-lg-8 col-lg-offset-2'>
        <h1><i class='fa fa-user-plus'></i> Update {{$user->name}}</h1>
        <hr>
        @include('users.fields')
        <div class="clearfix col-sm-6">
            {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
        </div>
    </div>
</div>
{{ Form::close() }}
@endsection