<div class="table-responsive">
    <table class="table" id="modules-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Module ID</th>
                <th>Public</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($modules as $module)
            <tr>
                <td>{!! $module->name !!}</td>
                <td>{!! $module->id !!}</td>
                <td>{!! $module->public ? 'yes' : 'no' !!}</td>
                <td>
                    {!! Form::open(['route' => ['modules.destroy', $module->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('modules.show', [$module->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('modules.edit', [$module->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>