<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		@foreach($languages as $language)
		<li class="{!! $language['class'] !!}">
			<a href="#tab_{!! $language['id'] !!}" data-toggle="tab"><img src="/img/pmflags/{!! $language['code'] !!}.png" /> {!! $language['name'] !!}</a>
		</li>
		@endforeach
	</ul>
	<div class="tab-content">
		@foreach($languages as $language)
			<div class="tab-pane {!! $language['class'] !!}" id="tab_{!! $language['id'] !!}">
				@include('smishing.table')
			</div>
		@endforeach
	</div>
</div>
