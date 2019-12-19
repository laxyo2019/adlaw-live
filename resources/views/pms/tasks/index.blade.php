@extends('layouts.tabler')
@section('title', 'Laxyo Tasks')

@section('navbar')
  @include('partials._tabler.navbar')
@endsection

@push('links')
	<script src="{{ asset('tabler/assets/js/require.min.js') }}"></script>
	<script>
		requirejs.config({
				baseUrl: '/tabler'
		});
	</script>

	<script src="{{ asset('js/app.js') }}" defer></script>
	<script src="{{ asset('tabler/assets/js/dashboard.js') }}"></script>
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
@endpush

@section('content')
	<div class="my-3 my-md-5">
		<tasks :users="{{ json_encode($users) }}"
			:user="{{ json_encode($logged_user) }}"
			:tasks="{{ json_encode($tasks) }}"
			:teams="{{ json_encode($logged_user->user_in_teams) }}"
		></tasks>
	</div>
@endsection