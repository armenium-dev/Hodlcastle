<table class="table table-responsive" id="campaigns-table">
    <thead>
        <tr>
            <th>Module</th>
            <th>User</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($trainings as $training)
        @if($training->user->company_id == $company_id)
        <tr>
            <td>{!! $training->module->name !!}</td>
            <td>{!! $training->user_id ? $training->user->name : '' !!}</td>
            <td>{!! $training->created_at ? $training->created_at : '' !!}</td>
            <td>
                {!! Form::open(['route' => ['trainings.destroy', $training->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('trainings.show', [$training->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
        @endif
    @endforeach
    </tbody>
</table>