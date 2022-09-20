@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>Email Template</h1>
   </section>
   <div class="content">
       @include('flash::message')
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($emailTemplate, ['route' => ['emailTemplates.update', $emailTemplate->id], 'method' => 'patch', 'files' => true]) !!}

                        @include('email_templates.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection