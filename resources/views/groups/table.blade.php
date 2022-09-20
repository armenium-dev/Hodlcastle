<table class="table table-responsive" id="groups-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Recipients</th>
            @if(!Auth::user()->hasRole('customer'))
            <th>Company </th>
            @endif
            <th class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
    <?php $recipients_total = 0; ?>
    @foreach($groups as $group)
        <?php $recipients_total += $group->recipients->count() ?>
        <tr>
            <td>{!! $group->name !!}</td>
            <td>{!! $group->recipients->count() !!}</td>
            @if(!Auth::user()->hasRole('customer'))
            <td>{!! $group->company->name !!}</td>
            @endif
            <td>
                {!! Form::open(['route' => ['groups.destroy', $group->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('groups.show', [$group->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('groups.edit', [$group->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right">Total: </td>
            <td>{{ $recipients_total }}</td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
</table>