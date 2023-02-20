<!-- Name Field -->
<div class="row">

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('soft_limit', 'Soft limit:') !!}
                    {!! Form::text('soft_limit', null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('max_recipients', 'Max recipients:') !!}
                    {!! Form::text('max_recipients', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('expires_at', 'Expires at:') !!}
            {!! Form::text('expires_at', null, [
				'class' => 'form-control datepicker',
				'data-val' => isset($company) ? $company->expires_at : '',
			]) !!}
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    {!! Form::label('profile_id', 'Profile') !!}
                    {!! Form::select('profile_id', $profiles, isset($company) ? $company->profile_id : null, ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-6">
                    {!! Form::label('sms_credits', 'SMS Credits') !!}
                    {!! Form::number('sms_credits', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12">
                    @if(Auth::user()->can('company.set_trial'))
                        {!! Form::checkbox('is_trial', 1, isset($company) ? $company->is_trial : false, ['id' => 'is_trial']) !!}
                        {!! Form::label('is_trial', 'Trial Mode') !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('logo', 'Logo:') !!}
            <div>
                @if(isset($company) && $company->logo)
                    <img src="{{ $company->logo->crop(135, 135, true) }}" alt="" />
                @endif
            </div>
            {!! Form::file('logo', null, ['class' => '']) !!}
        </div>
        <div class="form-group">
            @if(isset($company))
                <label>Domain Whitelist(s):</label>
                <domain-repeater-component id="{{ $company->id }}"></domain-repeater-component>
            @else
                <domain-repeater-component></domain-repeater-component>
            @endif
        </div>

    </div>

    <div class="col-sm-12">
        <div class="form-group">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('companies.index') !!}" class="btn btn-default">Cancel</a>
        </div>
    </div>

</div>
