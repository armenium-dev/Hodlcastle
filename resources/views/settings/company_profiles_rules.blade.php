@extends('layouts.app')

@section('content')
	<section class="content-header">
		<h1>Manage Company Profiles Access</h1>
	</section>
	<div class="content">
		<div class="clearfix"></div>

		@include('flash::message')

		<div class="clearfix"></div>

		@if(Auth::user()->hasRole('captain'))
		<div class="nav-tabs-custom">
			@include('settings.tabs', ['active' => 'rules'])
			<div class="tab-content">
				<div class="tab-pane active">
					@include('settings.company_profiles.rules')
				</div>
			</div>
		</div>
		@endif
	</div>

	@include('settings.scripts.company_profiles')
@endsection
