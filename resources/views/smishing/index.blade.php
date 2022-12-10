@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="pull-right">
            <a class="js_change_view_type btn {!! $display_type == 'table' ? 'active' : '' !!}" data-type="table" href="{!! route('smishing') !!}"><i class="fa fa-table fa-2x" aria-hidden="true"></i></a>
            <a class="js_change_view_type btn {!! $display_type == 'grid' ? 'active' : '' !!}" data-type="grid" href="{!! route('smishing') !!}"><i class="fa fa-th-large fa-2x" aria-hidden="true"></i></a>
            <a class="btn btn-success ms-20" href="{!! route('smsTemplates.create') !!}">Custom SMS</a>
        </div>
        <h1>Smishing</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        @include('smishing.tabs')
    </div>

	<script type="text/javascript">
		$(document).ready(function(){
			let FJS = {
				elements: {},
				Init: function(){
					$(document)
						.on('click', '.js_change_view_type', FJS.View.change);
				},
				Common: {
					getSessionStorage: function(key){
						return sessionStorage.getItem(key);
					},
					setSessionStorage: function(key, value){
						sessionStorage.setItem(key, value);
					},
					setCookies: function(cname, cvalue, exdays){
						var d = new Date();
						d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);

						var options = {
							expires: d.toUTCString(),
							path: "/",
							domain: '{!! request()->getHost() !!}',
							//secure: globals.secure,
							//httponly: false,
							//samesite: "Lax",
						};

						var updatedCookie = encodeURIComponent(cname) + "=" + encodeURIComponent(cvalue);

						for(var optionKey in options){
							updatedCookie += "; " + optionKey + "=" + options[optionKey];
						}
						//console.log(updatedCookie);

						document.cookie = updatedCookie;
					},
					getCookie: function(cname){
						var name = cname + "=";
						var ca = document.cookie.split(';');
						for(var i = 0; i < ca.length; i++){
							var c = ca[i];
							while(c.charAt(0) == ' '){
								c = c.substring(1);
							}
							if(c.indexOf(name) == 0){
								return c.substring(name.length, c.length);
							}
						}
						return "";
					},
				},
				View: {
					change: function(e){
						e.preventDefault();
						e.stopPropagation();

						let type = $(this).data('type'),
							href = $(this).attr('href');

						FJS.Common.setCookies('display_type', type, 365);
						window.location.href = href;

						return false;
					},
				},
			};

			FJS.Init();
		});
	</script>
@endsection
