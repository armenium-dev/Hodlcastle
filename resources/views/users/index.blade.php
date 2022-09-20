@extends('layouts.app')
@section('title', '| Users')
@section('content')
    <div class="container liquid">
        <section class="content-header">
            <div class="btn-group pull-right flex" role="group" aria-label="false">
                <a href="{{ route('users.create') }}" class="button btn btn-success">Add User</a>
                <a href="{{ route('roles.index') }}" class="button btn btn-info">Roles</a>
                <a href="{{ route('permissions.index') }}" class="button btn btn-info">Permissions</a>
            </div>
            <h1><i class="fa fa-users"></i> User Management</h1>
        </section>
        <hr>


        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Date/Time Added</th>
                    <th>User Roles</th>
                    <th>Active</th>
                    <th class="text-right">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->company ? $user->company->name : '-' }}</td>
                        <td>{{ $user->created_at->format('F d, Y h:ia') }}</td>
                        <td>{{ $user->roles()->pluck('name')->implode(' ') }}</td>
                        <td>{{ $user->is_active ? 'yes' : 'no' }}</td>
                        <td class="text-right">
                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id] ]) !!}
                            <div class="btn-group flex" role="group" aria-label="false">
                                <a href="{{ route('users.edit', $user->id) }}" class="button btn btn-warning">Edit</a>
                                {!! Form::button('<i class="fa fa-trash"></i> Delete', ['type' => 'submit', 'class' => 'button btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
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