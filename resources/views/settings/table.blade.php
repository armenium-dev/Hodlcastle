<table class="table table-responsive" id="campaigns-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Key</th>
            <th class="w-full">Value</th>
            <th class="min-w-130">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($options as $option)
        <tr>
            <td>{!! $option->id !!}</td>
            <td class="text-nowrap">{!! $option->option_name !!}</td>
            <td>{!! $option->option_key !!}</td>
            <td>{!! $option->option_value !!}</td>
            <td class="">
                @if($option->custom_option)
                    <a href="{!! $option->custom_option_page_link !!}" class='btn btn-default'><i class="fa fa-sliders"></i> Config</a>
                @else
                {!! Form::open(['route' => ['settings.destroy', $option->id], 'method' => 'delete']) !!}
                <div class='btn-group text-nowrap'>
                    <a href="{!! route('settings.show', [$option->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('settings.edit', [$option->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>