<div class="table-responsive">
    <table class="table" id="emailTemplates-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Last Modified</th>
                <th colspan="3" width="140">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($emailTemplates as $emailTemplate)
            @if(is_object($emailTemplate))
            <tr>
                <td>{!! $emailTemplate->name !!}</td>
                <td>{!! $emailTemplate->updated_at !!}</td>
                <td>
                    {!! Form::open(['route' => ['emailTemplates.destroy', $emailTemplate->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('emailTemplates.show', [$emailTemplate->id]) !!}" class='btn btn-info'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('emailTemplates.edit', [$emailTemplate->id]) !!}" class='btn btn-warning'><i class="fa fa-edit"></i></a>
                        <a href="{!! route('emailTemplates.copy', [$emailTemplate->id]) !!}" class='btn btn-success'><i class="fa fa-copy"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
            @else
                <tr>
                    <td><?php var_dump($emailTemplate) ?></td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>