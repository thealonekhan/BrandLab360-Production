<!DOCTYPE html>
<!--
* CoreUI Free Laravel Bootstrap Admin Template
* @version v2.0.1
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
	<head>
		<base href="./">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<meta name="description" content="BrandLab360 Analytics">
		<meta name="author" content="BrandLab360">
		<meta name="keyword" content="BrandLab360, BrandLab360 Analytics">
		<title>{{ !empty(auth()->user()) ? Illuminate\Support\Str::of(Illuminate\Support\Str::limit(auth()->user()->name, 20))->title().' - ' : '' }}BrandLab360 Analytics</title>
		<!-- <link rel="apple-touch-icon" sizes="57x57" href="{{url('assets/favicon/apple-icon-57x57.png')}}">
		<link rel="apple-touch-icon" sizes="60x60" href="{{url('assets/favicon/apple-icon-60x60.png')}}">
		<link rel="apple-touch-icon" sizes="72x72" href="{{url('assets/favicon/apple-icon-72x72.png')}}">
		<link rel="apple-touch-icon" sizes="76x76" href="{{url('assets/favicon/apple-icon-76x76.png')}}">
		<link rel="apple-touch-icon" sizes="114x114" href="{{url('assets/favicon/apple-icon-114x114.png')}}">
		<link rel="apple-touch-icon" sizes="120x120" href="{{url('assets/favicon/apple-icon-120x120.png')}}">
		<link rel="apple-touch-icon" sizes="144x144" href="{{url('assets/favicon/apple-icon-144x144.png')}}">
		<link rel="apple-touch-icon" sizes="152x152" href="{{url('assets/favicon/apple-icon-152x152.png')}}">
		<link rel="apple-touch-icon" sizes="180x180" href="{{url('assets/favicon/apple-icon-180x180.png')}}">
		<link rel="icon" type="image/png" sizes="192x192" href="{{url('assets/favicon/android-icon-192x192.png')}}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{url('assets/favicon/favicon-32x32.png')}}">
		<link rel="icon" type="image/png" sizes="96x96" href="{{url('assets/favicon/favicon-96x96.png')}}"> -->
		<link rel="icon" type="image/png" sizes="16x16" href="{{url('assets/favicon/BrandLab360_Icon_Black.png')}}">
		<link rel="manifest" href="{{url('assets/favicon/manifest.json')}}">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="{{url('assets/favicon/ms-icon-144x144.png')}}">
		<meta name="theme-color" content="#ffffff">
		<!-- Icons-->
		<link href="{{ asset('css/free.min.css') }}" rel="stylesheet"> <!-- icons -->
		<link href="{{ asset('css/flag.min.css') }}" rel="stylesheet"> <!-- icons -->
		<!-- Main styles for this application-->
		<link href="{{ asset('css/style.css') }}" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}" />

		@yield('css')

		<!-- Global site tag (gtag.js) - Google Analytics-->
		<script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
		<script>
			window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push(arguments);
			}
			gtag('js', new Date());
			// Shared ID
			gtag('config', 'UA-118965717-3');
			// Bootstrap ID
			gtag('config', 'UA-118965717-5');
		</script>

		<link href="{{ asset('css/coreui-chartjs.css') }}" rel="stylesheet">
	</head>



	<body class="c-app">
	
		<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">

			@include('dashboard.shared.nav-builder')

			@include('dashboard.shared.header')

			<div class="c-body">

				<main class="c-main">
					<div class="loading">Loading&#8230;</div>
					@if ($errors->any())
						<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
											<li>{!! $error !!}</li>
									@endforeach
								</ul>
						</div>
					@endif
					@if(Session::has('message'))
					<div class="alert 
					{{ Session::get('alert-class', 'alert-success') }}">{{Session::get('message') }}</div>
					@endif
					@if(Session::has('error'))
					<div class="alert 
					{{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('error') }}</div>
					@endif
					@yield('content') 

					<div class="modal fade" id="dangerModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
						<div class="modal-dialog modal-danger" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Alert!</h4>
									<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
								</div>
								<div class="modal-body">
								</div>
							</div>
							<!-- /.modal-content-->
						</div>
						<!-- /.modal-dialog-->
					</div>

				</main>
				@include('dashboard.shared.footer')
				<input type="hidden" name="_projectToken" value="{{csrf_token()}}">
			</div>
		</div>



		<!-- CoreUI and necessary plugins-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
		<script src="{{ asset('js/coreui-utils.js') }}"></script>
		<script src="{{ asset('js/popovers.js') }}"></script>
		<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
		@yield('javascript')
		
		<script type="text/javascript">
			$(document).ready(function(){

				$('div.alert').delay(3000).slideUp(300);


				$("#project-selection").on("change", function(){
					var selectedProject = $(this).val();
					var _token = $("input[name='_projectToken']").val();
					$(".loading").hide();
					$.ajaxSetup({
						// headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
					});
					jQuery.ajax({
						url: "{{ route('dashboard.project.ajax') }}",
						type:'POST',
						data: {_token:_token, project:selectedProject},
						beforeSend: function(){
							$(".loading").show();
						},
						success: function(data) {
							$(".loading").hide();
							location.reload();
						}
					});
				});
				$(function(){
					$(".project-popover").on("click", function(e){
						e.preventDefault();
					});

					// display a modal (small modal)
					$('body').on('click', '#modal-delete-btn', function(event) {
						event.preventDefault();
						let href = $(this).attr('data-attr');
						$.ajax({
							url: href, 
							// beforeSend: function() {
							//     $('#loader').show();
							// },
							// return the result
							success: function(result) {
									// $('#dangerModal').modal("show");
									$('#dangerModal').modal('toggle');
									$('#dangerModal .modal-body').html(result).show();
							},
							error: function(jqXHR, testStatus, error) {
									console.log(error);
									alert("Page " + href + " cannot open. Error:" + error);
							}, 
							timeout: 8000
						})
					});

				});
			
			});
		</script>



	</body>
</html>
