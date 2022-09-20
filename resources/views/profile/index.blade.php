@extends('layouts.app')

@section('content')

    <section class="container liquid">

        <div class="row">
            <div class="col-sm-12">
                @include('flash::message')
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Profile</div>

                    <div class="panel-body">
                        <div>Name: {{ $user->name }}</div>
                        <div>E-mail: {{ $user->email }}</div>
                        <div>Role: {{ $user->roles->pluck('name')->implode(', ') }}</div>
                        @if($user->company)
                            <div>Company: {{ $user->company->name }}</div>
                            <div>PMID: {{ $user->company->created_at->timestamp }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Change Password</div>

                    <div class="panel-body">
                        {!! Form::open(['route' => 'profile.changepassword']) !!}
                            <!-- Password Fields -->
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    {!! Form::label('current_password', 'Current Password:') !!}
                                    {!! Form::password('current_password', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    {!! Form::label('new_password', 'New Password:') !!}
                                    {!! Form::password('new_password', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    {!! Form::label('new_confirm_password', 'New Confirm Password:') !!}
                                    {!! Form::password('new_confirm_password', ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <!-- Submit Field -->
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Two-Factor Authentication</div>

                    <div class="panel-body">
                        @if (Auth::user()->google2fa_secret)
                            <a href="{{ url('2fa/disable') }}" class="btn btn-warning">Disable 2FA</a>
                        @else
                            <a href="{{ url('2fa/enable') }}" class="btn btn-primary">Enable 2FA</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Permanently override default landing page</div>

                    <div class="panel-body">
                        {!! Form::open(['route' => 'profile.updateredirect']) !!}
                        <div class="form-group">
                            {!! Form::checkbox('send_to_landing', 1, isset($user) ? $user->send_to_landing : 1, ['class' => 'flat-green', 'id' => 'send_to_landing']) !!}
                            {!! Form::label('send_to_landing', 'Use default PhishManager landing page (recommended)') !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('redirect_url', 'Custom Redirect URL:') !!}
                            {!! Form::text('redirect_url', $user->redirect_url, ['class' => 'form-control', 'id' => 'redirect_url']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection