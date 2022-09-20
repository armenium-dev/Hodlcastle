@extends('layouts.app')
@section('title', '| Roles')
@section('content')
    <div class="container liquid">
        <section class="content-header">
            <div class="btn-group pull-right flex" role="group" aria-label="false">
                <a href="{{ URL::to('roles/create') }}" class="button btn btn-success">Add Role</a>
                <a href="{{ route('users.index') }}" class="button btn btn-info">Users</a>
                <a href="{{ route('permissions.index') }}" class="button btn btn-info">Permissions</a>
            </div>
            <h1><i class="fa fa-key"></i> Roles Management</h1>
        </section>
        <hr>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ str_replace(array('[', ']', '"', ','),array('', '', '', ', '), $role->permissions()->pluck('name')) }}</td>
                        <td class="text-right">
                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                            <div class="btn-group flex" role="group" aria-label="false">
                                <a href="{{ URL::to('roles/'.$role->id.'/edit') }}" class="button btn btn-warning">Edit</a>
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