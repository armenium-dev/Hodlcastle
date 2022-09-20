@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Supergroup</h1>
   </section>
   <div class="content" id="app">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($supergroup, ['route' => ['supergroups.update', $supergroup->id], 'method' => 'patch']) !!}

                   @include('supergroups.form_tabs', compact('supergroup'))

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection