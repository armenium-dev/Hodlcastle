<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $campaign->id !!}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $campaign->name !!}</p>
</div>

<!-- Email Template Id Field -->
<div class="form-group">
    {!! Form::label('email_template_id', 'Email Template:') !!}
    @if($campaign->schedule && $campaign->schedule->emailTemplate)
        <a href="{{ route('emailTemplates.edit', ['id' => $campaign->schedule->emailTemplate->id]) }}" target="_blank">
            {!! $campaign->schedule->emailTemplate ? $campaign->schedule->emailTemplate->name : '-' !!}
        </a>
    @endif
</div>

<!-- Landing Page Id Field -->
<div class="form-group">
    {!! Form::label('landing_page_id', 'Landing Page:') !!}
    @if($campaign->schedule && $campaign->schedule->landing)
        <a href="{{ route('landings.edit', ['id' => $campaign->schedule->landing->id]) }}" target="_blank">
            {!! $campaign->schedule->landing ? $campaign->schedule->landing->name : '-' !!}
        </a>
    @endif
</div>

<!-- Domain Field -->
<div class="form-group">
    {!! Form::label('domain', 'Domain:') !!}
    @if($campaign->schedule && $campaign->schedule->domain)
    <a href="{{ route('domains.edit', ['id' => $campaign->schedule->domain->id]) }}" target="_blank">
        {!! $campaign->schedule->domain ? $campaign->schedule->domain->name : '-' !!}
    </a>
    @endif
</div>

<!-- Group Id Field -->
<div class="form-group">
    {!! Form::label('group_id', 'Group:') !!}
    @foreach($campaign->groups as $group)
        <a href="{{ route('groups.edit', ['id' => $group->id]) }}" target="_blank">{{ $group->name }}</a>
    @endforeach
</div>

<div class="form-group">
    {!! Form::label('email', 'E-mail:') !!}
    <p>{!! $campaign->email !!}</p>
</div>

<!-- Group Id Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{!! $campaign->StatusCalcTitle !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{!! $campaign->created_at->format('j F, Y H:i') !!}</p>
</div>

<!-- URL shortener Field -->
<div class="form-group">
    {!! Form::label('is_short', 'URL shortener enabled?') !!}
    <p>{!! ($campaign->is_short ? 'Yes' : 'No') !!}</p>
</div>