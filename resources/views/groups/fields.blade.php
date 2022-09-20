<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

@if(Auth::user()->hasRole('captain'))
<div class="form-group col-sm-6">
    {!! Form::label('company_id', 'Company:') !!}
    {!! Form::select('company_id', $companies, null, ['class' => 'form-control']) !!}
</div>
@endif

<div class="form-group col-sm-12">
                        <div class="box-body">
                            A Template CSV file that can be used to test bulk import can be downloaded here (placeholder) ;
                        </div>

    {!! Form::label('recipients', 'Recipients:') !!}
    @if(isset($group))
        <div>Remaining Recipients Licenses: {{ $group->company->RecipientsCapacity }}</div>
    @else
        @if(Auth::user()->company)
        <div>Remaining Recipients Licenses: {{ Auth::user()->company->RecipientsCapacity }}</div>
        @endif
    @endif
    @if(isset($group))
        <repeater-component id="{{ $group->id }}"></repeater-component>
    @else
        <repeater-component></repeater-component>
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('groups.index') !!}" class="btn btn-default">Cancel</a>
</div>

{{--<script>--}}
    {{--$('.repeater-row').each(function () {--}}

    {{--});--}}
{{--</script>--}}
