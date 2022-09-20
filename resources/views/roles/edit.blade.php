@extends('layouts.app')
@section('title', '| Update Role')
@section('content')
    <div class='col-md-4 col-md-offset-4'>
        <h1><i class='fa fa-key'></i> Update Role: {{$role->name}}</h1>
        <hr>
        {{ Form::model($role, array('route' => array('roles.update', $role->id), 'method' => 'PUT')) }}
        <div class="form-group">
            {{ Form::label('name', 'Role Name') }}
            {{ Form::text('name', null, array('class' => 'form-control')) }}
        </div>
        <h3>Assign Permissions</h3>
        @foreach ($permissions as $permission)
            {{Form::checkbox('permissions[]',  $permission->id, $role->permissions ) }}
            {{Form::label($permission->name, ucfirst($permission->name)) }}<br>
        @endforeach
        <br>
        {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}
        {{ Form::close() }}
    </div>
@endsection