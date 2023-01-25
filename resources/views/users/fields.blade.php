<div class="clearfix">
    <div class="form-group col-sm-6 @if ($errors->has('name')) has-error @endif">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>
    <div class="form-group col-sm-6 @if ($errors->has('email')) has-error @endif">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', null, array('class' => 'form-control')) }}
    </div>
    <div class="form-group col-sm-6">
        {!! Form::label('logo', 'Logo:') !!}
        <div>
            @if(isset($user) && $user->logo)
                <img src="{{ $user->logo->crop(135, 135, true) }}" alt="" />
            @endif
        </div>
        {!! Form::file('logo', null, ['class' => '']) !!}
    </div>
    <div class="form-group col-sm-6 @if ($errors->has('roles')) has-error @endif">
        <h5><b>Assign Role</b></h5>
        @foreach ($roles as $role)
            {{ Form::checkbox('roles[]',  $role->id, isset($user) ? $user->roles : [] ) }}
            {{ Form::label($role->name, ucfirst($role->name)) }}<br>
        @endforeach
    </div>
    <div class="form-group col-sm-6 @if ($errors->has('company_id')) has-error @endif">
        {!! Form::label('company_id', 'Company:') !!}
        {!! Form::select('company_id', $companies, null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-sm-6 @if ($errors->has('is_active')) has-error @endif">
        {!! Form::label('is_active', 'Is active:') !!}
        {{ Form::checkbox('is_active',  1, isset($user) ? $user->is_active : 1 ) }}
    </div>
</div>
<hr>
<div class="clearfix">
    <div class="col-sm-12">
        <small>Min 16 characters of which at least 1 letter, 1 number and 1 special character.</small>
    </div>
    <div class="form-group col-sm-6 @if ($errors->has('password')) has-error @endif">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control')) }}
    </div>
    <div class="form-group col-sm-6 @if ($errors->has('password')) has-error @endif">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
    </div>
    @if ($errors->has('password'))
        <div class="col-sm-12 error-text"></div>
    @endif
</div>
<script type="text/javascript">
    $(document).ready(function(){
        setTimeout(function(){
            $('input[type="password"]').val('');
        }, 500);
    });
</script>