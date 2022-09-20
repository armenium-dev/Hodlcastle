<?php
use Carbon\Carbon;
?>
<table class="table table-responsive" id="scenarios-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Language</th>
            <th>Created At</th>
            <th>Created By</th>
            <th>Modified At</th>
            <th>Modified By</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($scenarios as $scenario)
        <tr>
            <td>{!! $scenario->id !!}</td>
            <td>
                @if($scenario->image)
                <img src="{{ $scenario->image->crop(30, 30, true) }}" />
                @else
                    <img src="/img/thumbnail-phishmanager.png" style="width: 30px; height: 30px;"/>
                @endif
            </td>
            <td>{!! $scenario->name !!}</td>
            <td><img src="/img/pmflags/{{ $scenario->language->code }}.png" /> {!! $scenario->language->name !!}</td>
            <td>{!! Carbon::parse($scenario->created_at)->format('Y-m-d / H:i') !!}</td>
            <td>{!! $scenario->created_by_user['name'] !!}</td>
            <td>{!! isset($scenario->updated_by_user['name']) ? Carbon::parse($scenario->updated_at)->format('Y-m-d / H:i') : '' !!}</td>
            <td>{!! isset($scenario->updated_by_user['name']) ? $scenario->updated_by_user['name'] : '' !!}</td>
            <td><i class="fa fa-{!! $scenario->is_active ? 'check-square' : 'times-circle' !!}"></i></td>
            <td>
                {!! Form::open(['route' => ['scenario.builder.destroy', $scenario->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('scenarios.select', [$scenario->id]) !!}" class='btn btn-success'><i class="fa fa-mouse-pointer"></i> Run</a>
                    <a href="{!! route('scenario.builder.show', [$scenario->id]) !!}" class='btn btn-info'><i class="fa fa-eye"></i></a>
                    <a href="{!! route('scenario.builder.edit', [$scenario->id]) !!}" class='btn btn-warning'><i class="fa fa-edit"></i></a>
                    {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
