<table class="table table-responsive" id="companies-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Expiration date</th>
            <th>Active</th>
            <th>Recipients number</th>
            <th>Soft limit</th>
            <th>Max Recipients</th>
            <th>Trial Mode</th>
            <th>Profile</th>
            <th>Action</th>
            <!--<th colspan="3">Action</th>-->
        </tr>
    </thead>
    <tbody>
    @foreach($companies as $company)
        <tr>
            <td>{!! $company->name !!}</td>
            <td>{!! \Carbon\Carbon::parse($company->expires_at)->toDateString() !!}</td>
            <td>{!! $company->active ? 'yes' : 'no' !!}</td>
            <td>{!! $company->getRecipients()->count() !!}</td>
            <td>{!! $company->soft_limit !!}</td>
            <td>{!! $company->max_recipients !!}</td>
            <td>{!! $company->is_trial ? 'yes' : 'no' !!}</td>
            <td>{!! $company->profile->name !!}</td>
            <td>
                {!! Form::open(['route' => ['companies.destroy', $company->id], 'method' => 'delete']) !!}
                <div class="btn-group d-flex flex-nowrap">
                    <a href="{!! route('companies.show', [$company->id]) !!}" class='btn btn-info'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('companies.edit', [$company->id]) !!}" class='btn btn-warning'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>