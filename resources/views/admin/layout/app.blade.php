<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laravel Shop :: Administrative Panel</title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/fontawesome-free/css/all.min.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/css/adminlte.min.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/css/custom.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/summernote/summernote.min.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/select2/css/select2.min.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/css/datetimepicker.css')}}">
		<meta name="csrf-token" content="{{csrf_token()}}">
	</head>
	<body class="hold-transition sidebar-mini">
		<div class="wrapper">
			<nav class="main-header navbar navbar-expand navbar-white navbar-light">
				<ul class="navbar-nav">
					<li class="nav-item">
					  	<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
					</li>					
				</ul>
				<div class="navbar-nav pl-2">
				</div>
				
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" data-widget="fullscreen" href="#" role="button">
							<i class="fas fa-expand-arrows-alt"></i>
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
							<img src="{{asset('admin-assets/img/avatar5.png')}}" class='img-circle elevation-2' width="40" height="40" alt="">
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3">
							<h4 class="h4 mb-0"><strong>{{Auth::guard('admin')->user()->name}}</strong></h4>
							<div class="mb-3">{{Auth::guard('admin')->user()->email}}</div>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-user-cog mr-2"></i> Settings								
							</a>
							<div class="dropdown-divider"></div>
							<a href="{{route('admin.changePassword')}}" class="dropdown-item">
								<i class="fas fa-lock mr-2"></i> Change Password
							</a>
							<div class="dropdown-divider"></div>
							<a href="{{route('admin.logout')}}" class="dropdown-item text-danger">
								<i class="fas fa-sign-out-alt mr-2"></i> Logout							
							</a>							
						</div>
					</li>
				</ul>
			</nav>
			<aside class="main-sidebar sidebar-dark-primary elevation-4">
				<a href="#" class="brand-link">
					<img src="{{asset('admin-assets/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
					<span class="brand-text font-weight-light">LARAVEL SHOP</span>
				</a>
				@include('admin.layout.sidebar')
         	</aside>
			<div class="content-wrapper">
				@yield('content')
			</div>
			<footer class="main-footer">
				<strong>Copyright &copy; 2014-2022 AmazingShop All rights reserved.
			</footer>
		</div>
		<script src="{{asset('admin-assets/plugins/jquery/jquery.min.js')}}"></script>
		<script src="{{asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.js')}}"></script>
		<script src="{{asset('admin-assets/plugins/select2/js/select2.min.js')}}"></script>
		<script src="{{asset('admin-assets/plugins/summernote/summernote.min.js')}}"></script>
		<script src="{{asset('admin-assets/js/datetimepicker.js')}}"></script>
		<script src="{{asset('admin-assets/js/adminlte.min.js')}}"></script>
		<script src="{{asset('admin-assets/js/demo.js')}}"></script>
		<script>
			$.ajaxSetup({
				headers:{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
				}
			})
			$('.summernote').summernote({
				height:250
			});
		</script>
        @yield('js')
	</body>
</html>