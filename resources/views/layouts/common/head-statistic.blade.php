<head>
	<meta charset="UTF-8">
	<title>PhishManager</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="{{ asset('css/bootstrap-3.3.7.min.css') }}">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<link href="https://cdn.quilljs.com/1.0.0/quill.snow.css" rel="stylesheet">

	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

	<!-- Theme style -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

	<!-- iCheck -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">

	<link rel="stylesheet" href="{{ asset('vendor/lightpick/lightpick.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/timepicki/timepicki.css') }}">
	<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
	      integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
	      crossorigin=""/>

	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

	<link rel="stylesheet" href="{{ asset('css/app.css') }}">

	@yield('css')

	<!-- jQuery 3.1.1 -->
	<script src="{{ asset('js/jquery-2.1.1.min.js') }}"></script>

	<!-- DataTables -->
	<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
	<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>


	<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
</head>
