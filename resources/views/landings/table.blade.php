<table class="table table-responsive" id="landings-table">
    <thead>
        <tr>
            <th>Company</th>
            <th>Name</th>
            <th>Redirect Url</th>
            <th>Capture Credentials</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($landings as $landing)
        <tr>
            <td>{!! $landing->company ? $landing->company->name : '-' !!}</td>
            <td>{!! $landing->name !!}</td>
            <td>{!! $landing->redirect_url !!}</td>
            <td>{!! $landing->capture_credentials ? 'Yes' : 'No' !!}</td>
            <td>
                {!! Form::open(['route' => ['landings.destroy', $landing->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('landings.show', [$landing->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('landings.edit', [$landing->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>