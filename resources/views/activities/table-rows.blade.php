@foreach($activities as $k => $item)
    <tr id="js_item_{!! $item->id !!}"
        data-company="{!! $item->company_id !!}"
        data-campaign="{!! $item->campaign_id !!}"
        data-recipient="{!! $item->recipient_id !!}"
        class="{{auth()->id() == $item->user_id ? 'bg-success' : ''}}"
    >

        @if($item->action == \App\Models\AccountActivity::ACTION_SMS_CREDIT)
            <td>
                <a href="{!! route('campaigns.show', [$item->campaign_id]) !!}" target="_blank">{{$item->action}} <span class="text-danger">{{"-$item->sms_credit"}}</span></a>
            </td>
        @else
            <td>{!! $item->action !!}</td>
        @endif

{{--        @if(Auth::user()->hasRole('captain'))--}}
        <td>{!! $item->user->name !!}</td>
{{--        @endif--}}
        <td>{!! $item->ip_address !!}</td>
        <td>{!! $item->created_at !!}</td>
    </tr>
@endforeach
