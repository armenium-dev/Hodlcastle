@extends('layouts.app')
@section('title', '| Create Permission')
@section('content')
    <div class='col-md-4 col-md-offset-4'>
        <h1><i class='fa fa-key'></i> Create New Permission</h1>
        <br>
        {{ Form::open(array('url' => 'permissions')) }}
        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', '', array('class' => 'form-control')) }}
        </div><br>
        @if(!$roles->isEmpty())
            <h3>Assign Permission to Roles</h3>
            @foreach ($roles as $role)
                {{ Form::checkbox('roles[]',  $role->id, null, ['id' => $role->name] ) }}
                {{ Form::label($role->name, ucfirst($role->name)) }}<br>
            @endforeach
        @endif
        <br>
        {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
    </div>
@endsection