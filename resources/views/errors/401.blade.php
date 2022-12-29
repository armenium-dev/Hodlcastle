@extends('layouts.app')

@section('content')
    <div class="d-flex flex-row align-items-center justify-content-center h-full">
        <div class="box max-w-300">
            <div class="box-body text-center">
                <h1>401</h1>
                <p>{{ $exception->getMessage() }}</p>
                <p>You don't have permission on this page</p>
                <div>
                    <a href="#" onclick="history.back();">&laquo; Go Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection