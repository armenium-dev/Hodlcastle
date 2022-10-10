<div class="table-responsive">
    <table class="table" id="campaigns-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Template</th>
                <th>Landing Page</th>
                <th>Domain</th>
                <th>User</th>
                @if(isset($show_status) && $show_status)
                    <th>Status</th>
                    <th>Schedule start</th>
                    {{--<th>Schedule end</th>--}}
                @endif
                <th class="text-right">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($campaigns as $campaign)
            <tr class="{{ $campaign->IsCompleted ? 'bg-danger' : ($campaign->IsActive ? 'bg-success' : 'bg-inactive') }}">
                <td>{!! $campaign->name !!}</td>
                <td>
                    {!! $campaign->schedule && $campaign->schedule->emailTemplate ? 'Email' : '' !!}
                    {!! $campaign->schedule && $campaign->schedule->smsTemplate ? 'SMS' : '' !!}
                </td>
                <td>
                    {!! $campaign->schedule && $campaign->schedule->emailTemplate ? $campaign->schedule->emailTemplate->name : '' !!}
                    {!! $campaign->schedule && $campaign->schedule->smsTemplate ? $campaign->schedule->smsTemplate->name : '' !!}
                </td>
                <td>{!! $campaign->schedule && $campaign->schedule->landing ? $campaign->schedule->landing->name : '-' !!}</td>
                <td>{!! $campaign->schedule && $campaign->schedule->domain ? $campaign->schedule->domain->name : '-' !!}</td>
                <td>{!! $campaign->user_id ? $campaign->user->name : '' !!}</td>
                @if(isset($show_status) && $show_status)
                    <td>{{ $campaign->statusCalcTitle }}</td>
                    <td>{{ $campaign->schedule && $campaign->schedule->schedule_start ? $campaign->schedule->schedule_start->format('j F, Y') : '-' }}</td>
                    {{--<td>{{ $campaign->schedule && $campaign->schedule->schedule_end ? $campaign->schedule->schedule_end->format('j F, Y') : '-' }}</td>--}}
                @endif
                <td>
                    <div class="btn-group flex">
                        {!! Form::open(['route' => ['campaigns.destroy', $campaign->id], 'method' => 'delete']) !!}
                        <div class='btn-group flex'>
                            <a href="{!! route('campaigns.show', [$campaign->id]) !!}" class='btn btn-default'><i class="fa fa-eye"></i></a>
                            @if($campaign_status === $campaign->getStatusCalcAttribute())
                                <a href="{!! route('campaigns.edit', [$campaign->id, 'type' => $type]) !!}" class='btn btn-default'><i class="fa fa-edit"></i></a>
                            @endif
                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-custom', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                        @if(Auth::user()->hasRole('captain'))
                            {!! Form::open(['route' => ['campaigns.kickoff', 'campaign_id' => $campaign->id]]) !!}
                            @if($campaign->is_kickoff)
                                {!! Form::button('<i class="fa fa-bookmark"></i>', ['type' => 'submit', 'class' => 'btn btn-warning btn-right-custom', 'onclick' => "return confirm('Mark a campaign as the “kickoff campaign”?')"]) !!}
                            @else
                                {!! Form::button('<i class="fa fa-bookmark-o"></i>', ['type' => 'submit', 'class' => 'btn btn-success btn-right-custom', 'onclick' => "return confirm('Mark a campaign as the “kickoff campaign”?')"]) !!}
                            @endif
                            {!! Form::close() !!}
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
