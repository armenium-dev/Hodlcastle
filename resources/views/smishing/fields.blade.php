@php $checkbox_id_suffix = '-s'; @endphp
<div class="row">
    <div class="col-max-width-650">

        <table class="scenario-params">
            <thead>
            <tr>
                <th colspan="2">1. Selected SMS template:</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="130">
                    @if($sms_template->image)
                        <img src="{{ $sms_template->image->crop(100, 100, true) }}"/>
                    @else
                        <img src="/img/thumbnail-phishmanager.png"/>
                    @endif
                </td>
                <td>
                    <div><strong>{!! $sms_template->name !!}</strong></div>
                    <div>{!! nl2br($sms_template->content) !!}</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
            <tr>
                <th colspan="2">2. Enter Name:</th>
            </tr>
            <tr>
                <td colspan="2">
                    {!! Form::text('name', $sms_template->name, ['class' => 'form-control']) !!}
                    {!! Form::hidden('template_name', $sms_template->name, ['class' => 'form-control']) !!}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
            <tr>
                <th colspan="2">3. Select Groups:</th>
            </tr>
            <tr>
                <td colspan="2">
                    @foreach($groups as $group_id=>$group_name)
                        <div class="col-sm-6">
                            <input type="checkbox" name="groups[{{$group_id}}]" value="{{$group_id}}"
                                   id="group-{{$group_id}}-{{$checkbox_id_suffix}}" {{isset($sms_template->campaign) && $sms_template->campaign->groups->contains($group_id) ? 'checked' : ''}}/>
                            <label for="group-{{$group_id}}-{{$checkbox_id_suffix}}">{{$group_name}}</label>
                        </div>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th colspan="2">Domain:</th>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="form-group">
                        {!! Form::select('schedule[domain_id]', $domains, null, ['class' => 'form-control', 'id' => 'domain_id']) !!}
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="2">Sender Number:</th>
            </tr>
            <tr>
                <td colspan="2">
                    <select name="sms_from" class="form-control">
                        <option value="">Select number</option>
                        @foreach($numbers as $key => $group)
                            <optgroup label="{{$key}}">
                                @foreach($group as $number)
                                    <option value="{{$number}}">{{$number}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="">
                    <hr>
                </td>
            </tr>
            <tr>
                <th colspan="2" class="">4. Select Schedule type:</th>
            </tr>
            <tr>
                <td colspan="2" class="">
                    @if(auth()->user()->hasRole('captain'))
                        @include('schedules.fields.type', ['model' => isset($sms_template->campaign) ? $sms_template->campaign : null, 'checkbox_id_suffix' => $checkbox_id_suffix, 'display_send_weekend' => false])
                    @else
                        @include('schedules.fields.type_customer', ['model' => isset($sms_template->campaign) ? $sms_template->campaign : null, 'checkbox_id_suffix' => $checkbox_id_suffix, 'display_send_weekend' => false])
                    @endif
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
            <tr>
                <th colspan="2">Finish:</th>
            </tr>
            @if(auth()->user()->hasRole('customer'))
                <tr>
                    <th colspan="2">Sms Credits
                        Available: {{Auth::user()->company ? Auth::user()->company->sms_credits : 0}}</th>
                </tr>
            @endif
            <tr>
                <td colspan="2">
                    <div class="btn-group">
                        <a href="{!! route('smishing') !!}" class="btn btn-default"><i class="fa fa-caret-left"></i>
                            Cancel</a>
                        {!! Form::button('<i class="fa fa-paper-plane"></i> Schedule campaign', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

