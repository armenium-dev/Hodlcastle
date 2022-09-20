<div class="col-sm-7">
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('id', 'Scenario ID:') !!}</div>
        <div class="col-sm-7">{!! $scenario->id !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('image', 'Scenario Image:') !!}</div>
        <div class="col-sm-7">
            @if(isset($scenario) && $scenario->image)
                <img src="{!! $scenario->image->crop(50, 50, true) !!}" alt="" />
            @endif
        </div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('is_active', 'Scenario Is active:') !!}</div>
        <div class="col-sm-7">{!! $scenario->is_active ? 'Yes' : 'No' !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('name', 'Scenario Name:') !!}</div>
        <div class="col-sm-7">{!! $scenario->name !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('description', 'Scenario Description:') !!}</div>
        <div class="col-sm-7">{!! $scenario->description !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('language_id', 'Scenario Language:') !!}</div>
        <div class="col-sm-7">{!! $scenario->language['name'] !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('campaign_name', 'Campaign Name:') !!}</div>
        <div class="col-sm-7">{!! $scenario->campaign_name !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('email_template_id', 'Email Template:') !!}</div>
        <div class="col-sm-7">{!! $scenario->email_template['name'] !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('domain_id', 'Domain:') !!}</div>
        <div class="col-sm-7">{!! $scenario->domain['domain'] !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('email', 'From E-mail:') !!}</div>
        <div class="col-sm-7">{!! $scenario->email !!}</div>
    </div>
    {{--<div class="flex-row row">--}}
        {{--<div class="col-sm-5 text-right">{!! Form::label('with_attach', 'With attachment:') !!}</div>--}}
        {{--<div class="col-sm-7">{!! $scenario->with_attach ? 'Yes' : 'No' !!}</div>--}}
    {{--</div>--}}
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('is_short', 'Enable URL shortener:') !!}</div>
        <div class="col-sm-7">{!! $scenario->is_short ? 'Yes' : 'No' !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('send_to_landing', 'Send to default landing:') !!}</div>
        <div class="col-sm-7">{!! $scenario->send_to_landing ? 'Yes' : 'No' !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('redirect_url', 'Redirect url:') !!}</div>
        <div class="col-sm-7">{!! $scenario->redirect_url !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right"></div>
        <div class="col-sm-7">
            {!! Form::open(['route' => ['scenario.builder.destroy', $scenario->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
                <a href="{!! route('scenario.builder') !!}" class="btn btn-info"><i class="fa fa-caret-left"></i> Back</a>
                <a href="{!! route('scenario.builder.edit', $scenario->id) !!}" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</a>
                {!! Form::button('<i class="fa fa-trash"></i> Delete', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

