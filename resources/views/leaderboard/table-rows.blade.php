@foreach($leaderboard as $k => $item)
    <tr id="js_item_{!! $item->company_id !!}_{!! $item->campaign_id !!}_{!! $item->recipient_id !!}"
        data-company="{!! $item->company_id !!}"
        data-campaign="{!! $item->campaign_id !!}"
        data-recipient="{!! $item->recipient_id !!}">
        <td>{!! ($k+1) !!}</td>
        <td>{!! $item->send_date !!}</td>
        <td>{!! $item->first_name !!}</td>
        <td>{!! $item->last_name !!}</td>
        <td>{!! $item->email !!}</td>
        <td>{!! $item->phone !!}</td>
        <td class="text-right">{!! $item->mails_sent !!}</td>
        <td class="text-right">{!! $item->reported_phishes !!}</td>
        <td class="text-right">{!! $item->phished !!}</td>
        <td class="text-right">{!! $item->phish_rate !!}%</td>
        <td class="text-right">{!! $item->reporting_rate !!}%</td>
        <td class="text-right">{!! $item->sms_sent !!}</td>
        <td class="text-right">{!! $item->smished !!}</td>
        <td class="text-right">{!! !empty($item->smish_rate) ? $item->smish_rate : 0 !!}%</td>
        <td>{!! $item->department !!}</td>
        <td>{!! $item->location !!}</td>
    </tr>
@endforeach
