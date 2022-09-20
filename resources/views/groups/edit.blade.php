@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Group</h1>
   </section>
   <div class="content" id="app">
       @include('adminlte-templates::common.errors')
       @include('flash::message')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($group, ['route' => ['groups.update', $group->id], 'method' => 'patch', 'class' => 'ajax-form']) !!}

                        @include('groups.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection