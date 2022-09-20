@extends('layouts.app')
@section('title', '| Permissions')
@section('content')
    <div class="container liquid">
        <section class="content-header">
            <div class="btn-group pull-right flex" role="group" aria-label="false">
                <a href="{{ URL::to('permissions/create') }}" class="button btn btn-success">Add Permission</a>
                <a href="{{ route('users.index') }}" class="button btn btn-info">Users</a>
                <a href="{{ route('roles.index') }}" class="button btn btn-info">Roles</a>
            </div>
            <h1><i class="fa fa-key"></i> Permissions Management</h1>
        </section>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Permissions</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                        <td class="text-right">
                            {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                                <div class="btn-group flex" role="group" aria-label="false">
                                    <a href="{{ URL::to('permissions/'.$permission->id.'/edit') }}" class="button btn btn-warning">Edit</a>
                                    {!! Form::submit('Delete', ['class' => 'button btn btn-danger']) !!}
                                </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection