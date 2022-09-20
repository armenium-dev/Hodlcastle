<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Companies & Groups</a></li>
        <li><a href="#tab_2" data-toggle="tab">Schedules</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
            @include('supergroups.fields')
            </div>
        </div>
        <div class="tab-pane" id="tab_2">
            <schedules-component id="{{ isset($supergroup) ? $supergroup->id : null }}"></schedules-component>
        </div>
    </div>
    <!-- /.tab-content -->
</div>
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if(isset($supergroup))
        <a href="{!! route('supergroups.generate', ['id' => $supergroup->id]) !!}"
           class="btn btn-success {{ $supergroup->campaigns->count() ? 'disabled' : '' }}">Generate Campaigns</a>
    @endif
    <a href="{!! route('supergroups.index') !!}" class="btn btn-default">Cancel</a>
</div>