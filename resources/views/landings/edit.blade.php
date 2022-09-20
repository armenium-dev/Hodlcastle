@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Landing</h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($landing, ['route' => ['landings.update', $landing->id], 'method' => 'patch']) !!}

                        @include('landings.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection