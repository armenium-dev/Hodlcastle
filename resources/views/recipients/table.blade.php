<table class="table table-responsive" id="recipients-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Group</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($recipients as $recipient)
        <tr>
            <td>{!! $recipient->full_name !!}</td>
            <td>{!! $recipient->email !!}</td>
            <td>{!! $recipient->group->name !!}</td>
            <td>
                {!! Form::open(['route' => ['recipients.destroy', $recipient->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('recipients.show', [$recipient->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('recipients.edit', [$recipient->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>