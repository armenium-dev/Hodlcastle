@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Vimeo Video Attachment</h3>
            </div>
            <div class="box-body">
                <div>Paste the code of the vimeo video and save the form.</div>
                <img src="/img/10-01-2023 22-25-15.jpg" />
            </div>
        </div>
    </div>

    <section class="content-header">
        <h1>
            Page Video Edit
        </h1>
   </section>
   <div class="content" id="app">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($pagecontent, ['route' => ['pagevideos.update', $pagecontent->id], 'method' => 'patch']) !!}

                        @include('pagevideos.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection