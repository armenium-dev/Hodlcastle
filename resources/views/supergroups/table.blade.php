<table class="table table-responsive" id="supergroups-table">
    <thead>
        <tr>
            <th>Name</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($supergroups as $supergroup)
        <tr>
            <td>{!! $supergroup->name !!}</td>
            <td>
                {!! Form::open(['route' => ['supergroups.destroy', $supergroup->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('supergroups.show', [$supergroup->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('supergroups.edit', [$supergroup->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>