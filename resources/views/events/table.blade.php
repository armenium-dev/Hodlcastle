<div class="table-responsive">
    <table class="table" id="events-table">
        <thead>
            <tr>
                <th>Campaign Id</th>
            <th>Email</th>
            <th>Time</th>
            <th>Message</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($events as $event)
            <tr>
                <td>{!! $event->campaign_id !!}</td>
                <td>{!! $event->email !!}</td>
                <td>{!! $event->time !!}</td>
                <td>{!! $event->message !!}</td>
                <td>
                    {!! Form::open(['route' => ['events.destroy', $event->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('events.show', [$event->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('events.edit', [$event->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>