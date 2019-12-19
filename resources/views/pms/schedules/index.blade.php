@extends('layouts.tabler')
@section('title', 'Laxyo Agenda')
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
@endpush
@php
// print_r(json_encode($focusAgenda)); die;
@endphp
@section('content')
	<div class="my-3 my-md-5">
  	<schedules :users = "{{ json_encode($users) }}"
  		:displays = "{{ json_encode($schedules) }}"
  		:logged_user = "{{ json_encode(auth()->user()) }}"
		></schedules>
	</div>
@endsection