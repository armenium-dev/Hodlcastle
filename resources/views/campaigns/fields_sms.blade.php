@php $checkbox_id_suffix = '-s'; @endphp
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('sms_template_id', 'SMS Template:') !!}
            {!! Form::select('schedule[sms_template_id]', $smsTemplates, null, ['class' => 'form-control', 'id' => 'sms_template_id']) !!}
        </div>
        @include('schedules.fields.type', ['model' => isset($campaign) ? $campaign : null, 'checkbox_id_suffix' => $checkbox_id_suffix, 'display_send_weekend' => false])
        <div id="app"></div>
    </div>
    <div class="col-sm-6">
        @include('campaigns.fields.groups_select', ['campaign' => isset($campaign) ? $campaign : null, 'groups' => $groups, 'checkbox_id_suffix' => $checkbox_id_suffix])
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        {!! Form::hidden('is_short', 1) !!}
        {!! Form::hidden('email', config('mail.email_service_desk')) !!}
        {!! Form::hidden('schedule[send_to_landing]', 1) !!}
        {!! Form::hidden('schedule[domain_id]', 3) !!}
        {!! Form::hidden('schedule[email_template_id]', 0) !!}
        {!! Form::hidden('type', 'sms') !!}

        <div class="btn-group">
            <a href="{!! route('campaigns.smishing') !!}" class="btn btn-info"><i class="fa fa-caret-left"></i> Cancel</a>
            {!! Form::button(isset($campaign) ? '<i class="fa fa-paper-plane"></i> Update campaign' : '<i class="fa fa-paper-plane"></i> Start campaign', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
        </div>
    </div>
</div>
