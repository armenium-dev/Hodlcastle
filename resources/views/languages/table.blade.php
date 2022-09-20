<table class="table table-responsive" id="languages-table">
    <thead>
        <tr>
            <th>Icon</th>
            <th>Name</th>
            <th>Code</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($languages as $language)
        <tr>
            <td><img src="/img/pmflags/{!! $language['code'] !!}.png" /></td>
            <td>{!! $language->name !!}</td>
            <td>{!! $language->code !!}</td>
            <td>
                {!! Form::open(['route' => ['languages.destroy', $language->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('languages.edit', [$language->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>