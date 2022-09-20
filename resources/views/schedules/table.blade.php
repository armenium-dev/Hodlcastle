<table class="table table-responsive" id="schedules-table">
    <thead>
        <tr>
            <th>Campaign Id</th>
        <th>Supergroup Id</th>
        <th>Email Template Id</th>
        <th>Landing Id</th>
        <th>Domain Id</th>
        <th>Schedule Start</th>
        <th>Schedule End</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($schedules as $schedule)
        <tr>
            <td>{!! $schedule->campaign_id !!}</td>
            <td>{!! $schedule->supergroup_id !!}</td>
            <td>{!! $schedule->email_template_id !!}</td>
            <td>{!! $schedule->landing_id !!}</td>
            <td>{!! $schedule->domain_id !!}</td>
            <td>{!! $schedule->schedule_start !!}</td>
            <td>{!! $schedule->schedule_end !!}</td>
            <td>
                {!! Form::open(['route' => ['schedules.destroy', $schedule->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('schedules.show', [$schedule->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('schedules.edit', [$schedule->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>