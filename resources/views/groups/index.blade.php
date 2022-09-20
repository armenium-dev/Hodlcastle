@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="pull-right">
            <a class="btn btn-success" href="{!! route('groups.create') !!}"><i class="fa fa-plus"></i> Add New</a>
        </div>
        <h1>Recipient Groups</h1>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @if(Auth::user()->company)
                <div>Remaining Recipients Licenses:
                    @if(Auth::user()->hasRole('captain'))
                    {{ Auth::user()->company->AllRecipientsCapacity }}
                    @else
                    {{ Auth::user()->company->RecipientsCapacity }}
                    @endif
                </div>
                @endif
                    @include('groups.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

