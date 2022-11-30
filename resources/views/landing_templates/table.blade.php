<div class="table-responsive">
    <table id="emailTemplates-table" class="table custom-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Last Modified</th>
                <th width="140">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($templates as $template)
            @if(is_object($template))
            <tr>
                <td>{!! $template->name !!}</td>
                <td>{!! $template->company ? $template->company->name : '' !!}</td>
                <td>{!! $template->updated_at !!}</td>
                <td>
                    {!! Form::open(['route' => ['landingTemplates.destroy', $template->id], 'method' => 'delete']) !!}
                    <div class="btn-group flex">
                        <a href="{!! route('landingTemplates.show', [$template->id]) !!}" class='btn btn-info'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('landingTemplates.edit', [$template->id]) !!}" class='btn btn-warning'><i class="fa fa-edit"></i></a>
                        <a href="{!! route('landingTemplates.copy', [$template->id]) !!}" class='btn btn-success'><i class="fa fa-copy"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
            @else
                <tr>
                    <td><?php var_dump($template) ?></td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>

