<div class="table-responsive">
    <table class="table" id="domains-table">
        <thead>
            @if(!Auth::user()->hasRole('captain'))
            <tr>
                <th colspan="3">
                    <div class="alert alert-danger">To add a custom source domain we need to perform some extra steps server-side please create a ticket by sending an email to <a href="mailto:support@verifysecurity.nl">support@verifysecurity.nl</a></div>
                </th>
            </tr>
            @endif
            <tr>
                <th>Name</th>
                <th>Domain</th>
                @if(Auth::user()->can('domain.view_company'))
                <th>Company</th>
                @endif
                @if(Auth::user()->can('domains.set_public'))
                    <th>Is public</th>
                @endif
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($domains as $domain)
            <tr>
                <td>{!! $domain->name !!}</td>
                <td>{!! $domain->domain !!}</td>
                @if(Auth::user()->can('domain.view_company'))
                <td>{!! $domain->company ? $domain->company->name : '-' !!}</td>
                @endif
                @if(Auth::user()->can('domains.set_public'))
                    <td>{!! $domain->is_public ? 'Yes' : 'No' !!}</td>
                @endif
                <td>
                    {!! Form::open(['route' => ['domains.destroy', $domain->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('domains.show', [$domain->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                        @if(Auth::user()->hasRole('captain'))
                        <a href="{!! route('domains.edit', [$domain->id]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        @endif
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>