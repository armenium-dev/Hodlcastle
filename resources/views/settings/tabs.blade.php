<ul class="nav nav-tabs">
	<li class="{{ $active == 'index' ? 'active' : '' }}"><a href="{!! route('settings.company_profiles.index') !!}">1. Profiles</a></li>
	<li class="{{ $active == 'terms' ? 'active' : '' }}"><a href="{!! route('settings.company_profiles.terms') !!}">2. Terms</a></li>
	<li class="{{ $active == 'rules' ? 'active' : '' }}"><a href="{!! route('settings.company_profiles.rules') !!}">3. Rules</a></li>
</ul>
