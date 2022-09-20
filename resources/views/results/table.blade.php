<div class="table-responsive">
    <table class="table" id="results-table">
        <thead>
            <tr>
                <th>Campaign Id</th>
                <th>Customer Id</th>
                <th>Redirect Id</th>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Status</th>
                <th>Ip</th>
                <th>Lat</th>
                <th>Lng</th>
                <th>Send Date</th>
                <th>Reported</th>
                <th>Sent</th>
                <th>Open</th>
                <th>Click</th>
                <th>Attach</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>{!! $result->campaign_id !!}</td>
                <td>{!! $result->customer_id !!}</td>
                <td>{!! $result->redirect_id !!}</td>
                <td>{!! $result->email !!}</td>
                <td>{!! $result->first_name !!}</td>
                <td>{!! $result->last_name !!}</td>
                <td>{!! $result->status !!}</td>
                <td>{!! $result->ip !!}</td>
                <td>{!! $result->lat !!}</td>
                <td>{!! $result->lng !!}</td>
                <td>{!! $result->send_date !!}</td>
                <td>{!! $result->reported !!}</td>
                <td>{!! $result->sent !!}</td>
                <td>{!! $result->open !!}</td>
                <td>{!! $result->click !!}</td>
                <td>{!! $result->attachment !!}</td>
                <td>
                    {!! Form::open(['route' => ['results.destroy', $result->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('results.show', [$result->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('results.edit', [$result->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>