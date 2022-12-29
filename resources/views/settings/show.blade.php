@extends('layouts.app', ['title' => 'Option '.$model->id])
@section('styles')
<style type="text/css">
	.fa-square:before {text-shadow: 1px 1px 2px #000000;}
	@media print {
		table,
		table th,
		table thead th,
		table tbody th,
		table td,
		table tbody td,
		table tfoot td,
		table tr {border: 1px solid #000 !important;}
		.print-mt-big {margin-top: 80px;}
	}
</style>
@endsection
@section('content')
	<div class="content">
	<div class="box box-primary">
		<div class="box-body">
			@if (session('status'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('status') }}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@endif
			<div class="row">
				<div class="col-md-6 offset-md-3 mb-20">
					<h4 class="font-weight-bold font-red font-18">Option data:</h4>
					<table class="table table-bordered border-balck narrow-first-col slim-td-padding">
						<tbody>
							<tr>
								<td><b>ID:</b></td>
								<td>{{ $model->id }}</td>
							</tr>
							<tr>
								<td><b>Name:</b></td>
								<td>{{ $model->option_key ?? 'No Name' }}</td>
							</tr>
							<tr>
								<td><b>Value:</b></td>
								<td>{!! $model->option_value ?? 'No Value' !!}</td>
							</tr>
							<tr>
								<td><b>Active:</b></td>
								<td>{{ $model->active ? 'Yes' : 'No' }}</td>
							</tr>
						</tbody>
					</table>
					<div>
						<a href="{{route('settings.index')}}" class="btn btn-secondary"><i class="fa fa-angle-left"></i> Back to list</a>
						<a href="{{route('settings.edit', $model->id)}}" class="btn btn-secondary"><i class="fa fa-edit"></i> Edit</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
@endsection