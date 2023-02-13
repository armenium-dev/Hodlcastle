@foreach($activities as $k => $item)
    <tr id="js_item_{!! $item->id !!}"
        data-company="{!! $item->company_id !!}"
        data-campaign="{!! $item->campaign_id !!}"
        data-recipient="{!! $item->recipient_id !!}">
        <td>{!! $item->action !!}</td>
        <td>{!! $item->ip_address !!}</td>
        <td>{!! $item->created_at !!}</td>
    </tr>
@endforeach
