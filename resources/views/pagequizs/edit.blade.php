@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Page Quiz Edit
        </h1>
   </section>
   <div class="content" id="app">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($pagecontent, ['route' => ['pagequizs.update', $pagecontent->id], 'method' => 'patch']) !!}

                        @include('pagequizs.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection