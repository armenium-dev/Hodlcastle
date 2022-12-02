<div class="table-responsive">
    <table class="table" id="emailTemplates-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th>Language</th>
                <th>Last Modified</th>
                <th width="170">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($smsTemplates as $smsTemplate)
            @if(is_object($smsTemplate))
            <tr>
                <td>{!! $smsTemplate->name !!}</td>
                <td>{!! $smsTemplate->company->name !!}</td>
                <td><img src="/img/pmflags/{!! $smsTemplate->language->code !!}.png"> {!! $smsTemplate->language->name !!}</td>
                <td>{!! $smsTemplate->updated_at !!}</td>
                <td>
                    {!! Form::open(['route' => ['smsTemplates.destroy', $smsTemplate->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('smsTemplates.show', [$smsTemplate->id]) !!}" class='btn btn-info'><i class="fa fa-eye"></i></a>
                        <a href="{!! route('smsTemplates.edit', [$smsTemplate->id]) !!}" class='btn btn-warning'><i class="fa fa-edit"></i></a>
                        <a href="{!! route('smsTemplates.copy', [$smsTemplate->id]) !!}" class='btn btn-success'><i class="fa fa-copy"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
            @else
                <tr>
                    <td colspan="4"><?php var_dump($smsTemplate) ?></td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
