@extends('layouts.app')

@section('content')
	<section class="content-header">
		<div class="pull-right">
			<a class="btn btn-success hidden" href="{!! route('settings.create') !!}"><i class="fa fa-plus"></i> Add New</a>
		</div>
		<h1>Settings</h1>
	</section>

	<div class="content">
		<div class="clearfix"></div>

		@include('flash::message')

		<div class="clearfix"></div>

		<div class="box box-primary">
			<div class="box-body">
				@if(Auth::user()->hasRole('captain'))
					@include('settings.table')
				@else
					<div class="text-center">You do not have access to this section</div>
				@endif
			</div>
		</div>
	</div>
@endsection

