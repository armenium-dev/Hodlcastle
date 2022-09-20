<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('companies', 'Choose at least one company') !!}
    @if(isset($supergroup))
        <companies-groups-component id="{{ $supergroup->id }}"></companies-groups-component>
    @else
        <companies-groups-component></companies-groups-component>
    @endif
</div>

@if(isset($supergroup))
<div class="form-group col-sm-12">
    {!! Form::label('campaigns', 'Campaigns') !!}
    @foreach($supergroup->campaigns as $campaign)
        <div>
            <a href="{{ route('campaigns.edit', ['id' => $campaign->id]) }}">{{ $campaign->name }}</a>
        </div>
    @endforeach
</div>
@endif

<!-- Submit Field -->