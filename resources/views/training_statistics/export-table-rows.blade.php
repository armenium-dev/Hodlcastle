@foreach($rows as $k => $item)
    <tr id="js_item_{!! $item->ts_id !!}_{!! $item->m_id !!}_{!! $item->company_id !!}_{!! $item->recipient_id !!}"
        data-id="{!! $item->ts_id !!}"
        data-module="{!! $item->m_id !!}"
        data-company="{!! $item->company_id !!}"
        data-recipient="{!! $item->recipient_id !!}">
        <td>{!! ($k+1) !!}</td>
        <td>{!! $item->m_name !!}</td>
        <td>{!! $item->first_name !!} {!! $item->last_name !!}</td>
        <td>{!! $item->email !!}</td>
        <td>{!! $item->c_name !!}</td>
        <td>{!! $item->start_training !!}</td>
        <td>{!! $item->finish_training !!}</td>
        <td>{!! is_null($item->timespend) ? '' : $item->timespend.' min' !!}</td>
    </tr>
@endforeach
