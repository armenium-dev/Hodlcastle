@extends('layouts.app')

@section('content')
    <div class="container spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">2FA Secret Key</div>

                    <div class="panel-body">
                        Open up your 2FA mobile app and scan the following QR barcode:
                        <br />
                        <img alt="Image of QR barcode" src="{{ $image }}" />

                        <br />
                        If your 2FA mobile app does not support QR barcodes,
                        enter in the following number: <code>{{ $secret }}</code>
                        <br /><br />
                        <a href="{{ route('profile.index') }}">Back to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection