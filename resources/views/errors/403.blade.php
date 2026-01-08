@extends('layouts.master2')
@section('css')
<!--- Internal Fontawesome css-->
<link href="{{URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<!---Ionicons css-->
<link href="{{URL::asset('assets/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
<!---Internal Typicons css-->
<link href="{{URL::asset('assets/plugins/typicons.font/typicons.css')}}" rel="stylesheet">
<!---Internal Feather css-->
<link href="{{URL::asset('assets/plugins/feather/feather.css')}}" rel="stylesheet">
<!---Internal Falg-icons css-->
<link href="{{URL::asset('assets/plugins/flag-icon-css/css/flag-icon.min.css')}}" rel="stylesheet">
@endsection
@section('content')
		<!-- Main-error-wrapper -->
		<div class="main-error-wrapper  page page-h ">
			<img src="{{URL::asset('assets/img/media/403.png')}}" class="error-page" alt="error">
			<h2>Oopps. You are not AUTHORIZED, You do not have the Right Role.</h2>
			<h6>You Don't Have The Permission to Do This Action.</h6>
			<a class="btn btn-outline-danger" href="{{ route('home') }}">Back to Home</a>
			<div class="mt-5">
				<a class="btn btn-outline-danger" href="{{route('login') }}">Back to Login</a>
			</div>
		</div>
		<!-- /Main-error-wrapper -->
@endsection
@section('js')
@endsection