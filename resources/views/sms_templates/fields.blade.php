@php
    use App\Models\SmsTemplate;
@endphp

<div class="row">

    <div class="col-sm-6">
        <div class="form-group">
            @if(Auth::check() && Auth::user()->can('sms_template.set_company'))
                {!! Form::label('image', 'Template Image:') !!}
                @if(isset($smsTemplate) && $smsTemplate->image)
                    <img src="{{ $smsTemplate->image->crop(100, 100, true) }}" alt="" class="d-block" />
                @endif
                {!! Form::file('image', ['class' => 'form-control']) !!}
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('name', 'Name:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('name', 'Content:') !!}
            <textarea class="form-control" name="content">{!! isset($smsTemplate) ? $smsTemplate->content : "" !!}</textarea>
        </div>

        <div class="form-group">
            @if(Auth::check() && Auth::user()->can('sms_template.set_company'))
                {!! Form::label('company_id', 'For Company') !!}
                {!! Form::select('company_id', $companies, null, ['class' => 'form-control']) !!}
            @endif
        </div>

        <div class="form-group">
            @if(Auth::check() && Auth::user()->can('sms_template.see_lang_tags_image'))
                {!! Form::label('language_id', 'Lang:') !!}
                {!! Form::select('language_id', $languages, $defult_language_id, ['class' => 'form-control']) !!}
            @endif
        </div>

        <div class="form-group hidden">
            @if(Auth::check() && Auth::user()->can('sms_template.set_public'))
                {!! Form::checkbox('is_public', 1, $default_is_public, ['id' => 'is_public']) !!}
                {!! Form::label('is_public', 'Public template') !!}
            @endif
        </div>
    </div>
    <div class="col-sm-6">
        <table class="table">
            <thead>
            <tr>
                <th>Variable</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>@{{.FirstName}}</td>
                <td>Target’s first name</td>
                <td><a href="" class="copyLink" data-text="@{{.FirstName}}">Copy</a></td>
            </tr>
            <tr>
                <td>@{{.LastName}}</td>
                <td>Target’s last name</td>
                <td><a href="" class="copyLink" data-text="@{{.LastName}}">Copy</a></td>
            </tr>
            <!--
            <tr>
                <td>@{{.Position}}</td>
                <td>Target’s position</td>
                <td><a href="" class="copyLink" data-text="@{{.Position}}">Copy</a></td>
            </tr>
            <tr>
                <td>@{{.Department}}</td>
                <td>Target’s department</td>
                <td><a href="" class="copyLink" data-text="@{{.Department}}">Copy</a></td>
            </tr>
            <tr>
                <td>@{{.Sms}}</td>
                <td>Target’s e-mail</td>
                <td><a href="" class="copyLink" data-text="@{{.Sms}}">Copy</a></td>
            </tr>
            <tr>
                <td>@{{.From}}</td>
                <td>Source e-mail address</td>
                <td><a href="" class="copyLink" data-text="@{{.From}}">Copy</a></td>
            </tr>
            -->
            <tr>
                <td>@{{.URL}}</td>
                <td>URL to tracking handler (per engagement)</td>
                <td><a href="" class="copyLink" data-text="@{{.URL}}">Copy</a></td>
            </tr>
            <tr>
                <td>@{{.YEAR}}</td>
                <td>Current year (example {!! date('Y') !!})</td>
                <td><a href="" class="copyLink" data-text="@{{.YEAR}}">Copy</a></td>
            </tr>
            <tr>
                <td>@{{.MONTH}}</td>
                <td>Current month (number, example: {!! date('m') !!}))</td>
                <td><a href="" class="copyLink" data-text="@{{.MONTH}}">Copy</a></td>
            </tr>
            <tr>
                <td>@{{.DAY}}</td>
                <td>Current day (example: {!! date('d') !!}))</td>
                <td><a href="" class="copyLink" data-text="@{{.DAY}}">Copy</a></td>
            </tr>
            @if(count($landing_variables)  > 0)
                @foreach($landing_variables as $landing_variable)
                    <tr>
                        <td>@{{.{!! $landing_variable['variable'] !!}}}</td>
                        <td>{!! $landing_variable['description']; !!}</td>
                        <td><a href="" class="copyLink" data-text="@{{.{!! $landing_variable['variable'] !!}}}">Copy</a></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>

    <div class="col-sm-12">
        <div class="btn-group">
            <a href="{!! route('smsTemplates.index') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i> Cancel</a>
            {!! Form::button('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function(){
        if($('.copyLink').length >= 0){
            $('.copyLink').on('click', function(e){
                e.preventDefault();
                var text = $(this).data('text');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(text).select();
                document.execCommand("copy");
                $temp.remove();
            });
        }

		$('#company_id').on('change', function(){
			var v = ~~$(this).val();
			$('#is_public').prop('checked', !(v > 0));
		}).trigger('change');

    });
</script>
