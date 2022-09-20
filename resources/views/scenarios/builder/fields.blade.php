<div class="col-sm-7">
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('image', 'Scenario Image:') !!}</div>
        <div class="col-sm-7">
            @if(isset($scenario) && $scenario->image)
                <img src="{{ $scenario->image->crop(100, 100, true) }}" alt="" />
            @endif
                {!! Form::file('image', ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right @if($errors->has('is_active')) has-error @endif">{!! Form::label('is_active', 'Scenario Is active:') !!}</div>
        <div class="col-sm-7">{{Form::checkbox('is_active',  1, isset($scenario) ? $scenario->is_active : 1 )}}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('name', 'Scenario Name:') !!}</div>
        <div class="col-sm-7">{!! Form::text('name', null, ['class' => 'form-control']) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('description', 'Scenario Description:') !!}</div>
        <div class="col-sm-7">{!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2]) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('campaign_name', 'Campaign Name:') !!}</div>
        <div class="col-sm-7">{!! Form::text('campaign_name', null, ['class' => 'form-control', 'readonly' => 'true', 'id' => 'campaign_name']) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('email_template_id', 'Email Template:') !!}</div>
        <div class="col-sm-7">{!! Form::select('email_template_id', $emailTemplates, null, ['class' => 'form-control', 'id' => 'email_template_id']) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('domain_id', 'Domain:') !!}</div>
        <div class="col-sm-7">{!! Form::select('domain_id', $domains, null, ['class' => 'form-control', 'id' => 'domain_id']) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('email', 'From E-mail:') !!}</div>
        <div class="col-sm-7">{!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => '(optional)']) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('is_short', 'Enable URL shortener:') !!}</div>
        <div class="col-sm-7">{!! Form::checkbox('is_short', 1, isset($scenario) ? $scenario->is_short : false) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('send_to_landing', 'Send to default landing:') !!}</div>
        <div class="col-sm-7">{!! Form::checkbox('send_to_landing', 1, isset($scenario) ? $scenario->send_to_landing : true, ['class' => 'flat-green', 'id' => 'send_to_landing']) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right">{!! Form::label('redirect_url', 'Redirect url:') !!}</div>
        <div class="col-sm-7">{!! Form::text('redirect_url', null, ['class' => 'form-control', 'id' => 'redirect_url']) !!}</div>
    </div>
    <div class="flex-row row">
        <div class="col-sm-5 text-right"></div>
        <div class="col-sm-7">
            <div class='btn-group'>
                <a href="{!! route('scenario.builder') !!}" class="btn btn-info"><i class="fa fa-caret-left"></i> Cancel</a>
                {!! Form::button('<i class="fa fa-save"></i> Save', ['type' => 'submit', 'class' => 'btn btn-success']) !!}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var templateName = $('#email_template_id option:selected').text();
        $('#campaign_name').val('Scenario: '+templateName);
        $('#email_template_id').on('change', function () {
            var templateName = $('#email_template_id option:selected').text();
            $('#campaign_name').val('Scenario: '+templateName);
        })
    });
</script>
