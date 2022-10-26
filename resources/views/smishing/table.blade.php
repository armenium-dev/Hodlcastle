<?php
use Carbon\Carbon;
?>
<table class="table table-responsive smishing-table" id="smishing-table">
    <thead>
        <tr>
            <th colspan="2">Content</th>
            <th>Modified At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($sms_templates as $item)
        @if($item->language->code == $language['code'])
        <tr>
            <td class="image-col">
                @if($item->image)
                <img src="{!! $item->image->crop(100, 100, true) !!}" />
                @else
                <img src="/img/sms-phishmanager.png" />
                @endif
            </td>
            <td class="content-col">
                <div><strong>{!! $item->name !!}</strong></div>
                <div>{!! nl2br($item->content) !!}</div>
            </td>
            <td class="date-col">{!! Carbon::parse($item->updated_at)->format('Y-m-d / H:i') !!}</td>
            <td><a href="{!! route('smishing.select', [$item->id]) !!}" class='btn btn-info'><i class="fa fa-mouse-pointer"></i> Select</a></td>
        </tr>
        @endif
    @endforeach
    </tbody>
</table>
