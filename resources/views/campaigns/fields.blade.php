@php $checkbox_id_suffix = '-e'; @endphp
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('email_template_id', 'Email Template:') !!}
            {!! Form::select('schedule[email_template_id]', $emailTemplates, null, ['class' => 'form-control', 'id' => 'email_template_id']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('email', 'E-mail:') !!}
            {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required' ]) !!}
        </div>
        <div class="form-group">
            {!! Form::checkbox('is_short', 1, isset($campaign) ? $campaign->is_short : false, ['id' => 'is_short']) !!} {!! Form::label('is_short', 'Enable URL shortener', ['for' => 'is_short']) !!}
        </div>
        @include('schedules.fields.relation', ['model' => isset($campaign) ? $campaign : null, 'checkbox_id_suffix' => $checkbox_id_suffix])
        @include('schedules.fields.type', ['model' => isset($campaign) ? $campaign : null, 'checkbox_id_suffix' => $checkbox_id_suffix])
        <div id="app"></div>
    </div>
    <div class="col-sm-6">
        @include('campaigns.fields.groups_select', ['campaign' => isset($campaign) ? $campaign : null, 'groups' => $groups, 'checkbox_id_suffix' => $checkbox_id_suffix])
    </div>
</div>

<div class="row">
    <div class="form-group col-sm-12">
        {!! Form::hidden('schedule[sms_template_id]', 0) !!}
        {!! Form::hidden('type', 'email') !!}

        <div class="btn-group">
            <a href="{!! route('campaigns.index') !!}" class="btn btn-info"><i class="fa fa-caret-left"></i> Cancel</a>
            <a class="btn btn-warning" href="javascript:void(0);" data-request-url="{!! route('campaigns.test') !!}" data-request-data="email_template_id,landing_id,domain_id,send_to_landing,redirect_url"><i class="fa fa-envelope"></i> Send test email</a>
            {!! Form::button(isset($campaign) ? '<i class="fa fa-paper-plane"></i> Update campaign' : '<i class="fa fa-paper-plane"></i> Start campaign', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
        </div>
    </div>
</div>
