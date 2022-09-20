<?php
use Carbon\Carbon;
?>
<table class="table table-responsive" id="scenarios-table">
    <thead>
        <tr>
            <th colspan="2">Scenario</th>
            <th>Modified At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($scenarios as $scenario)
        @if($scenario->language->code == $language['code'])
        <tr>
            <td>
                @if($scenario->image)
                <img src="{{ $scenario->image->crop(100, 100, true) }}" />
                @else
                <img src="/img/thumbnail-phishmanager.png" style="width: 100px; height: 100px;"/>
                @endif
            </td>
            <td>
                <div><strong>{!! $scenario->name !!}</strong></div>
                <div>{!! $scenario->description !!}</div>
            </td>
            <td>{!! Carbon::parse($scenario->updated_at)->format('Y-m-d / H:i') !!}</td>
            <td><a href="{!! route('scenarios.select', [$scenario->id]) !!}" class='btn btn-info'><i class="fa fa-mouse-pointer"></i> Select</a></td>
        </tr>
        @endif
    @endforeach
    </tbody>
</table>
