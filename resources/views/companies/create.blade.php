@extends('layouts.app')

@section('content')
	<section class="content-header">
		<h1>Company</h1>
	</section>
	<div class="content" id="app">
		@include('adminlte-templates::common.errors')
		<div class="box box-primary">
			<div class="box-body">
				{!! Form::open(['route' => 'companies.store', 'files' => true]) !!}
				@include('companies.fields')
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@endsection
