<?php use Carbon\Carbon; ?>
<div id="smishing-grid" class="smishing-grid">
    @foreach($sms_templates as $item)
        @if($item->language->code == $language['code'])
            <div class="{!! $item->is_public ? 'public' : 'private' !!} wrap">
				<div class="inner">
					<span class="mark">{!! $item->is_public ? 'PUBLIC TEMPLATE' : 'MY TEMPLATE' !!}</span>
					@if($item->image)
						<img src="{!! $item->image->crop(100, 100, true) !!}"/>
					@else
						<img src="/img/sms-phishmanager.png"/>
					@endif
					<hr>
					<div class="flex-1"><h2>{!! $item->name !!}</h2></div>
					<div id="js_content_{!! $item->id !!}" class="template-content">
						<div class="d-flex flex-row flex-nowrap justify-space-between align-items-start header">
							<h3>Template content preview</h3>
							<a href="#" class="js_hide_content btn-close" data-target="#js_content_{!! $item->id !!}"></a>
						</div>
						<p>{!! nl2br($item->content) !!}</p>
					</div>
					<div class="buttons">
						<a href="#"
						   class="js_display_content btn-cinfo"
						   data-target="#js_content_{!! $item->id !!}"
						   title="Modified: {!! Carbon::parse($item->updated_at)->format('Y-m-d / H:i') !!}">Preview content <i></i></a>
						<a href="{!! route('smishing.select', [$item->id]) !!}" class="btn-select"><i></i> Select</a>
					</div>
				</div>
            </div>
        @endif
    @endforeach
</div>

<script type="text/javascript">
	$(document).ready(function(){
		let GJS = {
			Init: function(){
				$(document)
						.on('click', '.js_display_content', GJS.Item.showContent)
						.on('click', '.js_hide_content', GJS.Item.hideContent);
			},
			Item: {
				showContent: function(e){
					e.preventDefault();

					let $target = $($(this).data('target'));
					$target.addClass('show');
				},
				hideContent: function(e){
					e.preventDefault();

					let $target = $($(this).data('target'));
					$target.removeClass('show');
				},
			},
		};

		GJS.Init();
	});
</script>
